<?php
/**
 * REST API
 */

use SangoBlocks\App;

App::get('rest')->register(array(
  'path' => 'search-posts',
  'methods' => 'GET',
  'callback' => function ($req) {
      $params = $req->get_params();
      $args  = array_merge(
        array('s' => $params['search']),
        array(
          'post_status' => 'publish', // 公開済み
          'post_type'   => array( 'post', 'page' ), // 投稿ページと固定ページ
        )
      );
      $query = new WP_Query($args);
      return json_decode(json_encode($query->get_posts()));
  }
));
