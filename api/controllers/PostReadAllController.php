<?php

include_once ROOT_DIR . '/models/PostsModel.php';


class PostReadAllController {
  public function canHandle() {
    if ($_SERVER["REQUEST_METHOD"] === "GET" && $_SERVER["REQUEST_URI"] === '/api/posts') {
      return true;
    }
    return false;
  }

  public function handle() {

    //headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    $post = new PostsModel();

    //Post query
    $posts = $post->getAll();

    if (!empty($posts)) {
      echo json_encode($posts);
    } else {
      echo json_encode(
        array('message' => 'No posts found')
      );
    }
  }
}
