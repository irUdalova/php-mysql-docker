<?php

class DatabaseConnector {
  public static $connection;
  private function __construct() {
  }

  public static function getConnection() {
    if (!isset(DatabaseConnector::$connection)) {
      $config = parse_ini_file(ROOT_DIR . "/.env");
      $host = $config['MYSQL_HOST'];
      $username = $config['MYSQL_USERNAME'];
      $password = $config['MYSQL_PASSWORD'];
      $db_name = $config['MYSQL_DB_NAME'];

      mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
      try {
        DatabaseConnector::$connection = mysqli_connect($host, $username, $password, $db_name);
      } catch (mysqli_sql_exception $e) {
        echo "Error connection" . $e->getMessage();
        exit;
      }
    }
    return DatabaseConnector::$connection;
  }
}
