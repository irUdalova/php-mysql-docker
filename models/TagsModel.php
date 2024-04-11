<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);
require_once ROOT_DIR . '/config/DatabaseConnector.php';

class TagsModel {
  //DB vars
  private $conn;
  // private $tableWords = "words";
  private $tableTags = "tags";
  private $tableWordsTags = "words_tags";

  //Constructor with DB
  public function __construct() {
    $this->conn = DatabaseConnector::getConnection();
  }

  // Get all tags of word
  public function getByWordId($id) {
    $query = "SELECT 
    $this->tableWordsTags.tag_id,
    $this->tableTags.tag
    FROM $this->tableWordsTags
    JOIN $this->tableTags ON $this->tableWordsTags.tag_id = $this->tableTags.id
    WHERE $this->tableWordsTags.word_id = ?";

    //Prepare the statment
    $stmt = $this->conn->prepare($query);

    // Bind ID
    $stmt->bind_param('i', $id);

    //Execute query
    $stmt->execute();

    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);

    return $data;
  }

  // Get tag array [tag_id, tag_name] by its ID
  public function getTagById($id) {
    $query = "SELECT 
    $this->tableTags.id,
    $this->tableTags.tag
    FROM $this->tableTags
    WHERE $this->tableTags.id = ?";

    //Prepare the statment
    $stmt = $this->conn->prepare($query);

    // Bind ID
    $stmt->bind_param('i', $id);

    //Execute query
    $stmt->execute();

    $result = $stmt->get_result();
    $tag = $result->fetch_assoc();

    return $tag;
  }

  public function getAllTags() {

    $query = "SELECT * FROM $this->tableTags";

    //Prepare the statment
    $stmt = $this->conn->prepare($query);

    //Execute query
    $stmt->execute();

    $result = $stmt->get_result();
    $tags = $result->fetch_all(MYSQLI_ASSOC);

    return $tags;
  }

  public function createTag($tag) {
    //Create query
    $query = "INSERT INTO $this->tableTags (tag) VALUES (?)";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("s", $tag);

    if ($stmt->execute()) {
      $last_id = $this->conn->insert_id;
      return $last_id;
    };
    // printf("Error: %s. \n", $stmt->error);
    return false;
  }

  public function createWordTag($wordId, $tagId) {
    //Create query
    $query = "INSERT INTO $this->tableWordsTags (word_id, tag_id) VALUES (?,?)";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("ii", $wordId, $tagId);

    if ($stmt->execute()) {
      $last_id = $this->conn->insert_id;
      // return $last_id;
      return true;
    };
    // printf("Error: %s. \n", $stmt->error);
    return false;
  }

  public function deleteWordTags($word_id) {
    //Delete query
    $query = "DELETE FROM $this->tableWordsTags WHERE word_id=?";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("s", $word_id);

    if ($stmt->execute()) {
      return true;
    };
    printf("Error: %s. \n", $stmt->error);
    return false;
  }

  public function deleteTag($tag_id) {
    //Delete query
    $query = "DELETE FROM $this->tableTags WHERE id=?";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("s", $tag_id);

    if ($stmt->execute()) {
      return true;
    };
    printf("Error: %s. \n", $stmt->error);
    return false;
  }

  // public function deleteUnusedTags($tags) {
  // TO DO or NOT TO DO?
  // }
}
