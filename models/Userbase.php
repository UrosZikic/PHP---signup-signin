<?php
// __DIR__ resolves pathing issue
require_once __DIR__ . '/../SQL/connect_database.php';
class Userbase extends Connect_Database
{
  public $userbase;
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
}