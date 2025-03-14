<?php
$userId = $_SESSION["user_id"] ?? NULL;
// for profile image in the nav-bar
// if ($userId) {
//   include_once ROOT_DIR . '/models/UsersModel.php';
//   $user = new UsersModel;
//   $userData = $user->getById($userId);
// }
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>MY PHP, MYSQL, MY LIFE</title>
  <link rel="stylesheet" href="/app/globals.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

  <!-- data from DB for js -->
  <script>
    <?php
    function getTags() {
      include_once ROOT_DIR . '/models/TagsModel.php';
      $tagModel = new TagsModel();
      return $tagModel->getAllTags();
    } ?>

    const application = {
      tags: <?php echo json_encode(getTags()); ?>
    }
  </script>

</head>

<body>

  <header class="header">
    <div class="container">

      <div class="nav-container">

        <div class="logo-ico">
          <a class="logo-link" href="/"></a>
        </div>

        <nav class="nav">
          <ul class="nav-list">
            <li class="nav-item"><a href="/" class="nav-link <?php echo parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) === "/" ? "active" : null; ?>">HOME</a></li>
            <li class="nav-item"><a href="/explore" class="nav-link <?php echo parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) === "/explore" ? "active" : null; ?>">EXPLORE</a></li>

            <?php if (!$userId) : ?>
              <li class="nav-item nav-login"><a href="/login" class="nav-link  <?php echo ($_SERVER["REQUEST_URI"] === "/login") ? "active" : null; ?>">LOG IN</a></li>
              <li class=" nav-item"><a href="/signup" class="nav-link signup">SIGN UP</a></li>
            <?php endif; ?>

            <?php if ($userId) : ?>
              <li class="nav-item"><a href="/myposts" class="nav-link <?php echo (parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) === "/myposts") ? "active" : null; ?>">MY POSTS</a></li>
              <li class="nav-item"><a href="/favorites" class="nav-link <?php echo ($_SERVER["REQUEST_URI"] === "/favorites") ? "active" : null; ?>">FAVORITES</a></li>
              <li class="nav-item nav-profile"><a href="/profile" class="nav-link <?php echo ($_SERVER["REQUEST_URI"] === "/profile") ? "active" : null; ?>">
                  <!-- <div class="profile-image small">
                    <img class="avatar-img" src=<?
                                                // php echo $userData['profile_img'] ?? "/public/img/user-default.png" 
                                                ?> alt="Profile image" width="40" height="40">
                  </div> -->
                  PROFILE
                </a></li>
              <li class="nav-item"><a href="/logout" class="nav-link signout">LOG OUT</a></li>
            <?php endif; ?>

          </ul>
        </nav>

      </div>
    </div>
  </header>

  <main>
    <div class="container">

      {{content}}

    </div>
  </main>
  <footer>

    <div class="foot-container">

      <div class="info">
        <p>©</p>
        <p>2024</p>
        <a class="github-link" href="https://github.com/irUdalova">
          <p>irUdalova</p>
        </a>
      </div>

    </div>

  </footer>
  </div>
  <script type="module" src="/js/tag.js"></script>
  <script type="module" src="/js/controls.js"></script>
</body>

</html>