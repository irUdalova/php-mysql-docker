<?php

class Database {
  //DB parametrs to connect
  private $host;
  private $username;
  private $password;
  private $db_name;
  private $conn;

  public function __construct(string $host, string $username, string $password, string $db_name) {
    $this->host = $host;
    $this->username = $username;
    $this->password = $password;
    $this->db_name = $db_name;
  }

  //DB connect method
  public function connect(){
    $this->conn = null;
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
  try {
    $this->conn = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
  } catch(mysqli_sql_exception $e) {
    echo "Error connection" . $e->getMessage();
  }
    return $this->conn;
  } 
}

?>