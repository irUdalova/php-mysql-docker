<?php

include_once ROOT_DIR . '/models/UsersModel.php';


class ProfileImgDeleteController {

  public function canHandle() {
    if ($_SERVER["REQUEST_METHOD"] === "POST" && $_SERVER["REQUEST_URI"] === '/profile/delete-image') {
      return true;
    }
    return false;
  }

  public function handle() {
    session_start();
    $userId = $_SESSION["user_id"] ?? NULL;

    if ($userId) {
      $user = new UsersModel;
      $userData = $user->getById($userId);

      if ($userData['profile_img']) {
        $isDeleteImg = $user->deleteImg($userId);
        if ($isDeleteImg) {
          header("Location: /profile");
          exit;
        }
      } else {
        header("Location: /profile");
        exit;
      }
    }
  }
}
