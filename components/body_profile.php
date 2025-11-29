<?php
require_once "SQL/connect_database.php";

// validate logged session
session_start();
if (!isset($_SESSION['logged']))
  Header("Location: /sign-in");


class Retrieve_User extends Connect_Database
{
  // validate existance of the user
  public function retrieve_user()
  {
    $query = "SELECT * FROM userbase WHERE email = :email";
    $stmt = $this->pdo->prepare($query);
    $stmt->bindParam(":email", $_SESSION['logged']["email"]);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    return $user;
  }
}

$validate_user = new Retrieve_User();
if (!$validate_user->retrieve_user())
  Header("Location: /sign-in");
?>


<main>
  <h1>Dashboard: <?php echo $validate_user->retrieve_user()['name'] ?></h1>
  <a href="/signout-user">sign out</a>
</main>