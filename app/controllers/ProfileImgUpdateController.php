<?php
include_once ROOT_DIR . '/models/UsersModel.php';
include_once ROOT_DIR . '/models/WordsModel.php';


class ProfileImgUpdateController {

  public function canHandle() {
    $isMethodSupported = $_SERVER["REQUEST_METHOD"] === "GET" || $_SERVER["REQUEST_METHOD"] === "POST";
    if ($isMethodSupported && $_SERVER["REQUEST_URI"] === '/profile/update-image') {
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
    $params['postsCount'] = count($word->getByUserId($params['userID']));

    if ($_SERVER["REQUEST_METHOD"] === "GET") {
      echo $this->renderView('profileImgEdit', $params);
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
      $profileImg = $_FILES['profile-img'];

      $params['errors'] = $this->imgUploadValidation($profileImg);
      $isErrors = count(array_filter($params['errors']));

      if (empty($isErrors)) {
        $imgPath = $this->uploadProfileImg($profileImg);

        $user = new UsersModel;
        $isUpdate = $user->updateImg($imgPath, $params['userID']);

        if ($isUpdate) {
          header("Location: /profile");
          exit;
        } else {
          $params['succes'] = false;
          $params['message'] = "Something went wrong, try again";
        }
      }
      echo $this->renderView('profileImgEdit', $params);
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
    extract($params);
    ob_start();
    include_once ROOT_DIR . "/app/views/$view.php";
    return ob_get_clean();
  }

  protected function uploadProfileImg($imgFile) {
    $fileName = $imgFile['name'];
    $fileTmpName = $imgFile['tmp_name'];
    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));

    $fileNameNew = uniqid('', true) . "." . $fileActualExt;
    $fileDestination = 'public/uploads/' . $fileNameNew;
    if (move_uploaded_file($fileTmpName, $fileDestination)) {
      return $fileDestination;
    } else return false;
  }

  protected function imgUploadValidation($imgFile) {
    $errors = [];

    $fileName = $imgFile['name'];
    $fileSize = $imgFile['size'];
    $fileError = $imgFile['error'];

    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));
    $allowed = array('jpg', 'jpeg', 'png');

    if (!$fileName) {
      $errors['profileImg'] = 'please choose file to upload';
    } elseif ($fileSize > 1000000) {
      $errors['profileImg'] = 'your file is to big';
    } elseif (!in_array($fileActualExt, $allowed)) {
      $errors['profileImg'] = 'you cannot upload file of this type';
    } elseif (!$fileError === 0) {
      $errors['profileImg'] = 'there was en error uploading your file';
    }
    return $errors;
  }
}
