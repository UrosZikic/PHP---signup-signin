<?php

$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if (str_contains($request, ".php")) {
  $request = "/";
}

switch ($request) {
  case '/':
  case '/home':
    require_once __DIR__ . "/front_page/home.php";
    break;
  case '/register':
    require_once __DIR__ . "../front_page/views/register.php";
    break;
  case '/register-user':
  case '/signin-user':
  case '/signout-user':
  case '/delete-user':
    require_once __DIR__ . "../controllers/Userbase_Controller.php";
    break;
  case '/sign-in':
    require_once __DIR__ . "../front_page/views/sign_in.php";
    break;
  case '/confirm-delete':
    require_once __DIR__ . "../front_page/views/delete_user.php";
    break;
  case '/profile':
  case '/profile-settings':
    require_once __DIR__ . "../front_page/views/profile.php";
    break;

  default:
    require_once __DIR__ . "/errors/404.php";
    break;
}

