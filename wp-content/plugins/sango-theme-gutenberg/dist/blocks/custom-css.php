<?php

use SangoBlocks\App;

function sng_custom_css( $block_content, $block ) {
  if (substr($block['blockName'], 0, 4) === 'sgb/' && isset($block['attrs']['scopedCSS']) && isset($block['attrs']['blockId'])) {
    // var_dump($block['blockName'], $block['attrs']['scopedCSS']);
    $id = $block['attrs']['blockId'];
    App::get('css')->register($id, $block['attrs']['scopedCSS']);
    return "<div id=\"$id\">$block_content</div>";
  }
  return $block_content;
}

add_filter( 'render_block', 'sng_custom_css', 10, 2 );

function sng_render_custom_css() {
  $style = App::get('css')->get_style();
  if (!$style) {
    return;
  }
  echo '<style>'.  $style . '</style>';
}
add_action( 'wp_footer', 'sng_render_custom_css');
