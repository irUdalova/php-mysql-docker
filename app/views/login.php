<?php
//variable $params->errors, succes, message from the LoginPageController->renderOnlyView is available here
if (!empty($message)) : ?>
  <p class="form-sbm-status <?php echo $succes ? "succes" : null; ?>"><?php echo $message; ?></p>
<?php endif; ?>

<div class="form-wrap form-login">
  <h2 class="title">SIGN IN TO JOT JOURNAL</h2>
  <form class="submit" action="/login" method="post">

    <input class="inp-text" type="email" name="email" placeholder="Email" value="<?= $userData['email'] ?? "" ?>"></input>
    <?php
    if (isset($errors['email'])) { ?>
      <div class='sbm-err'><?= $errors['email'] ?></div>
    <?php } ?>

    <input class="inp-text" type="password" name="password" placeholder="Password"></input>
    <?php
    if (isset($errors['password'])) { ?>
      <div class='sbm-err'><?= $errors['password'] ?></div>
    <?php } ?>

    <button class="sbm-btn" type="submit" name="submit">Sign in</button>
  </form>
  <p class="login-footer">Don't have an account? <span><a href="/signup" class="link-login">Sign up</a></span></p>
</div>