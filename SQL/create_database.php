<?php
class Create_Database
{
  private $dsn = "mysql:host=localhost";
  private $username = "root";
  private $password = "";
  private $pdo;

  public function __construct()
  {
    $this->pdo = new PDO($this->dsn, $this->username, $this->password);
    $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $this->create_database();
  }

  public function create_database()
  {
    $query = "CREATE DATABASE IF NOT EXISTS `registration`";
    return $this->pdo->exec($query);
  }
}
$create_database = new Create_Database();
