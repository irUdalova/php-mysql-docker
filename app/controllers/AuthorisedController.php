<?php
abstract class AuthorisedController {

  public function getAuthUserId() {
    session_start();
    $userID = $_SESSION["user_id"] ?? NULL;
    if (!$userID) {
      session_unset();
      session_destroy();
      header("Location: /login");
      exit;
    } else {
      return $userID;
    }
  }
}
