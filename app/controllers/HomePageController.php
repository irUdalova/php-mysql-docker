<?php

include_once ROOT_DIR . '/models/WordsModel.php';
include_once ROOT_DIR . '/models/TagsModel.php';


class HomePageController {

  public function canHandle() {
    $isMethodSupported = $_SERVER["REQUEST_METHOD"] === "GET";
    $urlPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    if ($isMethodSupported && $urlPath === '/') {
      return true;
    }
    return false;
  }

  public function handle() {
    session_start();
    $word = new WordsModel();

    $params = [
      'userID' => $_SESSION["user_id"] ?? NULL,
      'randomWord',
      'topFavorites'
    ];

    $params['randomWord'] = $word->getRandomWord();
    $params['topFavorites'] = $word->getMostFavorite();

    echo $this->renderView('home', $params);
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
}
