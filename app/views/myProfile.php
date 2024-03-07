<?php
// function isActive() {
// TODO
// }
?>

<div class="profile-wrap">

  <div class="sidebar">
    <a class="side-item <?php echo ($_SERVER["REQUEST_URI"] === "/myprofile/profile") ? "active" : null; ?>" href="/myprofile/profile"><span class="material-symbols-outlined">person</span><span>Profile</span></a>
    <a class="side-item <?php echo ($_SERVER["REQUEST_URI"] === "/myprofile/my-posts") ? "active" : null; ?>" href="/myprofile/my-posts"><span class="material-symbols-outlined">note_stack</span><span>My posts</span></a>
    <a class="side-item <?php echo ($_SERVER["REQUEST_URI"] === "/myprofile/create") ? "active" : null; ?>" href="/myprofile/create"><span class="material-symbols-outlined">note_stack_add</span><span>Create new post</span></a>
    <a class="side-item <?php echo ($_SERVER["REQUEST_URI"] === "/myprofile/favourites") ? "active" : null; ?>" href="/myprofile/favourites"><span class="material-symbols-outlined">stack_star</span><span>Favourites</span></a>
  </div>

  <!-- Tab content -->
  <div class="profile-content">
    {{profile-content}}
  </div>

</div>