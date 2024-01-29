<?php

//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once __DIR__ . '/../../config/Database.php';
include_once __DIR__ . '/../../models/Posts.php';

// $config = parse_ini_file("../../.env");
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

//Post query
$posts = $post->getAll();

if (!empty($posts)) {
  echo json_encode($posts);
} else {
  echo json_encode(
    array('message' => 'No posts found')
  );
}
