<?php
// __DIR__ resolves pathing issue
require_once __DIR__ . '/../SQL/connect_database.php';
class Userbase extends Connect_Database
{
  private $query;
  private $stmt;

  public function __construct()
  {
    parent::__construct();
  }

  protected function insert_into_userbase($name, $email, $password)
  {
    $this->query = "INSERT INTO userbase (name, email, password) VALUES (:name, :email, :password)";
    $this->stmt = $this->pdo->prepare($this->query);
    return $this->stmt->execute([
      ':name' => $name,
      ':email' => $email,
      ':password' => $password
    ]);
  }

  protected function validate_signout()
  {
    unset($_SESSION['logged']);
  }

  protected function read_from_userbase($email, $path = null)
  {
    $query = "SELECT * FROM userbase WHERE email = :email";
    $stmt = $this->pdo->prepare($query);
    $stmt->bindParam(":email", $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    return $user;
  }

  protected function delete_from_userbase($email)
  {
    $query = "DELETE FROM `userbase` WHERE email = :email";
    $stmt = $this->pdo->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    return true;
  }

  protected function edit_userbase_name($email, $name)
  {
    $query = "UPDATE `userbase` SET `name` = :name WHERE email = :email";
    $stmt = $this->pdo->prepare($query);
    $stmt->bindParam(":name", $name);
    $stmt->bindParam(":email", $email);
    $stmt->execute();
    return true;
  }

  protected function edit_userbase_password($email, $hash_password)
  {
    $query = "UPDATE `userbase` SET `password` = :password WHERE email = :email";
    $stmt = $this->pdo->prepare($query);
    $stmt->bindParam(":password", $hash_password);
    $stmt->bindParam(":email", $email);
    $stmt->execute();
    return true;
  }

  protected function get_userbase()
  {
    $query = "SELECT * FROM `userbase`";
    $stmt = $this->pdo->query($query);
    $stmt->execute();
    return $stmt->fetchAll();
  }


  protected function soft_delete($email)
  {
    $query = "SELECT `soft_deleted` FROM `userbase` WHERE email = :email";
    $stmt = $this->pdo->prepare($query);
    $stmt->bindParam(":email", $email);
    $stmt->execute();
    $user = $stmt->fetch();

    if (!$user['soft_deleted']) {
      $query = "UPDATE `userbase` SET `soft_deleted` = 1 WHERE email = :email";
      $stmt = $this->pdo->prepare($query);
      $stmt->bindParam(":email", $email);
      $stmt->execute();
      return true;
    } else {
      $query = "DELETE FROM `userbase` WHERE email = :email";
      $stmt = $this->pdo->prepare($query);
      $stmt->bindParam(":email", $email);
      $stmt->execute();
      return true;
    }
  }


  protected function get_softdeleted_userbase()
  {
    $query = "SELECT * FROM `userbase` WHERE `soft_deleted` = 1";
    $stmt = $this->pdo->query($query);
    $stmt->execute();
    return $stmt->fetchAll();
  }
}