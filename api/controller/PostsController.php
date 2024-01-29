<?php

class PostsController {
  public function processRequest(string $method, ?string $id): void {
    if ($id) {
      switch ($method) {
        case "GET":
          include_once './api/post/read-single.php';
          break;

        case "PUT":
          include_once './api/post/update.php';
          break;

        case "DELETE":
          include_once './api/post/delete.php';
          break;
      }
    } else {

      switch ($method) {
        case "GET":
          include_once './api/post/read.php';
          break;

        case "POST":
          include_once './api/post/create.php';
          break;
      }
    }
  }
}
