<?php
require "create_user.php";

class Registration_validation extends Registration
{
  public function __construct(string $username, string $email, string $pwd, string $re_pwd)
  {
    $this->set_username($username);
    $this->set_email($email);
    $this->set_pwd($pwd);
    $this->set_re_pwd($re_pwd);
  }

  // validate regex -> this function is called from within a loop in a registration_validation
  protected function checkPregMatch(string $pregMatchParam, string $pwd, )
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
  public function registration_validation(...$props)
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
      require_once '../SQL/pdo.php';
      $query = "INSERT INTO users (username, pwd, email) VALUES (:username, :pwd, :email)";
      $stmt = $pdo->prepare($query);
      $stmt->bindParam(":username", $this->username);
      $stmt->bindParam(":pwd", $hashedpwd);
      $stmt->bindParam(":email", $this->email);
      $stmt->execute();
      $pdo = null;
      $stmt = null;
      $this->registration_attempt("User: " . $this->get_username() . " Email: " . $this->get_email() . " attempted to register and Succeeded.");
      header("Location: ../signin/sign_in.php");
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

$registration_validation = new Registration_validation($register->get_username(), $register->get_email(), $register->get_pwd(), $register->get_re_pwd());
$registration_validation->registration_validation();