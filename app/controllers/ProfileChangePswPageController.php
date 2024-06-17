<?php

include_once ROOT_DIR . '/models/UsersModel.php';
include_once ROOT_DIR . '/models/WordsModel.php';


class ProfileChangePswPageController {

  public function canHandle() {
    $isMethodSupported = $_SERVER["REQUEST_METHOD"] === "GET" || $_SERVER["REQUEST_METHOD"] === "POST";
    if ($isMethodSupported && $_SERVER["REQUEST_URI"] === '/profile/change-password') {
      return true;
    }
    return false;
  }

  public function handle() {
    session_start();
    $user = new UsersModel;
    $word = new WordsModel();

    $params = [
      'userID' => $_SESSION["user_id"] ?? NULL,
      'userData' => [],
      'errors' => [],
      'succes' => '',
      'message' => '',
      'postsCount' => ''
    ];

    if (!$params['userID']) {
      header("Location: /");
      exit;
    }

    $params['userData'] = $user->getById($params['userID']);
    $params['postsCount'] = $word->countWordsByUserId($params['userID']);

    if ($_SERVER["REQUEST_METHOD"] === "GET") {
      echo $this->renderView('profileChangePsw', $params);
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

      if (isset($_POST['cancel'])) {
        header("Location: /profile");
        exit;
      }

      $postData = $this->getPostData();

      $params['errors'] = $this->editFormValidation($postData);
      $isErrors = count(array_filter($params['errors']));

      if (empty($isErrors)) {

        if ($postData['password']) {
          $passwordHash = password_hash($postData['password'], PASSWORD_DEFAULT);
        }

        $user = new UsersModel;
        $isUpdate = $user->changePassword($passwordHash, $params['userID']);

        if ($isUpdate) {
          header("Location: /profile");
          exit;
        } else {
          $params['succes'] = false;
          $params['message'] = "Something went wrong, try again";
        }
      } else {
        $params['userData']['password'] = $postData['password'];
        $params['userData']['password-confirm'] = $postData['password-confirm'];
      }
      echo $this->renderView('profileChangePsw', $params);
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

  protected function editFormValidation($data) {
    $errors = [];

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

    if (!empty($data['password']) && empty($data['password-confirm'])) {
      $errors['password-confirm'] = 'Password confirmation is required';
    } elseif ($data['password'] !== $data['password-confirm']) {
      $errors['password-confirm'] = 'Passwords must mutch';
    }

    return $errors;
  }
}
