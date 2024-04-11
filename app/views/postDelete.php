<?php if (!empty($message)) : ?>
  <p class="form-sbm-status <?php echo $succes ? "succes" : null; ?>"><?php echo $message; ?></p>
<?php endif; ?>

<div class="form-wrap">
  <h2 class="title">OH, NO!</h2>

  <div>
    <p>Seems you want to delete word</p>
    <p class="delete-word-title"><?= $wordData['word'] ?></p>
    <p>It can't be undone. Do you still want to do it?</p>
  </div>

  <form class="form-handle-word" action="/posts/<?= $wordData['word_id']; ?>/delete" method="post" autocomplete="off">

    <div class="choose-submit">
      <button class="sbm-btn delete-word" type="submit" name="delete">Delete</button>
      <button class="sbm-btn cancel delete-word" type="submit" name="cancel">Cancel</button>
    </div>

  </form>
</div>