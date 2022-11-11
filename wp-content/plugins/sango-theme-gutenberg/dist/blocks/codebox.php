<?php

function sng_highlight_js() {
  $script =<<< EOM
  jQuery(document).ready(function() {
    jQuery('pre.js-sng-highlight code').each(function(i, block) {
      hljs.highlightBlock(block);
    });
  });
EOM;
  echo '<script>' . sng_minify_js($script) . '</script>';
}

function sng_render_highlight( $block_content, $block ) {
  if ( isset($block['blockName']) && $block['blockName'] === 'sgb/codebox') {
    if (isset($block['attrs']['highlight']) && $block['attrs']['highlight']) {
      wp_enqueue_script(
        'sng-highlight', // Handle.
        '//cdnjs.cloudflare.com/ajax/libs/highlight.js/11.2.0/highlight.min.js'
      );
      add_action('wp_footer', 'sng_highlight_js', 999);
    }
  }
  return $block_content;
}

add_filter( 'render_block', 'sng_render_highlight', 10, 2 );
