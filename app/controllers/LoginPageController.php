<?php

include_once ROOT_DIR . '/models/UsersModel.php';


class LoginPageController {

  public function canHandle() {
    $isMethodSupported = $_SERVER["REQUEST_METHOD"] === "GET" || $_SERVER["REQUEST_METHOD"] === "POST";
    if ($isMethodSupported && $_SERVER["REQUEST_URI"] === '/login') {
      return true;
    }
    return false;
  }

  public function handle() {
    $params = [
      'errors' => [],
      'userData' => [],
      'succes' => false,
      'message' => ''
    ];

    if ($_SERVER["REQUEST_METHOD"] === "GET") {
      echo $this->renderView('login', $params);
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
      $postData = $this->getPostData();
      $params['errors'] = $this->loginFormValidation($postData);

      $isErrors = count(array_filter($params['errors']));

      if (empty($isErrors)) {
        $user = new UsersModel;
        //Create user
        $userDB = $user->getByEmail($postData['email']);
        if ($userDB) {
          if (password_verify($postData['password'], $userDB['password_hash'])) {
            $params['succes'] = true;
            $params['message'] = "Login succesful";

            session_start();
            $_SESSION["user_id"] = $userDB["id"];
            header("Location: /");
            exit;
          } else {
            $params['userData']['email'] = $postData['email'];
            $params['succes'] = false;
            $params['message'] = "Invalid login";
          }
        } else {
          $params['userData']['email'] = $postData['email'];
          $params['succes'] = false;
          $params['message'] = "There is no user with that email address";
        }
      }
      echo $this->renderView('login', $params);
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
    //extract variable $params->$posts
    extract($params);
    ob_start();
    include_once ROOT_DIR . "/app/views/$view.php";
    return ob_get_clean();
  }

  protected function getPostData() {

    $body = [];
    foreach ($_POST as $key => $value) {
      $body[$key] = trim(filter_input(INPUT_POST, $key, FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    }

    return $body;
  }

  protected function loginFormValidation($data) {
    $errors = [];

    if (empty($data['email'])) {
      $errors['email'] = 'Email is required';
    } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
      $errors['email'] = 'Valid email is required';
    }

    if (empty($data['password'])) {
      $errors['password'] = 'Password is required';
    }

    return $errors;
  }
}
