<?php session_start();
	//Remove all Global Variables
	$_SESSION = [];
	//Destroy Session
	session_destroy();
  // redirect
	echo "<script>window.location.assign('../index.php');</script>";
?>
