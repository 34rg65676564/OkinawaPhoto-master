<?php
/**
 * ðã­ã°ã¤ã³ã¦ã¼ã¶ã¼ã®ã¿
 * ãã®ãã¡ã¤ã«ã§ã¯æç¨¿ãã¼ã¸ãã«ãã´ãªã¼è¨­å®ãã¼ã¸ã§ç¨ãããã
 * ã«ã¹ã¿ã ãã£ã¼ã«ãç³»ã®é¢æ°ãã¾ã¨ãã¦ãã¾ãã
 */

/*****************************
 * æç¨¿/åºå®ãã¼ã¸ã®ã«ã¹ã¿ã ãã£ã¼ã«ã
 ******************************/
add_action('admin_menu', 'add_sngmeta_field');
add_action('save_post', 'save_sngmeta_field');

function add_sngmeta_field() {
  // ä½æ
  // æç¨¿ãã¼ã¸
  add_meta_box('sng-meta-description', 'ã¡ã¿ãã¹ã¯ãªãã·ã§ã³', 'sng_field_meta_description', 'post', 'normal');
  add_meta_box('sng-meta-description', 'ã¡ã¿ãã¹ã¯ãªãã·ã§ã³', 'sng_field_meta_description', 'page', 'normal');
  add_meta_box('sng-title-tag', 'ãé«åº¦ãªè¨­å®ãtitleã¿ã°', 'sng_field_title_tag', 'post', 'normal');
  add_meta_box('sng-title-tag', 'ãé«åº¦ãªè¨­å®ãtitleã¿ã°', 'sng_field_title_tag', 'page', 'normal');
  add_meta_box('sng-canonical-url', 'Canonical URL', 'sng_field_canonical_url', 'post', 'normal');
  add_meta_box('sng-canonical-url', 'Canonical URL', 'sng_field_canonical_url', 'page', 'normal');
  add_meta_box('sng-meta-roboto', 'ã¡ã¿ã­ãããè¨­å®', 'sng_field_meta_robots', 'post', 'side');
  add_meta_box('sng-meta-roboto', 'ã¡ã¿ã­ãããè¨­å®', 'sng_field_meta_robots', 'page', 'side');
  add_meta_box('sng-no-ads', 'åºåãéè¡¨ç¤º', 'disable_ads', 'post', 'side');
  add_meta_box('sng-no-share-buttons', 'ã·ã§ã¢ãã¿ã³ãéè¡¨ç¤º', 'sng_field_disable_share', 'post', 'side');
  add_meta_box('sng-no-share-buttons', 'ã·ã§ã¢ãã¿ã³ãéè¡¨ç¤º', 'sng_field_disable_share', 'page', 'side');
  add_meta_box('sng-one-column', '1ã«ã©ã ï¼ãµã¤ããã¼ãéè¡¨ç¤ºï¼', 'sng_field_one_column', 'post', 'side');
  add_meta_box('sng-contents-style', 'ã³ã³ãã³ãè¨­å®', 'sng_field_content_width', 'page', 'side');
}

function sng_field_meta_description() {
  global $post;
  echo '<p class="howto">Googleæ¤ç´¢çµæãªã©ã«è¡¨ç¤ºãããè¨äºã®è¦ç´ã§ãï¼å¥åã¯å¿é ã§ã¯ããã¾ããï¼ã100å­ä»¥åã«æããã®ãè¯ããã¨æãã¾ãã</p><textarea name="sng_meta_description" cols="65" rows="4" onkeyup="document.getElementById(\'description_count\').value=this.value.length + \'å­\'" style="max-width: 100%">' . get_post_meta($post->ID, 'sng_meta_description', true) . '</textarea><p><strong><input type="text" id="description_count" style="float: none;width: 40px;display: inline;border: none;box-shadow: none;"></strong></p>';
}

function sng_field_title_tag() {
  global $post;
  $result = '<p class="howto">è¨äºã¿ã¤ãã«ã¨ã¯å¥ã®titleã¿ã°ãåºåãããå ´åã«å¥åãã¾ããç©ºæ¬ã«ããã¨è¨äºã¿ã¤ãã«ãtitleã¿ã°ã«åºåããã¾ãã</p>';
  $result .= '<textarea name="sng_title" cols="65" rows="1" style="max-width: 100%">'. get_post_meta($post->ID, 'sng_title', true) . '</textarea>';
  echo $result;
}

function sng_field_canonical_url() {
  global $post;
  $result = '<p class="howto">ã«ããã«ã«URLãæå®ãã¾ããåºæ¬çã«ã¯ç©ºã§æ§ãã¾ããã</p>';
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
  $data = "1ã«ã©ã ã§è¡¨ç¤º";
  $check = ($meta_value) ? "checked" : "";
  echo '<div><label><input type="checkbox" name="one_column_options" value="' . $data . '" ' . $check . '>' . $data . '</label></div>';
}

function disable_ads() {
  global $post;
  $meta_value = get_post_meta($post->ID, 'disable_ads', true);
  $data = "åºåãéè¡¨ç¤ºã«ãã";
  $check = ($meta_value) ? "checked" : "";
  echo '<div><label><input type="checkbox" name="disable_ads" value="' . $data . '" ' . $check . '>' . $data . '</label></div>';
}

function sng_field_disable_share() {
  global $post;
  $meta_value = get_post_meta($post->ID, 'sng_disable_share', true);
  $data = "ã·ã§ã¢ãã¿ã³ãéè¡¨ç¤ºã«ãã";
  $check = ($meta_value) ? "checked" : "";
  echo '<div><label><input type="checkbox" name="sng_disable_share" value="' . $data . '" ' . $check . '>' . $data . '</label></div>';
}

function sng_field_content_width() {
  global $post;
  $meta_value = get_post_meta($post->ID, 'sng_content_width', true);
  echo '<div><span style="font-weight: bold;">ããããã¼ã¸ç¨ 1ã«ã©ã </span>ã®ãã³ãã¬ã¼ãã«ã®ã¿æå¹ãªè¨­å®</div>';
  echo '<div style="margin-top: 10px;"><label>ã³ã³ãã³ãæå¤§å¹</label><input type="text" name="sng_content_width" value="' . $meta_value . '">px</div>';

  $meta_value = get_post_meta($post->ID, 'sng_content_padding_zero', true);
  $data = "ã³ã³ãã³ãä¸ä¸ã®ä½ç½ããªãã";
  $check = ($meta_value) ? "checked" : "";
  echo '<div style="margin-top: 10px;"><label><input type="checkbox" name="sng_content_padding_zero" value="' . $data . '" ' . $check . '>ã³ã³ãã³ãä¸ä¸ã®ä½ç½ããªãã</label></div>';
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

// å¤ãä¿å­
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
