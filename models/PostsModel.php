<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);
require_once ROOT_DIR . '/config/DatabaseConnector.php';

class PostsModel {
  //DB vars
  private $conn;
  private $table = "posts";

  //Constructor with DB
  public function __construct() {
    $this->conn = DatabaseConnector::getConnection();
  }

  //Get Posts
  public function getAll() {
    $query = "SELECT * FROM $this->table";

    //Prepare the statment
    $stmt = $this->conn->prepare($query);

    //Execute query
    $stmt->execute();

    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);

    return $data;
  }

  //Get Single Post
  public function getById($id) {
    $query = "SELECT * FROM $this->table WHERE id = ?";

    //Prepare the statment
    $stmt = $this->conn->prepare($query);

    // Bind ID
    $stmt->bind_param('i', $id);

    //Execute query
    $stmt->execute();

    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    return $data;
  }

  public function create($title, $body) {
    //Create query
    $query = "INSERT INTO $this->table (title, body) VALUES (?,?)";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("ss", $title, $body);

    if ($stmt->execute()) {
      $last_id = $this->conn->insert_id;
      return $last_id;
    };
    printf("Error: %s. \n", $stmt->error);
    return false;
  }

  public function update($title, $body, $id) {
    //Update query
    $query = "UPDATE $this->table SET title=?, body=? WHERE id=?";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("ssi", $title, $body, $id);

    if ($stmt->execute()) {
      return true;
    };
    printf("Error: %s. \n", $stmt->error);
    return false;
  }

  public function delete($id) {
    //Delete query
    $query = "DELETE FROM $this->table WHERE id=?";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("s", $id);

    if ($stmt->execute()) {
      return true;
    };
    printf("Error: %s. \n", $stmt->error);
    return false;
  }
}
