#!/usr/bin/env node
/**
 * Validate the block markup in every theme pattern, template, and part.
 *
 * The verify pass looks at the *front end* — HTTP status, console errors, PHP
 * notices, screenshots. Block validity is invisible to all of that. WordPress
 * decides a block is invalid by re-running the block's `save()` against its
 * stored attributes and diffing the result with the markup on disk; a mismatch
 * shows up as "Block contains unexpected or invalid content" in the editor
 * while the front end keeps rendering the stored HTML perfectly. Hand-authored
 * block markup is exactly where that goes wrong, and this project writes all of
 * its markup by hand.
 *
 * This runs the same validator the editor uses — `@wordpress/blocks` `parse()`
 * with the core block library registered — in Node, so it needs neither Docker
 * nor a browser and can gate a commit.
 *
 * Usage:
 *   npm run validate:blocks [-- --verbose] [--path <glob-ish dir>]
 *
 * Exit code is non-zero when any block fails validation or resolves to an
 * unregistered block type.
 */

import fs from 'node:fs';
import path from 'node:path';
import { createRequire } from 'node:module';

// The WordPress packages' ESM builds import JSON without an import attribute,
// which Node's strict ESM loader rejects. Their CommonJS builds are equivalent
// and load cleanly, so go through require().
const require = createRequire(import.meta.url);

const args = process.argv.slice(2);
const verbose = args.includes('--verbose');
const flag = (name, fallback) => {
  const i = args.indexOf(name);
  return i !== -1 && args[i + 1] ? args[i + 1] : fallback;
};
const root = flag('--path', 'wp-content');

/**
 * `@wordpress/block-library` reaches for browser globals as its modules load.
 * Stand up a DOM before importing anything from the WordPress packages.
 */
async function installDom() {
  const { JSDOM, VirtualConsole } = require('jsdom');

  // jsdom reports on CSS it cannot parse in the block library's inline styles.
  // None of it matters for block validation; keep the output clean.
  const virtualConsole = new VirtualConsole();
  const dom = new JSDOM('<!doctype html><html><body></body></html>', {
    url: 'http://localhost:8888',
    pretendToBeVisual: true,
    virtualConsole,
  });

  globalThis.window = dom.window;
  globalThis.document = dom.window.document;
  globalThis.self = dom.window;

  // Node 22 exposes a getter-only `navigator`; plain assignment throws.
  Object.defineProperty(globalThis, 'navigator', {
    value: dom.window.navigator,
    configurable: true,
    writable: true,
  });

  // Copy every constructor and helper the block library's dependency tree
  // reaches for at module scope. Anything jsdom provides wins; the rest get a
  // minimal stand-in, since none of it runs during parsing.
  for (const key of [
    'HTMLElement', 'HTMLDocument', 'Element', 'Node', 'NodeList', 'DOMParser',
    'Event', 'CustomEvent', 'MouseEvent', 'KeyboardEvent', 'FocusEvent',
    'MutationObserver', 'IntersectionObserver', 'ResizeObserver',
    'getComputedStyle', 'DocumentFragment', 'Text', 'Range', 'XMLSerializer',
    'File', 'FileList', 'Blob', 'FormData', 'URL', 'URLSearchParams',
    'localStorage', 'sessionStorage', 'location', 'history', 'screen',
  ]) {
    if (dom.window[key] !== undefined && globalThis[key] === undefined) {
      globalThis[key] = dom.window[key];
    }
  }

  class NoopObserver {
    observe() {}
    unobserve() {}
    disconnect() {}
    takeRecords() {
      return [];
    }
  }
  for (const key of ['MutationObserver', 'IntersectionObserver', 'ResizeObserver']) {
    if (globalThis[key] === undefined) globalThis[key] = NoopObserver;
    if (dom.window[key] === undefined) dom.window[key] = globalThis[key];
  }

  globalThis.requestAnimationFrame = dom.window.requestAnimationFrame || ((cb) => setTimeout(() => cb(Date.now()), 0));
  globalThis.cancelAnimationFrame = dom.window.cancelAnimationFrame || ((id) => clearTimeout(id));
  globalThis.requestIdleCallback = (cb) => setTimeout(() => cb({ didTimeout: false, timeRemaining: () => 50 }), 0);
  globalThis.cancelIdleCallback = (id) => clearTimeout(id);
  globalThis.matchMedia =
    dom.window.matchMedia ||
    (() => ({ matches: false, media: '', onchange: null, addListener() {}, removeListener() {}, addEventListener() {}, removeEventListener() {}, dispatchEvent: () => false }));
  dom.window.matchMedia = dom.window.matchMedia || globalThis.matchMedia;
}

/**
 * Reduce a pattern's PHP down to the block markup underneath it.
 *
 * Patterns are PHP files: a docblock header, sometimes a few variable
 * assignments, and inline `<?php echo esc_url( … ); ?>` inside attributes.
 * Substituting a plausible literal for each inline expression leaves markup
 * that parses exactly as it would at runtime.
 *
 * Caveat worth knowing: a pattern that branches (`<?php if … else … endif; ?>`)
 * has both branches stripped, so every branch's blocks get validated together.
 * That is fine while conditionals wrap whole blocks — which is the convention
 * here — and would misreport only if a conditional split a single block's
 * opening and closing markup.
 */
function phpToMarkup(source) {
  let out = source;

  // Leading header/docblock and any setup PHP before the markup starts.
  out = out.replace(/^﻿?\s*<\?php[\s\S]*?\?>/, '');

  // Inline expressions inside attributes — anything echoing a URI or an id.
  out = out.replace(/<\?php\s+echo[\s\S]*?\?>/g, 'https://example.test/placeholder');

  // Control flow and any remaining PHP: drop it, keep the markup around it.
  out = out.replace(/<\?php[\s\S]*?\?>/g, '');

  return out.trim();
}

function collectSources(dir) {
  const found = [];
  if (!fs.existsSync(dir)) return found;

  for (const entry of fs.readdirSync(dir, { withFileTypes: true })) {
    const full = path.join(dir, entry.name);
    if (entry.isDirectory()) {
      if (['node_modules', 'build', 'assets', '.git'].includes(entry.name)) continue;
      found.push(...collectSources(full));
      continue;
    }
    const inPatterns = /(^|\/)patterns\//.test(full) && entry.name.endsWith('.php');
    const inTemplates = /(^|\/)(templates|parts)\//.test(full) && entry.name.endsWith('.html');
    if (inPatterns || inTemplates) found.push(full);
  }
  return found;
}

/**
 * Run a function with the console muted.
 *
 * `parse()` logs the full expected-vs-actual diff for every invalid block. That
 * is the useful detail, but it is captured per block via `validationIssues`, so
 * letting it also stream to stdout buries the report and makes the output
 * useless in CI.
 */
function quietly(fn) {
  const saved = { log: console.log, info: console.info, warn: console.warn, error: console.error };
  const noop = () => {};
  Object.assign(console, { log: noop, info: noop, warn: noop, error: noop });
  try {
    return fn();
  } finally {
    Object.assign(console, saved);
  }
}

/** Walk a parsed tree, collecting every block including inner ones. */
function flatten(blocks, acc = []) {
  for (const block of blocks) {
    acc.push(block);
    if (block.innerBlocks?.length) flatten(block.innerBlocks, acc);
  }
  return acc;
}

/**
 * Flag site-root-relative internal links.
 *
 * `href="/menu/"` resolves correctly on a single-site install at the domain
 * root — which is exactly what wp-env is — and 404s on a multisite subdirectory
 * install, where the site lives at /<slug>/. The local environment therefore
 * cannot catch this, and `npm run verify` builds its URLs from the base rather
 * than following links in the markup, so it cannot either. Only a source lint
 * sees it before the deploy does.
 *
 * The fix in a pattern is `home_url()`:
 *   href="<?php echo esc_url( home_url( '/menu/' ) ); ?>"
 *
 * Static templates and parts cannot run PHP, so link-bearing markup belongs in
 * a pattern — which is the layering rule anyway.
 *
 * Left alone: absolute URLs, protocol-relative URLs, anchors, mailto:, tel:,
 * and anything already wrapped in a PHP echo.
 */
function findRootRelativeLinks(raw, file) {
  const hits = [];
  // Strip PHP blocks so an href already built with home_url() does not match.
  const stripped = raw.replace(/<\?php[\s\S]*?\?>/g, 'PHP_EXPR');
  const patterns = [
    { re: /href="(\/[^"]*)"/g, what: 'href' },
    { re: /"url":"(\\?\/[^"]*)"/g, what: 'navigation url' },
  ];
  for (const { re, what } of patterns) {
    let m;
    while ((m = re.exec(stripped))) {
      const url = m[1].replace(/\\\//g, '/');
      if (url.startsWith('//')) continue; // protocol-relative
      const line = stripped.slice(0, m.index).split('\n').length;
      hits.push({ file, line, what, url });
    }
  }
  return hits;
}

async function main() {
  await installDom();

  const { parse, getBlockTypes } = require('@wordpress/blocks');
  const { registerCoreBlocks } = require('@wordpress/block-library');
  registerCoreBlocks();

  const registered = new Set(getBlockTypes().map((b) => b.name));
  const files = collectSources(root).sort();

  if (!files.length) {
    console.error(`No patterns, templates, or parts found under ${root}`);
    process.exit(1);
  }

  let invalidCount = 0;
  let missingCount = 0;
  let blockCount = 0;
  const problems = [];

  const linkHits = [];

  for (const file of files) {
    const raw = fs.readFileSync(file, 'utf8');
    linkHits.push(...findRootRelativeLinks(raw, file));
    const markup = file.endsWith('.php') ? phpToMarkup(raw) : raw;
    if (!markup.trim()) continue;

    let parsed;
    try {
      parsed = quietly(() => parse(markup));
    } catch (err) {
      problems.push({ file, kind: 'parse-error', detail: err.message });
      invalidCount += 1;
      continue;
    }

    const all = flatten(parsed).filter((b) => b.name);
    blockCount += all.length;

    for (const block of all) {
      // `core/missing` is what an unregistered block name resolves to. Plugin
      // blocks legitimately are not registered here, so report them apart from
      // real validation failures.
      if (block.name === 'core/missing') {
        const original = (block.attributes?.originalName || 'unknown').trim();
        problems.push({ file, kind: 'unregistered', detail: original });
        missingCount += 1;
        continue;
      }

      if (!registered.has(block.name)) {
        problems.push({ file, kind: 'unregistered', detail: block.name });
        missingCount += 1;
        continue;
      }

      if (block.isValid === false) {
        const issues = (block.validationIssues || [])
          .map((issue) => {
            const args = (issue.args || []).map(String).join(' | ');
            return `${issue.log?.name || 'issue'}: ${args}`.slice(0, 220);
          })
          .join('\n           ');
        problems.push({ file, kind: 'invalid', detail: block.name, issues });
        invalidCount += 1;
      }
    }
  }

  console.log(`Checked ${blockCount} block(s) across ${files.length} file(s).\n`);

  if (linkHits.length) {
    console.log('Site-root-relative internal links (these 404 on a multisite subdirectory install):\n');
    for (const h of linkHits) {
      console.log(`  ${h.file}:${h.line}  ${h.what}  ${h.url}`);
    }
    console.log(
      `\n  ${linkHits.length} link(s). Use home_url() in a pattern:\n` +
        `    href="<?php echo esc_url( home_url( '/menu/' ) ); ?>"\n` +
        '  Static templates and parts cannot run PHP — move link-bearing markup into a pattern.\n'
    );
  }

  if (!problems.length && !linkHits.length) {
    console.log('All block markup is valid.');
    return;
  }

  if (!problems.length) {
    process.exit(1);
  }

  const byFile = new Map();
  for (const p of problems) {
    if (!byFile.has(p.file)) byFile.set(p.file, []);
    byFile.get(p.file).push(p);
  }

  for (const [file, list] of byFile) {
    console.log(file);
    for (const p of list) {
      if (p.kind === 'invalid') {
        console.log(`  [INVALID]      ${p.detail}`);
        if (verbose && p.issues) console.log(`           ${p.issues}`);
      } else if (p.kind === 'unregistered') {
        console.log(`  [UNREGISTERED] ${p.detail}`);
      } else {
        console.log(`  [PARSE ERROR]  ${p.detail}`);
      }
    }
    console.log('');
  }

  console.log(
    `${invalidCount} invalid block(s), ${missingCount} unregistered block reference(s).` +
      (verbose ? '' : '\nRe-run with --verbose for the per-block validation diff.')
  );

  // Unregistered blocks alone are not a failure: plugin-provided blocks are not
  // registered in this Node context. Invalid markup is.
  if (invalidCount > 0 || linkHits.length > 0) process.exit(1);
}

main().catch((err) => {
  console.error(err);
  process.exit(1);
});
