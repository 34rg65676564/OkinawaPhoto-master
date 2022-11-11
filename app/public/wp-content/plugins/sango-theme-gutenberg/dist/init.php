<?php
/**
 * Initialize Plugin
 *
 * Enqueue CSS/JS for Gutenberg on SANGO default styles and  all the blocks.
 *
 * @since   1.0.0
 * @package SGB
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use SangoBlocks\App;

/**
 * Enqueue Gutenberg block assets for both frontend + Gutenberg.
 *
 * @since 1.0.0
 */

function sango_theme_gutenberg_common_assets() { // phpcs:ignore
	// Styles.
	wp_enqueue_style(
		'sango_theme_gutenberg-style',
		plugins_url( 'dist/blocks.style.build.css?ver', dirname( __FILE__ ) ).SGB_VER_NUM
	);
}

/*******************************
 * bodyに付与するクラスを追加
*******************************/
if (!function_exists('sng_admin_original_body_class')) {
  function sng_admin_original_body_class($classes) {
    // FontAwesome5を使用している場合"fa5"を付与
    $classes .= get_option('use_fontawesome4') ? ' fa4 ' : ' fa5 ';
    return $classes;
  }
}
add_filter('admin_body_class', 'sng_admin_original_body_class');

// Hook: Frontend assets.
add_action( 'enqueue_block_assets', 'sango_theme_gutenberg_common_assets' );

/**
 * Enqueue Gutenberg block assets for Gutenberg editor.
 *
 * @since 1.0.0
 */

function sng_original_admin_body_class($classes) {
	// FontAwesome5を使用している場合"fa5"を付与
	$classes .= get_option('use_fontawesome4') ? 'fa4' : 'fa5';
	return $classes;
}

function sango_theme_gutenberg_editor_assets() { // phpcs:ignore

	add_filter('admin_body_class', 'sng_original_admin_body_class');
  global $pagenow;
	// Scripts.
  $deps = $pagenow === 'widgets.php' ? array( 'wp-blocks', 'wp-element', 'wp-rich-text', 'wp-plugins', 'wp-edit-widgets' ) : array( 'wp-blocks', 'wp-element', 'wp-editor', 'wp-rich-text', 'wp-plugins', 'wp-edit-post' );

  wp_enqueue_script(
    'sango_theme_gutenberg-block-js', // Handle.
    plugins_url( '/dist/blocks.build.js?ver', dirname( __FILE__ ) ).SGB_VER_NUM,
    $deps
  );

  // 親テーマのget_optionの値を全てエディターのJSにわたす->必要に応じてブロックで利用
	// $editor_include_options = wp_load_alloptions();
	$cats = get_categories();
	$categories = array();
	foreach ($cats as $cat) {
		$categories[] = $cat;
	}
	$tgs = get_tags();
	$tags = array();
	foreach ($tgs as $tag) {
		$tags[] = $tag;
	}
	$plugin_path = plugin_dir_url( __FILE__ );
  $editor_include_options = array(
		'site_url' => site_url(),
    'say_image_upload' => get_option("say_image_upload"),
		'say_name' => get_option("say_name"),
		'image_dir' => $plugin_path.'/images',
		'categories' => $categories,
		'tags' => $tags,
    'custom_formats' => @App::get('format')->get(),
    'custom_colors' => @App::get('color')->get(),
    'infeed_enabled' => get_theme_mod('enable_ad_infeed', false)
  );
  wp_localize_script( 'sango_theme_gutenberg-block-js', 'sgb_parent_options', $editor_include_options );
  wp_enqueue_code_editor(['type' => 'text/css']);
  wp_enqueue_style('wp-codemirror');
	// Block Editor Styles.
	wp_enqueue_style(
		'sango_theme_gutenberg-block-editor-style', // Handle.
		plugins_url( 'dist/blocks.editor.build.css?ver', dirname( __FILE__ ) ).SGB_VER_NUM,
    array( 'wp-edit-blocks' )
  );
  // Custom styles from SANGO theme.
  wp_add_inline_style( 'sango_theme_gutenberg-block-editor-style', sango_theme_parent_colors().sgb_custom_colors() );

  wp_enqueue_style('slick-style', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.min.css');
  wp_enqueue_style('slick-theme-style', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick-theme.min.css');
}

// Hook: Editor assets.
add_action( 'enqueue_block_editor_assets', 'sango_theme_gutenberg_editor_assets' );

/**
 * Include FontAwesome to Gutenberg editor
 *
 * @since 1.0.0
 */
function sango_theme_gutenberg_font_awesome() { // phpcs:ignore
  $fontawesome5_ver = get_option('fontawesome5_ver_num') ? preg_replace("/( |　)/", "", get_option('fontawesome5_ver_num') ) : '5.7.2';
  wp_enqueue_style(
    'sango_theme_gutenberg_font_awesome',
    'https://use.fontawesome.com/releases/v'. $fontawesome5_ver .'/css/all.css'
  );
}
add_action( 'enqueue_block_editor_assets', 'sango_theme_gutenberg_font_awesome' );

/**
 * Register SANGO custom block category
 *
 * @since 1.0.0
 */
function sango_theme_gutenberg_register_block_category( $categories ) {
  $categories[] = [
    'slug'  => 'sgb_custom',
    'title' => 'SANGOカスタムブロック'
  ];
  return $categories;
}

if ( function_exists( 'get_default_block_categories' ) && function_exists( 'get_block_editor_settings' ) ) {
  add_filter( 'block_categories_all', 'sango_theme_gutenberg_register_block_category' );
} else{
  add_filter( 'block_categories', 'sango_theme_gutenberg_register_block_category' );
}

/**
 * Register SANGO custom inline css (Frontend + Backend)
 *
 * @since 1.0.4
 */
function sango_theme_gutenberg_custom_inline_css() { // phpcs:ignore
  wp_add_inline_style( 'sango_theme_gutenberg-style', sgb_custom_colors().sgb_custom_format() );
}
add_action( 'wp_enqueue_scripts', 'sango_theme_gutenberg_custom_inline_css' );

function sango_block_init() {
  add_theme_support( 'align-wide' );
	require_once plugin_dir_path( __FILE__ ). './blocks/posts.php';
	require_once plugin_dir_path( __FILE__ ). './blocks/kanren.php';
  require_once plugin_dir_path( __FILE__ ). './blocks/conditional.php';
  require_once plugin_dir_path( __FILE__ ). './blocks/slider.php';
  require_once plugin_dir_path( __FILE__ ). './blocks/hero.php';
  require_once plugin_dir_path( __FILE__ ). './blocks/custom-css.php';
  require_once plugin_dir_path( __FILE__ ). './blocks/codebox.php';
  require_once plugin_dir_path( __FILE__ ). './blocks/table.php';
  App::setRootPluginDir(plugin_dir_path( __FILE__ ));
  App::setRootPluginUrl(plugin_dir_url( __FILE__ ));
  App::register('format', 'SangoBlocks\Format');
  App::register('css', 'SangoBlocks\CustomCSS');
  App::register('color', 'SangoBlocks\Color');
  App::register('content-block', 'SangoBlocks\ContentBlock');
	if (!is_user_logged_in()) {
		return;
	}
	App::register('rest', 'SangoBlocks\Rest');
  App::register('preset', 'SangoBlocks\Preset');
	App::run();
}

add_action('init', 'sango_block_init');
add_action('widgets_init', function () {
  require_once plugin_dir_path( __FILE__ ). './classes/ContentBlockWidget.php';
  register_widget('ContentBlockWidget');
});

if (!function_exists('sng_content_block_template')) {
  function sng_content_block_template($single)
  {
    global $post;
    $plugin_path = plugin_dir_path( __FILE__ );
    if (get_post_type($post) === "content_block") {
      if (file_exists($plugin_path . 'single-content_block.php')) {
          return $plugin_path . 'single-content_block.php';
      }
    }
    return $single;
  }
}

add_filter('single_template', 'sng_content_block_template');

if (!function_exists('sng_block_tab_js')) {
  function sng_block_tab_js()
  {
		$script =<<< EOM
jQuery(function(){
  jQuery('.wp-block-sgb-tab .post-tab').each(function(index){
    var tabAppendClass = "js-tab-id-" + index;
    jQuery(this).addClass(tabAppendClass);
    var activeColor = jQuery(this).data("activeColor");
    var style = jQuery("<style>");
    style.html(".post-tab."+ tabAppendClass + " > label.tab-active { background: "+ activeColor + "}");
    jQuery(document.body).append(style);
  });
  jQuery('.post-tab > label').click(function(){
		jQuery(this).siblings().removeClass('tab-active');
		var tab = jQuery(this).closest('.wp-block-sgb-tab');
		var panels = tab.children('.post-tab__content');
		panels.removeClass('tab-active');
		var tabClass = jQuery(this).attr('class').split(" ")[0];
		jQuery(this).addClass('tab-active');
		panels.each(function(){
			if (jQuery(this).attr('class').indexOf(tabClass) != -1) {
				jQuery(this).addClass('tab-active').fadeIn();
			} else {
				jQuery(this).hide();
			}
		});
  });
});
EOM;
    if (function_exists('sng_minify_js')) {
        echo '<script>' . sng_minify_js($script) . '</script>';
    } else {
        echo '<script>' . $script . '</script>';
    }
	}
}
add_action('wp_footer', 'sng_block_tab_js', 999);
