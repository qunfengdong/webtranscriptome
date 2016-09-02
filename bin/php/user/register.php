<!DOCTYPE html>
<?php

## Have the login details here.
require ("../mysql/database_connect.php");

// This if statement checks to determine whether the registration form has been submitted 
// If it has, then the registration code is run, otherwise the form is displayed
try {

if(!empty($_POST)) {
	if(preg_match( '/[^a-z0-9_\.]/i', $_POST['username'])){
		throw new Exception("Username must be alphanumeric, allowed special characters are . and _");
	}
	if(empty($_POST['firstname'])) {
		throw new Exception("Please enter your first name.");
	}
	if(empty($_POST['lastname'])) {
		throw new Exception("Please enter your last name.");
	} 
	if(empty($_POST['username'])) {
		throw new Exception("Please enter your username.");
	}
	if(strlen($_POST['username']) < 6){
		throw new Exception("Username should be at least 6 characters");
	}
	if(empty($_POST['password'])) {
		throw new Exception("Please enter a password.");
	}
	if(empty($_POST['password_confirmation'])) {
		throw new Exception("Please confirm the password.");
	}
	if($_POST['password'] != $_POST['password_confirmation']){
		throw new Exception("Passwords do not match.");
	}
	if(preg_match( '/[^a-z0-9]/i', $_POST['password'])){
		throw new Exception("Password should be alphanumeric");
	}
	if(strlen($_POST['password']) < 6){
		throw new Exception("Password should be at least 6 characters");
	}
	if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
		throw new Exception("Invalid E-Mail Address");
	} 
         
  $query = "SELECT 1 FROM users WHERE email = :email OR username = :username";
	$query_params = array(':email' => $_POST['email'], ':username' => $_POST['username']);
	
	try {
		$stmt = $db->prepare($query);
		$result = $stmt->execute($query_params);
	}
	catch(PDOException $ex) {
		throw new Exception("Failed to run query: " . $ex->getMessage());
	}
	
	$row = $stmt->fetch();
	if($row) {
		throw new Exception("This email is already in use");
	}
	
	$query = "
			INSERT INTO users (
				firstname,
				lastname,
				username,
				password, 
				salt, 
				email,
				level
			) 
			VALUES (
				:firstname,
				:lastname,
				:username,
				:password, 
				:salt, 
				:email,
				0
			)
		";
	
	$salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
	$password = hash('sha256', $_POST['password'] . $salt);
	for($round = 0; $round < 65536; $round++) {
		$password = hash('sha256', $password . $salt);
	}
	
	$query_params = array(
		':firstname' => $_POST['firstname'],
		':lastname' => $_POST['lastname'],
		':username' => $_POST['username'],
		':password' => $password,
		':salt' => $salt,
		':email' => $_POST['email']
	);
	
	$email = $_POST['email'];
	system("mkdir ../tmp/$email");
	
	try {
		// Execute the query to create the user
		$stmt = $db->prepare($query);
		$result = $stmt->execute($query_params);
	}
	catch(PDOException $ex) {
		// Note: On a production website, you should not output $ex->getMessage().
		// It may provide an attacker with helpful information about your code.
		throw new Exception("Failed to run query: " . $ex->getMessage());
	}
	
	// This redirects the user back to the login page after they register
	header("Location: login.php");
	
	// Calling die or exit after performing a redirect using the header function
	// is critical.  The rest of your PHP script will continue to execute and
	// will be sent to the user if you do not die or exit.
	throw new Exception("Redirecting to login.php");
} 
}
catch(Exception $ex){
	$error = '<br><div class="alert alert-danger alert-dismissable">';
	$error .= '<i class="fa fa-ban"></i>';
	$error .= '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
	$error .= '<b>ERROR!</b> '.$ex->getMessage().'</div>';
}
     
?> 

<?php
include ('login_head.php');
?>

<div class="form-box" id="login-box">
 	<div class="header">Register</div>
	 	<form action="register.php" method="post">
 		<div class="body bg-gray">
 			<?php echo $error ?>
 			<table class="table table-bordered">
	 			<tr><td>First Name:</td><td><input type="text" name="firstname" value="" /></td></tr>
  			<tr><td>Last Name:</td><td><input type="text" name="lastname" value="" /></td></tr>
   			<tr><td>Username:</td><td><input type="text" name="username" value="" /></td></tr>
   			<tr><td>E-Mail:</td><td><input type="text" name="email" value="" /></td></tr>
   			<tr><td>Password:</td><td><input type="password" name="password" value="" /></td></tr>
   			<tr><td>Confirm Password:</td><td><input type="password" name="password_confirmation" value="" /></td></tr>
			</table>
		</div>
    <div class="footer">
     	<input class="btn bg-olive btn-block" type="submit" value="Register" />
		</div>
		</form>
	</div>
</div>

<?php
include ('login_foot.php');
?>
