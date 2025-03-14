<?php

include_once ROOT_DIR . '/models/WordsModel.php';
include_once ROOT_DIR . '/models/UsersModel.php';


class UserProfilePageController {
  public $userID;

  public function __construct() {
    $parts = explode("/", $_SERVER['REQUEST_URI']);
    $idURI = $parts[3] ?? null;
    $this->userID = filter_var($idURI, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  }

  public function canHandle() {
    $isMethodSupported = $_SERVER["REQUEST_METHOD"] === "GET";

    $urlPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    $regex = '~^/profile/user/[0-9]+$~i';
    if ($isMethodSupported && preg_match($regex, $urlPath)) {
      return true;
    }
    return false;

    // $request = $_SERVER["REQUEST_URI"];
    // $regex = '~^/profile/user/[0-9]+$~i';
    // if ($isMethodSupported && preg_match($regex, $request)) {
    //   return true;
    // }
    // return false;
  }

  public function handle() {
    session_start();
    $word = new WordsModel();
    $user = new UsersModel();

    $params = [
      'words' => [],
      'userData' => [],
      'postsCount' => '',
      'pagination' => []
    ];

    $allWordsCount = $word->countWordsByUserId($this->userID);

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

    $params['words'] = $word->getByUserId($this->userID, $params['pagination']['start'], $perPage);
    $params['userData'] = $user->getById($this->userID);
    $params['postsCount'] = $allWordsCount;


    // $params['words'] = $word->getByUserId($this->userID);
    // $params['userData'] = $user->getById($this->userID);
    // $params['postsCount'] = count($params['words']);
    // $params['postsCount'] = $word->countWordsByUserId($this->userID); // perhaps an excessive method!!????

    echo $this->renderView('userProfile', $params);
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
