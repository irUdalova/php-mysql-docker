<?php

include_once ROOT_DIR . '/models/WordsModel.php';
include_once ROOT_DIR . '/models/TagsModel.php';
include_once ROOT_DIR . '/app/controllers/AuthorisedController.php';


class PostEditPageController extends AuthorisedController {
  public $postID;

  public function __construct() {
    $parts = explode("/", $_SERVER['REQUEST_URI']);
    $idURI = $parts[2] ?? null;
    $this->postID = filter_var($idURI, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  }

  public function canHandle() {
    $isMethodSupported = $_SERVER["REQUEST_METHOD"] === "GET" || $_SERVER["REQUEST_METHOD"] === "POST";
    $request = $_SERVER["REQUEST_URI"];
    $regex = '~^/posts/[0-9]+/edit$~i';
    if ($isMethodSupported && preg_match($regex, $request)) {
      return true;
    }
    return false;
  }

  public function handle() {
    $userID = $this->getAuthUserId();
    $word = new WordsModel();

    $params = [
      'errors' => [],
      'wordData' => [],
      'formData' => [],
      'succes' => '',
      'message' => '',
    ];

    $params['wordData'] = $word->getWordById($this->postID);
    $params['wordData']['tagsStr'] = $this->tagsToStr($params['wordData']['tags']);

    if ($_SERVER["REQUEST_METHOD"] === "GET") {

      $params['formData'] = $params['wordData'];

      if ($params['wordData']['user_id'] !== $userID) {
        http_response_code(403);
        exit;
      }
      echo $this->renderView('postEdit', $params);
    }



    if ($_SERVER["REQUEST_METHOD"] === "POST") {

      if (isset($_POST['cancel'])) {
        header("Location: /myposts");
        exit;
      }

      $postData = $this->getPostData();

      $params['errors'] = $this->createPostFormValidation($postData);
      $isErrors = count(array_filter($params['errors']));

      if (empty($isErrors)) {
        if ($postData['tags']) {
          $tagsProcessed = $this->processTags($postData['tags']);
        } else {
          $tagsProcessed = null;
        }

        $word = new WordsModel();
        $wordName = strtolower(trim($postData['word'], '.'));
        $wordDefin = strtolower(trim($postData['definition'], '.'));
        $wordEx = ucfirst(strtolower(trim($postData['example'], '.'))) . '.';


        if ($word->update($wordName, $wordDefin, $wordEx, $this->postID)) {
          if ($tagsProcessed) {
            $tagModel = new TagsModel();

            if ($tagModel->deleteWordTags($this->postID)) {

              foreach ($params['wordData']['tags'] as $tag) {
                // find words with this tag
                $tagWords = $word->getByTagId($tag['tag_id']);
                if (!$tagWords) {
                  $tagModel->deleteTag($tag['tag_id']);
                }
              }

              foreach ($tagsProcessed as $tag) {
                $tagModel->createWordTag($this->postID, $tag['id']);
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
      } else {
        $params['formData']['word'] = $postData['word'];
        $params['formData']['definition'] = $postData['definition'];
        $params['formData']['example'] = $postData['example'];
        $params['formData']['tags'] = $postData['tags'];
        $params['formData']['tagsStr'] = $postData['tags'] ? implode(',', $postData['tags']) : '';
      }

      echo $this->renderView('postEdit', $params);
    }
  }

  public function processTags($tagsPost) {

    $tagModel = new TagsModel();
    $tagsDB = $tagModel->getAllTags();
    $tagsProcessed = [];

    if ($tagsPost) {
      foreach ($tagsPost as $key => $tag) {
        $keyTag = array_search($tag, array_column($tagsDB, 'tag'));
        if ($keyTag !== false) {
          $tagsProcessed[] = $tagsDB[$keyTag];
        } else {
          $newTagId = $tagModel->createTag($tag);
          if ($newTagId) {
            $tagsProcessed[] = $tagModel->getTagById($newTagId);
          } else {
            header('HTTP/1.1 503 Service Temporarily Unavailable');
            exit;
          }
        }
      }
    }

    return $tagsProcessed;
  }

  public function tagsToStr($tags) {
    if ($tags) {
      $tagsArr = array_column($tags, 'tag');
      return implode(',', $tagsArr);
    } else {
      return '';
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

    $args = array(
      "word" => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
      "definition" => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
      "example" => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
      "tags" => array(
        'filter' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'flags'  => FILTER_REQUIRE_ARRAY,
      )
    );

    $body = filter_input_array(INPUT_POST, $args);
    return $body;
  }

  protected function createPostFormValidation($data) {

    $wordModel = new WordsModel;
    $wordPostName = strtolower(trim($data['word']));

    $wordDB = $wordModel->isWordTaken($wordPostName, $this->postID);


    $regAllSymbols = "/[\s!\"#$%&'()*+,-.\/\:\;<=>?@[\]^_`{|}~\d]/i";
    $regSymbols = "/[&'()+\/<=>[\]^_`{|}~\d]/i";

    $errors = [];

    if (empty($data['word'])) {
      $errors['word'] = 'This field is required';
    } elseif (preg_match($regAllSymbols, $wordPostName)) {
      $errors['word'] =  'This field contains characters that are not allowed';
    } elseif (!empty($wordDB)) {
      $errors['word'] = 'Oops, seems like this word is already exist';
      $errors['existingWord'] =  $wordDB;
    }

    if (empty($data['definition'])) {
      $errors['definition'] = 'This field is required';
    } elseif (preg_match($regSymbols, $data['definition'])) {
      $errors['definition'] =  'This field contains characters that are not allowed';
    }

    if (empty($data['example'])) {
      $errors['example'] = 'This field is required';
    } elseif (preg_match($regSymbols, $data['example'])) {
      $errors['example'] =  'This field contains characters that are not allowed';
    }

    if (!empty($data['tags'])) {
      foreach ($data['tags'] as $key => $tag) {
        if (preg_match($regAllSymbols, $tag)) {
          $errors['tags'] =  'This field contains characters that are not allowed';
        }
      }
    }

    return $errors;
  }
}
