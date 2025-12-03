<?php
// __DIR__ resolves pathing issue
require_once __DIR__ . '/../models/Userbase.php';
// session_start();
// reset on visit
$_SESSION['error'] = "";
// necessary in order to call the right function
$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
// needed for profile.php to import a proper component
$_SESSION['request'] = $request;



class Userbase_controller extends Userbase
{
  private string $name;
  private string $email;
  private string $password;
  private string $re_password;

  private function validate_csrf(string $path, string $uri)
  {
    // validate CSRF logic
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
      $_SESSION['error'] = 'invalid-request';
      $this->fileto(path: $path);

      Header("Location: $uri");
      exit();
    } else {
      return true;
    }
  }

  public function validate_registration()
  {
    // validate CSRF
    $this->validate_csrf("register", "/register");

    // validate input values
    $this->name = $_POST['name'] ?? false;
    $this->email = $_POST['email'] ?? false;
    $this->password = $_POST['password'] ?? false;
    $this->re_password = $_POST['re_password'] ?? false;




    // validate request
    if ($_SERVER['REQUEST_METHOD'] === "POST") {

      if (!$this->name) {
        $_SESSION['error'] = 'name-invalid';
        $this->fileto(path: 'register');
        Header("Location: /register");
        exit();

      } else if (!$this->email) {
        $_SESSION['error'] = 'email-invalid';
        $this->fileto(path: 'register');

        Header("Location: /register");
        exit();

      } else if (!$this->password) {
        $_SESSION['error'] = 'password-invalid';
        $this->fileto(path: 'register');
        Header("Location: /register");
        exit();

      } else if (!$this->re_password) {
        $_SESSION['error'] = 're_password-invalid';
        $this->fileto(path: 'register');

        Header("Location: /register");
        exit();

      }


      // validate username
      if (empty($this->name)) {
        $_SESSION['error'] = 'name-empty';
        $this->fileto(path: 'register');

        Header("Location: /register");
        exit();

      } else if (preg_match('/\d/', $this->name)) {
        $_SESSION['error'] = 'name-number';
        $this->fileto(path: 'register');

        Header("Location: /register");
        exit();

      }
      // validate email 
      if (empty($this->email)) {
        $_SESSION['error'] = 'email-empty';
        $this->fileto(path: 'register');

        Header("Location: /register");
        exit();

      } else if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = 'email-invalid';
        $this->fileto(path: 'register');

        Header("Location: /register");
        exit();

      }

      //validate password
      if (empty($this->password)) {
        $_SESSION['error'] = 'password-empty';
        $this->fileto(path: 'register');
        Header("Location: /register");
        exit();

      } else if (!preg_match('/[A-Z]/', $this->password)) {
        $_SESSION['error'] = 'password-capitalize';
        $this->fileto(path: 'register');
        Header("Location: /register");
        exit();

      } else if (!preg_match('/[a-z]/', $this->password)) {
        $_SESSION['error'] = 'password-letter';
        $this->fileto(path: 'register');
        Header("Location: /register");
        exit();

      } else if (!preg_match('/.{10,}/', $this->password)) {
        $_SESSION['error'] = 'password-short';
        $this->fileto(path: 'register');
        Header("Location: /register");
        exit();

      } else if (!preg_match('/\d/', $this->password)) {
        $_SESSION['error'] = 'password-number';
        $this->fileto(path: 'register');
        Header("Location: /register");
        exit();

      } else if (!preg_match('/[\W_]/', $this->password)) {
        $_SESSION['error'] = 'password-symbol';
        $this->fileto(path: 'register');
        Header("Location: /register");
        exit();

      } else if ($this->password !== $this->re_password) {
        $_SESSION['error'] = 'password-mismatch';
        $this->fileto(path: 'register');
        Header("Location: /register");
        exit();
      }
      // validate if email is unique
      if (!$this->read(email: $this->email, path: 'register')) {
        $this->create(name: $this->name, email: $this->email, password: $this->password);
      } else {
        $_SESSION['error'] = 'user-exists';
        $this->fileto(path: 'register');
        Header("Location: /register");
        exit();
      }
    } else {
      $_SESSION['error'] = 'invalid-request';
      $this->fileto(path: 'register');
      Header("Location: /register");
      exit();
    }
  }

  private function fileto(string $path)
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

  private function read(string $email, string|null $path = null)
  {
    try {
      // call Userbase model
      return $this->read_from_userbase(email: $this->email, path: $path);
    } catch (PDOException $e) {
      $_SESSION['error'] = 'validation failed';
      $this->fileto(path: $path);
      Header("Location: /register");
      exit();
    }
  }


  private function create(string $name, string $email, string $password)
  {
    // validate CSRF
    $this->validate_csrf("register", "/register");
    try {
      $password_hashed = password_hash($this->password, PASSWORD_DEFAULT);
      // call Userbase model function and insert data
      $this->insert_into_userbase(name: $this->name, email: $this->email, password: $password_hashed);
      // success no errors
      $_SESSION['error'] = null;
      // record this attempt
      $this->fileto(path: 'register');
      // send the user to the next step
      header("Location: /sign-in");
      exit();

    } catch (PDOException $e) {
      $_SESSION['error_message'] = $e->getMessage();
      $_SESSION['error'] = 'invalid-request';
      $this->fileto(path: 'register');
      Header("Location: /register");
      exit();
    }
  }

  public function validate_user_request(string $fileto, string $header, string $email_post, string $password_post)
  {
    // validate input values
    $this->email = $email_post ?? false;
    $this->password = $password_post ?? false;


    // validate CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
      $_SESSION['error'] = 'invalid-request';
      $this->fileto(path: $fileto);
      Header("Location: /$header");
      exit();
    }

    // validate if they exist
    if ($_SERVER['REQUEST_METHOD'] === "POST") {
      // validate email
      if (!$this->email) {
        $_SESSION['error'] = 'email-invalid';
        $this->fileto($fileto);
        Header("Location: /$header");
        exit();
        // validate password
      } else if (!$this->password) {
        $_SESSION['error'] = 'password-invalid';
        $this->fileto($fileto);
        Header("Location: /$header");
        exit();
      }


      if ($fileto === 'signin') {
        $this->signin($this->email, $this->password);
      } else if ($fileto === 'delete')
        $this->delete_user($this->email, $this->password);

    } else {
      // if request method is not post, cancel signin
      $_SESSION['error'] = 'invalid-request';
      $this->fileto($fileto);
      Header("Location: /$header");
      exit();
    }
  }

  private function signin(string $email, string $password)
  {
    // validate CSRF
    $this->validate_csrf("signin", "/sign-in");

    $this->email = $email;
    $this->password = $password;

    if (!$this->read($this->email, 'signin') || $this->read($this->email, 'signin')['soft_deleted']) {
      $_SESSION['error'] = 'user-fail';
      $this->fileto('signin');
      Header("Location: /sign-in");
      exit();
    } else {
      $password_verify = password_verify($this->password, $this->read($this->email, 'signin')["password"]);
      $password_verify = $password_verify ? true : false;

      if ($password_verify) {
        // if password checksout approve signin
        $_SESSION['error'] = null;
        $_SESSION['logged'] = ["email" => $this->read($this->email, 'signin')["email"], "name" => $this->read($this->email, 'signin')["name"]];
        $_SESSION['user'] = $this->read(email: $this->email, path: null);
        $this->fileto('signin');
        setcookie('auth', true, time() + 1800, '/', 'localhost', true, true);
        Header("Location: /profile");
        exit();
      } else {
        $_SESSION['error'] = 'password-fail';
        $this->fileto(path: 'signin');
        Header("Location: /sign-in");
        exit();
      }
    }
  }


  private function delete_user(string $email, string $password)
  {
    // validate CSRF
    $this->validate_csrf("delete", "/profile");

    $this->email = $email;
    $this->password = $password;

    if (!$this->read(email: $this->email, path: 'signin')) {
      $_SESSION['error'] = 'user-fail';
      $this->fileto('delete');
      Header("Location: /profile-settings");
      exit();

    } else {
      // if the user tries deleting someone else's profile, the action will be aborted
      if ($_SESSION['user']['email'] !== $this->read(email: $this->email, path: null)['email']) {
        $_SESSION['error'] = 'invalid email provided';
        $this->fileto(path: 'delete');
        Header("Location: /profile-settings");
        exit();
      }

      $password_verify = password_verify($this->password, $this->read(email: $this->email, path: 'delete')["password"]);
      $password_verify = $password_verify ? true : false;

      if ($password_verify) {
        // if password checksout approve signin
        $_SESSION['error'] = null;
        $_SESSION['logged'] = null;
        $_SESSION['user'] = null;
        $this->fileto(path: 'delete');
        setcookie('auth', false, time() - 1, '/', 'localhost', true, true);
        $this->delete_from_userbase(email: $this->email);
        Header("Location: /home");
        exit();

      } else {
        $_SESSION['error'] = 'password-fail';
        $this->fileto(path: 'delete');

        Header("Location: /profile-settings");
        exit();

      }
    }
  }
  public function edit_user_name()
  {
    // validate CSRF
    $this->validate_csrf("edit", "/profile");

    $this->name = $_POST['name'] ?? false;
    $this->email = $_POST['email'] ?? false;
    $this->password = $_POST['password'] ?? false;

    // validate username
    if (empty($this->name)) {
      $_SESSION['error'] = 'name-empty';
      $this->fileto(path: 'edit');

      Header("Location: /profile-settings");
      exit();

    } else if (preg_match('/\d/', $this->name)) {
      $_SESSION['error'] = 'name-number';
      $this->fileto(path: 'edit');
      Header("Location: /profile-settings");
      exit();

    }
    $this->validate_user_request(fileto: 'edit', header: 'change-name', email_post: $this->email, password_post: $this->password);
    $user = $this->read(email: $this->email);
    if (!$user) {
      $_SESSION['error'] = 'email-invalid';
      $this->fileto('edit');

      Header("Location: /profile-settings");
      exit();
    } else if (!password_verify($this->password, $user["password"])) {
      $_SESSION['error'] = 'password-incorrect';
      $this->fileto('edit');
      Header("Location: /profile-settings");
      exit();
    } else if ($this->edit_userbase_name(email: $this->email, name: $this->name)) {
      unset($_SESSION['error']);
      $_SESSION['user'] = $this->read($this->email, null);
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

  public function edit_user_password()
  {
    // validate CSRF
    $this->validate_csrf("edit", "/profile");

    $this->email = $_POST['email'] ?? false;
    $old_password = $_POST['old_password'] ?? false;
    $new_password = $_POST['new_password'] ?? false;
    $re_password = $_POST['re_password'] ?? false;


    if ($new_password !== $re_password) {
      $_SESSION['error'] = "password-bad-match";
      $this->fileto('edit');
      Header("Location: /profile-settings");
    }
    $hash_password = password_hash($new_password, PASSWORD_DEFAULT);

    $this->validate_user_request(fileto: 'edit', header: '/profile-settings', email_post: $this->email, password_post: $old_password);


    // same code in the edit name section -- some changes though
    $user = $this->read(email: $this->email);
    if (!$user) {
      $_SESSION['error'] = 'email-invalid';
      $this->fileto(path: 'edit');

      Header("Location: /profile-settings");
      exit();
    } else if (!password_verify($old_password, $user["password"])) {
      $_SESSION['error'] = 'password-incorrect';
      $this->fileto(path: 'edit');
      Header("Location: /profile-settings");
      exit();
    } else if ($this->edit_userbase_password(email: $this->email, hash_password: $hash_password)) {
      unset($_SESSION['error']);
      $_SESSION['user'] = $this->read(email: $this->email, path: null);
      $_SESSION['success'] = true;
      $this->fileto(path: 'edit');
      Header("Location: /profile-settings");
      exit();
    } else {
      $_SESSION['error'] = 'invalid-request';
      $this->fileto(path: 'edit');
      Header("Location: /profile-settings");
      exit();
    }
  }

  public function get_users()
  {
    if ($this->get_userbase()) {
      $users = $this->get_userbase();
      unset($_SESSION['error']);
      require_once 'admin/admin_users.php';
    } else {
      require_once 'admin/admin_blank_users.php';
    }
  }
  public function get_softdeleted_users()
  {
    if ($this->get_softdeleted_userbase()) {
      $users = $this->get_softdeleted_userbase();
      unset($_SESSION['error']);

      require_once 'admin/admin_users.php';
    } else {
      require_once 'admin/admin_blank_users.php';
    }
  }


  public function signout()
  {
    $this->validate_signout();
    $this->fileto(path: 'signout');
    setcookie('auth', false, time() - 1, '/', 'localhost', true, true);
    unset($_SESSION['logged']);
    Header("Location: /sign-in");
    exit();
  }

  public function admin_delete_user()
  {
    $this->email = $_GET['user'];
    $this->soft_delete(email: $this->email);
    Header("Location: /get-users");
  }

  public function admin_restore_user()
  {
    $this->email = $_GET['email'];
    $this->restore_user(email: $this->email);
    $_SESSION['success'] = true;
    Header("Location: /admin-manager");
    exit();
  }

  public function upload_profile_image()
  {
    $this->email = $_POST['email'];

    if (isset($_FILES['file']['name'])) {
      $upload_dir = "images/profile_images/";

      if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
      }

      $target_file = $upload_dir . $_FILES['file']['name'];

      // remove previous image from the localfiles
      $user = $this->read_from_userbase(email: $this->email);
      unlink($user['image_path']);

      // upload the new image and update your SQL entry
      $this->upload_image_path(email: $this->email, path: $target_file);
      $_SESSION['user'] = $this->read_from_userbase($this->email);
      move_uploaded_file($_FILES['file']['tmp_name'], $target_file);
      Header("Location: /user-details");
      exit();

    } else {
      echo 'image upload failed';
    }
  }

}

$userbase_controller = new Userbase_controller();

switch ($request) {
  case '/register-user':
    $userbase_controller->validate_registration();
    break;
  case '/signin-user':
    $userbase_controller->validate_user_request('signin', 'sign-in', $_POST['email'], $_POST['password']);
    break;
  case '/delete-user':
    $userbase_controller->validate_user_request('delete', 'profile-settings', $_POST['email'], $_POST['password']);
    break;
  case '/edit-user-name':
    $userbase_controller->edit_user_name();
    break;
  case '/edit-user-password':
    $userbase_controller->edit_user_password();
    break;
  case '/get-users':
    $userbase_controller->get_users();
    break;
  case '/get-deleted-users':
    $userbase_controller->get_softdeleted_users();
    break;
  case '/admin-delete-user':
    $userbase_controller->admin_delete_user();
    break;
  case '/admin-restore-user':
    $userbase_controller->admin_restore_user();
    break;
  case '/upload-profile-image':
    $userbase_controller->upload_profile_image();
    break;
  default:
    $userbase_controller->signout();
    break;
}

