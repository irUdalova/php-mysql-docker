<?php

//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once __DIR__ . '/../../config/Database.php';
include_once __DIR__ . '/../../models/Posts.php';

$config = parse_ini_file(__DIR__ . "/../../.env");

$host = $config['MYSQL_HOST'];
$username = $config['MYSQL_USERNAME'];
$password = $config['MYSQL_PASSWORD'];
$db_name = $config['MYSQL_DB_NAME'];
$db_table = "php_docker_table";

//Instantiate DB & connect
$database = new Database($host, $username, $password, $db_name);
$db = $database->connect();

//Get id from URL
// $id = isset($_GET['id']) ? $_GET['id'] : die();
$parts = explode("/", $_SERVER['REQUEST_URI']);
if ($parts[1] !== "posts") {
  http_response_code(404);
  exit;
}
$id = $parts[2] ?? null;


//Instantiate post object
$posts = new Posts($db);

//Get post
$post = $posts->getById($id);

if (!empty($post)) {
  echo json_encode($post);
} else {
  echo json_encode(
    array('message' => 'No posts found')
  );
}
