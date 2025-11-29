<?php
session_start();
$csrf_token = $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

if (!isset($_COOKIE['logged']))
  Header("Location: /sign-in");

// validate logged session and user session
if (!isset($_SESSION['logged']) || !isset($_SESSION['user']))
  Header("Location: /sign-in");
require_once 'error_pool.php';
?>


<div class="form_layout flex_default flex_column form_border">
  <div class="form_layout flex_default flex_column form_info">
    <h3>Confirm and delete your profile</h3>
    <h4>Please enter your details</h4>
    <p class="error_field"><?php if (isset($_SESSION['error']) && isset($_GET['error']) && strlen($_GET['error']))
      echo $registration_error_pool[$_SESSION['error']] ?></p>
    </div>
    <form action="delete-user" method="POST" class="form_layout flex_default flex_column">
      <input type="hidden" hidden name="csrf_token" value="<?php echo $csrf_token ?>">
    <div>
      <label for="email" class="blank_background">email</label>
      <input type="email" name="email" id="email" required>
    </div>
    <div>
      <label for="password" class="blank_background">password</label>
      <input type="password" name="password" id="password" required>
    </div>
    <button type="submit" value="sign_in" class="submit_color">sign-in</button>
  </form>
</div>