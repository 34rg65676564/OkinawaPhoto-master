<?php
/**
 * REST API
 */

use SangoBlocks\App;

App::get('rest')->register(array(
  'path' => 'format',
  'methods' => 'POST',
  'callback' => function ($req) {
    $params = $req->get_params();
    $name = $params['name'];
    $className = $params['className'];
    $id = isset($params['id']) ? $params['id'] : '';
    $css = $params['css'];
    if ($id) {
      App::get('format')->update(array(
        'id' => $id,
        'name' => $name,
        'className' => $className,
        'css' => $css
      ));
    } else {
      App::get('format')->create(array(
        'name' => $name,
        'className' => $className,
        'css' => $css
      ));
    }
    return array(
      'ok' => 'ok'
    );
  }
));

App::get('rest')->register(array(
  'path' => 'format',
  'methods' => 'GET',
  'callback' => function ($req) {
    return App::get('format')->get();
  }
));

App::get('rest')->register(array(
  'path' => 'format',
  'methods' => 'DELETE',
  'callback' => function ($req) {
    $params = $req->get_params();
    $id = $params['id'];
    App::get('format')->remove($id);
    return array(
      'ok' => 'ok'
    );
  }
));
