<?php
require_once "../SQL/connect_database.php";

class Userbase extends Connect_Database
{
  public $userbase;
  private $query;
  private $stmt;

  public function __construct()
  {
    parent::__construct();
  }

  public function insert_into_userbase($name, $email, $password)
  {
    $this->query = "INSERT INTO 'userbase' (name, email, password) VALUES (:name, :email, :password)";
    $this->stmt = $this->pdo->prepare($this->query);
    return $this->stmt->execute([
      ':name' => $name,
      ':email' => $email,
      ':password' => $password
    ]);
  }
}