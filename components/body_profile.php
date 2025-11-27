<?php
// validate logged user
session_start();
$logged;
if (isset($_SESSION['logged']))
  $logged = $_SESSION['logged'];
else
  Header("Location: /sign-in");
?>

<main>
  <h1>Dashboard: <?php echo $logged ?></h1>
  <a href="/signout-user">sign out</a>
</main>