<?php
if (!$_COOKIE['username'] || !$_COOKIE['password']) {
	header("Location:index.php");
	exit();
}

$CHR['username']=$_COOKIE['username'];
$CHR['password']=$_COOKIE['password'];
setcookie("username", $_COOKIE['username'], time()+1800);
setcookie("password", $_COOKIE['password'], time()+1800);

?>