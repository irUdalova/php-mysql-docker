<?php

include_once ROOT_DIR . '/app/helpers/functions.php';
?>

<div class="main-page">


  <!-- <h2>DISCOVER SOMETHING NEW</h2> -->

  <section class="hero-image">
    <div class="hero-text">
      <h1 class="main-title">Explore and enrich your language!</h1>
      <p></p>

      <p class="hero-about">Every family has its own unique words—newly created expressions used daily,
        sometimes unknown beyond their home. Some words belong to a specific region, shaping local identity.
        Let's dive into this boundless world of language! <br> Discover, explore, and share your own words!</p>
      <a class="sbm-btn explore" href="/explore">EXPLORE</a>
    </div>
  </section>

  <section class="random-word">
    <div class="random-text">
      <h2 class="random-title">Check out a random word!</h2>
      <p>This word could be a sign or inspiration for your day!</p>
      <a class="post-link random" href="/posts/<?= $randomWord['id']; ?>">
        <p class="title word-random"><?= $randomWord['word'] ?> </p>
      </a>
    </div>
  </section>

  <section class="top-rated">
    <?php if (!empty($topFavorites)) : ?>
      <div class="post-top-favorites">
        <h2 class="post-top-fav-title">Top three popular favorites words:</h2>

        <?php foreach ($topFavorites as $favWord) : ?>
          <div class="post-fav">
            <a class="post-link top-fav" href="/posts/<?= $favWord['word_id']; ?>">
              <h2 class="title title-fav"><?= $favWord['word'] ?> </h2>
              <div class="fav-amount">
                <span class=fav-amount-num><?= $favWord['amount'] ?></span>
                <span class="material-symbols-outlined">favorite</span>
              </div>
            </a>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif ?>
  </section>

  <section class="about">
    <div class="about-text">
      <h2 class="about-title">About</h2>
      <p class="about-description">This application is an MVP and a test version of a platform designed to collect,
        preserve, and popularize unique words we use every day—words that won't be found in dictionaries.
        While this version is in English, its main inspiration comes from the Ukrainian language.
        Every region, every community, and every family is an endless source of fascinating and authentic
        words, worth discovering, sharing, and keeping alive. I hope this portal becomes
        a true source of inspiration, helping to expand the boundaries of our language.</p>
      <!-- <a class="post-link random" href="/posts/<?= $randomWord['id']; ?>">
        <h2 class="title title-fav"><?= $randomWord['word'] ?> </h2>
      </a> -->
    </div>
  </section>

</div>