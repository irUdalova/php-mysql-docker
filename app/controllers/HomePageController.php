<?php

include_once ROOT_DIR . '/models/PostsModel.php';


class HomePageController {

  public function canHandle() {
    if ($_SERVER["REQUEST_METHOD"] === "GET" && $_SERVER["REQUEST_URI"] === '/') {
      return true;
    }
    return false;
  }

  public function handle() {
    //Instantiate post object
    $post = new PostsModel();

    //Post query
    $posts = $post->getAll();

    $params = [
      'posts' => $posts
    ];

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
