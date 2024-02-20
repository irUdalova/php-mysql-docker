<?php
//variable $params->titleErr, bodyErr, succes, message from the  MyNotesPageController->renderOnlyView is available here

if (!empty($message)) : ?>
  <p class="form-sbm-status <?php echo $succes ? "succes" : null; ?>"><?php echo $message; ?></p>
<?php endif; ?>

<div class="form-wrap">
  <h2 class="title">Create your note</h2>
  <form class="submit" action="/mynotes" method="post">
    <textarea class="inp-text <?php echo $titleErr ? "invalid" : null; ?>" name="title" placeholder="Your title" contenteditable></textarea>
    <?php echo $titleErr ? "<div class='sbm-err'>$titleErr</div>" : null; ?>
    <textarea class="inp-text <?php echo $bodyErr ? "invalid" : null; ?>" name="body" placeholder="Your text" contenteditable></textarea>
    <?php echo $titleErr ? "<div class='sbm-err'>$bodyErr</div>" : null; ?>
    <button class="sbm-btn" type="submit" name="submit">Send</button>
  </form>
</div>