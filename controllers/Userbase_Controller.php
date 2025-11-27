<?php
require_once 'SQL/connect_database.php';
session_start();
$_SESSION['error'] = "";



class Userbase_controller extends Connect_Database
{
  public function validate()
  {
    // validate input values
    $name = $_POST['name'] ?? false;
    $email = $_POST['email'] ?? false;
    $password = $_POST['password'] ?? false;
    $re_password = $_POST['re_password'] ?? false;


    // validate CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
      $_SESSION['error'] = 'invalid request';
      $this->fileto();

      Header("Location: /register?error=" . $_SESSION['error']);
      exit();

    }

    // validate request
    if ($_SERVER['REQUEST_METHOD'] === "POST") {

      if (!$name) {
        $_SESSION['error'] = 'name-invalid';
        $this->fileto();
        Header("Location: /register?error=" . $_SESSION['error']);
        exit();

      } else if (!$email) {
        $_SESSION['error'] = 'email-invalid';
        $this->fileto();

        Header("Location: /register?error=" . $_SESSION['error']);
        exit();

      } else if (!$password) {
        $_SESSION['error'] = 'password-invalid';
        $this->fileto();
        Header("Location: /register?error=" . $_SESSION['error']);
        exit();

      } else if (!$re_password) {
        $_SESSION['error'] = 're_password-invalid';
        $this->fileto();

        Header("Location: /register?error=" . $_SESSION['error']);
        exit();

      }


      // validate username
      if (empty($name)) {
        $_SESSION['error'] = 'name-empty';
        $this->fileto();

        Header("Location: /register?error=" . $_SESSION['error']);
        exit();

      } else if (preg_match('/\d/', $name)) {
        $_SESSION['error'] = 'name-number';
        $this->fileto();

        Header("Location: /register?error=" . $_SESSION['error']);
        exit();

      }
      // validate email 
      if (empty($email)) {
        $_SESSION['error'] = 'email-empty';
        $this->fileto();

        Header("Location: /register?error=" . $_SESSION['error']);
        exit();

      } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = 'email-invalid';
        $this->fileto();

        Header("Location: /register?error=" . $_SESSION['error']);
        exit();

      }

      //validate password
      if (empty($password)) {
        $_SESSION['error'] = 'password-empty';
        $this->fileto();
        Header("Location: /register?error=" . $_SESSION['error']);
        exit();

      } else if (!preg_match('/[A-Z]/', $password)) {
        $_SESSION['error'] = 'password-capitalize';
        $this->fileto();
        Header("Location: /register?error=" . $_SESSION['error']);
        exit();

      } else if (!preg_match('/[a-z]/', $password)) {
        $_SESSION['error'] = 'password-letter';
        $this->fileto();
        Header("Location: /register?error=" . $_SESSION['error']);
        exit();

      } else if (!preg_match('/.{10,}/', $password)) {
        $_SESSION['error'] = 'password-short';
        $this->fileto();
        Header("Location: /register?error=" . $_SESSION['error']);
        exit();

      } else if (!preg_match('/\d/', $password)) {
        $_SESSION['error'] = 'password-number';
        $this->fileto();
        Header("Location: /register?error=" . $_SESSION['error']);
        exit();

      } else if (!preg_match('/[\W_]/', $password)) {
        $_SESSION['error'] = 'password-symbol';
        $this->fileto();
        Header("Location: /register?error=" . $_SESSION['error']);
        exit();

      } else if ($password !== $re_password) {
        $_SESSION['error'] = 'password-mismatch';
        $this->fileto();
        Header("Location: /register?error=" . $_SESSION['error']);
        exit();

      }
      if (!$this->read($email)) {
        $this->create($name, $email, $password);
      } else {
        $_SESSION['error'] = 'user-exists';
        $this->fileto();
        Header("Location: /register?error=" . $_SESSION['error']);
      }
    } else {
      $_SESSION['error'] = 'invalid-request';
      $this->fileto();
      Header("Location: /register?error=" . $_SESSION['error']);
    }
  }

  private function fileto()
  {
    //document registration attempt
    $document_message = "attempt to register user: " . $_POST['email'] . " - outcome: " . (isset($_SESSION['error']) && $_SESSION['error'] ? $_SESSION['error'] : " success - ") . " - Request made on " . date("F d Y H:i:s", filemtime("filesystem/registration_attempt.txt")) . " IP: " . $_SERVER['REMOTE_ADDR'] . " Browser: " . $_SERVER['HTTP_USER_AGENT'] . PHP_EOL;

    file_put_contents('filesystem/registration_attempt.txt', $document_message, FILE_APPEND);
  }

  private function read($email)
  {
    try {
      $query = "SELECT email FROM userbase WHERE email = :email";
      $stmt = $this->pdo->prepare($query);
      $stmt->bindParam(":email", $email);
      $stmt->execute();
      return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      $_SESSION['error'] = 'validation failed';
      $this->fileto();


      Header("Location: /register?error=" . $_SESSION['error']);
    }
  }


  private function create($name, $email, $password)
  {

    try {
      $password_hashed = password_hash($password, PASSWORD_DEFAULT);
      $query = "INSERT INTO userbase (name, email, password) VALUES(:name, :email, :password)";
      $stmt = $this->pdo->prepare($query);
      $stmt->bindParam(":name", $name);
      $stmt->bindParam(":email", $email);
      $stmt->bindParam(":password", $password_hashed);
      $stmt->execute();

      $this->fileto();

      header("Location: /sign-in");
      exit();

    } catch (PDOException $e) {
      $_SESSION['error_message'] = $e->getMessage();
      $_SESSION['error'] = 'invalid request';
      $this->fileto();
      Header("Location: /register?error=" . $_SESSION['error']);
    }
  }
}
$userbase_controller = new Userbase_controller();
$userbase_controller->validate();