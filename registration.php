<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registration</title>
</head>

<body>
  <form action="create_user.php" method="POST">
    <label for="username">username</label>
    <input type="text" name="username" id="username" required>

    <label for="email">email</label>
    <input type="email" name="email" id="email" required>

    <label for="password">password</label>
    <input type="password" name="password" id="password" required>

    <input type="submit" value="register your account">
  </form>
</body>

</html>