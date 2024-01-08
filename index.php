<?php

$dbconfig = parse_ini_file(".env");

$connect = mysqli_connect(
  'db', #servise name
  'php_docker', #username
  $dbconfig['MYSQL_PASSWORD'], #password
  'php_docker', #db table
);

$table_name = "php_docker_table";

$query = "SELECT * FROM $table_name";

$response = mysqli_query($connect, $query);

echo "<strong>$table_name</strong>";
while($i = mysqli_fetch_assoc($response))
{
  echo "<p>".$i['title']."</p>";
  echo "<p>".$i['body']."</p>";
  echo "<p>".$i['date_created']."</p>";
  echo "<hr>";
}
