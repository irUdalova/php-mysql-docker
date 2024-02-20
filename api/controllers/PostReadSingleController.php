<?php

include_once ROOT_DIR . '/models/PostsModel.php';

class PostReadSingleController {
  public $id;

  public function __construct() {
    $parts = explode("/", $_SERVER['REQUEST_URI']);
    $idURI = $parts[3] ?? null;
    $this->id = filter_var($idURI, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  }

  public function canHandle() {
    $request = $_SERVER["REQUEST_URI"];
    $regex = '~^/api/posts/[0-9]+$~i'; //use ~ instead of / delimiter, cause there are // in a string
    if ($_SERVER["REQUEST_METHOD"] === "GET" && preg_match($regex, $request)) {
      return true;
    }
    return false;
  }

  public function handle() {
    //headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    $post = new PostsModel();

    //Get post
    $postSingle = $post->getById($this->id);

    if (!empty($postSingle)) {
      echo json_encode($postSingle);
    } else {
      echo json_encode(
        array('message' => 'No posts found')
      );
    }
  }
}
