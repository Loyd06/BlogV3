<?php session_start(); 
unset ($_SESSION['profil']);
header("location: dashboard.php");
?>