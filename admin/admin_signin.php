<?php
require_once "admin.php";
echo $_SESSION['verify_admin_hash'];

require_once "../components/body_head.php";
require_once "../components/sign_in_comp.php";
require_once "../components/body_foot.php";