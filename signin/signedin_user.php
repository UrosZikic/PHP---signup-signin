<?php
declare(strict_types=1);

$username = $_GET['username'];
if (!$username)
  Header("Location: ../registration/registration.php");
else
  echo $username;
