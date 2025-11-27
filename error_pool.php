<?php

$error = $_SESSION['error'];

$registration_error_pool = [
  'invalid-request' => 'registration failed',
  'name-invalid' => 'Please, enter the proper name',
  'email-invalid' => 'Please, enter the proper email',
  'password-invalid' => "Password field can't be left empty",
  'password-empty' => "Password field can't be left empty",
  'password-capitalized' => 'Password field must contain at least 1 capitalized letter',
  'password-letter' => 'Password field must contain at least 1 undercase letter',
  'password-short' => 'Password must contain at least 10 characters',
  'password-number' => 'Password must contain at least 1 number',
  'password-symbol' => 'Password must contain at least 1 symbol',
  'password-mismatch' => "passwords don't match",
  're_password-invalid' => "Re-password field can't be left empty",
  'name-empty' => "Name field can't be left empty",
  'name-number' => "Name field can't contain numbers",
  'email-empty' => "Email field can't be left empty",
  'user-exists' => "The user under this email is already registered",
  'user-fail' => "The user doesn't exist",
  'password-fail' => "The password doesn't match",
  '' => ''
];