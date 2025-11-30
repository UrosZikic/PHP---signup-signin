<?php
// __DIR__ resolves pathing issue
require_once __DIR__ . '/../models/Userbase.php';
session_start();
// reset on visit
$_SESSION['error'] = "";
// necessary in order to call the right function
$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
// needed for profile.php to import a proper component
$_SESSION['request'] = $request;



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
      $_SESSION['error'] = 'invalid-request';
      $this->fileto('register');

      Header("Location: /register");
      exit();
    }

    // validate request
    if ($_SERVER['REQUEST_METHOD'] === "POST") {

      if (!$name) {
        $_SESSION['error'] = 'name-invalid';
        $this->fileto('register');
        Header("Location: /register");
        exit();

      } else if (!$email) {
        $_SESSION['error'] = 'email-invalid';
        $this->fileto('register');

        Header("Location: /register");
        exit();

      } else if (!$password) {
        $_SESSION['error'] = 'password-invalid';
        $this->fileto('register');
        Header("Location: /register");
        exit();

      } else if (!$re_password) {
        $_SESSION['error'] = 're_password-invalid';
        $this->fileto('register');

        Header("Location: /register");
        exit();

      }


      // validate username
      if (empty($name)) {
        $_SESSION['error'] = 'name-empty';
        $this->fileto('register');

        Header("Location: /register");
        exit();

      } else if (preg_match('/\d/', $name)) {
        $_SESSION['error'] = 'name-number';
        $this->fileto('register');

        Header("Location: /register");
        exit();

      }
      // validate email 
      if (empty($email)) {
        $_SESSION['error'] = 'email-empty';
        $this->fileto('register');

        Header("Location: /register");
        exit();

      } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = 'email-invalid';
        $this->fileto('register');

        Header("Location: /register");
        exit();

      }

      //validate password
      if (empty($password)) {
        $_SESSION['error'] = 'password-empty';
        $this->fileto('register');
        Header("Location: /register");
        exit();

      } else if (!preg_match('/[A-Z]/', $password)) {
        $_SESSION['error'] = 'password-capitalize';
        $this->fileto('register');
        Header("Location: /register");
        exit();

      } else if (!preg_match('/[a-z]/', $password)) {
        $_SESSION['error'] = 'password-letter';
        $this->fileto('register');
        Header("Location: /register");
        exit();

      } else if (!preg_match('/.{10,}/', $password)) {
        $_SESSION['error'] = 'password-short';
        $this->fileto('register');
        Header("Location: /register");
        exit();

      } else if (!preg_match('/\d/', $password)) {
        $_SESSION['error'] = 'password-number';
        $this->fileto('register');
        Header("Location: /register");
        exit();

      } else if (!preg_match('/[\W_]/', $password)) {
        $_SESSION['error'] = 'password-symbol';
        $this->fileto('register');
        Header("Location: /register");
        exit();

      } else if ($password !== $re_password) {
        $_SESSION['error'] = 'password-mismatch';
        $this->fileto('register');
        Header("Location: /register");
        exit();
      }
      // validate if email is unique
      if (!$this->read($email, 'register')) {
        $this->create($name, $email, $password);
      } else {
        $_SESSION['error'] = 'user-exists';
        $this->fileto('register');
        Header("Location: /register");
        exit();
      }
    } else {
      $_SESSION['error'] = 'invalid-request';
      $this->fileto('register');
      Header("Location: /register");
      exit();
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
    } else if ($path === 'delete') {
      $document_message .= date("F d Y H:i:s", filemtime("filesystem/delete_attempt.txt")) . " IP: " . $_SERVER['REMOTE_ADDR'] . " Browser: " . $_SERVER['HTTP_USER_AGENT'] . PHP_EOL;
      file_put_contents('filesystem/signin_attempt.txt', $document_message, FILE_APPEND);
    } else if ($path === 'edit') {
      $document_message .= date("F d Y H:i:s", filemtime("filesystem/edit_attempt.txt")) . " IP: " . $_SERVER['REMOTE_ADDR'] . " Browser: " . $_SERVER['HTTP_USER_AGENT'] . PHP_EOL;
      file_put_contents('filesystem/edit_attempt.txt', $document_message, FILE_APPEND);
    } else {
      $signout_message = "attempt to logout the user " . $_POST["email"] . " - successful " . "- Request made on " . date("F d Y H:i:s", filemtime("filesystem/signout_attempt.txt")) . " IP: " . $_SERVER['REMOTE_ADDR'] . " Browser: " . $_SERVER['HTTP_USER_AGENT'] . PHP_EOL;
      file_put_contents('filesystem/signout_attempt.txt', $signout_message, FILE_APPEND);
    }
  }

  private function read($email, $path = null)
  {
    try {
      // call Userbase model
      return $this->read_from_userbase($email, $path);
    } catch (PDOException $e) {
      $_SESSION['error'] = 'validation failed';
      $this->fileto($path);
      Header("Location: /register");
      exit();
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
      $_SESSION['error'] = 'invalid-request';
      $this->fileto('register');
      Header("Location: /register");
      exit();
    }
  }

  public function validate_user_request($fileto, $header, $email_post, $password_post)
  {
    // validate input values
    $email = $email_post ?? false;
    $password = $password_post ?? false;


    // validate CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
      $_SESSION['error'] = 'invalid-request';
      $this->fileto($fileto);
      Header("Location: /$header");
      exit();
    }

    // validate if they exist
    if ($_SERVER['REQUEST_METHOD'] === "POST") {
      // validate email
      if (!$email) {
        $_SESSION['error'] = 'email-invalid';
        $this->fileto($fileto);
        Header("Location: /$header");
        exit();
        // validate password
      } else if (!$password) {
        $_SESSION['error'] = 'password-invalid';
        $this->fileto($fileto);
        Header("Location: /$header");
        exit();
      }


      if ($fileto === 'signin') {
        $this->signin($email, $password);
      } else if ($fileto === 'delete')
        $this->delete_user($email, $password);

    } else {
      // if request method is not post, cancel signin
      $_SESSION['error'] = 'invalid-request';
      $this->fileto($fileto);
      Header("Location: /$header");
      exit();
    }

  }

  private function signin($email, $password)
  {
    if (!$this->read($email, 'signin')) {
      $_SESSION['error'] = 'user-fail';
      $this->fileto('signin');
      Header("Location: /sign-in");
      exit();
    } else {
      $password_verify = password_verify($password, $this->read($email, 'signin')["password"]);
      $password_verify = $password_verify ? true : false;

      if ($password_verify) {
        // if password checksout approve signin
        $_SESSION['error'] = null;
        $_SESSION['logged'] = ["email" => $this->read($email, 'signin')["email"], "name" => $this->read($email, 'signin')["name"]];
        $_SESSION['user'] = $this->read($email, null);
        $this->fileto('signin');
        setcookie('auth', true, time() + 1800, '/', 'localhost', true, true);
        Header("Location: /profile");
        exit();
      } else {
        $_SESSION['error'] = 'password-fail';
        $this->fileto('signin');
        Header("Location: /sign-in");
        exit();
      }
    }
  }


  private function delete_user($email, $password)
  {
    if (!$this->read($email, 'signin')) {
      $_SESSION['error'] = 'user-fail';
      $this->fileto('delete');
      Header("Location: /profile-settings");
      exit();

    } else {
      // if the user tries deleting someone else's profile, the action will be aborted
      if ($_SESSION['user']['email'] !== $this->read($email, null)['email']) {
        $_SESSION['error'] = 'invalid email provided';
        $this->fileto('delete');
        Header("Location: /profile-settings");
        exit();
      }

      $password_verify = password_verify($password, $this->read($email, 'delete')["password"]);
      $password_verify = $password_verify ? true : false;

      if ($password_verify) {
        // if password checksout approve signin
        $_SESSION['error'] = null;
        $_SESSION['logged'] = null;
        $_SESSION['user'] = null;
        $this->fileto('delete');
        setcookie('auth', false, time() - 1, '/', 'localhost', true, true);
        $this->delete_from_userbase($email);
        Header("Location: /home");
        exit();

      } else {
        $_SESSION['error'] = 'password-fail';
        $this->fileto('delete');

        Header("Location: /profile-settings");
        exit();

      }
    }
  }
  public function edit_user_name()
  {
    $name = $_POST['name'] ?? false;
    $email = $_POST['email'] ?? false;
    $password = $_POST['password'] ?? false;

    // validate username
    if (empty($name)) {
      $_SESSION['error'] = 'name-empty';
      $this->fileto('edit');

      Header("Location: /profile-settings");
      exit();

    } else if (preg_match('/\d/', $name)) {
      $_SESSION['error'] = 'name-number';
      $this->fileto('edit');
      Header("Location: /profile-settings");
      exit();

    }
    $this->validate_user_request('edit', 'change-name', $email, $password);
    $user = $this->read($email);
    if (!$user) {
      $_SESSION['error'] = 'email-invalid';
      $this->fileto('edit');

      Header("Location: /profile-settings");
      exit();
    } else if (!password_verify($password, $user["password"])) {
      $_SESSION['error'] = 'password-incorrect';
      $this->fileto('edit');
      Header("Location: /profile-settings");
      exit();
    } else if ($this->edit_userbase_name($email, $name)) {
      unset($_SESSION['error']);
      $_SESSION['user'] = $this->read($email, null);
      $_SESSION['success'] = true;
      $this->fileto('edit');
      Header("Location: /profile-settings");
      exit();
    } else {
      $_SESSION['error'] = 'invalid-request';
      $this->fileto('edit');
      Header("Location: /profile-settings");
      exit();
    }

  }


  public function signout()
  {
    $this->validate_signout();
    $this->fileto('signout');
    setcookie('auth', false, time() - 1, '/', 'localhost', true, true);
    unset($_SESSION['logged']);
    Header("Location: /sign-in");
    exit();
  }

}

$userbase_controller = new Userbase_controller();

if ($request === '/register-user')
  $userbase_controller->validate_registration();
else if ($request === '/signin-user')
  $userbase_controller->validate_user_request('signin', 'sign-in', $_POST['email'], $_POST['password']);
else if ($request === '/delete-user')
  $userbase_controller->validate_user_request('delete', 'profile-settings', $_POST['email'], $_POST['password']);
else if ($request === '/edit-user-name')
  $userbase_controller->edit_user_name();
else
  $userbase_controller->signout();
