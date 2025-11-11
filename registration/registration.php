<?php
declare(strict_types=1);
session_start();

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registration</title>
</head>

<body>
  <form action="registration_validation.php" method="POST">
    <fieldset>
      <label for="username">username</label>
      <input type="text" name="username" id="username" required>
      <?php
      if (isset($_SESSION["error"][0]) && $_SESSION["error"][0] === 0):
        ?>
        <p><?php echo $_SESSION["error"][1] ?></p>
      <?php endif;
      ?>
    </fieldset>

    <fieldset>
      <label for="email">email</label>
      <input type="email" name="email" id="email" required>
      <?php
      if (isset($_SESSION["error"][0]) && $_SESSION["error"][0] === 1):
        ?>
        <p><?php echo $_SESSION["error"][1] ?></p>
      <?php endif; ?>
    </fieldset>

    <fieldset>
      <label for="password">password</label>
      <input type="password" name="password" id="password" required>
      <?php
      if (isset($_SESSION["error"][0]) && $_SESSION["error"][0] === 2):
        ?>
        <p><?php echo $_SESSION["error"][1] ?></p>
      <?php endif; ?>
    </fieldset>

    <fieldset>
      <label for="password_repeat">re-enter password</label>
      <input type="password" name="password_repeat" id="password_repeat">
      <?php
      if (isset($_SESSION["error"][0]) && $_SESSION["error"][0] === 3):
        ?>
        <p><?php echo $_SESSION["error"][1] ?></p>
      <?php endif; ?>
    </fieldset>
    <input type="submit" value="register your account">
  </form>

  <?php session_destroy() ?>
</body>

</html>