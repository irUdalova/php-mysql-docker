<?php
include_once ROOT_DIR . '/app/helpers/functions.php';
?>

<div class="myposts-page">

  <!-- <h1>MY WORDS</h1> -->

  <?php if (empty($words)) {
    if ($pagination['page'] > 1) { ?>
      <p>There no words on this page</p>
    <?php } else { ?>
      <p>You did not add any words yet, maybe it is time to add new one?</p>
    <?php } ?>
  <?php } ?>

  <div class="posts">

    <div class="post create">
      <p class="material-symbols-outlined create-post">add_circle</p>
      <h2 class="title">create word</h2>
      <a class="create-post-link" href="/post/create"></a>
    </div>

    <?php foreach ($words as $word) : ?>
      <div class="post">

        <div class="post-favorites">

          <?php if (isset($word['isFavorite']) && $word['isFavorite']) : ?>
            <a class="post-remove-fav-link" href="/posts/<?= $word['word_id']; ?>/remfav"><span class="material-symbols-outlined">favorite</span></a>
          <?php else: ?>
            <a class="post-add-fav-link" href="/posts/<?= $word['word_id']; ?>/addfav"><span class="material-symbols-outlined">favorite</span></a>
          <?php endif; ?>

        </div>

        <div class="post-controllers-wrap">
          <a class="post-edit-link" href="/posts/<?= $word['word_id']; ?>/edit"><span class="material-symbols-outlined">edit</span></a>
          <a class="post-delete-link" href="/posts/<?= $word['word_id']; ?>/delete"><span class="material-symbols-outlined">delete</span></a>
        </div>

        <a class="post-link" href="/posts/<?= $word['word_id']; ?>">
          <h2 class="title"><?php echo $word['word']; ?> </h2>
          <p class="post-body"><?php echo $word['definition']; ?> </p>
        </a>

        <div class="post-info-wrap">

          <div class="post-tags-container">
            <?php foreach ($word['tags'] as $tag) : ?>
              <p class="post-tag"> <?= $tag['tag'] ?></p>
            <?php endforeach; ?>
          </div>

          <div class=post-info>

            <p class="post-date"><?php echo formatDate($word['word_date_created']); ?> </p>
            <div class="post-user-data">

              <p class="post-user-name">Added by </br> <?php echo $word['name']; ?> </p>

              <a class="user-profile-link" href=<?= $userID === $word['user_id'] ? "/profile" : "/profile/user/" . $word['user_id'] ?>>
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

  <?php if ($pagination['totalPages'] > 1) { ?>
    <div class="pagination-wrap">
      <?php include_once ROOT_DIR . '/app/views/pagination.php'; ?>
    </div>
  <?php } ?>

</div>