<?php

namespace SangoBlocks;

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

class Format {
  private $table_name = 'sgb_format';

  public function init() {}

  public function createDb() {
    global $wpdb;
    $table_name = $wpdb->prefix . $this->table_name;
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE IF NOT EXISTS $table_name(
      format_id BIGINT(20) NOT NULL AUTO_INCREMENT,
      format_name VARCHAR(255) NOT NULL,
      format_class_name VARCHAR(255),
      format_css LONGTEXT,
      PRIMARY KEY (format_id)
    ) $charset_collate";
    dbDelta($sql);
  }

  public function get() {
    global $wpdb;
    $this->createDb();
    $table_name = $wpdb->prefix . $this->table_name;
    $results = $wpdb->get_results("SELECT *
      FROM $table_name
    ");
    if ($results) {
      return $results;
    }
    return array();
  }

  public function remove($id) {
    global $wpdb;
    $this->createDb();
    $table_name = $wpdb->prefix . $this->table_name;
    $wpdb->query(
    "DELETE FROM $table_name
      WHERE format_id = \"$id\"
    ");
  }

  public function create($data) {
    global $wpdb;
    $this->createDb();
    $table_name = $wpdb->prefix . $this->table_name;

    $wpdb->insert($table_name, array(
      "format_name" => $data['name'],
      "format_class_name" => $data["className"],
      "format_css" => $data["css"]
    ));
  }

  public function update($data) {
    global $wpdb;
    $this->createDb();
    $table_name = $wpdb->prefix . $this->table_name;

    $wpdb->update($table_name,
      array(
        "format_name" => $data['name'],
        "format_class_name" => $data["className"],
        "format_css" => $data["css"]
      ),
      array(
        "format_id" => $data['id']
      )
    );
  }
}
