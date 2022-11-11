<?php

use SangoBlocks\App;

function sng_build_table_css($options) {
  $id = $options['id'];
  $tableWidth = $options['tableWidth'];
  $fixFirstCol = $options['fixFirstCol'];
  $fixFirstRow = $options['fixFirstRow'];
  $headingFirstCol = $options['headingFirstCol'];
  $headingBgColor = $options['headingBgColor'];
  $headingColor = $options['headingColor'];
  $borderColor = $options['borderColor'];
  $cellMinWidth = $options['cellMinWidth'];
  $cellMaxWidth = $options['cellMaxWidth'];

  $css = "";
  if ($borderColor) {
    $css .= "
      #${id} table {
        border-width: 2px;
        border-color: ${borderColor};
      }
      #${id} table td,
      #${id} table th {
        border-width: 2px;
        border-color: ${borderColor};
      }
    ";
  } else {
    $borderColor = "#e0e0e0";
  }
  if ($cellMinWidth) {
    $css .= "
      #${id} table td,
      #${id} table th {
        min-width: ${cellMinWidth}px;
      }
    ";
  }
  if ($cellMaxWidth) {
    $css .= "
      #${id} table td,
      #${id} table th {
        max-width: ${cellMaxWidth}px;
      }
    ";
  }
  if ($tableWidth && $fixFirstCol) {
    $css .= "
      #${id} figure {
        overflow-x: auto;
      }
      #${id} table {
        width: ${tableWidth}px;
        max-width: ${tableWidth}px;
        border-collapse: separate;
        border-left: none;
      }
      #${id} table td,
      #${id} table th {
        border-bottom: 2px solid $borderColor;
      }
      #${id} table tbody tr:last-child td,
      #${id} table tbody tr:last-child th,
      #${id} table tfoot tr td,
      #${id} table tfoot tr th {
        border-bottom: none;
      }
      #${id} table tfoot tr td,
      #${id} table tfoot tr th {
        border-top: 2px dotted $borderColor;
      }
      #${id} table tr > :first-child {
        position: sticky;
        top: 0;
        left: 0;
        z-index: 1;
        border-left: 2px solid $borderColor;
      }
      #${id} table tbody tr:last-child > :first-child {
        border-bottom: none;
      }
      #${id} table tfoot tr td:first-child,
      #${id} table tfoot tr th:first-child {
        border-bottom: none;
      }
      #${id} .is-style-sango-table-scroll-hint table td {
        white-space: normal;
      }
    ";
  }

  if ($fixFirstRow) {
    $top = is_user_logged_in() ? '32px' : '0';
    $css .= "
      #${id} figure {
        overflow: visible;
      }
      #${id} table {
        border-collapse: separate;
        border-top: none;
      }
      #${id} table thead tr > td,
      #${id} table thead tr > th {
        position: sticky;
        z-index: 1;
        top: ${top};
        border-top: 2px solid $borderColor;
      }
      #${id} table td,
      #${id} table th {
        border-bottom: 2px solid $borderColor;
      }
      #${id} table tbody tr:last-child td,
      #${id} table tbody tr:last-child th,
      #${id} table tfoot tr td,
      #${id} table tfoot tr th {
        border-bottom: none;
      }
      #${id} table tfoot tr td,
      #${id} table tfoot tr th {
        border-top: 2px dotted ${borderColor};
      }
      #${id} table thead:empty + tbody tr:first-child td,
      #${id} table thead:empty + tbody tr:first-child th {
        position: sticky;
        z-index: 1;
        top: ${top};
        border-top: 2px solid $borderColor;
      }
    ";
  }

  if ($headingFirstCol) {
    $backgroundColor = $headingBgColor ? "background-color: ${headingBgColor};" : "";
    $color = $headingColor ? "color: ${headingColor};" : "";

    $css .= "
    #${id} table tr td:first-child {
      padding: 7px;
      border-right: 2px solid $borderColor;
      border-bottom: 2px solid $borderColor;
      background-color: #f8f9fa;
      text-align: center;
      font-weight: bold;
      white-space: nowrap;
      $backgroundColor
      $color
    }
    #${id} table tfoot tr td:first-child {
      padding: 7px;
      text-align: center;
      font-weight: bold;
      white-space: nowrap !important;
      $backgroundColor
      $color
    }
    #${id} table tbody tr:last-child td:first-child {
      border-bottom: none;
    }
    ";
  }

  if ($headingColor) {
    $css .= "
      #${id} table th {
        color: ${headingColor};
      }
    ";
  }

  if ($headingBgColor) {
    $css .= "
      #${id} table th {
        background-color: ${headingBgColor};
      }
    ";
  }

  return $css;
}

function sng_table_render($block_content, $block) {
  if ($block['blockName'] !== 'core/table') {
    return $block_content;
  }
  $id = isset($block['attrs']['blockId']) ? $block['attrs']['blockId'] : '';
  $tableWidth = isset($block['attrs']['tableWidth']) ? $block['attrs']['tableWidth'] : 1200;
  $fixFirstCol = isset($block['attrs']['fixFirstCol']) ? $block['attrs']['fixFirstCol'] : '';
  $fixFirstRow = isset($block['attrs']['fixFirstRow']) ? $block['attrs']['fixFirstRow'] : '';
  $headingFirstCol = isset($block['attrs']['headingFirstCol']) ? $block['attrs']['headingFirstCol'] : '';
  $headingBgColor = isset($block['attrs']['headingBgColor']) ? $block['attrs']['headingBgColor'] : '';
  $headingColor = isset($block['attrs']['headingColor']) ? $block['attrs']['headingColor'] : '';
  $borderColor = isset($block['attrs']['borderColor']) ? $block['attrs']['borderColor'] : '';
  $cellMinWidth = isset($block['attrs']['cellMinWidth']) ? $block['attrs']['cellMinWidth'] : '';
  $cellMaxWidth = isset($block['attrs']['cellMaxWidth']) ? $block['attrs']['cellMaxWidth'] : '';

  $css = sng_build_table_css(array(
    'id' => $id,
    'tableWidth' => $tableWidth,
    'fixFirstCol' => $fixFirstCol,
    'fixFirstRow' => $fixFirstRow,
    'headingFirstCol' => $headingFirstCol,
    'headingBgColor' => $headingBgColor,
    'headingColor' => $headingColor,
    'borderColor' => $borderColor,
    'cellMinWidth' => $cellMinWidth,
    'cellMaxWidth' => $cellMaxWidth
  ));

  if (!$css) {
    return $block_content;
  }

  App::get('css')->register($id, $css);
  return "<div id=\"$id\">$block_content</div>";
}

add_filter( 'render_block', 'sng_table_render', 10, 2 );
