<?php
$csrf_token = $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
require_once 'error_pool.php';

if (isset($_COOKIE['auth'])) {
  Header("Location: /");
}
?>


<div class="form_layout flex_default flex_column form_border">
  <div class="form_layout flex_default flex_column form_info">
    <h3>Sign-in</h3>
    <h4>Please enter your details</h4>
    <p class="error_field"><?php if (isset($_SESSION['error']))
      echo $registration_error_pool[$_SESSION['error']];
    unset($_SESSION['error']);
    ?></p>
  </div>
  <form action="signin-user" method="POST" class="form_layout flex_default flex_column">
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