<?php

// include_once ROOT_DIR . '/models/PostsModel.php';


class LogoutController {

  public function canHandle() {
    if ($_SERVER["REQUEST_METHOD"] === "GET" && $_SERVER["REQUEST_URI"] === '/logout') {
      return true;
    }
    return false;
  }

  public function handle() {
    session_start();
    session_unset();
    session_destroy();
    // go to previous page
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
  }
}
