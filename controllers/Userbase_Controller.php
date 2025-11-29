<?php
// __DIR__ resolves pathing issue
require_once __DIR__ . '/../models/Userbase.php';

session_start();
// reset on visit
$_SESSION['error'] = "";
// necessary in order to call the right function
$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);



class Userbase_controller extends Userbase
{
  public function validate_registration()
  {
    // validate input values
    $name = $_POST['name'] ?? false;
    $email = $_POST['email'] ?? false;
    $password = $_POST['password'] ?? false;
    $re_password = $_POST['re_password'] ?? false;


    // validate CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
      $_SESSION['error'] = 'invalid request';
      $this->fileto('register');

      Header("Location: /register?error=" . $_SESSION['error']);
      exit();
    }

    // validate request
    if ($_SERVER['REQUEST_METHOD'] === "POST") {

      if (!$name) {
        $_SESSION['error'] = 'name-invalid';
        $this->fileto('register');
        Header("Location: /register?error=" . $_SESSION['error']);
        exit();

      } else if (!$email) {
        $_SESSION['error'] = 'email-invalid';
        $this->fileto('register');

        Header("Location: /register?error=" . $_SESSION['error']);
        exit();

      } else if (!$password) {
        $_SESSION['error'] = 'password-invalid';
        $this->fileto('register');
        Header("Location: /register?error=" . $_SESSION['error']);
        exit();

      } else if (!$re_password) {
        $_SESSION['error'] = 're_password-invalid';
        $this->fileto('register');

        Header("Location: /register?error=" . $_SESSION['error']);
        exit();

      }


      // validate username
      if (empty($name)) {
        $_SESSION['error'] = 'name-empty';
        $this->fileto('register');

        Header("Location: /register?error=" . $_SESSION['error']);
        exit();

      } else if (preg_match('/\d/', $name)) {
        $_SESSION['error'] = 'name-number';
        $this->fileto('register');

        Header("Location: /register?error=" . $_SESSION['error']);
        exit();

      }
      // validate email 
      if (empty($email)) {
        $_SESSION['error'] = 'email-empty';
        $this->fileto('register');

        Header("Location: /register?error=" . $_SESSION['error']);
        exit();

      } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = 'email-invalid';
        $this->fileto('register');

        Header("Location: /register?error=" . $_SESSION['error']);
        exit();

      }

      //validate password
      if (empty($password)) {
        $_SESSION['error'] = 'password-empty';
        $this->fileto('register');
        Header("Location: /register?error=" . $_SESSION['error']);
        exit();

      } else if (!preg_match('/[A-Z]/', $password)) {
        $_SESSION['error'] = 'password-capitalize';
        $this->fileto('register');
        Header("Location: /register?error=" . $_SESSION['error']);
        exit();

      } else if (!preg_match('/[a-z]/', $password)) {
        $_SESSION['error'] = 'password-letter';
        $this->fileto('register');
        Header("Location: /register?error=" . $_SESSION['error']);
        exit();

      } else if (!preg_match('/.{10,}/', $password)) {
        $_SESSION['error'] = 'password-short';
        $this->fileto('register');
        Header("Location: /register?error=" . $_SESSION['error']);
        exit();

      } else if (!preg_match('/\d/', $password)) {
        $_SESSION['error'] = 'password-number';
        $this->fileto('register');
        Header("Location: /register?error=" . $_SESSION['error']);
        exit();

      } else if (!preg_match('/[\W_]/', $password)) {
        $_SESSION['error'] = 'password-symbol';
        $this->fileto('register');
        Header("Location: /register?error=" . $_SESSION['error']);
        exit();

      } else if ($password !== $re_password) {
        $_SESSION['error'] = 'password-mismatch';
        $this->fileto('register');
        Header("Location: /register?error=" . $_SESSION['error']);
        exit();

      }
      if (!$this->read($email, 'register')) {
        $this->create($name, $email, $password);
      } else {
        $_SESSION['error'] = 'user-exists';
        $this->fileto('register');
        Header("Location: /register?error=" . $_SESSION['error']);
      }
    } else {
      $_SESSION['error'] = 'invalid-request';
      $this->fileto('register');
      Header("Location: /register?error=" . $_SESSION['error']);
    }
  }

  private function fileto($path)
  {

    //document registration attempt
    $document_message = "attempt to $path user: " . $_POST['email'] . " - outcome: " . (isset($_SESSION['error']) && $_SESSION['error'] ? $_SESSION['error'] : " success - ") . " - Request made on ";
    if ($path === 'register') {
      $document_message .= date("F d Y H:i:s", filemtime("filesystem/registration_attempt.txt")) . " IP: " . $_SERVER['REMOTE_ADDR'] . " Browser: " . $_SERVER['HTTP_USER_AGENT'] . PHP_EOL;
      file_put_contents('filesystem/registration_attempt.txt', $document_message, FILE_APPEND);
    } else if ($path === 'signin') {
      $document_message .= date("F d Y H:i:s", filemtime("filesystem/signin_attempt.txt")) . " IP: " . $_SERVER['REMOTE_ADDR'] . " Browser: " . $_SERVER['HTTP_USER_AGENT'] . PHP_EOL;
      file_put_contents('filesystem/signin_attempt.txt', $document_message, FILE_APPEND);
    } else {
      $signout_message = "attempt to logout the user " . $_POST["email"] . " - successful " . "- Request made on " . date("F d Y H:i:s", filemtime("filesystem/signout_attempt.txt")) . " IP: " . $_SERVER['REMOTE_ADDR'] . " Browser: " . $_SERVER['HTTP_USER_AGENT'] . PHP_EOL;
      file_put_contents('filesystem/signout_attempt.txt', $signout_message, FILE_APPEND);
    }
  }

  private function read($email, $path = null)
  {
    try {
      $this->read_from_userbase($email, $path);
    } catch (PDOException $e) {
      $_SESSION['error'] = 'validation failed';
      $this->fileto($path);
      Header("Location: /register?error=" . $_SESSION['error']);
    }
  }


  private function create($name, $email, $password)
  {

    try {
      $password_hashed = password_hash($password, PASSWORD_DEFAULT);
      // call Userbase model function and insert data
      $this->insert_into_userbase($name, $email, $password_hashed);
      // success no errors
      $_SESSION['error'] = null;
      // record this attempt
      $this->fileto('register');
      // send the user to the next step
      header("Location: /sign-in");
      exit();

    } catch (PDOException $e) {
      $_SESSION['error_message'] = $e->getMessage();
      $_SESSION['error'] = 'invalid request';
      $this->fileto('register');
      Header("Location: /register?error=" . $_SESSION['error']);
    }
  }

  public function validate_signin()
  {
    // validate input values
    $email = $_POST['email'] ?? false;
    $password = $_POST['password'] ?? false;

    // validate CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
      $_SESSION['error'] = 'invalid request';
      $this->fileto('signin');
      Header("Location: /sign-in?error=" . $_SESSION['error']);
      exit();
    }
    // validate if they exist
    if ($_SERVER['REQUEST_METHOD'] === "POST") {

      if (!$email) {
        $_SESSION['error'] = 'email-invalid';
        $this->fileto('signin');
        Header("Location: /sign-in?error=" . $_SESSION['error']);
        exit();

      } else if (!$password) {
        $_SESSION['error'] = 'password-invalid';
        $this->fileto('signin');
        Header("Location: /sign-in?error=" . $_SESSION['error']);
        exit();
      }
    } else {
      $_SESSION['error'] = 'invalid-request';
      $this->fileto('signin');
      Header("Location: /sign-in?error=" . $_SESSION['error']);
      exit();
    }

    if (!$this->read($email, 'signin')) {
      $_SESSION['error'] = 'user-fail';
      $this->fileto('signin');
      Header("Location: /sign-in?error=" . $_SESSION['error']);
    } else {
      $password_verify = password_verify($password, $this->read($email, 'signin')["password"]);
      $password_verify = $password_verify ? true : false;

      if ($password_verify) {
        $_SESSION['error'] = null;
        $_SESSION['logged'] = ["email" => $this->read($email, 'signin')["email"], "name" => $this->read($email, 'signin')["name"]];
        $this->fileto('signin');
        Header("Location: /profile");
      } else {
        $_SESSION['error'] = 'password-fail';
        $this->fileto('signin');

        Header("Location: /sign-in?error=" . $_SESSION['error']);
      }
    }
  }

  public function signout()
  {
    $this->validate_signout();
    $this->fileto('signout');
    Header("Location: /sign-in");
    exit();
  }
}

$userbase_controller = new Userbase_controller();

if ($request === '/register-user')
  $userbase_controller->validate_registration();
else if ($request === '/signin-user')
  $userbase_controller->validate_signin();
else
  $userbase_controller->signout();
