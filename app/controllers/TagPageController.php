<?php

include_once ROOT_DIR . '/models/WordsModel.php';
include_once ROOT_DIR . '/models/TagsModel.php';


class TagPageController {
  public $tagID;

  public function __construct() {
    $parts = explode("/", $_SERVER['REQUEST_URI']);
    $idURI = $parts[2] ?? null;
    $this->tagID = filter_var($idURI, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  }

  public function canHandle() {
    $isMethodSupported = $_SERVER["REQUEST_METHOD"] === "GET";
    $urlPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $regex = '~^/tags/[0-9]+$~i';
    if ($isMethodSupported && preg_match($regex, $urlPath)) {
      return true;
    }
    return false;
  }

  public function handle() {
    session_start();
    $word = new WordsModel();
    $tag = new TagsModel();

    $params = [
      'words' => [],
      'userID' => $_SESSION["user_id"] ?? NULL,
      'activeTag' => [],
      'pagination' => []
    ];

    $allWordsCount = $word->countWordsByTagId($this->tagID);
    echo '<pre>';
    var_dump($allWordsCount, 'allWordsCount');
    echo '</pre>';


    // pagination
    $perPage = 6;
    $params['pagination']['totalPages'] = ceil($allWordsCount / $perPage);

    if (isset($_GET['page'])) {
      $params['pagination']['page'] = $_GET['page'];
      $params['pagination']['start'] = (($_GET['page'] - 1) * $perPage);
    } else {
      $params['pagination']['page'] = 1;
      $params['pagination']['start'] = 0;
    }



    $params['words'] = $word->getByTagId($this->tagID, $params['pagination']['start'], $perPage);
    $params['activeTag'] = $tag->getTagById($this->tagID);

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
