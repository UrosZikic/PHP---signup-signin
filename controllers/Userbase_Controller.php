<?php
session_start();
class Userbase_controller
{
  public function create()
  {
    $name = $_POST['name'] ?? false;
    $email = $_POST['email'] ?? false;
    $password = $_POST['password'] ?? false;
    $re_password = $_POST['re_password'] ?? false;

    if (!$name) {
      $_SESSION['error'] = 'name';
      Header("Location: /register?error=" . $_SESSION['error']);
    } else if (!$email) {
      $_SESSION['error'] = 'email';
      Header("Location: /register?error=" . $_SESSION['error']);
    } else if (!$password) {
      $_SESSION['error'] = 'password';
      Header("Location: /register?error=" . $_SESSION['error']);
    } else if (!$re_password) {
      $_SESSION['error'] = 're_password';
      Header("Location: /register?error=" . $_SESSION['error']);
    }


  }
}