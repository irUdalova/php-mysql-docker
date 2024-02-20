<?php

include_once ROOT_DIR . '/models/PostsModel.php';

class PostDeleteController {
  public $id;

  public function __construct() {
    $parts = explode("/", $_SERVER['REQUEST_URI']);
    $idURI = $parts[3] ?? null;
    $this->id = filter_var($idURI, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  }

  public function canHandle() {
    $request = $_SERVER["REQUEST_URI"];
    $regex = '~^/api/posts/[0-9]+$~i'; //use ~ instead of / delimiter, cause there are // in a string
    if ($_SERVER["REQUEST_METHOD"] === "DELETE" && preg_match($regex, $request)) {
      return true;
    }
    return false;
  }

  public function handle() {
    //headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    $post = new PostsModel();

    if ($post->delete($this->id)) {
      echo json_encode(
        array([
          "Message" => "Post deleted",
          "Deleted post id" => $this->id
        ])
      );
    } else {
      echo json_encode(
        array('Message' => 'Post not deleted')
      );
    }
  }
}
