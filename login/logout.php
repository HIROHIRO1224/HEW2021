<?php

session_start();

include_once '../mod/LoginClass.php';

$loginclass = new LoginClass();
$loginclass->Logout();

header("Location: ../index.php");
exit();
