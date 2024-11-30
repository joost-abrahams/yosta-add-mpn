<?php
/*
Plugin Name: Yosta add MPN
Description: Add MPN
Version: 1.0
Author: Joost Abrahams
Author URI: https://mantablog.nl
GitHub Plugin URI: https://github.com/joost-abrahams/yosta-add-mpn/
Source  URI: https://wpfactory.com/blog/how-to-add-mpn-to-woocommerce/
License: GPLv3
Requires Plugins: woocommerce
*/

// Exit if accessed directly
defined( 'ABSPATH' ) or die;

//declare complianz with consent level API
$plugin = plugin_basename( __FILE__ );
add_filter( "wp_consent_api_registered_{$plugin}", '__return_true' );

// Happy hacking

add_action( 'woocommerce_product_options_sku', 'wpfactory_add_mpn' );
add_action( 'save_post_product', 'wpfactory_save_mpn', 10, 2 );

if ( ! function_exists( 'wpfactory_add_mpn' ) ) {
    /**
     * Adds MPN field to product admin edit page.
     */
    function wpfactory_add_mpn() {
        woocommerce_wp_text_input( array(
            'id'    => 'mpn',
            'value' => get_post_meta( get_the_ID(), 'mpn', true ),
            'label' => esc_html__( 'MPN', 'text-domain' ),
        ) );
    }
}

if ( ! function_exists( 'wpfactory_save_mpn' ) ) {
    /**
     * Saves product MPN field.
     */
    function wpfactory_save_mpn( $post_id, $__post ) {
        if ( isset( $_POST['mpn'] ) && empty( $_REQUEST['woocommerce_quick_edit'] ) ) {
            update_post_meta( $post_id, 'mpn', wc_clean( $_POST['mpn'] ) );
        }
    }
}

add_action( 'woocommerce_variation_options_pricing', 'wpfactory_add_mpn_variation', 10, 3 );
add_action( 'woocommerce_save_product_variation', 'wpfactory_save_mpn_variation', 10, 2 );

if ( ! function_exists( 'wpfactory_add_mpn_variation' ) ) {
    /**
     * Adds MPN field to variations.
     */
    function wpfactory_add_mpn_variation( $loop, $variation_data, $variation ) {
        woocommerce_wp_text_input( array(
            'id'            => "variable_mpn_{$loop}",
            'name'          => "variable_mpn[{$loop}]",
            'value'         => ( isset( $variation_data['mpn'][0] ) ? $variation_data['mpn'][0] : '' ),
            'label'         => esc_html__( 'MPN', 'text-domain' ),
            'wrapper_class' => 'form-row form-row-full',
        ) );
    }
}

if ( ! function_exists( 'wpfactory_save_mpn_variation' ) ) {
    /**
     * Saves variation MPN field.
     */
    function wpfactory_save_mpn_variation( $variation_id, $i ) {
        if ( isset( $_POST['variable_mpn'][ $i ] ) ) {
            update_post_meta( $variation_id, 'mpn', wc_clean( $_POST['variable_mpn'][ $i ] ) );
        }
    }
}

add_action( 'woocommerce_product_meta_start', 'wpfactory_display_mpn' );

if ( ! function_exists( 'wpfactory_display_mpn' ) ) {
    /**
     * Display MPN.
     */
    function wpfactory_display_mpn() {
        if ( '' !== ( $mpn = get_post_meta( get_the_ID(), 'mpn', true ) ) ) {
            printf( '<span class="mpn_wrapper">%s: <span class="mpn">%s</span></span>',
                esc_html__( 'MPN', 'text-domain' ), $mpn );
        }
    }
}

add_filter( 'woocommerce_available_variation', 'wpfactory_display_mpn_variation', 10, 3 );

if ( ! function_exists( 'wpfactory_display_mpn_variation' ) ) {
    /**
     * Display MPN in variation description.
     */
    function wpfactory_display_mpn_variation( $args, $product, $variation ) {
        if ( '' !== ( $mpn = $variation->get_meta( 'mpn' ) ) ) {
            $args['variation_description'] .= sprintf( '<span class="mpn_wrapper">%s: <span class="mpn">%s</span></span>',
                esc_html__( 'MPN', 'text-domain' ), $mpn );
        }
        return $args;
    }
}

add_filter( 'woocommerce_structured_data_product', 'wpfactory_add_mpn_structured_data_product', 10, 2 );

if ( ! function_exists( 'wpfactory_add_mpn_structured_data_product' ) ) {
    /**
     * Adds MPN field to product structured data.
     */
    function wpfactory_add_mpn_structured_data_product( $markup, $product ) {
        if ( '' !== ( $mpn = get_post_meta( $product->get_id(), 'mpn', true ) ) ) {
            $markup['mpn'] = $mpn;
        }
        return $markup;
    }
}

