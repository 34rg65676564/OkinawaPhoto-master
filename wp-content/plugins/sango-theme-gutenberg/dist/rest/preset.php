<?php
/**
 * REST API
 */

use SangoBlocks\App;

App::get('rest')->register(array(
  'path' => 'preset',
  'methods' => 'GET',
  'callback' => function ($req) {
    $params = $req->get_params();
    $name = $params['name'];
    return App::get('preset')->get($name);
  }
));


App::get('rest')->register(array(
  'path' => 'preset',
  'methods' => 'POST',
  'callback' => function ($req) {
    $params = $req->get_params();
    $name = $params['name'];
    $data = $params['data'];
    App::get('preset')->save($name, $data);
    return array(
      'ok' => 'ok'
    );
  }
));
