<?php
session_start();
$csrf_token = $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
require_once 'error_pool.php';
?>
<div class="form_layout flex_default flex_column form_border">
  <div class="form_layout flex_default flex_column form_info">
    <h3>Register your profile</h3>
    <h4>Please enter your details</h4>
    <p class="error_field"><?php if (isset($_SESSION['error']))
      echo $registration_error_pool[$_SESSION['error']];
    unset($_SESSION['error']);
    ?></p>
  </div>
  <form action="/register-user" method="POST" class="form_layout flex_default flex_column">
    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token ?>" hidden>
    <div>
      <label for="name" class="blank_background">Name</label>
      <input type="text" name="name" id="name" required>
    </div>
    <div>
      <label for="email" class="blank_background">Email</label>
      <input type="email" name="email" id="email" required>
    </div>
    <div>
      <label for="password" class="blank_background">Password</label>
      <input type="password" name="password" id="password" required>
    </div>
    <div>
      <label for="re_password" class="blank_background">Repeat password</label>
      <input type="password" name="re_password" id="re_password" required>
    </div>
    <div>
      <button type="submit" id="submit" class="submit_color">Register</button>
    </div>
  </form>
</div>