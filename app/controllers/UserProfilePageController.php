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
    $request = $_SERVER["REQUEST_URI"];
    $regex = '~^/profile/user/[0-9]+$~i';
    if ($isMethodSupported && preg_match($regex, $request)) {
      return true;
    }
    return false;
  }

  public function handle() {
    session_start();
    $word = new WordsModel();
    $user = new UsersModel();

    $params = [
      'words' => [],
      'userData' => [],
      'postsCount' => ''
    ];

    $params['words'] = $word->getByUserId($this->userID);
    $params['userData'] = $user->getById($this->userID);
    $params['postsCount'] = count($params['words']);

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
