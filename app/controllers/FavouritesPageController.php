<?php

include_once ROOT_DIR . '/models/PostsModel.php';


class FavouritesPageController {

  public function canHandle() {
    $isMethodSupported = $_SERVER["REQUEST_METHOD"] === "GET";
    if ($isMethodSupported && $_SERVER["REQUEST_URI"] === '/favourites') {
      return true;
    }
    return false;
  }

  public function handle() {
    session_start();
    $user = new UsersModel;

    $params = [
      'userID' => $_SESSION["user_id"] ?? NULL,
      'userData' => [],
    ];

    if (!$params['userID']) {
      header("Location: /");
      exit;
    }

    $params['userData'] = $user->getById($params['userID']);

    echo $this->renderView('favourites', $params);
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
