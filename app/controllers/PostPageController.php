<?php

include_once ROOT_DIR . '/models/WordsModel.php';


class PostPageController {
  public $postID;

  public function __construct() {
    $parts = explode("/", $_SERVER['REQUEST_URI']);
    $idURI = $parts[2] ?? null;
    $this->postID = filter_var($idURI, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  }

  public function canHandle() {
    $isMethodSupported = $_SERVER["REQUEST_METHOD"] === "GET";
    $request = $_SERVER["REQUEST_URI"];
    $regex = '~^/posts/[0-9]+$~i';
    if ($isMethodSupported && preg_match($regex, $request)) {
      return true;
    }
    return false;
  }

  public function handle() {
    session_start();
    $word = new WordsModel();

    $params = [
      'wordData' => [],
      'userID' => $_SESSION["user_id"] ?? NULL
    ];

    $params['wordData'] = $word->getWordById($this->postID);

    //define favorites words for current user
    if (!empty($params['userID'])) {
      $wordsFav = $word->getAllFavorites($params['userID']);

      $params['wordData']['isFavorite'] = in_array($params['wordData']['word_id'], $wordsFav);
    }

    echo $this->renderView('post', $params);
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
