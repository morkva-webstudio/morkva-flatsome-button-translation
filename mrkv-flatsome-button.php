<?php
/**
 * Plugin Name: Morkva Flatsome Button Translation
 * Plugin URI: https://morkva.co.ua/
 * Description: Enables header buttons string translation. Need flatsome customization services? support@morkva.co.ua
 * Version: 0.1.2
 * Author: MORKVA
 * Text Domain: morkva-flatsome-button-translation
 * License: GPLv2
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'WPINC' ) ) {
    die;
}

add_action( 'before_woocommerce_init', function() {
    if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
    }
} );

function mrkv_flatsome_button_for_polylang() 
{
    if (function_exists('pll_register_string')) 
    {
        $btn_text = get_theme_mod( 'header_button_1', 'Button 1' );
        pll_register_string('flatsome_button1', $btn_text, 'flatsome');
        $btn_text_2 = get_theme_mod( 'header_button_2', 'Button 2' );
        pll_register_string('flatsome_button2', $btn_text_2, 'flatsome');
        $sale_text = get_theme_mod( 'sale_bubble_text', 'Знижка' );
        pll_register_string('sale_bubble_text', $sale_text, 'flatsome');
    }
}
add_action('init', 'mrkv_flatsome_button_for_polylang');

add_filter('theme_mod_header_button_1', function($value){
    return function_exists('pll__') ? pll__($value) : $value;
});

add_filter('theme_mod_header_button_2', function($value){
    return function_exists('pll__') ? pll__($value) : $value;
});

add_filter('theme_mod_sale_bubble_text', function($value){
    return function_exists('pll__') ? pll__($value) : $value;
});

add_filter('woocommerce_sale_flash', function($html, $post, $product) {
    if (!get_theme_mod('sale_bubble', 1) || !$product->is_on_sale()) {
        return $html;
    }

    $custom_text = pll__( get_theme_mod('sale_bubble_text', 'Sale!') );
    $text        = $custom_text ? $custom_text : __( 'Sale!', 'woocommerce' );

    if (get_theme_mod('sale_bubble_percentage')) {
        $text = flatsome_presentage_bubble($product, $text);
    }

    $badge_style = get_theme_mod('badge_style', 'default');

    return '<div class="callout badge badge-' . esc_attr($badge_style) . '">
                <div class="badge-inner secondary on-sale">
                    <span class="onsale">' . esc_html($text) . '</span>
                </div>
            </div>';
}, 10, 3);