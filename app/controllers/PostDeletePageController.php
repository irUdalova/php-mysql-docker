<?php

include_once ROOT_DIR . '/models/UsersModel.php';
include_once ROOT_DIR . '/app/controllers/AuthorisedController.php';


class PostDeletePageController extends AuthorisedController {
  public $postID;

  public function __construct() {
    $parts = explode("/", $_SERVER['REQUEST_URI']);
    $idURI = $parts[2] ?? null;
    $this->postID = filter_var($idURI, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  }

  public function canHandle() {
    $isMethodSupported = $_SERVER["REQUEST_METHOD"] === "GET" || $_SERVER["REQUEST_METHOD"] === "POST";
    $request = $_SERVER["REQUEST_URI"];
    $regex = '~^/posts/[0-9]+/delete$~i';
    if ($isMethodSupported && preg_match($regex, $request)) {
      return true;
    }
    return false;
  }

  public function handle() {
    $userID = $this->getAuthUserId();
    $word = new WordsModel();

    $params = [
      'wordData' => [],
      'succes' => '',
      'message' => '',
    ];

    $params['wordData'] = $word->getWordById($this->postID);

    if ($_SERVER["REQUEST_METHOD"] === "GET") {

      if ($params['wordData']['user_id'] !== $userID) {
        http_response_code(403);
        exit;
      }

      echo $this->renderView('postDelete', $params);
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

      if (isset($_POST['cancel'])) {
        header("Location: /myposts");
        exit;
      }

      if ($word->delete($this->postID)) {


        if ($params['wordData']['tags']) {
          $tagModel = new TagsModel();

          foreach ($params['wordData']['tags'] as $tag) {
            // find words with this tag
            $tagWords = $word->getByTagId($tag['tag_id']);
            if (!$tagWords) {
              $tagModel->deleteTag($tag['tag_id']);
            }
          }
        }

        $params['succes'] = true;
        $params['message'] = "The post was added successfully!";
        header("Location: /myposts");
      } else {
        $params['succes'] = false;
        $params['message'] = "Something went wrong, the post was not created";
        header('HTTP/1.1 503 Service Temporarily Unavailable');
        exit;
      }

      // echo $this->renderView('postDelete', $params);
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
    //extract variable $params
    extract($params);
    ob_start();
    include_once ROOT_DIR . "/app/views/$view.php";
    return ob_get_clean();
  }
}
