<?php
//variable $params->errors, succes, message from the SignupPageController->renderOnlyView is available here

if (!empty($message)) : ?>
  <p class="form-sbm-status <?php echo $succes ? "succes" : null; ?>"><?php echo $message; ?></p>
<?php endif; ?>

<div class="form-wrap form-login">
  <h2 class="title">CREATE YOUR ACCOUNT</h2>
  <form class="submit" action="/signup" method="post" novalidate>

    <input class="inp-text <?php echo isset($errors['name']) ? "invalid" : null; ?>" type="text" name="name" placeholder="Name" value="<?= $userData['name'] ?? "" ?>"></input>
    <?php
    if (isset($errors['name'])) { ?>
      <div class='sbm-err'><?= $errors['name'] ?></div>
    <?php } ?>

    <input class="inp-text <?php echo $emailError ? "invalid" : null; ?>" type="email" name="email" placeholder="Email" value="<?= $userData['email'] ?? "" ?>"></input>
    <?php
    if (isset($errors['email'])) { ?>
      <div class='sbm-err'><?= $errors['email'] ?></div>
    <?php } ?>

    <input class="inp-text" type="password" name="password" placeholder="Password" value="<?= $userData['password'] ?? "" ?>"></input>
    <?php
    if (isset($errors['password'])) { ?>
      <div class='sbm-err'><?= $errors['password'] ?></div>
    <?php } ?>

    <input class="inp-text" type="password" name="password-confirm" placeholder="Confirm password" value="<?= $userData['password-confirm'] ?? "" ?>"></input>
    <?php
    if (isset($errors['password-confirm'])) { ?>
      <div class='sbm-err'><?= $errors['password-confirm'] ?></div>
    <?php } ?>

    <button class="sbm-btn" type="submit">Create account</button>
  </form>
  <p class="login-footer">Already have an account? <span><a href="/login" class="link-login">Log in</a></span></p>
</div>