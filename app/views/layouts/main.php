<?php
$isSession = $_SESSION["user_id"] ?? NULL;
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>MY PHP, MYSQL, MY LIFE</title>
  <link rel="stylesheet" href="/app/globals.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
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
            <li class="nav-item"><a href="/" class="nav-link <?php echo ($_SERVER["REQUEST_URI"] === "/") ? "active" : null; ?>">HOME</a></li>
            <li class="nav-item"><a href="" class="nav-link <?php echo ($_SERVER["REQUEST_URI"] === "") ? "active" : null; ?>">ABOUT</a></li>

            <?php if (!$isSession) : ?>
              <li class="nav-item nav-login"><a href="/login" class="nav-link  <?php echo ($_SERVER["REQUEST_URI"] === "/login") ? "active" : null; ?>">LOG IN</a></li>
              <li class=" nav-item"><a href="/signup" class="nav-link signup">SIGN UP</a></li>
            <?php endif; ?>

            <?php if ($isSession) : ?>
              <li class="nav-item nav-profile"><a href="/myprofile/profile" class="nav-link <?php echo (explode("/", $_SERVER['REQUEST_URI'])[1] === "myprofile") ? "active" : null; ?>">MY PROFILE</a></li>
              <li class="nav-item"><a href="/logout" class="nav-link signout">LOG OUT</a></li>
            <?php endif; ?>

          </ul>
        </nav>

        <!-- <div class="login-container">
          <li class="nav-item"><a href="" class="nav-link login">LOG IN</a></li>
          <li class="nav-item"><a href="" class="nav-link signup">SIGN UP</a></li>
        </div> -->

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
          <p>github</p>
        </a>
      </div>

    </div>

  </footer>
  </div>
</body>

</html>