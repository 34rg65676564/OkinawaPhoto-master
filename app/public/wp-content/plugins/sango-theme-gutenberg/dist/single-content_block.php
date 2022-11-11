<?php

use SangoBlocks\App;

$content_block = App::get('content-block');
  /* コンテンツブロックのプレビューページ */
?>
<!doctype html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="robots" content="noindex">
    <?php wp_head(); ?>
    <style>
    .sng-cb {
      position: relative;
    }
    :root {
      --wrap-default-width: 700px;
    }
    .entry-content {
      max-width: 730px;
      margin-right: auto;
      margin-left: auto;
      padding: 0 15px;
    }
    .page-forfront .alignfull {
      width: 100vw;
      margin-left: calc(50% - 50vw);
      max-width: 100vw !important;
    }
    @media screen and (max-width: 700px) {
      .sgb-full-bg__content {
        padding-right: 15px;
        padding-left: 15px;
        margin-right: 0;
        margin-left: 0;
      }
    }
    </style>
  </head>
  <body <?php body_class(); ?>>
    <div class="page-forfront">
      <div class="entry-content">
        <div style="width: 100%;">
          <div style="margin-top: 2rem; padding: 1rem; font-size: 0.8em; color: rgba(0, 12, 30, 0.45); background: #f2f5f9;">
            <i class="fa fa-play-circle" style="color: #70b7ff"></i>
            コンテンツブロック「<?php the_title(); ?>」のプレビュー
          </div>
        </div>
      </div>
      <?php echo $content_block->get_content_block( get_the_ID(), 'entry-content' ); ?>
    </div>
    <?php wp_footer(); ?>
  </body>
</html>
