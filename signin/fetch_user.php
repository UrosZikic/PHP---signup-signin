<?php
declare(strict_types=1);

function fetch_user(object $pdo, string $email, string $pwd)
{
  $query = "SELECT * FROM users WHERE email = :email;";
  $stmt = $pdo->prepare($query);
  $stmt->bindParam(":email", $email);
  $stmt->execute();
  $results = $stmt->fetch(PDO::FETCH_ASSOC);
  if (empty($results)) {
    $_SESSION['error'] = [1, 'email does not exist'];
    return false;
  } else {
    $fetched_pwd = $results['pwd'];
    $validate_pwd = password_verify($pwd, $fetched_pwd);

    if ($validate_pwd) {
      return $results;
    } else {
      $_SESSION['error'] = [2, 'incorrect password'];
      return false;
    }
  }
}