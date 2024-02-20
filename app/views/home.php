<?php
//variable $params->$posts from the HomePageController->renderOnlyView is available here

function formatDate($date) {
  $time = strtotime($date);
  $dateFormated = date("d M Y", $time);
  return $dateFormated;
}
?>

<h1>DISCOVER SOMETHING NEW</h1>

<?php if (empty($posts)) : ?>
  <p>There is no posts</p>
<?php endif; ?>

<div class="posts">

  <?php foreach ($posts as $post) : ?>
    <div class="post">
      <h2 class="title"><?php echo $post['title']; ?> </h2>
      <p><?php echo formatDate($post['date_created']); ?> </p>
      <p><?php echo $post['body']; ?> </p>
    </div>
  <?php endforeach; ?>

</div>