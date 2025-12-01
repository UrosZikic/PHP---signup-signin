<?php
$user = $_SESSION['user'];
if (!isset($_COOKIE['auth']))
  Header("Location: /sign-in");

// validate logged session and user session
if (!isset($_SESSION['logged']) || !isset($_SESSION['user']))
  Header("Location: /sign-in");
?>

<main>
  <h1 style="font-weight: 300; margin: 0 0 20px 10px">Dashboard: <?php echo $user['name'];
  ?></h1>
  <p style="font-weight: 300; margin: 0 0 20px 10px">Email address: <?php echo $user['email'] ?></p>
</main>