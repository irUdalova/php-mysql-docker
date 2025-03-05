<?php

include_once ROOT_DIR . '/models/WordsModel.php';


class PostAddFavController {
  public $postID;

  public function __construct() {
    $parts = explode("/", $_SERVER['REQUEST_URI']);
    $idURI = $parts[2] ?? null;
    $this->postID = filter_var($idURI, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  }

  public function canHandle() {
    $isMethodSupported = $_SERVER["REQUEST_METHOD"] === "GET";
    $request = $_SERVER["REQUEST_URI"];
    $regex = '~^/posts/[0-9]+/addfav$~i';
    if ($isMethodSupported && preg_match($regex, $request)) {
      return true;
    }
    return false;
  }

  public function handle() {
    session_start();
    $word = new WordsModel();
    $userID = $_SESSION["user_id"] ?? NULL;

    if ($userID) {
      //check if the word_id exists for this user_id, if so, then do not add it. In Safari it was added several times if you go back to the previous page.
      $isExist = $word->checkFavExist($userID, $this->postID);

      if (empty($isExist)) {
        $word->addToFavorites($userID, $this->postID);
      }
    }

    header('Location: ' . $_SERVER['HTTP_REFERER']); // go to previous page
    exit;
  }
}
