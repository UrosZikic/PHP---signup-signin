<?php
session_start();
$csrf_token = $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
?>


<form action="/register-user" method="post">
  <input type="hidden" name="csrf_token" value="<?php echo $csrf_token ?>" hidden>
  <div>
    <label for="name">Name</label>
    <input type="text" name="name" id="name">
  </div>
  <div>
    <label for="email">Email</label>
    <input type="email" name="email" id="email">
  </div>
  <div>
    <label for="password">Password</label>
    <input type="password" name="password" id="password">
  </div>
  <div>
    <label for="re_password">Repeat password</label>
    <input type="password" name="re_password" id="re_password">
  </div>
  <button type="submit" id="submit">Register</button>
</form>