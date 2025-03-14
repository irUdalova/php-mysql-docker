<?php

include_once ROOT_DIR . '/models/WordsModel.php';
include_once ROOT_DIR . '/models/TagsModel.php';


class ExplorePageController {

  public function canHandle() {
    $isMethodSupported = $_SERVER["REQUEST_METHOD"] === "GET";
    $urlPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    if ($isMethodSupported && $urlPath === '/explore') {
      return true;
    }
    return false;
  }

  public function handle() {
    session_start();
    $word = new WordsModel();

    $params = [
      'words' => [],
      'userID' => $_SESSION["user_id"] ?? NULL,
      'pagination' => [],
      'control'
    ];

    $getData = $this->getGetData();

    if (isset($getData['sort'])) {
      $order = addslashes(SORT_PARAMS[$getData['sort']]['order']);
      $orderType = addslashes(SORT_PARAMS[$getData['sort']]['order_type']);
    } else {
      $order = SORT_PARAMS['new']['order'];
      $orderType = SORT_PARAMS['new']['order_type'];
    }

    //search sort filters
    if (isset($getData['search'])) {
      $search = addslashes($getData['search']);
    } else {
      $search = '';
    }

    $params['control']['sort'] = $getData['sort'] ?? 'new';
    $params['control']['search'] = $getData['search'] ?? '';

    // filter tags
    if (isset($getData['tags'])) {
      // $tags = $this->getTagsWithId($getData['tags']);
      $tags = $this->getFullTagsData($getData['tags']);
    } else {
      $tags = NULL;
    }
    // $params['control']['tagsStr'] = $this->tagsToStr($tags);
    $params['control']['tagsStr'] = $this->tagsToStr($tags);

    //new!!!!
    $params['control']['tags'] = $tags;

    // pagination
    $allWordsCount = $word->countAllWords($search, $tags);

    $perPage = 6;
    $params['pagination']['totalPages'] = ceil($allWordsCount / $perPage);

    if (isset($getData['page'])) {
      $params['pagination']['page'] = $getData['page'];
      $params['pagination']['start'] = (($getData['page'] - 1) * $perPage);
    } else {
      $params['pagination']['page'] = 1;
      $params['pagination']['start'] = 0;
    }

    $params['words'] = $word->getAll($search, $tags, $order, $orderType, $params['pagination']['start'], $perPage);

    //define favorites words for current user
    if (!empty($params['userID'])) {
      $wordsFav = $word->getAllFavorites($params['userID']);

      if (!empty($wordsFav)) {
        foreach ($params['words'] as $index => $wordSingle) {
          $params['words'][$index]['isFavorite'] = in_array($wordSingle['word_id'], $wordsFav);
        }
      }
    }

    echo $this->renderView('explore', $params);
  }

  // public function tagsToStr($tags) {
  //   if ($tags) {
  //     $tagsArr = array_column($tags, 'tag');
  //     return implode(',', array_unique($tagsArr));
  //   } else {
  //     return '';
  //   }
  // }

  public function tagsToStr($tags) {
    if ($tags) {
      $tagsArr = array_column($tags, 'id');
      return implode(',', array_unique($tagsArr));
    } else {
      return '';
    }
  }

  public function getTagsWithId($tagsGet) {

    $tagModel = new TagsModel();
    $tagsDB = $tagModel->getAllTags();
    $tagsWithId = [];

    if ($tagsGet) {
      foreach ($tagsGet as $key => $tag) {
        //trim tags from post data
        $tagTrim = strtolower(trim($tag));
        $keyTag = array_search($tagTrim, array_column($tagsDB, 'tag'));
        if ($keyTag !== false) {
          $tagsWithId[] = $tagsDB[$keyTag];
        } else {
          $tagsWithId[] = '';
        }
      }
    }
    return $tagsWithId;
  }

  public function getFullTagsData($tagsGet) {

    $tagModel = new TagsModel();
    $tagsDB = $tagModel->getAllTags();
    $tagsFullData = [];

    if ($tagsGet) {
      foreach ($tagsGet as $key => $tagId) {
        //trim tags from post data
        $tagTrim = strtolower(trim($tagId));
        $keyTag = array_search($tagTrim, array_column($tagsDB, 'id'));
        if ($keyTag !== false) {
          $tagsFullData[] = $tagsDB[$keyTag];
        } else {
          $tagsFullData[] = '';
        }
      }
    }
    return $tagsFullData;
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

  //without array of the tags
  // protected function getGetData() {
  //   $body = [];
  //   foreach ($_GET as $key => $value) {
  //     $body[$key] = trim(filter_input(INPUT_GET, $key, FILTER_SANITIZE_FULL_SPECIAL_CHARS));
  //   }
  //   return $body;
  // }

  protected function getGetData() {
    $body = [];

    $args = array(
      "search" => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
      "tags" => array(
        'filter' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'flags'  => FILTER_REQUIRE_ARRAY,
      ),
      "sort" => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
      "page" => FILTER_SANITIZE_FULL_SPECIAL_CHARS
    );

    $body = filter_input_array(INPUT_GET, $args);
    return $body;
  }
}
