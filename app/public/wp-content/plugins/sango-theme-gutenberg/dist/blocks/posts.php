<?php

register_block_type('sgb/posts', array(
	'editor_script' => 'sgb',
	'attributes' => array(
		'layoutName' => array(
			'type' => 'string',
			'default' => 'card'
		),
		'numberOfItems' => array(
			'type'    => 'number',
			'default' => 6,
		),
    'skipItems' => array(
      'type' => 'number',
      'default' => 0,
    ),
		'orderBy' => array(
			'type'    => 'string',
			'default' => 'date',
		),
		'order' => array(
			'type'    => 'string',
			'default' => 'DESC',
		),
		'showDate' => array(
			'type' => 'boolean',
			'default' => false,
		),
		'countSpan' => array(
			'type'    => 'number',
			'default' => 7,
		),
		'categories' => array(
			'type'     => 'array',
			'default'  => array(),
			'items' => array(
				'type' => 'number'
			)
		),
		'includeChildren' => array(
			'type'     => 'boolean',
			'default'  => false,
		),
		'tags' => array(
			'type' => 'array',
			'default' => array(),
			'items' => array(
				'type' => 'number'
			)
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
    'showInfeed' => array(
      'type' => 'boolean',
      'default' => false,
    )
	),
	'render_callback' => function ($attributes) {
		$style_name = $attributes['layoutName'];
		$number_of_items = $attributes['numberOfItems'];
    $skip_items = $attributes['skipItems'];
		$order = $attributes['order'];
		$order_by = $attributes['orderBy'];
		$show_date = $attributes['showDate'];
		$cats = $attributes['categories'];
		$include_children = $attributes['includeChildren'];
    $show_infeed = $attributes['showInfeed'];
		$tags = $attributes['tags'];
		$query = array(
			'post_type' => 'post',
			'post_status' => 'publish',
			'numberposts' => $number_of_items,
			'order' => $order,
		);
		if ($order_by === 'popular') {
			$query = array_merge($query, array(
				'meta_key' => 'post_views_count',
				'orderby' => 'meta_value_num',
			));
		} else {
			$query = array_merge($query, array(
				'orderby' => $order_by
			));
		}
    if ($skip_items > 0) {
      $query = array_merge($query, array(
        'offset' => $skip_items
      ));
    }
		// 子カテゴリを含める（オプション）
		if (count($cats) > 0 && $include_children) {
			foreach ($cats as $parent_id) {
				$child_ids = get_term_children($parent_id, 'category');
				$cats = array_merge($cats, $child_ids);
			}
		}
		if (count($cats) > 0) {
			$query = array_merge($query, array(
				'category__in' => $cats,
			));
		}
		if (count($tags) > 0) {
			$query = array_merge($query, array(
				'tag__in' => $tags,
			));
		}
		$posts = get_posts($query);
		if (count($posts) === 0) {
			return '';
		}
		$result = sng_get_cat_tag_post_output($posts, $style_name, true, $show_infeed);
		return $result;
	}
));
