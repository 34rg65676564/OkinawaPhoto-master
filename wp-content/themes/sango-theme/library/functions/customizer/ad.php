<?php
/*********************
 * åºåè¨­å®
 *********************/
$wp_customize->add_section('sango_panel_ad',
  array(
    'priority' => 61,
    'title' => 'ðï¸ åºåè¨­å®',
  )
);

$wp_customize->add_setting('google_ad_code', array(
  'type' => 'theme_mod',
  'sanitize_callback' => 'sng_skip_sanitize',
));
$wp_customize->add_control('google_ad_code', array(
  'settings' => 'google_ad_code',
  'label' => 'Google AdSenseã®scriptã¿ã°',
  'description' => 'Google AdSenseã§åå¾ããã³ã¼ãã®scriptã¿ã°ã¯ãã¡ãã«å¥åãã¦ãã ããã',
  'section' => 'sango_panel_ad',
  'type' => 'textarea',
));


$wp_customize->add_setting('enable_ad_infeed', array(
  'type' => 'theme_mod',
  'sanitize_callback' => 'sng_slug_sanitize_checkbox',
));
$wp_customize->add_control('enable_ad_infeed', array(
  'settings' => 'enable_ad_infeed',
  'label' => 'ã¤ã³ãã£ã¼ãåºåãæå¹åãã',
  'section' => 'sango_panel_ad',
  'type' => 'checkbox',
));


$wp_customize->add_setting('ad_infeed', array(
  'type' => 'theme_mod',
  'sanitize_callback' => 'sng_skip_sanitize',
));
$wp_customize->add_control('ad_infeed', array(
  'settings' => 'ad_infeed',
  'label' => 'ã¤ã³ãã£ã¼ãåºåï¼ã«ã¼ãã¿ã¤ãç¨ï¼',
  'description' => 'ã¤ã³ãã£ã¼ãåºåç¨ã®ã³ã¼ããå¥åãã¦ãã ããã',
  'section' => 'sango_panel_ad',
  'type' => 'textarea',
));

$wp_customize->add_setting('ad_infeed2', array(
  'type' => 'theme_mod',
  'sanitize_callback' => 'sng_skip_sanitize',
));
$wp_customize->add_control('ad_infeed2', array(
  'settings' => 'ad_infeed2',
  'label' => 'ã¤ã³ãã£ã¼ãåºåï¼æ¨ªé·ã¿ã¤ãç¨ï¼',
  'description' => 'ã¤ã³ãã£ã¼ãåºåç¨ã®ã³ã¼ããå¥åãã¦ãã ããã',
  'section' => 'sango_panel_ad',
  'type' => 'textarea',
));

$wp_customize->add_setting('ad_infeed3', array(
  'type' => 'theme_mod',
  'sanitize_callback' => 'sng_skip_sanitize',
));
$wp_customize->add_control('ad_infeed3', array(
  'settings' => 'ad_infeed3',
  'label' => 'ã¤ã³ãã£ã¼ãåºåï¼æ¨ªé·å¤§ã¿ã¤ãç¨ï¼',
  'description' => 'ã¤ã³ãã£ã¼ãåºåç¨ã®ã³ã¼ããå¥åãã¦ãã ããã',
  'section' => 'sango_panel_ad',
  'type' => 'textarea',
));


$wp_customize->add_setting('ad_infeed_pos1', array(
  'type' => 'theme_mod',
  'sanitize_callback' => 'wp_filter_nohtml_kses',
));
$wp_customize->add_control( 'ad_infeed_pos1', array (
  'settings' => 'ad_infeed_pos1',
  'label' => 'ã¤ã³ãã£ã¼ãåºåã®è¡¨ç¤ºä½ç½®1',
  'description' => 'é£åå£«ã«ã¤ã³ãã£ã¼ãåºåãè¨­ç½®ãããã¨ã¯ã§ãã¾ãã',
  'section' => 'sango_panel_ad',
  'type' => 'number',
  'input_attrs' => array(
    'min' => 1,
    'max' => 12,
    'step' => 1,
    'type' => 'number',
  ),
));

$wp_customize->add_setting('ad_infeed_pos2', array(
  'type' => 'theme_mod',
  'sanitize_callback' => 'wp_filter_nohtml_kses',
));
$wp_customize->add_control( 'ad_infeed_pos2', array (
  'settings' => 'ad_infeed_pos2',
  'label' => 'ã¤ã³ãã£ã¼ãåºåã®è¡¨ç¤ºä½ç½®2',
  'section' => 'sango_panel_ad',
  'type' => 'number',
  'input_attrs' => array(
    'min' => 1,
    'max' => 12,
    'step' => 1,
    'type' => 'number',
  ),
));

$wp_customize->add_setting('ad_infeed_pos3', array(
  'type' => 'theme_mod',
  'sanitize_callback' => 'wp_filter_nohtml_kses',
));
$wp_customize->add_control( 'ad_infeed_pos3', array (
  'settings' => 'ad_infeed_pos3',
  'label' => 'ã¤ã³ãã£ã¼ãåºåã®è¡¨ç¤ºä½ç½®3',
  'section' => 'sango_panel_ad',
  'type' => 'number',
  'input_attrs' => array(
    'min' => 1,
    'max' => 12,
    'step' => 1,
    'type' => 'number',
  ),
));
