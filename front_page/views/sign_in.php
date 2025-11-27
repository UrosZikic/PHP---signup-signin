<?php
session_start();
$csrf_token = $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
require_once 'error_pool.php';


?>
<div>
  <p><?php if (isset($_SESSION['error']) && $_SESSION['error'] && isset($_GET['error']) && strlen($_GET['error']))
    echo $registration_error_pool[$_SESSION['error']];
  ?></p>
  <form action="/signin-user" method="POST">
    <input type="hidden" hidden name="csrf_token" value="<?php echo $csrf_token ?>">
    <div>
      <label for="email">email</label>
      <input type="email" name="email" id="email" required>
    </div>
    <div>
      <label for="password">password</label>
      <input type="password" name="password" id="password" required>
    </div>
    <button type="submit" value="sign_in">sign-in</button>
  </form>
</div>