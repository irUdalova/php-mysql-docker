<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);
require_once ROOT_DIR . '/config/DatabaseConnector.php';

class UsersModel {
  //DB vars
  private $conn;
  private $table = "users";

  //Constructor with DB
  public function __construct() {
    $this->conn = DatabaseConnector::getConnection();
  }

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

  public function getByEmail($email) {
    $query = "SELECT * FROM $this->table WHERE email = ?";

    //Prepare the statment
    $stmt = $this->conn->prepare($query);

    // Bind ID
    $stmt->bind_param('s', $email);

    //Execute query
    $stmt->execute();

    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    if (!empty($data)) {
      return $data;
    }
    return NULL;
  }

  public function create($name, $email, $password) {
    //Create query
    $query = "INSERT INTO $this->table (name, email, password_hash) VALUES (?,?,?)";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("sss", $name, $email, $password);

    if ($stmt->execute()) {
      $last_id = $this->conn->insert_id;
      return $last_id;
    } else {
      throw new Exception('Something with databse');
    }
    return false;
  }

  // public function update($name, $password, $profileImg, $id) {
  //   // $name, $password, $profileImg, $id
  //   //Create query
  //   // $query = "INSERT INTO $this->table (name, password_hash, profile_img) VALUES (?,?,?)";
  //   $query = "UPDATE $this->table SET name=?, password_hash=?, profileImg=? WHERE id=?";
  //   $stmt = $this->conn->prepare($query);
  //   $stmt->bind_param("sssi", $name, $password, $profileImg, $id);

  //   if ($stmt->execute()) {
  //     $last_id = $this->conn->insert_id;
  //     return $last_id;
  //   } else {
  //     throw new Exception('Something with databse');
  //   }
  //   return false;
  // }

  public function changePassword($password, $id) {
    $query = "UPDATE $this->table SET password_hash=? WHERE id=?";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("si", $password, $id);

    if ($stmt->execute()) {
      return true;
    } else {
      throw new Exception('Something with databse');
    }
    return false;
  }

  public function update($name, $bio, $id) {
    // $query = "INSERT INTO $this->table (name, password_hash, profile_img) VALUES (?,?,?)";
    $query = "UPDATE $this->table SET name=?, bio=? WHERE id=?";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("ssi", $name, $bio, $id);

    if ($stmt->execute()) {
      return true;
    } else {
      throw new Exception('Something with databse');
    }
    return false;
  }

  public function updateImg($profileImg, $id) {
    //Create query
    // $query = "INSERT INTO $this->table (name, password_hash, profile_img) VALUES (?,?,?)";
    $query = "UPDATE $this->table SET profile_img=? WHERE id=?";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("si", $profileImg, $id);

    if ($stmt->execute()) {
      // $last_id = $this->conn->insert_id;
      // return $last_id;
      return true;
    } else {
      throw new Exception('Something with databse');
    }
    return false;
  }

  public function deleteImg($id) {
    //Create query
    // $query = "INSERT INTO $this->table (name, password_hash, profile_img) VALUES (?,?,?)";
    $profileImg = NULL;
    $query = "UPDATE $this->table SET profile_img=? WHERE id=?";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("si", $profileImg, $id);

    if ($stmt->execute()) {
      // $last_id = $this->conn->insert_id;
      // return $last_id;
      return true;
    } else {
      throw new Exception('Something with databse');
    }
    return false;
  }

  // public function update($title, $body, $id) {
  //   //Update query
  //   $query = "UPDATE $this->table SET title=?, body=? WHERE id=?";
  //   $stmt = $this->conn->prepare($query);
  //   $stmt->bind_param("ssi", $title, $body, $id);

  //   if ($stmt->execute()) {
  //     return true;
  //   };
  //   printf("Error: %s. \n", $stmt->error);
  //   return false;
  // }

  // public function delete($id) {
  //   //Delete query
  //   $query = "DELETE FROM $this->table WHERE id=?";
  //   $stmt = $this->conn->prepare($query);
  //   $stmt->bind_param("s", $id);

  //   if ($stmt->execute()) {
  //     return true;
  //   };
  //   printf("Error: %s. \n", $stmt->error);
  //   return false;
  // }
}
