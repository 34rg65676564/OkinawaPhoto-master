<?php

function sng_slider_js() {
  $script =<<< EOM
  jQuery(function(){
    if (jQuery(".js-sng-slider-alt").length > 0) {
      jQuery(".js-sng-slider-alt").slick();
    }
    if (jQuery(".js-sng-slider").length > 0) {
      jQuery(".js-sng-slider").slick({
        responsive: [
          {
            breakpoint: 768,
            settings: {
              slidesToShow: 1,
              slidesToScroll: 1
            }
          }
        ]
      });
    }
  });
EOM;
  echo '<script>' . sng_minify_js($script) . '</script>';
}

function sng_slider_render( $block_content, $block ) {
  if ( isset($block['blockName']) && $block['blockName'] === 'sgb/slider') {
    wp_enqueue_style('slick-style', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.min.css');
    wp_enqueue_style('slick-theme-style', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick-theme.min.css');
    wp_enqueue_script(
      'sng-slick', // Handle.
      'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.js'
    );
    add_action('wp_footer', 'sng_slider_js', 999);
  }
  return $block_content;
}

add_filter( 'render_block', 'sng_slider_render', 10, 2 );
