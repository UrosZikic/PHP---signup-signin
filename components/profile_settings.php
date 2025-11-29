<?php
if (!isset($_COOKIE['logged']))
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

  <h1 style="font-weight: 300; padding-left: 10px">Manage your profile: <?php echo $user['name'];
  ?></h1>
  <div>
    <a href="/confirm-delete">Delete profile</a>
  </div>
</main>
<script>
  const error_bubble = document.querySelector('.error_bubble');
  const remove_bubble = setTimeout(() => {
    error_bubble.classList.add('remove_bubble');
  }, 2000)
</script>