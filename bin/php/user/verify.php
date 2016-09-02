<?php 
require ("../mysql/database_connect.php");

#echo "kashi";

// At the top of the page we check to see whether the user is logged in or not
if(empty($_SESSION['user'])) {
	// If they are not, we redirect them to the login page.
	error_log(implode(", ", $_SERVER));
	error_log($_SERVER['REQUEST_URI']);
	$_SESSION['orig_url'] = $_SERVER['REQUEST_URI'];
	header("Location: /user/login.php");
	// Remember that this die statement is absolutely critical.  Without it,
	// people can view your members-only content without logging in.
	die("Redirecting to /user/login.php");
}

## KASHI

?>
