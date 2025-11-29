<?php
require_once 'connect_database.php';
require __DIR__ . '/../environment/config/bootstrap.php';
class Create_Userbase extends Connect_Database
{
  private $stmt;
  private $query;

  public function __construct()
  {
    // initiate $pdo
    parent::__construct();
    // create userbase table
    $this->query = "CREATE TABLE IF NOT EXISTS `userbase`(  
    -- unsigned restricts negative id value | SQL adds it on new entry
      id int UNSIGNED AUTO_INCREMENT PRIMARY KEY,
      name VARCHAR(50) NOT NULL,
      email VARCHAR(100) NOT NULL UNIQUE,
      password VARCHAR(100) NOT NULL,
      role VARCHAR(5) NOT NULL DEFAULT 'user',
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    $this->create_userbase();

    // create userbase default admin
    $this->create_userbase_default_admin();

    // re-direct to home
    Header("Location: /");

  }
  private function create_userbase_default_admin()
  {
    $name = $_ENV['NAME'];
    $email = $_ENV['EMAIL'];
    $password = password_hash($_ENV['PASSWORD'], PASSWORD_DEFAULT);
    $admin = $_ENV['ROLE'];

    $this->query = "INSERT INTO `userbase` (`name`, `email`, `password`, `role`)
              VALUES (:name, :email, :password, :role)";

    $this->stmt = $this->get_pdo()->prepare($this->query);
    $this->stmt->bindParam(":name", $name);
    $this->stmt->bindParam(":email", $email);
    $this->stmt->bindParam(':password', $password);
    $this->stmt->bindParam(':role', $admin);

    $this->stmt->execute();
  }
  private function create_userbase()
  {
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