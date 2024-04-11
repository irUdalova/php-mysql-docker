<?php
include_once ROOT_DIR . '/app/helpers/functions.php';
?>

<div class="profile-wrap">

  <div class="profile-image-wrap">
    <div class="profile-image">
      <img class="avatar-img" src="<?php echo $userData['profile_img'] ? STATIC_URL . $userData['profile_img'] : "/public/img/user-default.png" ?>" alt="Profile image" width="200" height="200">

      <form class="delete-image" action="/profile/delete-image" method="post">
        <button class="sbm-btn delete" type="submit"><span class="material-symbols-outlined">delete</span></button>
      </form>
    </div>

    <form class="update-image" action="/profile/update-image" method="post" enctype="multipart/form-data" novalidate>
      <label class="custom-file-upload">
        <p class="custom-label-text">Change image</p>
        <input class="inp-file" type="file" name="profile-img" accept=".jpg,.jpeg,.png"></input>
      </label>
      <button class="sbm-btn upload" type="submit">
        <span class="material-symbols-outlined">upload</span>
        <span>Upload</span>
      </button>
    </form>

    <div class="profile-image-hint">
      Upload a new avatar.<br />
      Maximum upload size is 1MB.<br />
      Available formats are<br />
      *.jpg, *.jpeg, *.png.
    </div>

  </div>

  <div class="user-content">
    <div class="user-data-wrap">
      <div class="user-data">
        <h1 class="user-name"><?= $userData['name'] ?></h1>
        <p class="user-email"><?= $userData['email'] ?></p>
        <?php
        if (!empty($userData['bio'])) { ?>
          <p class="user-bio"><?= $userData['bio'] ?></p>
        <?php } ?>
      </div>

      <div class="edit-profile-wrap">
        <a class="sbm-btn change-psw" href="/profile/change-password">Change password</a>
        <a class="sbm-btn edit" href="/profile/edit">Edit profile</a>
      </div>

    </div>
  </div>

  <div class="user-statistic-bar">
    <div class="stat-item-wrap">
      <p class="stat-item"><span class="material-symbols-outlined">assignment_ind</span>Member sinse</p>
      <p class="stat-item-num"><?php echo formatDate($userData['date_created']); ?></p>
    </div>
    <div class="stat-item-wrap">
      <p class="stat-item"><span class="material-symbols-outlined">note_stack</span>Posts</p>
      <p class="stat-item-num"><?php echo $postsCount ?></p>
    </div>
    <div class="stat-item-wrap">
      <p class="stat-item"><span class="material-symbols-outlined">comment</span>Comments </p>
      <p class="stat-item-num">0</p>
    </div>
  </div>
</div>