<?php
if (!isset($_COOKIE['auth']))
  Header("Location: /sign-in");

// validate logged session and user session
if (!isset($_SESSION['logged']) || !isset($_SESSION['user']))
  Header("Location: /sign-in");
?>
<main>
  <!-- error bubble -->
  <?php if (isset($_SESSION['error'])) { ?>
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

  <h1 style="font-weight: 300; margin: 0 0 20px 10px">Manage your profile: <?php echo $user['name'];
  ?></h1>
  <div class="flex_default flex_column border_bubble">
    <p style="margin: 0 0 0 10px">Delete your profile</p>
    <a href="/confirm-delete" class="submit_color delete_color" style="margin: 0 0 0 10px">Proceed</a>
  </div>

  <div class="flex_default flex_column border_bubble edit_bubble">
    <p style="margin: 0 0 0 10px">Change your name</p>
    <a href="/change-name" class="submit_color delete_color" style="margin: 0 0 0 10px">Proceed</a>
  </div>

  <div class="flex_default flex_column border_bubble edit_bubble">
    <p style="margin: 0 0 0 10px">Change your name</p>
    <a href="/change-name" class="submit_color delete_color" style="margin: 0 0 0 10px">Proceed</a>
  </div>
</main>
<script>
  const error_bubble = document.querySelector('.error_bubble');
  const remove_bubble = setTimeout(() => {
    error_bubble.classList.add('remove_bubble');
  }, 2000)
</script>