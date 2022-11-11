<?php
/**
 * 🖍ログインユーザーのみ
 * このファイルでは投稿ページやカテゴリー設定ページで用いられる
 * カスタムフィールド系の関数をまとめています。
 */

/*****************************
 * 投稿/固定ページのカスタムフィールド
 ******************************/
add_action('admin_menu', 'add_sngmeta_field');
add_action('save_post', 'save_sngmeta_field');

function add_sngmeta_field() {
  // 作成
  // 投稿ページ
  add_meta_box('sng-meta-description', 'メタデスクリプション', 'sng_field_meta_description', 'post', 'normal');
  add_meta_box('sng-meta-description', 'メタデスクリプション', 'sng_field_meta_description', 'page', 'normal');
  add_meta_box('sng-title-tag', '【高度な設定】titleタグ', 'sng_field_title_tag', 'post', 'normal');
  add_meta_box('sng-title-tag', '【高度な設定】titleタグ', 'sng_field_title_tag', 'page', 'normal');
  add_meta_box('sng-canonical-url', 'Canonical URL', 'sng_field_canonical_url', 'post', 'normal');
  add_meta_box('sng-canonical-url', 'Canonical URL', 'sng_field_canonical_url', 'page', 'normal');
  add_meta_box('sng-meta-roboto', 'メタロボット設定', 'sng_field_meta_robots', 'post', 'side');
  add_meta_box('sng-meta-roboto', 'メタロボット設定', 'sng_field_meta_robots', 'page', 'side');
  add_meta_box('sng-no-ads', '広告を非表示', 'disable_ads', 'post', 'side');
  add_meta_box('sng-no-share-buttons', 'シェアボタンを非表示', 'sng_field_disable_share', 'post', 'side');
  add_meta_box('sng-no-share-buttons', 'シェアボタンを非表示', 'sng_field_disable_share', 'page', 'side');
  add_meta_box('sng-one-column', '1カラム（サイドバーを非表示）', 'sng_field_one_column', 'post', 'side');
  add_meta_box('sng-contents-style', 'コンテンツ設定', 'sng_field_content_width', 'page', 'side');
}

function sng_field_meta_description() {
  global $post;
  echo '<p class="howto">Google検索結果などに表示される記事の要約です（入力は必須ではありません）。100字以内に抑えるのが良いかと思います。</p><textarea name="sng_meta_description" cols="65" rows="4" onkeyup="document.getElementById(\'description_count\').value=this.value.length + \'字\'" style="max-width: 100%">' . get_post_meta($post->ID, 'sng_meta_description', true) . '</textarea><p><strong><input type="text" id="description_count" style="float: none;width: 40px;display: inline;border: none;box-shadow: none;"></strong></p>';
}

function sng_field_title_tag() {
  global $post;
  $result = '<p class="howto">記事タイトルとは別のtitleタグを出力したい場合に入力します。空欄にすると記事タイトルがtitleタグに出力されます。</p>';
  $result .= '<textarea name="sng_title" cols="65" rows="1" style="max-width: 100%">'. get_post_meta($post->ID, 'sng_title', true) . '</textarea>';
  echo $result;
}

function sng_field_canonical_url() {
  global $post;
  $result = '<p class="howto">カノニカルURLを指定します。基本的には空で構いません。</p>';
  $result .= '<textarea name="sng_canonical_url" cols="65" rows="1" style="max-width: 100%" placeholder="https://example.com/duplicate-page">'. get_post_meta($post->ID, 'sng_canonical_url', true) . '</textarea>';
  echo $result;
}

function sng_field_meta_robots() {
  global $post;
  $exist_options = get_post_meta($post->ID, 'noindex_options', true);
  $noindex_options = $exist_options ? $exist_options : array();
  $data = array("noindex", "nofollow");

  foreach ($data as $d) {
    $check = (in_array($d, $noindex_options)) ? "checked" : "";
    echo '<div><label><input type="checkbox" name="noindex_options[]" value="' . $d . '" ' . $check . '>' . $d . '</label></div>';
  }
}

function sng_field_one_column() {
  global $post;
  $meta_value = get_post_meta($post->ID, 'one_column_options', true);
  $data = "1カラムで表示";
  $check = ($meta_value) ? "checked" : "";
  echo '<div><label><input type="checkbox" name="one_column_options" value="' . $data . '" ' . $check . '>' . $data . '</label></div>';
}

function disable_ads() {
  global $post;
  $meta_value = get_post_meta($post->ID, 'disable_ads', true);
  $data = "広告を非表示にする";
  $check = ($meta_value) ? "checked" : "";
  echo '<div><label><input type="checkbox" name="disable_ads" value="' . $data . '" ' . $check . '>' . $data . '</label></div>';
}

function sng_field_disable_share() {
  global $post;
  $meta_value = get_post_meta($post->ID, 'sng_disable_share', true);
  $data = "シェアボタンを非表示にする";
  $check = ($meta_value) ? "checked" : "";
  echo '<div><label><input type="checkbox" name="sng_disable_share" value="' . $data . '" ' . $check . '>' . $data . '</label></div>';
}

function sng_field_content_width() {
  global $post;
  $meta_value = get_post_meta($post->ID, 'sng_content_width', true);
  echo '<div><span style="font-weight: bold;">トップページ用 1カラム</span>のテンプレートにのみ有効な設定</div>';
  echo '<div style="margin-top: 10px;"><label>コンテンツ最大幅</label><input type="text" name="sng_content_width" value="' . $meta_value . '">px</div>';

  $meta_value = get_post_meta($post->ID, 'sng_content_padding_zero', true);
  $data = "コンテンツ上下の余白をなくす";
  $check = ($meta_value) ? "checked" : "";
  echo '<div style="margin-top: 10px;"><label><input type="checkbox" name="sng_content_padding_zero" value="' . $data . '" ' . $check . '>コンテンツ上下の余白をなくす</label></div>';
}

function sng_update_custom_text_fields($post_id, $field_name) {
  (isset($_POST[$field_name])) ? update_post_meta($post_id, $field_name, $_POST[$field_name]) : "";
}

function sng_update_custom_option_fields($post_id, $field_name) {
  if (isset($_POST[$field_name])) {
    $value = $_POST[$field_name];
  } else {
    $value = '';
  }
  update_post_meta($post_id, $field_name, $value);
}

// 値を保存
function save_sngmeta_field($post_id)
{
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
    return $post_id;
  }

  sng_update_custom_text_fields($post_id, 'sng_meta_description');
  sng_update_custom_text_fields($post_id, 'sng_title');
  sng_update_custom_text_fields($post_id, 'sng_canonical_url');

  sng_update_custom_option_fields($post_id, 'noindex_options');
  sng_update_custom_option_fields($post_id, 'one_column_options');
  sng_update_custom_option_fields($post_id, 'disable_ads');
  sng_update_custom_option_fields($post_id, 'sng_disable_share');
  sng_update_custom_option_fields($post_id, 'sng_content_width');
  sng_update_custom_option_fields($post_id, 'sng_content_padding_zero');
}
