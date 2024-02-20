<?php

include_once ROOT_DIR . '/models/PostsModel.php';

class PostUpdateController {
  public $id;

  public function __construct() {
    $parts = explode("/", $_SERVER['REQUEST_URI']);
    $idURI = $parts[3] ?? null;
    $this->id = filter_var($idURI, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  }

  public function canHandle() {
    $request = $_SERVER["REQUEST_URI"];
    $regex = '~^/api/posts/[0-9]+$~i'; //use ~ instead of / delimiter, cause there are // in a string
    if ($_SERVER["REQUEST_METHOD"] === "PUT" && preg_match($regex, $request)) {
      return true;
    }
    return false;
  }

  public function handle() {
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');
    $post = new PostsModel();

    //Get raw posted data
    $data = json_decode(file_get_contents("php://input"));

    if (!$data) {
      echo json_encode(
        array('Message' => 'No data to create')
      );
      exit;
    }

    //Update post
    if (!$post->getById($this->id)) {
      http_response_code(404);
      echo json_encode(
        array([
          "message" => "The post with ID = $this->id doesn't exist",
        ])
      );
      exit;
    }

    if ($post->update($data->title, $data->body, $this->id)) {
      echo json_encode($post->getById($this->id));
    } else {
      echo json_encode(
        array('Message' => 'Post not updated')
      );
    }
  }
}
