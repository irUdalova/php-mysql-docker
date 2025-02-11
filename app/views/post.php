<?php
include_once ROOT_DIR . '/app/helpers/functions.php';
?>


<div class="post-single-wrap">
  <div class="post-single">


    <?php if ($userID === $wordData['user_id']) { ?>
      <div class="post-controllers-wrap">
        <a class="post-edit-link" href="/posts/<?= $wordData['word_id']; ?>/edit"><span class="material-symbols-outlined">edit</span></a>
        <a class="post-delete-link" href="/posts/<?= $wordData['word_id']; ?>/delete"><span class="material-symbols-outlined">delete</span></a>
      </div>
    <?php } ?>

    <h1 class="title-post-single"><?php echo $wordData['word']; ?> </h1>
    <p class="post-definition"><?php echo $wordData['definition']; ?> </p>
    <p class="post-example"><?php echo $wordData['example']; ?> </p>

    <div class="post-info-wrap">

      <div class="post-tags-container">
        <?php foreach ($wordData['tags'] as $tag) : ?>
          <p class="post-tag"> <?= $tag['tag'] ?></p>
        <?php endforeach; ?>
      </div>

      <div class=post-info>

        <p class="post-date"><?php echo formatDate($wordData['word_date_created']); ?> </p>
        <div class="post-user-data">

          <p class="post-user-name">Added by </br> <?php echo $wordData['name']; ?> </p>

          <a class="user-profile-link" href=<?= $userID === $wordData['user_id'] ? "/profile" : "/profile/user/" . $wordData['user_id'] ?>>
            <div class="profile-image small">
              <img class="avatar-img" src=<?php echo $wordData['profile_img'] ? STATIC_URL . $wordData['profile_img'] : "/public/img/user-default.png" ?> alt="Profile image" width="40" height="40">
            </div>
          </a>

        </div>
      </div>
    </div>

  </div>
</div>