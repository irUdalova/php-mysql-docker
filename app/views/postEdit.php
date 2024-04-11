<?php if (!empty($message)) : ?>
  <p class="form-sbm-status <?php echo $succes ? "succes" : null; ?>"><?php echo $message; ?></p>
<?php endif; ?>

<div class="form-wrap">
  <h2 class="title">Update your word</h2>

  <form class="form-handle-word" action="/posts/<?= $wordData['word_id']; ?>/edit" method="post" autocomplete="off">

    <label for="word">Add your word*</label>
    <input class="inp-text <?php echo isset($errors['word']) ? "invalid" : null; ?>" name="word" placeholder="Word" value="<?= $formData['word'] ?? "" ?>" contenteditable></input>

    <?php if (isset($errors['word'])) { ?>
      <div class='sbm-err'><?= $errors['word'] ?></div>
    <?php } ?>

    <?php if (isset($errors['existingWord'])) { ?>
      <div class='sbm-err link'>Watch this word<a href="/posts/<?= $errors['existingWord']['id']; ?> " class="word-link">here</a></div>
    <?php } ?>

    <label for="definition">Add word definition*</label>
    <textarea class="inp-text <?php echo isset($errors['definition']) ? "invalid" : null; ?>" name="definition" placeholder="Definition" contenteditable><?= $formData['definition'] ?? "" ?></textarea>
    <?php
    if (isset($errors['definition'])) { ?>
      <div class='sbm-err'><?= $errors['definition'] ?></div>
    <?php } ?>

    <label for="example">Add word example*</label>
    <textarea class="inp-text <?php echo isset($errors['example']) ? "invalid" : null; ?>" name="example" placeholder="Example" contenteditable><?= $formData['example'] ?? "" ?></textarea>
    <?php
    if (isset($errors['example'])) { ?>
      <div class='sbm-err'><?= $errors['example'] ?></div> <?php } ?>

    <label>Add word tags*</label>
    <div class="tags-input" data-current="<?= $formData['tagsStr'] ?? "" ?>">
    </div>
    <?php
    if (isset($errors['tags'])) { ?>
      <div class='sbm-err'><?= $errors['tags'] ?></div>
    <?php } ?>

    <!-- <button class="sbm-btn" type="submit" name="submit">Update</button> -->

    <div class="choose-submit">
      <button class="sbm-btn update" type="submit" name="update">Update</button>
      <button class="sbm-btn cancel" type="submit" name="cancel">Cancel</button>
    </div>

  </form>
</div>