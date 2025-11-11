<?php
declare(strict_types=1);
session_start();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  require_once "../SQL/pdo.php";
  require_once "fetch_user.php";

  $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
  $pwd = htmlspecialchars($_POST['password']);


  $fetched_user = fetch_user($pdo, $email, $pwd);

  if ($fetched_user) {
    Header("Location: signedin_user.php?username=" . $fetched_user['username']);
    exit();
  } else {
    Header("Location: sign_in.php");
    die();
  }
}