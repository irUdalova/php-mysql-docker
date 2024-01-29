<?php

declare(strict_types=1);
include_once './api/controller/PostsController.php';


//Temporary: check if it's app URL
$request = $_SERVER['REQUEST_URI'];

if ($request === '/app/app.php') {
  include_once './app/app.php';
}

//API controls
$parts = explode("/", $_SERVER['REQUEST_URI']);

if ($parts[1] !== "posts") {
  http_response_code(404);
  exit;
}

$id = $parts[2] ?? null;

$controller = new PostsController();
$controller->processRequest($_SERVER["REQUEST_METHOD"], $id);
