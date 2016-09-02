<?php

$dir = getcwd();
$par = dirname($dir);
$path = $par . "/tmp/genelist/";

$oldname = $_GET['oldname'];
$newname = $_GET['newname'];
$list = $_GET['list'];
$list_arr = explode("\n", $list);

#echo $oldname,"<br>";
#echo $newname,"<br>";
#echo $list,"<br>";

try{
	include ("../mysql/database_connect.php");

	$oldname = $_SESSION['user']['id'] .".". $oldname;
	$newname = $_SESSION['user']['id'] .".". $newname;

	#echo $oldname,"<br>";
	#echo $newname,"<br>";

	unlink($path.$oldname);
	$file = $path . $newname;
	file_put_contents($file, $list);

	$query = "UPDATE mygenelist SET listname = :newname, created = NOW() WHERE userid = :userid AND listname = :oldname";
	$query_params = array(':newname' =>  $newname, ':oldname' => $oldname, ':userid' => $_SESSION['user']['id']);

	$stmt = $db->prepare($query);
	$result = $stmt->execute($query_params);
}
catch (Exception $e){
	echo json_encode("<span style='color:red; font-weight: bold'>ERROR: ". $e->getMessage() ."</span><br>");
}

header("Location: /mydata/");

?>
