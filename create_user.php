<?php
declare(strict_types=1);
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $username = $_POST["username"];
  $email = $_POST["email"];
  $pwd = $_POST["password"];
  $re_pwd = $_POST["password_repeat"];
  class Registration
  {
    // declare data
    private string $username;
    private string $email;
    private string $pwd;
    private string $re_pwd;
    private array $pregMatchParams = ['/[a-z]/', '/[A-Z]/', '/[0-9]/', '/[\W_]/'];
    // constructor
    public function __construct(string $username, string $email, string $pwd, string $re_pwd)
    {
      $this->set_username($username);
      $this->set_email($email);
      $this->set_pwd($pwd);
      $this->set_re_pwd($re_pwd);
      $this->validate_fields($this->username, $this->email, $this->pwd, $this->re_pwd);

    }

    // set data
    private function set_username(string $username)
    {
      // validate username
      $this->username = htmlspecialchars($username);
    }
    private function set_email(string $email)
    {
      // validate email
      $this->email = filter_var($email, FILTER_VALIDATE_EMAIL);
    }
    private function set_pwd(string $pwd)
    {
      // validate password
      $this->pwd = htmlspecialchars($pwd);
    }
    private function set_re_pwd(string $re_pwd)
    {
      // validate repeated password
      $this->re_pwd = htmlspecialchars($re_pwd);
    }
    // retrieve data
    public function get_username()
    {
      return $this->username;
    }
    public function get_email()
    {
      return $this->email;
    }

    private function get_pwd()
    {
      return $this->pwd;
    }

    private function get_re_pwd()
    {
      return $this->re_pwd;
    }
    // report registration attempt
    private function registration_attempt(string $message)
    {
      $upload_dir = "user_records/";
      $time = date("Y-m-d H:i_s");
      $entry = "[$time] $message" . PHP_EOL;

      if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
      }

      file_put_contents($upload_dir . "registration_report.txt", $entry, FILE_APPEND);
    }


    // validate regex
    private function checkPregMatch(string $pregMatchParam, string $pwd, )
    {
      $validate_pwd = preg_match($pregMatchParam, $pwd);

      if (!$validate_pwd) {
        $_SESSION['error'] = [2, "submitted password is too weak"];
        $this->registration_attempt("User: " . $this->get_username() . " Email: " . $this->get_email() . " attempted to register and failed. Reason: submitted pwd that's too weak");
        Header("Location: registration.php?error=password_too_weak");
        die("submitted password is too weak");
      }
      return $validate_pwd;
    }

    // validation
    private function validate_fields(...$props)
    {
      //check if input is empty
      foreach ($props as $key => $prop) {
        // key 0 = username, 1 = email, 2 = pwd, 3 = re_pwd
        if (empty($prop)) {
          $_SESSION['error'] = [$key, "a field was left empty"];
          $this->registration_attempt("User: " . $this->get_username() . " Email: " . $this->get_email() . " attempted to register and failed. Reason: left an empty field");
          Header("Location: registration.php?error=empty_field");
          die("a field was left empty");
        }
      }
      // validate email
      if (!filter_var($this->email)) {
        $_SESSION['error'] = [1, "submitted email is invalid"];
        $this->registration_attempt("User: " . $this->get_username() . " Email: " . $this->get_email() . " attempted to register and failed. Reason: submitted email is invalid");
        Header("Location: registration.php?error=email_invalid");
        die("submitted email is invalid");
      }
      // validate password
      // validate length
      if (strlen($this->pwd) < 8) {
        $_SESSION['error'] = [2, "submitted password is too short"];
        $this->registration_attempt("User: " . $this->get_username() . " Email: " . $this->get_email() . " attempted to register and failed. Reason: submitted pwd that's too short");
        Header("Location: registration.php?error=password_too_short");
        die("submitted password is too short");
      }

      // validate password strength
      foreach ($this->pregMatchParams as $param) {
        $this->checkPregMatch($param, $this->pwd);
      }

      //validate pwd = re_pwd
      if ($this->pwd !== $this->re_pwd) {
        $_SESSION['error'] = [3, "submitted passwords don't match"];
        $this->registration_attempt("User: " . $this->get_username() . " Email: " . $this->get_email() . " attempted to register and failed. Reason: submitted pwd and re_pwd don't match");
        Header("Location: registration.php?error=passwords_do_not_match");
        die("submitted passwords don't match");
      }

      // hash the password
      $hashedpwd = password_hash($this->pwd, PASSWORD_BCRYPT, ['cost' => 12]);

      // finish registration

      try {
        require_once 'SQL/pdo.php';
        $query = "INSERT INTO users (username, pwd, email) VALUES (:username, :pwd, :email)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":pwd", $hashedpwd);
        $stmt->bindParam(":email", $this->email);
        $stmt->execute();
        $pdo = null;
        $stmt = null;
        $this->registration_attempt("User: " . $this->get_username() . " Email: " . $this->get_email() . " attempted to register and Succeeded.");
        header("Location: ./sign_in.php");
        exit();
      } catch (PDOException $e) {
        $err_code = "";
        if (str_contains($e->getMessage(), '23000')) {
          $err_code = "email_already_registered";
        }
        $_SESSION["error"] = [1, "email_already_registered"];
        $this->registration_attempt("User: " . $this->get_username() . " Email: " . $this->get_email() . " attempted to register and failed. Reason: submitted email is already registered");
        header("Location: registration.php?error={$err_code}");
        die($e->getMessage());
      }
    }
  }
  $register = new Registration($username, $email, $pwd, $re_pwd);


} else {
  header("Location: registration.php?error=something_went_wrong");
}

