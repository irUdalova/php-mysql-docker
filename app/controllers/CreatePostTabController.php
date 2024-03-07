<?php

include_once ROOT_DIR . '/models/PostsModel.php';


class CreatePostTabController {

  public function canHandle() {
    $isMethodSupported = $_SERVER["REQUEST_METHOD"] === "GET" || $_SERVER["REQUEST_METHOD"] === "POST";
    if ($isMethodSupported && $_SERVER["REQUEST_URI"] === '/myprofile/create') {
      return true;
    }
    return false;
  }

  public function handle() {
    session_start();

    $params = [
      'titleErr' => '',
      'bodyErr' => '',
      'succes' => false,
      'message' => '',
      'userID' => $_SESSION["user_id"] ?? NULL
    ];

    if ($_SERVER["REQUEST_METHOD"] === "GET") {

      if (!$params['userID']) {
        header("Location: /");
        exit;
      }
      echo $this->renderView('myProfile', $params);
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
      echo $this->renderView('myProfile', $params);
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
    ob_start();
    include_once ROOT_DIR . "/app/views/$view.php";
    $viewTab = $this->renderActiveTab($params);
    return str_replace('{{profile-content}}', $viewTab, ob_get_clean());
  }

  protected function renderActiveTab($params) {
    extract($params);
    ob_start();
    include_once ROOT_DIR . "/app/views/profile-tabs/createPost.php";
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
