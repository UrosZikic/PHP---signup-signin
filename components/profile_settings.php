<?php
if (!isset($_COOKIE['auth']))
  Header("Location: /sign-in");

// validate logged session and user session
if (!isset($_SESSION['logged']) || !isset($_SESSION['user']))
  Header("Location: /sign-in");
?>
<main>
  <!-- error bubble -->
  <?php if (isset($_SESSION['error']) && $_SESSION['error'] !== "") { ?>
    <div class="error_bubble">
      <p>Error occured...</p>
      <p><?php echo $_SESSION['error'];
      unset($_SESSION['error']);
      ?></p>
    </div>
  <?php }
  ; ?>
  <!-- success bubble -->
  <?php if (isset($_SESSION['success'])) { ?>
    <div class="error_bubble success_bubble">
      <p>Success</p>
    </div>
  <?php }
  ;
  unset($_SESSION['success'])
    ?>

  <?php if (!auth() || $request === '/profile-settings') { ?>
    <h1 style="font-weight: 300; margin: 0 0 20px 10px">Manage your profile: <?php echo $user['name'];
    ?></h1>
    <div class="flex_default flex_column border_bubble load_bubble">
      <p style="margin: 0 0 0 10px">Delete your profile</p>
      <a href="/confirm-delete" class="submit_color delete_color" style="margin: 0 0 0 10px">Proceed</a>
    </div>

    <div class="flex_default flex_column border_bubble edit_bubble load_bubble">
      <p style="margin: 0 0 0 10px">Change your name</p>
      <a href="/change-name" class="submit_color delete_color" style="margin: 0 0 0 10px">Proceed</a>
    </div>

    <div class="flex_default flex_column border_bubble edit_bubble load_bubble">
      <p style="margin: 0 0 0 10px">Change your password</p>
      <a href="/change-password" class="submit_color delete_color" style="margin: 0 0 0 10px">Proceed</a>
    </div>

    <div class="flex_default flex_column border_bubble success_bubble load_bubble">
      <p style="margin: 0 0 0 10px">Profile information</p>
      <a href="/user-details" class="submit_color delete_color success_color" style="margin: 0 0 0 10px">Proceed</a>
    </div>
  <?php } else {
    ; ?>
    <div class="flex_default flex_column border_bubble success_bubble load_bubble">
      <p style="margin: 0 0 0 10px">View users</p>
      <a href="/get-users" class="submit_color delete_color success_color" style="margin: 0 0 0 10px">Proceed</a>
    </div>

    <div class="flex_default flex_column border_bubble edit_bubble load_bubble">
      <p style="margin: 0 0 0 10px">View soft-deleted users</p>
      <a href="/get-deleted-users" class="submit_color delete_color success_color" style="margin: 0 0 0 10px">Proceed</a>
    </div>
    <?php
  }
  ?>
</main>
<script src="../javascript/DOM.js"></script>