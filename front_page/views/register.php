<?php
session_start();
$csrf_token = $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
require_once 'error_pool.php';


?>
<div>
  <p><?php if (isset($_SESSION['error']) && $_SESSION['error'] && isset($_GET['error']) && strlen($_GET['error']))
    echo $registration_error_pool[$_SESSION['error']] ?></p>
    <form action="/register-user" method="POST">
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
</div>