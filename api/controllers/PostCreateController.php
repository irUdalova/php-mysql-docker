<?php

include_once ROOT_DIR . '/models/PostsModel.php';

class PostCreateController {

  public function canHandle() {
    if ($_SERVER["REQUEST_METHOD"] === "POST" && $_SERVER["REQUEST_URI"] === '/api/posts') {
      return true;
    }
    return false;
  }

  public function handle() {
    //headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
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

    //Create post
    $id = $post->create($data->title, $data->body);
    if ($id) {
      echo json_encode($post->getById($id));
    } else {
      echo json_encode(
        array('Message' => 'Post not created')
      );
    }
  }
}
