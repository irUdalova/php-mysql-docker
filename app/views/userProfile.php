<?php
include_once ROOT_DIR . '/app/helpers/functions.php';
?>

<div class="user-profile-container">

  <div class="user-profile-wrap">

    <div class="profile-image-wrap">
      <div class="profile-image">
        <img class="avatar-img" src="<?php echo $userData['profile_img'] ? STATIC_URL . $userData['profile_img'] : "/public/img/user-default.png" ?>" alt="Profile image" width="200" height="200">
      </div>
    </div>

    <div class="user-content">

      <div class="user-data-wrap">
        <div class="user-data">
          <h1 class="user-name"><?= $userData['name'] ?></h1>
          <?php
          if (!empty($userData['bio'])) { ?>
            <p class="user-bio"><?= $userData['bio'] ?></p>
          <?php } ?>
        </div>
      </div>

      <div class="user-profile-statistic-bar">
        <div class="stat-item-wrap">
          <p class="stat-item user-profile"><span class="material-symbols-outlined">assignment_ind</span>Member sinse</p>
          <p class="stat-item-num"><?php echo formatDate($userData['date_created']); ?></p>
        </div>
        <div class="stat-item-wrap">
          <p class="stat-item user-profile"><span class="material-symbols-outlined">note_stack</span>Posts</p>
          <p class="stat-item-num"><?php echo $postsCount ?></p>
        </div>
        <div class="stat-item-wrap">
          <p class="stat-item user-profile"><span class="material-symbols-outlined">comment</span>Comments </p>
          <p class="stat-item-num">0</p>
        </div>
      </div>

    </div>
  </div>
</div>

<div class="posts">

  <?php foreach ($words as $word) : ?>
    <div class="post">

      <a class="post-link" href="/posts/<?= $word['word_id']; ?>">
        <h2 class="title"><?php echo $word['word']; ?> </h2>
        <p class="post-body"><?php echo $word['definition']; ?> </p>
      </a>

      <div class="post-info-wrap">

        <div class="post-tags-container">
          <?php foreach ($word['tags'] as $tag) : ?>
            <a class="post-tag-link <?= $activeTag && $activeTag['id'] === $tag['tag_id'] ? "active" : NULL ?>" href=<?= "/tags/" . $tag['tag_id'] ?>> <?= $tag['tag'] ?></a>
          <?php endforeach; ?>
        </div>

        <div class=post-info>

          <p class="post-date"><?php echo formatDate($word['word_date_created']); ?> </p>
          <div class="post-user-data">

            <p class="post-user-name">Added by </br> <?php echo $word['name']; ?> </p>

            <a class="user-profile-link" href=<?= "/profile/user/" . $word['user_id'] ?>>
              <div class="profile-image small">
                <img class="avatar-img" src=<?php echo $word['profile_img'] ? STATIC_URL . $word['profile_img'] : "/public/img/user-default.png" ?> alt="Profile image" width="40" height="40">
              </div>
            </a>

          </div>
        </div>
      </div>

    </div>
  <?php endforeach; ?>

</div>