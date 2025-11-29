<?php
if (!isset($_COOKIE['logged']))
  Header("Location: /sign-in");

// validate logged session and user session
if (!isset($_SESSION['logged']) || !isset($_SESSION['user']))
  Header("Location: /sign-in");
?>

<main>
  <h1 style="font-weight: 300; padding-left: 10px">Dashboard: <?php echo $user['name'];
  ?></h1>
  <a href="/profile-settings">Manage your profile</a>
</main>