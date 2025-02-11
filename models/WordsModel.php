<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);
require_once ROOT_DIR . '/config/DatabaseConnector.php';
include_once ROOT_DIR . '/models/TagsModel.php';

class WordsModel {
  //DB vars
  private $conn;
  private $tags;
  private $tableWords = "words";
  private $tableUsers = "users";
  private $tableWordsTags = "words_tags";

  //Constructor with DB
  public function __construct() {
    $this->conn = DatabaseConnector::getConnection();
    $this->tags = new TagsModel();
  }

  //Get Words with tags
  // public function getAll() {
  //   $query = "SELECT 
  //   $this->tableWords.id AS word_id,
  //   $this->tableWords.word,
  //   $this->tableWords.definition, 
  //   $this->tableWords.example,
  //   $this->tableWords.user_id,
  //   $this->tableWords.date_created AS word_date_created,
  //   $this->tableUsers.name,
  //   $this->tableUsers.profile_img
  //   FROM $this->tableWords
  //   JOIN $this->tableUsers ON $this->tableWords.user_id = $this->tableUsers.id";

  //   //Prepare the statment
  //   $stmt = $this->conn->prepare($query);

  //   //Execute query
  //   $stmt->execute();

  //   $result = $stmt->get_result();
  //   $words = $result->fetch_all(MYSQLI_ASSOC);

  //   return $this->addTags($words);
  // }

  // public function countAllWords() {
  //   $query = "SELECT COUNT(id) as words_total FROM $this->tableWords";

  //   //Prepare the statment
  //   $stmt = $this->conn->prepare($query);

  //   //Execute query
  //   $stmt->execute();

  //   $result = $stmt->get_result();

  //   $wordsTotal = $result->fetch_array();

  //   return $wordsTotal['words_total'];
  // }


  // public function countAllWords($search = '') {
  //   $query = "SELECT COUNT(id) as words_total
  //    FROM $this->tableWords 
  //    WHERE $this->tableWords.word LIKE ?";

  //   $searchSQL = '%' . $search . '%';

  //   //Prepare the statment
  //   $stmt = $this->conn->prepare($query);

  //   $stmt->bind_param('s', $searchSQL);

  //   //Execute query
  //   $stmt->execute();

  //   $result = $stmt->get_result();

  //   $wordsTotal = $result->fetch_array();

  //   return $wordsTotal['words_total'];
  // }

  public function countAllWords($search, $tags) {
    // $query = "SELECT COUNT(id) as words_total
    //  FROM $this->tableWords 
    //  WHERE $this->tableWords.word LIKE ?";

    // $searchSQL = '%' . $search . '%';

    // //Prepare the statment
    // $stmt = $this->conn->prepare($query);

    // $stmt->bind_param('s', $searchSQL);

    // //Execute query
    // $stmt->execute();

    // $result = $stmt->get_result();

    // $wordsTotal = $result->fetch_array();


    $params = [];

    $query = "SELECT COUNT(DISTINCT $this->tableWords.id) AS words_total
    FROM $this->tableWords 
    JOIN $this->tableWordsTags ON $this->tableWords.id = $this->tableWordsTags.word_id";

    //search params
    if (strlen($search) > 0) {
      $query .= " WHERE $this->tableWords.word LIKE ?";
      $params[] = '%' . $search . '%';
    }

    //filter tags
    if ($tags) {
      foreach ($tags as $tag) {
        $params[] = $tag['id'];
      }
      $filterParams = str_repeat('?,', count($tags) - 1) . '?';
      $query .= " AND $this->tableWordsTags.tag_id IN ($filterParams)";
    }

    $result = $this->conn->execute_query($query, $params);

    $wordsTotal = $result->fetch_array();

    return $wordsTotal['words_total'];
  }

  public function countWordsByUserId($id) {
    $query = "SELECT COUNT(id) as words_total FROM $this->tableWords WHERE $this->tableWords.user_id = ?";

    //Prepare the statment
    $stmt = $this->conn->prepare($query);

    // Bind ID
    $stmt->bind_param('i', $id);

    //Execute query
    $stmt->execute();

    $result = $stmt->get_result();

    $wordsTotal = $result->fetch_array();

    return $wordsTotal['words_total'];
  }

  public function countWordsByTagId($id) {
    $query = "SELECT COUNT(id) as words_total FROM $this->tableWordsTags WHERE $this->tableWordsTags.tag_id = ?";

    //Prepare the statment
    $stmt = $this->conn->prepare($query);

    // Bind ID
    $stmt->bind_param('i', $id);

    //Execute query
    $stmt->execute();

    $result = $stmt->get_result();

    $wordsTotal = $result->fetch_array();

    return $wordsTotal['words_total'];
  }


  public function getAll($search, $tags, $order, $orderType, $start = 0, $perPage = 18446744073709551615) {
    $orderDB = mysqli_escape_string($this->conn, $order);
    $orderTypeDB = mysqli_escape_string($this->conn, $orderType);

    $params = [];

    $query = "SELECT DISTINCT $this->tableWords.id AS word_id,
    $this->tableWords.word,
    $this->tableWords.definition, 
    $this->tableWords.example,
    $this->tableWords.user_id,
    $this->tableWords.date_created AS word_date_created,
    $this->tableUsers.name,
    $this->tableUsers.profile_img
    FROM $this->tableWords
    JOIN $this->tableUsers ON $this->tableWords.user_id = $this->tableUsers.id
    JOIN $this->tableWordsTags ON $this->tableWords.id = $this->tableWordsTags.word_id";


    //search params
    if (strlen($search) > 0) {
      $query .= " WHERE $this->tableWords.word LIKE ?";
      $params[] = '%' . $search . '%';
    }

    //filter tags
    if ($tags) {
      foreach ($tags as $tag) {
        $params[] = $tag['id'];
      }
      $filterParams = str_repeat('?,', count($tags) - 1) . '?';
      $query .= " AND $this->tableWordsTags.tag_id IN ($filterParams)";
    }

    //sort params
    $query .= " ORDER BY $this->tableWords.$orderDB $orderTypeDB";

    //LIMIT for pagination
    $query .= " LIMIT ?,?";
    $params[] = $start;
    $params[] = $perPage;


    $result = $this->conn->execute_query($query, $params);

    $words = $result->fetch_all(MYSQLI_ASSOC);

    return $this->addTags($words);
  }

  // Add array of tags to the array of words
  public function addTags($words) {
    foreach ($words as $index => $wordSingle) {
      $words[$index]['tags'] = $this->tags->getByWordId($wordSingle['word_id']);
    }
    return $words;
  }

  // Get array of word by specific tag
  public function getByTagId($id, $start = 0, $perPage = 1844674407370955161) {
    $query = "SELECT
    $this->tableWords.id AS word_id,
    $this->tableWords.word,
    $this->tableWords.definition, 
    $this->tableWords.example,
    $this->tableWords.user_id,
    $this->tableWords.date_created AS word_date_created,
    $this->tableUsers.name,
    $this->tableUsers.profile_img
    FROM $this->tableWords
    JOIN $this->tableUsers ON $this->tableWords.user_id = $this->tableUsers.id 
    JOIN $this->tableWordsTags ON $this->tableWords.id = $this->tableWordsTags.word_id
    WHERE $this->tableWordsTags.tag_id = ?
    LIMIT ?,?";

    //Prepare the statment
    $stmt = $this->conn->prepare($query);

    // Bind ID
    $stmt->bind_param('iii', $id, $start, $perPage);

    //Execute query
    $stmt->execute();

    $result = $stmt->get_result();
    $words = $result->fetch_all(MYSQLI_ASSOC);

    return $this->addTags($words);
  }

  // public function getByTagId($id) {
  //   $query = "SELECT
  //   $this->tableWords.id AS word_id,
  //   $this->tableWords.word,
  //   $this->tableWords.definition, 
  //   $this->tableWords.example,
  //   $this->tableWords.user_id,
  //   $this->tableWords.date_created AS word_date_created,
  //   $this->tableUsers.name,
  //   $this->tableUsers.profile_img
  //   FROM $this->tableWords
  //   JOIN $this->tableUsers ON $this->tableWords.user_id = $this->tableUsers.id 
  //   JOIN $this->tableWordsTags ON $this->tableWords.id = $this->tableWordsTags.word_id
  //   WHERE $this->tableWordsTags.tag_id = ?";

  //   //Prepare the statment
  //   $stmt = $this->conn->prepare($query);

  //   // Bind ID
  //   $stmt->bind_param('i', $id);

  //   //Execute query
  //   $stmt->execute();

  //   $result = $stmt->get_result();
  //   $words = $result->fetch_all(MYSQLI_ASSOC);

  //   return $this->addTags($words);
  // }

  //use the large number 18446744073709551615 for the second parameter to get all the values from table (From the MySQL documentation https://dev.mysql.com/doc/refman/8.4/en/select.html)
  //but with this number table returns only one record, so i reduced the number to 1844674407370955161 and somehow it works
  public function getByUserId($id, $start = 0, $perPage = 1844674407370955161) {
    $query = "SELECT 
    $this->tableWords.id AS word_id,
    $this->tableWords.word,
    $this->tableWords.definition, 
    $this->tableWords.example,
    $this->tableWords.user_id,
    $this->tableWords.date_created AS word_date_created,
    $this->tableUsers.name,
    $this->tableUsers.profile_img
    FROM $this->tableWords
    JOIN $this->tableUsers ON $this->tableWords.user_id = $this->tableUsers.id
    WHERE $this->tableWords.user_id = ? 
    LIMIT ?,?";

    //Prepare the statment
    $stmt = $this->conn->prepare($query);

    // Bind ID
    $stmt->bind_param('iii', $id, $start, $perPage);

    //Execute query
    $stmt->execute();

    $result = $stmt->get_result();
    $words = $result->fetch_all(MYSQLI_ASSOC);


    return $this->addTags($words);
  }

  // Get array of User words (with tags)
  // public function getByUserId($id) {
  //   $query = "SELECT 
  //   $this->tableWords.id AS word_id,
  //   $this->tableWords.word,
  //   $this->tableWords.definition, 
  //   $this->tableWords.example,
  //   $this->tableWords.user_id,
  //   $this->tableWords.date_created AS word_date_created,
  //   $this->tableUsers.name,
  //   $this->tableUsers.profile_img
  //   FROM $this->tableWords
  //   JOIN $this->tableUsers ON $this->tableWords.user_id = $this->tableUsers.id
  //   WHERE $this->tableWords.user_id = ?";

  //   //Prepare the statment
  //   $stmt = $this->conn->prepare($query);

  //   // Bind ID
  //   $stmt->bind_param('i', $id);

  //   //Execute query
  //   $stmt->execute();

  //   $result = $stmt->get_result();
  //   $words = $result->fetch_all(MYSQLI_ASSOC);

  //   return $this->addTags($words);
  // }

  // Get single word by ID with its tags
  public function getWordById($id) {
    $query = "SELECT 
    $this->tableWords.id AS word_id,
    $this->tableWords.word,
    $this->tableWords.definition, 
    $this->tableWords.example,
    $this->tableWords.user_id,
    $this->tableWords.date_created AS word_date_created,
    $this->tableUsers.name,
    $this->tableUsers.profile_img
    FROM $this->tableWords
    JOIN $this->tableUsers ON $this->tableWords.user_id = $this->tableUsers.id
    WHERE $this->tableWords.id = ?";

    //Prepare the statment
    $stmt = $this->conn->prepare($query);

    // Bind ID
    $stmt->bind_param('i', $id);

    //Execute query
    $stmt->execute();

    $result = $stmt->get_result();
    $word = $result->fetch_assoc();

    $word['tags'] = $this->tags->getByWordId($id);

    return $word;
  }

  public function getByWord($word) {
    $query = "SELECT * FROM $this->tableWords WHERE word = ?";

    //Prepare the statment
    $stmt = $this->conn->prepare($query);

    // Bind ID
    $stmt->bind_param('s', $word);

    //Execute query
    $stmt->execute();

    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    if (!empty($data)) {
      return $data;
    }
    return NULL;
  }

  public function isWordTaken($word, $currentId) {

    $query = "SELECT * FROM $this->tableWords WHERE word = ? AND id != ?";

    //Prepare the statment
    $stmt = $this->conn->prepare($query);

    // Bind ID
    $stmt->bind_param('si', $word, $currentId);

    //Execute query
    $stmt->execute();

    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    if (!empty($data)) {
      return $data;
    }
    return NULL;
  }

  // Create a new word (WITHOUT TAGS!!!!)
  public function create($word, $definition, $example, $userID) {
    //Create query
    $query = "INSERT INTO $this->tableWords (word, definition, example, user_id) VALUES (?,?,?,?)";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("sssi", $word, $definition, $example, $userID);

    if ($stmt->execute()) {
      $last_id = $this->conn->insert_id;
      return $last_id;
    };
    printf("Error: %s. \n", $stmt->error);
    return false;
  }

  // Update a new word (WITHOUT TAGS!!!!)
  public function update($word, $definition, $example, $id) {
    //Update query
    $query = "UPDATE $this->tableWords SET word=?, definition=?, example=? WHERE id=?";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("sssi", $word, $definition, $example, $id);

    if ($stmt->execute()) {
      return true;
    };
    printf("Error: %s. \n", $stmt->error);
    return false;
  }

  public function delete($id) {
    //Delete query
    $query = "DELETE FROM $this->tableWords WHERE id=?";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("s", $id);

    if ($stmt->execute()) {
      return true;
    };
    printf("Error: %s. \n", $stmt->error);
    return false;
  }
}
