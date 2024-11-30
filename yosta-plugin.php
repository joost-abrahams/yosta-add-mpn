<?php
/*
Plugin Name: Yosta
Description: 
Version: 1.0
Author: Joost Abrahams
Author URI: https://mantablog.nl
GitHub Plugin URI: https://github.com/joost-abrahams/yosta-/
Source  URI: https://
License: GPLv3
Requires Plugins: woocommerce
*/

// Exit if accessed directly
defined( 'ABSPATH' ) or die;

//declare complianz with consent level API
$plugin = plugin_basename( __FILE__ );
add_filter( "wp_consent_api_registered_{$plugin}", '__return_true' );

// Happy hacking
