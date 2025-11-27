<?php

class Connect_Database
{
  private $dsn = "mysql:host=localhost;dbname=registration;";
  private $username = "root";
  private $password = "";
  protected $pdo;

  public function __construct()
  {
    try {
      $this->pdo = new PDO($this->dsn, $this->username, $this->password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      ]);
    } catch (PDOException $e) {
      die("Database connection failed: " . $e->getMessage());
    }
  }

  protected function get_pdo()
  {
    return $this->pdo;
  }
}
;
$connect_database = new Connect_Database();
