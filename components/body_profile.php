<?php
if (!isset($_COOKIE['auth']))
  Header("Location: /sign-in");

// validate logged session and user session
if (!isset($_SESSION['logged']) || !isset($_SESSION['user']))
  Header("Location: /sign-in");
?>

<main>
  <h1 style="font-weight: 300; margin: 0 0 20px 10px">Dashboard: <?php echo $user['name'];
  ?></h1>
  <a href="/profile-settings" class="submit_color" style="margin: 0 0 0 10px">Manage your profile</a>
  <?php
  if (auth()) {
    ?>
    <a href="/admin-manager" class="submit_color" style="margin: 0 0 0 10px">Manage users</a>
    <?php
  }
  ?>
</main>