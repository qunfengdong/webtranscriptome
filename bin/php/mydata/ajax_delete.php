<?php
$filename = $_GET['file'];

$dir = getcwd();
$par = dirname($dir);
$path = $par . "/tmp/genelist/";

$file = $path.$filename;

include ("../mysql/database_connect.php");
$query = "DELETE FROM mygenelist where userid = ". $_SESSION['user']['id'] ."  AND listname like \"" . $filename ."\"";

#echo $query . "<br>";

try {
	$stmt = $db->prepare($query);
	$result = $stmt->execute();
}
catch(PDOException $ex) {
	echo(json_encode("Failed to run query: " . $ex->getMessage()));
	die;
}

try {
	unlink($file);
}
catch(PDOException $ex) {
	echo(json_encode("Failed to delete file: " . $ex->getMessage()));
	die;
}

#header("Location: /mydata/");

echo json_encode("good");

?>