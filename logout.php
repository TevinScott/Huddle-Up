<?php
session_start();
require_once("logHandling.php");

if (isset($_SESSION['userlogin'])){
    $user = $_SESSION['userlogin'];
    newLogLogin($user, "Logout Success");

}

unset($_SESSION);
session_destroy();
session_write_close();
header('Location: Homepage/homepage.php');
die;


?>