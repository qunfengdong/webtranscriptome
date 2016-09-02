<?php require ("../mysql/database_connect.php"); ?>


<?php 

// We remove the user's data from the session
unset($_SESSION['user']);

// We redirect them to the login page
header("Location: /index.php");
die("Redirecting to: /index.php");
