<?php 
/*********************
 * 🖍ログインユーザーのみ
 * カテゴリーページへのフィールドの追加/リンクの出力
 *********************/

// カテゴリーページに「カスタムの入力欄」を表示
function sng_add_archive_title($term)
{
  $termid = $term->term_id;
  $taxonomy = $term->taxonomy;
  $term_meta = get_option($taxonomy . '_' . $termid);
  $hide_posts = isset($term_meta['category_hide_posts']) ? esc_attr($term_meta['category_hide_posts']) : '';
  $hide_posts_checked = $hide_posts ? ' checked' : '';

  $hide_header = isset($term_meta['category_hide_header']) ? esc_attr($term_meta['category_hide_header']) : '';
  $hide_header_checked = $hide_header ? ' checked' : '';

  $hide_infeed = isset($term_meta['category_hide_infeed']) ? esc_attr($term_meta['category_hide_infeed']) : '';
  $hide_infeed_checked = $hide_infeed ? ' checked' : '';

  ?>
  <tr class="form-field">
    <th scope="row"><label for="term_meta[category_title]">ページタイトル</label></th>
    <td>
      <textarea name="term_meta[category_title]" id="term_meta[category_title]" rows="1" cols="50" class="large-text"><?php echo isset($term_meta['category_title']) ? esc_attr($term_meta['category_title']) : ''; ?></textarea>
      <p class="description">カテゴリーページのタイトルを入力します。空欄の場合、カテゴリー名がページタイトルとなります。</p>
    </td>
  </tr>
  <tr class="form-field">
    <th scope="row"><label for="term_meta[category_description]">メタデスクリプション</label></th>
    <td>
      <textarea name="term_meta[category_description]" id="term_meta[category_description]" rows="3" cols="50" class="large-text"><?php echo isset($term_meta['category_description']) ? esc_attr($term_meta['category_description']) : ''; ?></textarea>
      <p class="description">カテゴリーページのメタデスクリプションを入力します。検索結果にページの説明文として表示されることがあります。</p>
    </td>
  </tr>
  <tr class="form-field">
    <th scope="row"><label for="term_meta[category_hide_header]">アーカイブヘッダーを非表示</label></th>
    <td>
      <label class="description">
        <input type="checkbox" <?php echo $hide_header_checked  ?> value="true" name="term_meta[category_hide_header]" id="term_meta[category_hide_header]" />
        このカテゴリーページではアーカイブヘッダーを非表示にします。
      </label>
    </td>
  </tr>
  <tr class="form-field">
    <th scope="row"><label for="term_meta[category_hide_posts]">記事一覧を非表示</label></th>
    <td>
      <label class="description">
        <input type="checkbox" <?php echo $hide_posts_checked ?> value="true" name="term_meta[category_hide_posts]" id="term_meta[category_hide_posts]" />
        このカテゴリーページでは記事一覧を非表示にします。
      </label>
    </td>
  </tr>
  <tr class="form-field">
    <th scope="row"><label for="term_meta[category_hide_infeed]">インフィード広告を非表示</label></th>
    <td>
      <label class="description">
        <input type="checkbox" <?php echo $hide_infeed_checked ?> value="true" name="term_meta[category_hide_infeed]" id="term_meta[category_hide_infeed]" />
        カスタマイザーでインフィード広告を有効にしている場合、このカテゴリーではインフィード広告を非表示にします。
      </label>
    </td>
  </tr>
<?php
}
add_action('category_edit_form_fields', 'sng_add_archive_title');

// オリジナルタイトルを保存
function sng_save_archive_title($term_id)
{
  global $taxonomy;
  if (isset($_POST['term_meta'])) {
    $term_meta = get_option($taxonomy . '_' . $term_id);
    $term_keys = array_keys($_POST['term_meta']);
    $term_meta["category_hide_posts"] = '';
    $term_meta["category_hide_header"] = '';
    $term_meta["category_hide_infeed"] = '';
    foreach ($term_keys as $key) {
      if (isset($_POST['term_meta'][$key])) {
        $term_meta[$key] = stripslashes_deep($_POST['term_meta'][$key]);
      }
    }
    update_option($taxonomy . '_' . $term_id, $term_meta);
  }
}
add_action('edited_term', 'sng_save_archive_title'); //値を保存

// アーカイブの説明欄でHTMLタグを使えるように
remove_filter('pre_term_description', 'wp_filter_kses');
