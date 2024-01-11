<?php 
$dbconfig = parse_ini_file("../.env");

$connect = mysqli_connect(
  'db', #servise name
  'php_docker', #username
  $dbconfig['MYSQL_PASSWORD'], #password
  'php_docker', #db table
);

// check connection

if ($connect->connect_error) {
  die('Connection failed' . $connect->connect_error);
}

$table_name = "php_docker_table";

$query = "SELECT * FROM $table_name";

$response = mysqli_query($connect, $query);

$posts = mysqli_fetch_all($response, MYSQLI_ASSOC);

// var_dump($posts);

?> 

<?php
$title=$body='';
$titleErr=$bodyErr='';


//form submit
if (isset($_POST['submit'])) {
  // $titleErr=$bodyErr='submit';

  if(empty($_POST['title'])) {
    $titleErr='Title is required';
  } else {
    $title=filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  }

  if(empty($_POST['body'])) { 
    $bodyErr='Text is required';
  } else {
    $body=filter_input(INPUT_POST, 'body', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  }

  if(empty($titleErr) && empty($bodyErr)) {
    //add to database
    $sql = "INSERT INTO $table_name (title, body) VALUES ('$title', '$body')";
    if(mysqli_query($connect, $sql)) {
      //success
      // echo $_SERVER['PHP_SELF'];
      // header('Location: index.php'); //redirect
    } else {
      echo 'Error' . mysqli_error($connect);
    }
  }
}
?>
 
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>MY PHP, MYSQL, MY LIFE</title>
    <link rel="stylesheet" href="globals.css">
  </head>
  <body>
    <main>
      <div class="container">
        
        <div class="form-wrap">
          <h2 class="title">Add some post</h2>
          <form class="submit" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="POST">
            <textarea class="inp-text <?php echo $titleErr ? "invalid" : null; ?>" name="title" placeholder="Your title" contenteditable></textarea>
            <?php echo $titleErr ? "<div class='sbm-err'>$titleErr</div>" : null; ?>
            <textarea class="inp-text <?php echo $bodyErr ? "invalid" : null; ?>" name="body" placeholder="Your text" contenteditable></textarea>
            <?php echo $titleErr ? "<div class='sbm-err'>$bodyErr</div>" : null; ?>
            <button class="sbm-btn" type="submit" name="submit">Send</button>
          </form>
        </div>

        <h1>Some random posts</h1>

        <?php if(empty($posts)): ?>
          <p>There is no posts</p>
        <?php endif; ?>

        <div class="posts">

          <?php foreach($posts as $post): ?>
            <div class="post">
            <h2 class="title"><?php echo $post['title']; ?> </h2>
            <p><?php echo $post['date_created']; ?> </p>
            <p><?php echo $post['body']; ?> </p>
            </div>
          <?php endforeach; ?>
       
        </div>
      </div>
    </main>
  </body>
</html>