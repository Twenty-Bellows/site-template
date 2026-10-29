---
name: woocommerce-development
description: WooCommerce customization guidance — products, checkout, orders, template overrides, HPOS compatibility. Use when building or modifying store functionality.
---

# Skill: WooCommerce Development

## Description
Expert guidance for customizing WooCommerce stores, including products, checkout, orders, and payment integrations.

## When to Use
- User wants to customize WooCommerce functionality
- User needs custom product fields or types
- User wants to modify checkout process
- User needs custom shipping or payment methods
- User wants to work with orders programmatically
- User needs WooCommerce REST API integration

## Verifying against the local environment
This repo runs WordPress through wp-env. To inspect or test store behavior:
```
npm run server:start                                   # boot the environment
npx wp-env run cli wp plugin list              # confirm WooCommerce is active
npx wp-env run cli wp wc product list --user=admin   # WC CLI commands
npx wp-env run cli wp shell                    # interactive PHP against the install
```

## Key Concepts

### Check WooCommerce Active
```php
if ( class_exists( 'WooCommerce' ) ) {
    // WooCommerce is active
}

// Or check function
if ( function_exists( 'WC' ) ) {
    $cart = WC()->cart;
}
```

### HPOS Compatibility (High-Performance Order Storage)
```php
// Always use order methods, not post meta
$order = wc_get_order( $order_id );
$email = $order->get_billing_email();
$order->update_meta_data( '_custom_field', 'value' );
$order->save();
```

## Common Tasks

### 1. Add Custom Product Field
```php
// Add field
add_action( 'woocommerce_product_options_general_product_data', function() {
    woocommerce_wp_text_input( array(
        'id' => '_custom_field',
        'label' => 'Custom Field',
        'desc_tip' => true,
        'description' => 'Enter custom value',
    ));
});

// Save field
add_action( 'woocommerce_process_product_meta', function( $post_id ) {
    $value = isset( $_POST['_custom_field'] ) ? sanitize_text_field( $_POST['_custom_field'] ) : '';
    update_post_meta( $post_id, '_custom_field', $value );
});

// Display on frontend
add_action( 'woocommerce_single_product_summary', function() {
    global $product;
    $value = get_post_meta( $product->get_id(), '_custom_field', true );
    if ( $value ) {
        echo '<p class="custom-field">' . esc_html( $value ) . '</p>';
    }
}, 25 );
```

### 2. Add Custom Checkout Field
```php
// Add field
add_action( 'woocommerce_after_order_notes', function( $checkout ) {
    woocommerce_form_field( 'delivery_date', array(
        'type' => 'date',
        'label' => 'Preferred Delivery Date',
        'required' => true,
        'class' => array( 'form-row-wide' ),
    ), $checkout->get_value( 'delivery_date' ) );
});

// Validate
add_action( 'woocommerce_checkout_process', function() {
    if ( empty( $_POST['delivery_date'] ) ) {
        wc_add_notice( 'Please select a delivery date.', 'error' );
    }
});

// Save to order
add_action( 'woocommerce_checkout_update_order_meta', function( $order_id ) {
    if ( ! empty( $_POST['delivery_date'] ) ) {
        $order = wc_get_order( $order_id );
        $order->update_meta_data( '_delivery_date', sanitize_text_field( $_POST['delivery_date'] ) );
        $order->save();
    }
});
```

### 3. Modify Cart/Checkout
```php
// Add fee
add_action( 'woocommerce_cart_calculate_fees', function( $cart ) {
    if ( $cart->subtotal > 100 ) {
        $cart->add_fee( 'Premium Handling', 10 );
    }
});

// Modify shipping
add_filter( 'woocommerce_package_rates', function( $rates, $package ) {
    // Remove free shipping if cart < 50
    if ( WC()->cart->subtotal < 50 ) {
        unset( $rates['free_shipping:1'] );
    }
    return $rates;
}, 10, 2 );
```

### 4. Work with Orders
```php
// Get order
$order = wc_get_order( $order_id );

// Order data
$status = $order->get_status();
$total = $order->get_total();
$items = $order->get_items();
$billing_email = $order->get_billing_email();

// Update order
$order->set_status( 'completed' );
$order->add_order_note( 'Order processed.' );
$order->save();

// Loop items
foreach ( $order->get_items() as $item_id => $item ) {
    $product_id = $item->get_product_id();
    $quantity = $item->get_quantity();
    $total = $item->get_total();
}
```

### 5. Custom Product Tab
```php
add_filter( 'woocommerce_product_tabs', function( $tabs ) {
    $tabs['custom'] = array(
        'title' => 'Specifications',
        'priority' => 50,
        'callback' => function() {
            global $product;
            echo '<h2>Specifications</h2>';
            // Output specifications
        },
    );
    return $tabs;
});
```

### 6. Custom Order Status
```php
// Register status
add_action( 'init', function() {
    register_post_status( 'wc-awaiting-pickup', array(
        'label' => 'Awaiting Pickup',
        'public' => true,
        'show_in_admin_status_list' => true,
        'label_count' => _n_noop( 'Awaiting Pickup (%s)', 'Awaiting Pickup (%s)' ),
    ));
});

// Add to dropdown
add_filter( 'wc_order_statuses', function( $statuses ) {
    $statuses['wc-awaiting-pickup'] = 'Awaiting Pickup';
    return $statuses;
});
```

## Useful Hooks

### Product Hooks
- `woocommerce_before_single_product`
- `woocommerce_single_product_summary`
- `woocommerce_after_single_product`
- `woocommerce_product_options_general_product_data`

### Cart Hooks
- `woocommerce_before_cart`
- `woocommerce_cart_contents`
- `woocommerce_cart_calculate_fees`
- `woocommerce_add_to_cart`

### Checkout Hooks
- `woocommerce_before_checkout_form`
- `woocommerce_checkout_fields`
- `woocommerce_checkout_process`
- `woocommerce_checkout_order_processed`

### Order Hooks
- `woocommerce_new_order`
- `woocommerce_order_status_changed`
- `woocommerce_payment_complete`

## Template Override

**Block themes (this repo):** WooCommerce supports block-theme templates. Put HTML
templates in the theme's `templates/` directory — `single-product.html`,
`archive-product.html` — composed from WooCommerce blocks
(`woocommerce/product-image-gallery`, `woocommerce/add-to-cart-form`,
`woocommerce/product-details`, and so on). This is the preferred override path.

**Classic PHP templates** (only when a block template cannot express it): copy from
`woocommerce/templates/` to the theme's `woocommerce/` folder, e.g.
`wp-content/themes/mytheme/woocommerce/single-product.php`. Keep the copied template's
version comment current — WooCommerce warns when overrides fall behind.

## Checklist
- [ ] Check WooCommerce is active before using
- [ ] Use HPOS-compatible methods for orders
- [ ] Prefer block-theme HTML templates for overrides; classic overrides live in the theme, not the plugin
- [ ] Test with different product types
- [ ] Consider multisite/currency compatibility
- [ ] Use WC form field functions in checkout
