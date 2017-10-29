<?php 
session_start();
require_once("functions.php");
confirm_logged_in();
$_SESSION["uid"]=null;
$_SESSION["username"]=null;
session_destroy();
redirect_to('login.php');

?>