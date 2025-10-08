<?php
declare(strict_types=1);


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $username = htmlspecialchars($_POST['username']);
  $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
  $pwd = htmlspecialchars($_POST['password']);

  if (empty($username) || empty($email) || empty($pwd)) {
    echo "one or more fields were not filled";
    die();
  }
  if (!filter_var($email)) {
    echo "submitted email is invalid";
    die();
  }


  function checkPregMatch(string $pregMatchParam, string $pwd)
  {
    $validate_pwd = preg_match($pregMatchParam, $pwd);

    if (!$validate_pwd) {
      // Header("Location: ./registration.php");
      echo "password is weak";
      exit();
    }
    return $validate_pwd;
  }
  $pregMatchParams = ['/[a-z]/', '/[A-Z]/', '/[0-9]/', '/[\W_]/'];

  foreach ($pregMatchParams as $param) {
    checkPregMatch($param, $pwd);
  }

  $hashedpwd = password_hash($pwd, PASSWORD_BCRYPT, ['cost' => 12]);


  try {
    require_once 'SQL/pdo.php';
    $query = "INSERT INTO users (username, pwd, email) VALUES (:username, :pwd, :email)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":username", $username);
    $stmt->bindParam(":pwd", $hashedpwd);
    $stmt->bindParam(":email", $email);
    $stmt->execute();
    $pdo = null;
    $stmt = null;
    header("Location: ./registration.php");
    die();


  } catch (PDOException) {
  }
} else {
  echo "something went wrong...";
}

