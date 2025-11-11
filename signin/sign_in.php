<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign in</title>
</head>

<body>
  <form action="signin_user.php" method="POST">
    <label for="email">email</label>
    <input type="email" name="email" id="email" required>
    <?php
    if (isset($_SESSION['error']) && $_SESSION['error'][0] === 1):
      ?>
      <p><?php echo $_SESSION['error'][1] ?></p>
    <?php endif; ?>

    <label for="password">password</label>
    <input type="password" name="password" id="password" required>
    <?php
    if (isset($_SESSION['error']) && $_SESSION['error'][0] === 2):
      ?>
      <p><?php echo $_SESSION['error'][1] ?></p>
    <?php endif; ?>

    <input type="submit" value="sign_in">
  </form>
</body>

</html>