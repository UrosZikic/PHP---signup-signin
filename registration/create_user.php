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
    protected string $username;
    protected string $email;
    protected string $pwd;
    protected string $re_pwd;
    protected array $pregMatchParams = ['/[a-z]/', '/[A-Z]/', '/[0-9]/', '/[\W_]/'];
    // constructor
    public function __construct(string $username, string $email, string $pwd, string $re_pwd)
    {
      $this->set_username($username);
      $this->set_email($email);
      $this->set_pwd($pwd);
      $this->set_re_pwd($re_pwd);
      // $this->validate_fields($this->username, $this->email, $this->pwd, $this->re_pwd);

    }

    // set data
    protected function set_username(string $username)
    {
      // validate username
      $this->username = htmlspecialchars($username);
    }
    protected function set_email(string $email)
    {
      // validate email
      $this->email = filter_var($email, FILTER_VALIDATE_EMAIL);
    }
    protected function set_pwd(string $pwd)
    {
      // validate password
      $this->pwd = htmlspecialchars($pwd);
    }
    protected function set_re_pwd(string $re_pwd)
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

    public function get_pwd()
    {
      return $this->pwd;
    }

    public function get_re_pwd()
    {
      return $this->re_pwd;
    }

    // report registration attempt
    protected function registration_attempt(string $message)
    {
      $upload_dir = "../user_records/";
      $time = date("Y-m-d H:i_s");
      $entry = "[$time] $message" . PHP_EOL;

      if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
      }

      file_put_contents($upload_dir . "registration_report.txt", $entry, FILE_APPEND);
    }
  }
  $register = new Registration($username, $email, $pwd, $re_pwd);


} else {
  header("Location: registration.php?error=something_went_wrong");
}

