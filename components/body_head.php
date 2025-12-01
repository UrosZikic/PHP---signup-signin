<?php
declare(strict_types=1);
require __DIR__ . '/../environment/config/bootstrap.php';
session_start();


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="description" content="Free Web tutorials">
  <meta name="keywords" content="HTML, CSS, JavaScript">
  <meta name="author" content="John Doe">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
    rel="stylesheet">

  <link rel="stylesheet" href="<?php echo "/styles/general_styles/general_styles.css" ?>">
  <link rel="stylesheet" href="<?php echo "/styles/form_styles/form_styles.css" ?>">
  <link rel="stylesheet" href="<?php echo "/styles/profile_styles/profile_styles.css" ?>">
  <link rel="stylesheet" href="<?php echo "/styles/admin_styles/admin_styles.css" ?>">

  <link rel="icon" type="image/x-icon" href="/images/favicon.png">


  <title>
    Profile management
  </title>
</head>
<?php
function auth()
{
  if ($_SESSION['user']['email'] === $_ENV['EMAIL'])
    return true;
  else
    return false;
}
;
?>


<body>
  <nav class="flex_default flex_align_center">
    <a href="/" style="padding: 0">
      <img src="images/logo.png" alt="website logo" class="logo_image">
    </a>
    <ul class="flex_default nav_ul_list">
      <?php
      if (!isset($_COOKIE['auth'])) {
        ?>
        <li>
          <a href="/sign-in">Sign-in</a>
        </li>
        <li>
          <a href="/register">Register</a>
        </li>
      <?php } else {
        ?>
        <li>
          <a href="/signout-user">Sign-out</a>
        </li>
        <li>
          <a href="/profile">Profile</a>
        </li>
        <?php
        if (auth()) {
          ?>
          <li>
            <a href="/admin-manager">Admin</a>
          </li>
        <?php }
      }
      ; ?>
    </ul>
  </nav>