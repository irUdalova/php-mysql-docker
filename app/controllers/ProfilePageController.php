<?php

include_once ROOT_DIR . '/models/UsersModel.php';
include_once ROOT_DIR . '/models/WordsModel.php';


class ProfilePageController {

  public function canHandle() {
    $isMethodSupported = $_SERVER["REQUEST_METHOD"] === "GET";
    if ($isMethodSupported && $_SERVER["REQUEST_URI"] === '/profile') {
      return true;
    }
    return false;
  }

  public function handle() {
    session_start();
    $user = new UsersModel;
    $word = new WordsModel();

    $params = [
      'userID' => $_SESSION["user_id"] ?? NULL,
      'userData' => [],
      'postsCount' => ''
    ];

    if (!$params['userID']) {
      header("Location: /");
      exit;
    }

    $params['userData'] = $user->getById($params['userID']);
    $params['postsCount'] = count($word->getByUserId($params['userID']));

    echo $this->renderView('profile', $params);
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
    //extract variable $params
    extract($params);
    ob_start();
    include_once ROOT_DIR . "/app/views/$view.php";
    return ob_get_clean();
  }
}
