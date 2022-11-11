<?php

if (!function_exists('sng_normal_link')) {
  function sng_normal_link($atts)
  {
    $output = '';
    $ids = isset($atts['id']) ? explode(',', $atts['id']) : null;
    if(!$ids) return "";
    $target = isset($atts['target']) ? ' target="_blank"' : "";
    $is_date = isset($atts['is_date']) && $atts['is_date'];

    foreach ($ids as $eachid) {
      list($url, $title, $img, $date) = sng_get_entry_link_data($eachid, 'thumb-520', $is_date);
      if ($url && $title) {
          $output .= <<<EOF
<p>
<a href="{$url}"{$target}>
  {$title}
</a>
</p>
EOF;
      } // endif
    } // end foreach
    return $output;
  }
}

register_block_type('sgb/kanren', array(
  'editor_script' => 'sgb',
  'attributes' => array(
    'id' => array(
      'type' => 'number',
      'default' => -1,
    ),
    'target' => array(
      'type' => 'boolean',
      'default' => false,
    ),
    'showDate' => array(
      'type' => 'boolean',
      'default' => true
    ),
    'type' => array(
      'type' => 'string',
      'default' => 'sng_entry_link'
    ),
    'css' => array(
      'type' => 'string',
      'default' => '',
    ),
    'scopedCSS' => array(
      'type' => 'string',
      'default' => '',
    ),
    'blockId' => array(
      'type' => 'string',
      'default' => '',
    ),
    'link' => array(
      'type' => 'boolean',
      'default' => false,
    )
  ),
  'render_callback' => function ($attributes) {
    $show_date      = $attributes['showDate'];
    $id = $attributes['id'];
    $target = $attributes['target'];
    $type = $attributes['type'];
    $link = $attributes['link'];

    if (!$id) {
        return '';
    }

    $option = array(
      'is_date' => $show_date,
      'id' => $id,
    );

    if ($target) {
      $option = array_merge($option, array( 'target' => true ));
    }

    if ($link) {
      return sng_normal_link($option);
    }

    if ($type === 'sng_card_link') {
      return sng_card_link($option);
    }

    if ($type === 'sng_longcard_link') {
      return sng_longcard_link($option);
    }
    return sng_entry_link($option);
  }
));
