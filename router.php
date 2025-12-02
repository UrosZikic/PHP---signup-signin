<?php

$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);



if (str_contains($request, ".")) {
  $request = "/";
  Header("Location: $request");
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
  case '/edit-user-name':
  case '/edit-user-password':
  case '/get-users':
  case '/get-deleted-users':
  case '/admin-delete-user':
  case '/admin-restore-user':
  case '/upload-profile-image':
    require_once __DIR__ . "../controllers/Userbase_Controller.php";
    break;
  case '/sign-in':
    require_once __DIR__ . "../front_page/views/sign_in.php";
    break;
  case '/confirm-delete':
    require_once __DIR__ . "../front_page/views/delete_user.php";
    break;
  case '/change-name':
    require_once __DIR__ . "../front_page/views/edit_user_name.php";
    break;
  case '/change-password':
    require_once __DIR__ . "../front_page/views/edit_user_password.php";
    break;
  case '/profile':
  case '/profile-settings':
    require_once __DIR__ . "../front_page/views/profile.php";
    break;
  case '/admin-manager':
    require_once __DIR__ . "../front_page/views/profile.php";
    break;
  case '/user-details':
    require_once __DIR__ . "../components/user_details.php";
    break;
  case '/expensly':
    require_once __DIR__ . "../project_expensly/expensly.php";
    break;
  default:
    require_once __DIR__ . "/errors/404.php";
    break;
}

