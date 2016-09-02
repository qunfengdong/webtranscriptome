<?php require ('../template/header.php'); ?>

<?php require ("../mysql/database_connect.php"); ?>

<?php
// First we execute our common code to connection to the database and start the session

function validate_form(){
	if(empty($_POST['firstname'])) {
		throw new Exception("Please enter your first name.");
	}
	if(empty($_POST['lastname'])) {
		throw new Exception("Please enter your last name.");
	}
	if(empty($_POST['password'])) {
		throw new Exception("Please enter the password.");
	}
	if(empty($_POST['password_confirmation'])) {
		throw new Exception("Please confirm the password.");
	}
	if($_POST['password'] != $_POST['password_confirmation']){
		throw new Exception("Passwords do not match.");
	}
	// Make sure the user entered a valid E-Mail address
	if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
		throw new Exception("Invalid E-Mail Address");
	}
}

function validate_email(){
	global $db;
	// If the user is changing their E-Mail address, we need to make sure that
	// the new value does not conflict with a value that is already in the system.
	// If the user is not changing their E-Mail address this check is not needed.
	if($_POST['email'] != $_SESSION['user']['email']) {
		// Define our SQL query
		$query = "SELECT 1 FROM users WHERE email = :email";

		// Define our query parameter values
		$query_params = array(':email' => $_POST['email']);

		try {
			// Execute the query
			$stmt = $db->prepare($query);
			$result = $stmt->execute($query_params);
		}
		catch(PDOException $ex) {
			// Note: On a production website, you should not output $ex->getMessage().
			// It may provide an attacker with helpful information about your code.
			throw new Exception("Failed to run query: " . $ex->getMessage());
		}

		// Retrieve results (if any)
		$row = $stmt->fetch();
		if($row) {
			throw new Exception("This E-Mail address is already in use");
		}
	}
}

function validate_username(){
	global $db;
	// If the user is changing their E-Mail address, we need to make sure that
	// the new value does not conflict with a value that is already in the system.
	// If the user is not changing their E-Mail address this check is not needed.
	if($_POST['username'] != $_SESSION['user']['username']) {
		// Define our SQL query
		$query = "SELECT 1 FROM users WHERE username = :username";

		// Define our query parameter values
		$query_params = array(':username' => $_POST['username']);

		try {
			// Execute the query
			$stmt = $db->prepare($query);
			$result = $stmt->execute($query_params);
		}
		catch(PDOException $ex) {
			// Note: On a production website, you should not output $ex->getMessage().
			// It may provide an attacker with helpful information about your code.
			throw new Exception("Failed to run query: " . $ex->getMessage());
		}

		// Retrieve results (if any)
		$row = $stmt->fetch();
		if($row) {
			throw new Exception("This username is already in use");
		}
	}
}

function get_old_user(){
	global $db;

	## Get old user
	$query = "SELECT * FROM users WHERE email = :email";
	$query_params = array(':email' => $_SESSION['user']['email']);
	try {
		// Execute the query
		$stmt = $db->prepare($query);
		$result = $stmt->execute($query_params);
	}
	catch(PDOException $ex) {
		// Note: On a production website, you should not output $ex->getMessage().
		// It may provide an attacker with helpful information about your code.
		die("Failed to run old user query: " . $ex->getMessage());
	}
	$row = $stmt->fetch();
	return $row;
}

// At the top of the page we check to see whether the user is logged in or not
if(empty($_SESSION['user'])) {
	// If they are not, we redirect them to the login page.
	header("Location: login.php");

	// Remember that this die statement is absolutely critical.  Without it,
	// people can view your members-only content without logging in.
	die("Redirecting to login.php");
}


// This if statement checks to determine whether the edit form has been submitted
// If it has, then the account updating code is run, otherwise the form is displayed
if(!empty($_POST)) {

	try {
		validate_form();
		validate_email();
		validate_username();
		$old_user = get_old_user();

	}
	catch (Exception $ex) {
		echo "<span style='color:red'>ERROR: ", $ex->getMessage() ,"</span>";
	}



	// If the user entered a new password, we need to hash it and generate a fresh salt
	// for good measure.
	#if(!empty($_POST['password'])) {
		$salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
		$password = hash('sha256', $_POST['password'] . $salt);
		for($round = 0; $round < 65536; $round++) {
			$password = hash('sha256', $password . $salt);
		}
	#}
	#else {
	#	// If the user did not enter a new password we will not update their old one.
	#	$password = null;
	#	$salt = null;
	#}
	// Initial query parameter values
	$query_params = array(
		':firstname' => $_POST['firstname'],
		':lastname' => $_POST['lastname'],
		':email' => $_POST['email'],
		':password' => $password,
		':salt' => $salt,
		':user_id' => $old_user['id'],
	);

	// Note how this is only first half of the necessary update query.  We will dynamically
	// construct the rest of it depending on whether or not the user is changing
	// their password.
	$query = "UPDATE users SET
			firstname = :firstname,
			lastname = :lastname,
			email = :email,
			password = :password,
			salt = :salt";

	// Finally we finish the update query by specifying that we only wish
	// to update the one record with for the current user.
	$query .= " WHERE id = :user_id";

	try {
		// Execute the query
		$stmt = $db->prepare($query);
		$result = $stmt->execute($query_params);
	}
	catch(PDOException $ex) {
		// Note: On a production website, you should not output $ex->getMessage().
		// It may provide an attacker with helpful information about your code.
		die("Failed to run update query: " . $ex->getMessage());
	}


	## Get old user
	$query = "SELECT * FROM users WHERE email = :email";
	$query_params = array(':email' => $_POST['email']);
	try {
		// Execute the query
		$stmt = $db->prepare($query);
		$result = $stmt->execute($query_params);
	}
	catch(PDOException $ex) {
		// Note: On a production website, you should not output $ex->getMessage().
		// It may provide an attacker with helpful information about your code.
		die("Failed to run new user query: " . $ex->getMessage());
	}
	$row = $stmt->fetch();
	$new_user = $row;

	$oldemail = $_SESSION['user']['email'];
	$newemail = $new_user['email'];

	system("mv tmp/$oldemail tmp/$newemail");

	// Now that the user's E-Mail address has changed, the data stored in the $_SESSION
	// array is stale; we need to update it so that it is accurate.
	$_SESSION['user'] = $new_user;

	// This redirects the user back to the members-only page after they register
	#header("Location: private.php");

	// Calling die or exit after performing a redirect using the header function
	// is critical.  The rest of your PHP script will continue to execute and
	// will be sent to the user if you do not die or exit.
	##die("Redirecting to private.php");
}

?>

<section class="content-header">
	<h1>Edit Profile</h1>
	<ol class="breadcrumb">
		<li><a href="index.php"><i class="fa fa-home"></i> Home</a></li>
		<li><a href="tools.php"> Profile</a></li>
		<li class="active">Edit</li>
	</ol>
</section>

<section class="content">
	<div class="row">

		<div class="col-md-6">
			<div class="box box-warning">
				<div class="box-header">
					<h3 class="box-title">Profile</h3>
				</div>
				<div class="box-body">

<form action="profile.php" method="post">
<p>
First Name:<br />
<input type="text" name="firstname" value="<?php echo htmlentities($_SESSION['user']['firstname'], ENT_QUOTES, 'UTF-8'); ?>" />
</p>

<p>Last Name:<br />
<input type="text" name="lastname" value="<?php echo htmlentities($_SESSION['user']['lastname'], ENT_QUOTES, 'UTF-8'); ?>" />
</p>

<p>
Username:<br />
<input type="text" name="username" disabled value="<?php echo htmlentities($_SESSION['user']['username'], ENT_QUOTES, 'UTF-8'); ?>" />
</p>

<p>
E-Mail:<br />
<input type="text" name="email" value="<?php echo htmlentities($_SESSION['user']['email'], ENT_QUOTES, 'UTF-8'); ?>" />
</p>

<p>
Password:<br />
<input type="password" name="password" value="" />
</p>

<p>
Confirm Password:<br />
<input type="password" name="password_confirmation" value="" />
</p>
				</div> <!-- box-body -->
				<div class="box-footer">
<p>
<input type="submit" value="Update" class="btn btn-success"/>
<a href="index.php" class="btn btn-danger">Cancel</a>
</form>

				</div> <!-- box-body -->
			</div> <!--  box -->
		</div><!-- col -->

	</div><!-- row -->
</section>

<?php require ('template/footer.php'); ?>