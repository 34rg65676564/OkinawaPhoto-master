<?php

require_once ABSPATH . 'wp-admin/includes/plugin.php';
require_once ABSPATH . 'wp-admin/includes/plugin-install.php'; //for plugins_api..
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/misc.php';
require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';


add_action('admin_notices', 'sng_admin_notice' );
add_action('rest_api_init', 'sng_rest_api_init' );

function sng_admin_notice() {
  $url = home_url($_SERVER['REQUEST_URI']);
  if (strpos($url, 'wp-admin/index.php') === false) {
    return;
  }
  $plugins = get_plugins();
  $filtered = array_filter($plugins, function($plugin) {
    if ($plugin["Name"] === "SANGO Gutenberg") {
      return true;
    }
    return false;
  });
  $exist = $filtered && count($filtered) > 0 ? true : false;
  if ($exist) {
    $keys = array_keys($filtered);
    $file = isset($keys[0]) ? $keys[0] : '';
    if (is_plugin_active($file)) {
      return;
    }
    if (isset($_COOKIE["sng-dismiss-gutenberg"])) {
      return;
    }
  }
  ?>
  <div>
    <div class="update-nag notice notice-warning inline js-sng-gutenberg-notice" style="position: relative; padding: 25px 20px 20px 20px;">
      <button tye="button" class="notice-dismiss js-sng-gutenberg-dismiss"></button>
      <a href="https://saruwakakun.com/sango/gutenberg-introduction" target="_blank" rel="noopener noreferrer">SANGO Gutenberg</a>を利用することでブログの執筆体験が向上します。
      <p>
        <?php
          if (!$exist) {
        ?>
          <button type="button" class="button js-sng-gutenberg-install">プラグインをインストール</button>
        <?php
          } else { ?>
          <button type="button" class="button js-sng-gutenberg-activate">プラグインを有効化</button>
        <?php 
          } 
        ?>
      </p>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/js-cookie@rc/dist/js.cookie.min.js"></script>
    <script>
    jQuery(function() {
      function activate_plugin() {
        var site_url = "<?php echo site_url(); ?>";
        jQuery(".js-sng-gutenberg-activate").click(function() {
          jQuery(".js-sng-gutenberg-activate").html("有効化中...");
          jQuery(".js-sng-gutenberg-activate").prop("disabled", true);
          jQuery
            .post(site_url + "/?rest_route=/sng/v1/plugin/activate")
            .done(function(e) {
              if (e.ok) {
                jQuery(".js-sng-gutenberg-activate").parent().html("有効化済み");
                jQuery(".js-sng-gutenberg-activate").prop("disabled", false);
              } else {
                jQuery(".js-sng-gutenberg-activate").html("有効化に失敗しました");
                jQuery(".js-sng-gutenberg-activate").prop("disabled", false);
              }
            })
        })
      }
      function install_plugin() {
        jQuery(".js-sng-gutenberg-install").click(function() {
          var site_url = "<?php echo site_url(); ?>";
          if (!confirm("SANGO Gutenbergをダウンロードします。よろしいですか？")) {
            return;
          }
          jQuery(".js-sng-gutenberg-install").html("インストール中...");
          jQuery(".js-sng-gutenberg-install").prop("disabled", true);
          jQuery
            .post(site_url + "/?rest_route=/sng/v1/plugin")
            .always(function(e) {
              if (e.status === 200) {
                jQuery(".js-sng-gutenberg-install").html("プラグインを有効化");
                jQuery(".js-sng-gutenberg-install").prop("disabled", false);
                jQuery(".js-sng-gutenberg-install").unbind("click");
                jQuery(".js-sng-gutenberg-install")
                  .removeClass("js-sng-gutenberg-install")
                  .addClass("js-sng-gutenberg-activate")
                activate_plugin();
              } else {
                jQuery(".js-sng-gutenberg-install").html("インストールに失敗しました");
                jQuery(".js-sng-gutenberg-install").prop("disabled", false);
              }
            })
        })
      }
      function dismiss() {
        jQuery(".js-sng-gutenberg-dismiss").click(function() {
          Cookies.set('sng-dismiss-gutenberg', 'true')
          jQuery(".js-sng-gutenberg-notice").remove()
        })
      }
      if (jQuery(".js-sng-gutenberg-install").length) {
        install_plugin();
      } else if (jQuery(".js-sng-gutenberg-activate").length) {
        activate_plugin();
      }
      dismiss();
    })
    </script>
  </div>
  <?php
}

function sng_download_gutenberg() {
  $upgrader = new Plugin_Upgrader();
  $upgrader->install('https://sango-gutenberg.netlify.com/sango-theme-gutenberg.zip');
  return array('ok' => 'ok');
}

function sng_activate_gutenberg() {
  $plugins = get_plugins();
  $filtered = array_filter($plugins, function($plugin) {
    if ($plugin["Name"] === "SANGO Gutenberg") {
      return true;
    }
    return false;
  });

  if (!$filtered) {
    return array('failed' => 'failed');
  }

  $keys = array_keys($filtered);
  $plugin = array_pop($filtered);
  $file = isset($keys[0]) ? $keys[0] : '';

  if (!$file) {
    return array('ng' => 'ng');
  }

  activate_plugin($file);
  return array('ok' => 'ok');
}

function sng_rest_api_init() {
  register_rest_route('sng/v1', 'plugin', array(
    'methods' => "POST",
    'callback' => "sng_download_gutenberg",
    'permission_callback' => '__return_true',
  ));
  register_rest_route('sng/v1', 'plugin/activate', array(
    'methods' => "POST",
    'callback' => "sng_activate_gutenberg",
    'permission_callback' => '__return_true',
  ));
}
