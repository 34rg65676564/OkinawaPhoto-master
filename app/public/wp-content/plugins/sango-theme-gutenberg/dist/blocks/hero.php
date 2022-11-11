<?php

function sng_movie_js() {
  $script =<<< EOM
  jQuery(function(){
    jQuery(".js-header-video").each(function() {
      var src = jQuery(this).attr("data-src");
      jQuery(this).attr("src", src);
    });
  });
EOM;
  echo '<script>' . sng_minify_js($script) . '</script>';
}

function sng_movie_render( $block_content, $block ) {
  if ( isset($block['blockName']) && $block['blockName'] === 'sgb/hero') {
    add_action('wp_footer', 'sng_movie_js', 999);
  }
  return $block_content;
}

add_filter( 'render_block', 'sng_movie_render', 10, 2 );
