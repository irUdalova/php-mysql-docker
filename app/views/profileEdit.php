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

    <form class="edit-profile-form" action="/profile/edit" method="post" novalidate>
      <?php
      if (!empty($succes)) { ?>
        <div class='message-err'><?= $message ?></div>
      <?php } ?>

      <label for="name">My name
        <input class="inp-text <?php echo isset($errors['name']) ? "invalid" : null; ?>" type="text" name="name" placeholder="Name" value="<?= $userData['name'] ?? "" ?>"></input>
      </label>
      <?php
      if (isset($errors['name'])) { ?>
        <div class='sbm-err'><?= $errors['name'] ?></div>
      <?php } ?>

      <label for="email">My email
        <input class="inp-text <?php echo $emailError ? "invalid" : null; ?>" type="email" name="email" placeholder="Email" value="<?= $userData['email'] ?? "" ?>" readonly="readonly"></input>
      </label>

      <label for="bio">A few words about me
        <textarea class="inp-text" name="bio" placeholder="Bio"><?= $userData['bio'] ?? "" ?></textarea>
      </label>

      <div class="choose-submit">
        <button class="sbm-btn update" type="submit" name="update">Update info</button>
        <button class="sbm-btn cancel" type="submit" name="cancel">Cancel</button>
      </div>
    </form>

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