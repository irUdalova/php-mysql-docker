<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>MY PHP, MYSQL, MY LIFE</title>
  <link rel="stylesheet" href="/app/globals.css">
</head>

<body>

  <header class="header">
    <div class="container">

      <div class="nav-container">
        <div class="logo-ico">
          <a href="/"></a>
        </div>

        <nav class="nav">
          <ul class="nav-list">
            <li class="nav-item"><a href="/" class="nav-link">HOME</a></li>
            <li class="nav-item"><a href="/mynotes" class="nav-link">MY NOTES</a></li>
            <li class="nav-item"><a href="" class="nav-link">ABOUT</a></li>
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
          <p>github</p>
        </a>
      </div>

    </div>

  </footer>
  </div>
</body>

</html>