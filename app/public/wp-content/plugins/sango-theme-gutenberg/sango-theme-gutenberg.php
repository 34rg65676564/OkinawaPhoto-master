<?php
/**
 * Plugin Name:     SANGO Gutenberg
 * Plugin URI:      https://saruwakakun.com/sango/gutenberg
 * Description:     SANGOでGutenbergエディターを快適に使用するためのプラグイン
 * Author:          CatNose
 * Author URI:      https://saruwakakun.com/sango
 * Text Domain:     sango-theme-gutenberg
 * Domain Path:     /languages
 * Version:         1.40.0
 *
 * @package SGB
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( "SGB_VER_NUM", "1.40.0" );

require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';
require_once plugin_dir_path( __FILE__ ) . 'dist/color.php';
require_once plugin_dir_path( __FILE__ ) . 'dist/init.php';

// Auto update
require_once plugin_dir_path( __FILE__ ) . 'plugin-update-checker/plugin-update-checker.php';
$SangoGutenbergUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
    'https://sango-gutenberg.netlify.com/update-info.json',
    __FILE__,
    'sango-theme-gutenberg'
);
