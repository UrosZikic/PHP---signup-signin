<h1>Error 404</h1>
<?php
session_start();
if (isset($_SESSION['error'])) {
  echo $_SESSION['error'];
}
