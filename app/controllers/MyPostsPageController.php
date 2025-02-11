<?php

include_once ROOT_DIR . '/models/WordsModel.php';
include_once ROOT_DIR . '/app/controllers/AuthorisedController.php';


class MyPostsPageController extends AuthorisedController {

  public function canHandle() {
    $isMethodSupported = $_SERVER["REQUEST_METHOD"] === "GET";
    $urlPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    if ($isMethodSupported && $urlPath === '/myposts') {
      return true;
    }
    return false;
  }

  public function handle() {
    $userID = $this->getAuthUserId();
    $word = new WordsModel();

    $params = [
      'words' => [],
      'userID' => $userID,
      'pagination' => []
    ];

    $allWordsCount = $word->countWordsByUserId($userID);

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

    $params['words'] = $word->getByUserId($userID, $params['pagination']['start'], $perPage);

    echo $this->renderView('myPosts', $params);
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
