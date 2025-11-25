<?php
$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

switch ($request) {
  case '/':
  case '/home':
    require_once __DIR__ . "/front-page/home.php";
    break;
  case '/register':
    require_once __DIR__ . "../front_page/register.php";
    break;
  case '/sign-in':
    require_once __DIR__ . "../front_page/sign_in.php";
    break;
  default:
    require_once __DIR__ . "/errors/404.php";
    break;
}

