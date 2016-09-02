<?php
$dir = getcwd();
$par = dirname($dir);
$path = $par . "/tmp/genelist/";

$list = $_GET['list'];

if(sizeof($list) <= 0){
	echo "ok";
	echo json_encode(array("result" => "error", "msg" => "No IDs in the list"));
	exit;
}

require ("../mysql/database_connect.php");
$filename = $_SESSION['user']['id'] .".". $_GET['name'] . ".txt";

#echo $filename, "<br>";

$query = "SELECT count(*) FROM mygenelist where userid = ". $_SESSION['user']['id'];

try {
	$stmt = $db->prepare($query);
	$result = $stmt->execute();
}
catch(PDOException $ex) {
	echo json_encode(array("result" => "error", "msg" => "Failed to run query: " . $ex->getMessage()));
}

$total = $stmt->fetchColumn();

if($total >= 10){
	echo json_encode(array("result" => "error", "msg" => "Limit reached"));
	exit;
}

$file = $path . $filename ;
if(file_exists($file)){
	$file .= "_". date('Ymd_His');
	$filename .= "_". date('Ymd_His');
}

try{
	file_put_contents($file, implode("\n", $list));
}
catch(Exception $ex) {
	echo json_encode(array("result" => "error", "msg" => "Failed to run query: " . $ex->getMessage()));
}

$query = "INSERT INTO mygenelist (listname, created, modified, userid)
				values(:listname, NOW(), NOW(), :userid)";

$query_params = array(':listname' =>  $filename, ':userid' => $_SESSION['user']['id']);

try {
	$stmt = $db->prepare($query);
	$result = $stmt->execute($query_params);
}
catch(PDOException $ex){
	echo json_encode(array("result" => "error", "msg" => "Failed to run query: " . $ex->getMessage()));
}

echo json_encode(array("result" => "success", "msg" => "Saved"));

?>
