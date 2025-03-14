<?php

include_once ROOT_DIR . '/models/WordsModel.php';
include_once ROOT_DIR . '/app/controllers/AuthorisedController.php';
include_once ROOT_DIR . '/app/helpers/functions.php'; //for function createPath($page);



class FavoritesPageController extends AuthorisedController {

  public function canHandle() {
    $isMethodSupported = $_SERVER["REQUEST_METHOD"] === "GET";
    $urlPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    if ($isMethodSupported && $urlPath === '/favorites') {
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
      'pagination' => [],
      'topFavorites'
    ];

    $allWordsCount = $word->countFavWordsByUserId($userID);

    // pagination
    $perPage = 6;
    $params['pagination']['totalPages'] = ceil($allWordsCount / $perPage);

    if (isset($_GET['page'])) {

      if ($_GET['page'] > $params['pagination']['totalPages']) {
        $prevPage = createPath($_GET['page'] - 1);
        header("Location: ?$prevPage");
        exit;
      }

      $params['pagination']['page'] = $_GET['page'];
      $params['pagination']['start'] = (($_GET['page'] - 1) * $perPage);
    } else {
      $params['pagination']['page'] = 1;
      $params['pagination']['start'] = 0;
    }

    $params['words'] = $word->getFavByUserId($userID, $params['pagination']['start'], $perPage);

    //define favorites words for current user
    $wordsFav = $word->getAllFavorites($userID);
    if (!empty($wordsFav)) {
      foreach ($params['words'] as $index => $wordSingle) {
        $params['words'][$index]['isFavorite'] = in_array($wordSingle['word_id'], $wordsFav);
      }
    }

    $params['topFavorites'] = $word->getMostFavorite();

    echo $this->renderView('favorites', $params);
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
