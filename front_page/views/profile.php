<?php
// session_start();
$user = $_SESSION['user'];

if ($request === '/profile') {
  unset($_SESSION['error']);
  require_once "components/body_profile.php";
} else
  require_once "components/profile_settings.php";
