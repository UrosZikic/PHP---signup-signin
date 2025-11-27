<?php
require_once 'SQL/connect_database.php';
session_start();



class Userbase_controller extends Connect_Database
{
  public function validate()
  {
    // // validate CSRF
    // if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    //   $_SESSION['error'] = 'invalid request';
    //   Header("Location: /register?error=" . $_SESSION['error']);
    //   exit();

    // }


    $name = $_POST['name'] ?? false;
    $email = $_POST['email'] ?? false;
    $password = $_POST['password'] ?? false;
    $re_password = $_POST['re_password'] ?? false;

    if (!$name) {
      $_SESSION['error'] = 'name';
      Header("Location: /register?error=" . $_SESSION['error']);
      exit();

    } else if (!$email) {
      $_SESSION['error'] = 'email';
      Header("Location: /register?error=" . $_SESSION['error']);
      exit();

    } else if (!$password) {
      $_SESSION['error'] = 'password';
      Header("Location: /register?error=" . $_SESSION['error']);
      exit();

    } else if (!$re_password) {
      $_SESSION['error'] = 're_password';
      Header("Location: /register?error=" . $_SESSION['error']);
      exit();

    }


    // validate username
    if (empty($name)) {
      $_SESSION['error'] = 'name-empty';
      Header("Location: /register?error=" . $_SESSION['error']);
      exit();

    } else if (preg_match('/\d/', $name)) {
      $_SESSION['error'] = 'name-number';
      Header("Location: /register?error=" . $_SESSION['error']);
      exit();

    }
    // validate email 
    if (empty($email)) {
      $_SESSION['error'] = 'email-empty';
      Header("Location: /register?error=" . $_SESSION['error']);
      exit();

    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $_SESSION['error'] = 'email-invalid';
      Header("Location: /register?error=" . $_SESSION['error']);
      exit();

    }

    //validate password
    if (empty($password)) {
      $_SESSION['error'] = 'password-empty';
      Header("Location: /register?error=" . $_SESSION['error']);
      exit();

    } else if (!preg_match('/[A-Z]/', $password)) {
      $_SESSION['error'] = 'password-capitalize';
      Header("Location: /register?error=" . $_SESSION['error']);
      exit();

    } else if (!preg_match('/[a-z]/', $password)) {
      $_SESSION['error'] = 'password-letter';
      Header("Location: /register?error=" . $_SESSION['error']);
      exit();

    } else if (!preg_match('/.{10,}/', $password)) {
      $_SESSION['error'] = 'password-short';
      Header("Location: /register?error=" . $_SESSION['error']);
      exit();

    } else if (!preg_match('/\d/', $password)) {
      $_SESSION['error'] = 'password-number';
      Header("Location: /register?error=" . $_SESSION['error']);
      exit();

    } else if (!preg_match('/[\W_]/', $password)) {
      $_SESSION['error'] = 'password-symbol';
      Header("Location: /register?error=" . $_SESSION['error']);
      exit();

    } else if ($password !== $re_password) {
      $_SESSION['error'] = 'password-mismatch';
      Header("Location: /register?error=" . $_SESSION['error']);
      exit();

    } else {
      $this->create($name, $email, $password);
    }


  }

  private function create($name, $email, $password)
  {
    try {
      $password_hashed = password_hash($password, PASSWORD_DEFAULT);
      $query = "INSERT INTO userbase (name, email, password) VALUES(:name, :email, :password)";
      $stmt = $this->pdo->prepare($query);
      $stmt->bindParam(":name", $name);
      $stmt->bindParam(":email", $email);
      $stmt->bindParam(":password", $password_hashed);
      $stmt->execute();
      header("Location: /sign-in");
      exit();

    } catch (PDOException $e) {
      $_SESSION['error_message'] = $e->getMessage();
      $_SESSION['error'] = 'invalid request';
      echo $e->getMessage();
      // Header("Location: /register?error=" . $_SESSION['error']);
    }
  }
}
$userbase_controller = new Userbase_controller();
$userbase_controller->validate();