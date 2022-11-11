<?php

namespace SangoBlocks;

class ContentBlock
{
  public function init()
  {
    $this->register_content_block();
    $this->register_content_block_as_block_patterns();
    $this->register_shortcode();
  }
  // コンテンツブロックの登録
  public function register_content_block()
  {
    $plugin_path = App::rootPluginUrl();
    register_post_type(
      'content_block',
      array(
        'labels'      => array(
          'name'      => 'コンテンツ・ブロック',
          'all_items' => 'コンテンツ・ブロック一覧',
        ),
        'public'        => true,
        'has_archive'   => false,
        'show_in_rest'  => true,
        'menu_position' => 20,
        'menu_icon'     => $plugin_path . 'images/content-block.png'
      )
    );
  }

  // 利用可能なコンテンツブロックを取得
  public function available_content_blocks()
  {
    return get_posts(
      array(
        'numberposts' => -1,
        'post_type'   => 'content_block',
        'post_status' => 'publish'
      )
    );
  }

  // 利用可能なコンテンツブロックの(id => 名前)の一覧をオブジェクトの配列で出力
  // 例
  // [ '25' => '自己紹介', '10' => '記事下用' ]
  public function available_content_block_name_list()
  {
      $posts = $this->available_content_blocks();
      if (! $posts) {
          return null;
      }

      $blocks = array();
      foreach ($posts as $post) {
          $blocks[ $post->ID ] = $post->post_title;
      }
      return $blocks;
  }

  public function register_content_block_as_block_patterns()
  {
      $availableBlocks = $this->available_content_blocks();
      if (!function_exists('register_block_pattern_category')) {
        return;
      }
      register_block_pattern_category(
        'sgb/content-block',
        array( 'label' => 'SANGO コンテンツブロック' )
      );

      foreach ($availableBlocks as $block) {
          $content = $block->post_content;
          $title = $block->post_title;
          $id = $block->ID;
          register_block_pattern(
            'sgb/'.$id,
            array(
              'title' => $title,
              'categories' => array('sgb/content-block'),
              'content' => $content
            )
          );
      }
  }

  public function register_shortcode() {
    add_shortcode('content_block', array($this, 'get_shortcode_content_block'));
  }

  public function get_shortcode_content_block($atts) {
    $class = isset($atts['class']) ? $atts['class'] : '';
    if (!isset($atts['id'])) {
      return '';
    }
    return $this->get_content_block($atts['id'], $class);
  }

  public function get_content_block($id, $class = '')
  {
      if (!$id) {
          return;
      }
      $the_post = get_post($id);
      // コンテンツブロックが見つからない場合
      if (! $the_post) {
          return '';
      }

      if ($the_post->post_type !== 'content_block') {
        return '';
      }
      // コンテンツブロックが非公開の場合は、ログインユーザーのみ閲覧可
      if (! ('publish' === $the_post->post_status || is_user_logged_in())) {
          return '';
      }
      // phpcs:ignore

      $edit_url = get_edit_post_link($id);

      return
      '<div class="post-' . $id . ' ' . $class . '">' .
        apply_filters('the_content', $the_post->post_content) .
      '</div>';
  }
}

