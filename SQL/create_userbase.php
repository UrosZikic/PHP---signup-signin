<?php
require_once 'connect_database.php';

class Create_Userbase extends Connect_Database
{
  private $stmt;
  private $query;

  public function __construct()
  {
    // initiate $pdo
    parent::__construct();

    $this->query = "CREATE TABLE IF NOT EXISTS `userbase`(  
    -- unsigned restricts negative id value | SQL adds it on new entry
      id int UNSIGNED AUTO_INCREMENT PRIMARY KEY,
      name VARCHAR(50) NOT NULL,
      email VARCHAR(100) NOT NULL,
      password VARCHAR(100) NOT NULL,
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    $this->create_userbase();
  }

  private function create_userbase()
  {
    // print_r(Connect_Database::$pdo);
    $this->stmt = $this->get_pdo()->exec($this->query);

    if ($this->stmt === false) {
      $error = $this->get_pdo()->errorInfo();
      die("Table creation failed: " . $error[2]);
    }

    echo "Table created successfully!";
  }
}
;
$create_userbase = new Create_Userbase();