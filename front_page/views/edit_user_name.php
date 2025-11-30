<?php
$csrf_token = $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

if (!isset($_COOKIE['auth']))
  Header("Location: /sign-in");

// validate logged session and user session
if (!isset($_SESSION['logged']) || !isset($_SESSION['user']))
  Header("Location: /sign-in");
require_once 'error_pool.php';
?>


<div class="form_layout flex_default flex_column form_border">
  <div class="form_layout flex_default flex_column form_info">
    <h3>Edit your profile</h3>
    <h4>Please enter a new name and confirm it with your log-in credentials</h4>
    <p class="error_field"><?php if (isset($_SESSION['error']))
      echo $registration_error_pool[$_SESSION['error']] ?></p>
    </div>
    <form action="edit-user-name" method="POST" class="form_layout flex_default flex_column">
      <input type="hidden" hidden name="csrf_token" value="<?php echo $csrf_token ?>">
    <div>
      <label for="name" class="blank_background">Change your name</label>
      <input type="text" name="name" id="name" required>
    </div>
    <div>
      <label for="email" class="blank_background">Email</label>
      <input type="email" name="email" id="email" required>
    </div>
    <div>
      <label for="password" class="blank_background">password</label>
      <input type="password" name="password" id="password" required>
    </div>
    <button type="submit" value="sign_in" class="submit_color">Delete</button>
  </form>
</div>


<?php unset($_SESSION['error']) ?>