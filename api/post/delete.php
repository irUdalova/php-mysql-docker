<?php

//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

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

//Instantiate post object
$post = new Posts($db);

$parts = explode("/", $_SERVER['REQUEST_URI']);
if ($parts[1] !== "posts") {
  http_response_code(404);
  exit;
}
$id = $parts[2] ?? null;

if ($post->delete($id)) {
  echo json_encode(
    array([
      "Message" => "Post deleted",
      "Deleted post id" => $id
    ])
  );
} else {
  echo json_encode(
    array('Message' => 'Post not deleted')
  );
}
