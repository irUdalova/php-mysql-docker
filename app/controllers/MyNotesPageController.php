<?php

include_once ROOT_DIR . '/models/PostsModel.php';


class MyNotesPageController {

  public function canHandle() {
    $isMethodSupported = $_SERVER["REQUEST_METHOD"] === "GET" || $_SERVER["REQUEST_METHOD"] === "POST";
    if ($isMethodSupported && $_SERVER["REQUEST_URI"] === '/mynotes') {
      return true;
    }
    return false;
  }

  public function handle() {

    $params = [
      'titleErr' => '',
      'bodyErr' => '',
      'succes' => false,
      'message' => ''
    ];

    if ($_SERVER["REQUEST_METHOD"] === "GET") {
      echo $this->renderView('myNotes', $params);
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
      $postData = $this->getPostData();

      if (empty($postData['title'])) {
        $params['titleErr'] = 'Title is required';
      }
      if (empty($postData['body'])) {
        $params['bodyErr'] = 'Text is required';
      }

      if (empty($params['titleErr']) && empty($params['bodyErr'])) {
        $post = new PostsModel();
        //Create post
        $id = $post->create($postData['title'], $postData['body']);
        if ($id) {
          $params['succes'] = true;
          $params['message'] = "The post was added successfully!";
        } else {
          $params['succes'] = false;
          $params['message'] = "Something went wrong, the post was not created";
        }
      }
      echo $this->renderView('myNotes', $params);
    }
  }

  public function renderView($view, $params = []) {
    $layoutContent = $this->layoutContent();
    $viewContent = $this->renderOnlyView($view, $params);
    return str_replace('{{content}}', $viewContent, $layoutContent);
  }

  protected function layoutContent() {
    ob_start();
    include_once ROOT_DIR . "/app/views/layouts/main.php";
    return ob_get_clean();
  }

  protected function renderOnlyView($view, $params) {
    //extract variable $params->$posts
    extract($params);
    ob_start();
    include_once ROOT_DIR . "/app/views/$view.php";
    return ob_get_clean();
  }

  protected function getPostData() {

    $body = [];
    foreach ($_POST as $key => $value) {
      $body[$key] = trim(filter_input(INPUT_POST, $key, FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    }

    return $body;
  }
}
