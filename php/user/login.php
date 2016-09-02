<!DOCTYPE html>

<?php
require ("../mysql/database_connect.php");

## Setup the login
$username = '';
#check = '';

## If there is no POST request
if(!empty($_POST)){
	## Query to login
	$query = "
	SELECT id, firstname, lastname, username, password, salt, email, level
	FROM users
	WHERE username = :username
	";

	$query_params = array(':username' => $_POST['username']);

	try {
		$stmt = $db->prepare($query);
		$result = $stmt->execute($query_params);
	}
	catch(PDOException $ex){
		die("Failed to run query: " . $ex->getMessage());
	}

	$boolean_login = false;

	$row = $stmt->fetch();

	if($row){
		$check_password = hash('sha256', $_POST['password'] . $row['salt']);
		for($round = 0; $round < 65536; $round++){
			$check_password = hash('sha256', $check_password . $row['salt']);
		}
		#$check_password = 'bangalore';
		if($check_password === $row['password']){
			$boolean_login = true;
		}
	}

	if($boolean_login){
		unset($row['salt']);
		unset($row['password']);
		$_SESSION['user'] = $row;
		
		echo var_dump($_SESSION);
		
		
		if($_SESSION['orig_url'] == ""){
			header("Location: /index.php");
		} 
		else {
			header("Location: ". $_SESSION['orig_url']);
		}
	}
}


?>

<?php
include ('login_head.php');
?>
<div class="form-box" id="login-box">
	<div class="header">Log In</div>
		<form action="login.php" method="post">
		<div class="body bg-gray">
			<?php if(isset($boolean_login) && ($boolean_login == false)){ ?>
				<br>
				<div class="alert alert-danger">
					<i class="fa fa-ban"></i>
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<b>Error!</b> Incorrect Username/Password.
				</div>
			<?php } ?>
			<div class="form-group">
				<input type="text" name="username" class="form-control" placeholder="User ID"/><br>
			</div>
			<div class="form-group">
				<input type="password" name="password" class="form-control" placeholder="Password"/><br>
			</div>
		</div>
		<div class="footer">
			<button type="submit" class="btn bg-olive btn-block">Sign me in</button>
			</form>
			New user? <a href="/user/register.php" type="submit" class="btn bg-blue btn-block">Register</a>
		</div>
	</div>
</div>

<?php
include ('login_foot.php');
?>