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
    <h4>Change your password</h4>
  </div>
  <form action="edit-user-password" method="POST" class="form_layout flex_default flex_column">
    <input type="hidden" hidden name="csrf_token" value="<?php echo $csrf_token ?>">
    <div>
      <label for="email" class="blank_background">Email</label>
      <input type="email" name="email" id="email" required>
    </div>
    <div>
      <label for="old_password" class="blank_background">Old password</label>
      <input type="password" name="old_password" id="old_password" required>
    </div>
    <div>
      <label for="new_password" class="blank_background">New password</label>
      <input type="password" name="new_password" id="new_password" required>
    </div>
    <div>
      <label for="re_password" class="blank_background">Repeat new password</label>
      <input type="password" name="re_password" id="re_password" required>
    </div>
    <button type="submit" value="sign_in" class="submit_color">Delete</button>
  </form>
</div>


<?php unset($_SESSION['error']) ?>