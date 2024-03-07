<?php

include_once ROOT_DIR . '/models/UsersModel.php';


class SignupPageController {

  public function canHandle() {
    $isMethodSupported = $_SERVER["REQUEST_METHOD"] === "GET" || $_SERVER["REQUEST_METHOD"] === "POST";
    if ($isMethodSupported && $_SERVER["REQUEST_URI"] === '/signup') {
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
      echo $this->renderView('signup', $params);
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
      $postData = $this->getPostData();
      $passwordHash = password_hash($postData['password'], PASSWORD_DEFAULT);

      $params['errors'] = $this->signupFormValidation($postData);
      $isErrors = count(array_filter($params['errors']));

      if (empty($isErrors)) {
        $user = new UsersModel;
        //Create user
        $id = $user->create($postData['name'], $postData['email'], $passwordHash);
        if ($id) {
          $params['succes'] = true;
          $params['message'] = "Sign up successful!";
          session_start();
          $_SESSION["user_id"] = $id;
          header("Location: /");
        } else {
          $params['succes'] = false;
          $params['message'] = "Something went wrong, try again";
        }
      } else {
        $params['userData']['name'] = $postData['name'];
        $params['userData']['email'] = $postData['email'];
        $params['userData']['password'] = $postData['password'];
        $params['userData']['password-confirm'] = $postData['password-confirm'];
      }
      echo $this->renderView('signup', $params);
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

  protected function getPostData() {
    $body = [];
    foreach ($_POST as $key => $value) {
      $body[$key] = trim(filter_input(INPUT_POST, $key, FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    }

    return $body;
  }

  protected function signupFormValidation($data) {
    $user = new UsersModel;
    $errors = [];

    if (empty($data['name'])) {
      $errors['name'] = 'Name is required';
    }

    if (empty($data['email'])) {
      $errors['email'] = 'Email is required';
    } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
      $errors['email'] = 'Valid email is required';
    } elseif (!empty($user->getByEmail($data['email']))) {
      $errors['email'] = 'This email already taken';
    }

    if (empty($data['password'])) {
      $errors['password'] = 'Password is required';
    } else {
      if (strlen($data['password']) < 6) {
        $errors['password'] = 'Password must be at least 8 characters';
      }
      if (!preg_match("/[a-z]/i", $data['password'])) {
        $errors['password'] = 'Password must contain at least one letter';
      }
      if (!preg_match("/[0-9]/i", $data['password'])) {
        $errors['password'] = 'Password must contain at least one number';
      }
    }

    if (empty($data['password-confirm'])) {
      $errors['password-confirm'] = 'Password confirmation is required';
    } elseif ($data['password'] !== $data['password-confirm']) {
      $errors['password-confirm'] = 'Passwords must mutch';
    }

    return $errors;
  }
}
