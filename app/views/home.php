<?php

include_once ROOT_DIR . '/app/helpers/functions.php';
?>

<div class="main-page">

  <?php if (empty($activeTag)) : ?>
    <h1>DISCOVER SOMETHING NEW</h1>
    <?php (!empty($control['tags'])) ? $activeTags = array_column($control['tags'], 'id') : $activeTags = NULL ?>
  <?php endif; ?>


  <form class="control-bar-form" action="/" method="get" autocomplete="off">
    <div class="control-search">
      <input class="inp-text" name="search" placeholder="Find a word" contenteditable id="search" value="<?= $control['search'] ?>" maxlength="100"></input>
      <button class="sbm-btn search" type="submit"><span class="material-symbols-outlined">search</span></button>
    </div>

    <div class="control-filter-tag">
      <div class="control-tags-container" data-current="<?= $control['tagsStr'] ?? "" ?>">
      </div>
      <button class="sbm-btn filter" type="submit">apply</button>
    </div>

    <div class="control-sort">
      <select class="control-sort" name="sort" id="sort">
        <option value="new" <?= $control['sort'] === 'new' ? 'selected' : ''; ?>>Newest first</option>
        <option value="old" <?= $control['sort'] === 'old' ? 'selected' : ''; ?>>Oldest first</option>
        <option value="AZ" <?= $control['sort'] === 'AZ' ? 'selected' : ''; ?>>From A to Z</option>
        <option value="ZA" <?= $control['sort'] === 'ZA' ? 'selected' : ''; ?>>From Z to A</option>
      </select>
    </div>
  </form>

  <?php if (empty($words) && empty($control['search'])) : ?>
    <p>There is no posts</p>
  <?php endif; ?>

  <?php if (!empty($control['search']) && empty($words)) : ?>
    <p>Searching <span><strong><?= $control['search'] ?></strong></span> has no result</p>
  <?php endif; ?>

  <div class="posts">

    <?php foreach ($words as $word) : ?>
      <div class="post">

        <?php if (!empty($userID)) : ?>
          <div class="post-favorites">
            <?php if (!empty($word['isFavorite'])) : ?>
              <a class="post-remove-fav-link" href="/posts/<?= $word['word_id']; ?>/remfav"><span class="material-symbols-outlined">favorite</span></a>
            <?php else: ?>
              <a class="post-add-fav-link" href="/posts/<?= $word['word_id']; ?>/addfav"><span class="material-symbols-outlined">favorite</span></a>
            <?php endif; ?>
          </div>
        <?php endif; ?>

        <a class="post-link" href="/posts/<?= $word['word_id']; ?>">
          <h2 class="title"><?php echo $word['word']; ?> </h2>
          <p class="post-body"><?php echo $word['definition']; ?> </p>
        </a>

        <div class="post-info-wrap">

          <div class="post-tags-container">
            <?php foreach ($word['tags'] as $tag) : ?>
              <p class="post-tag <?= $activeTags && in_array($tag['tag_id'], $activeTags) ? "active" : NULL ?>"> <?= $tag['tag'] ?></p>
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