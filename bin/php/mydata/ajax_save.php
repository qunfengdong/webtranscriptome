<?php
$dir = getcwd();
$par = dirname($dir);
$path = $par . "/tmp/genelist/";

try{

	if(empty($_FILES["file"])){
		throw new Exception("No File uploaded");
	}

	include ("../mysql/database_connect.php");
	$query = "SELECT count(*) FROM mygenelist where userid = ". $_SESSION['user']['id'];
	#echo $query . "<br>";

	$stmt = $db->prepare($query);
	$result = $stmt->execute();
	$total = $stmt->fetchColumn();
	#echo $total . "<br>";

	if($total <= 10){
		$boolean_file_limit = true;
	} else {
		throw new Exception("File limit reached");
		$boolean_file_limit = false;
	}
	#echo $boolean_file_limit;

	$filename = $_FILES["file"]["name"];
	$file = $path . $filename ;
	if(file_exists($file)){
	$file .= "_". date('Ymd_His');
			$filename .= "_". date('Ymd_His');
	}
	#echo $program;

	if($boolean_file_limit){
		if(empty($_FILES["file"]["tmp_name"])){
			throw new Exception("No File upload.");
		}
		if(!move_uploaded_file($_FILES["file"]["tmp_name"], $file)){
			throw new Exception("Unable to save file.");
		}
	}

	$fp = fopen($file, 'r');
	$line_count = 100;
	#echo "verifying: $file<br>";
	while (!feof($fp)) {
		$line = fgets($fp);
		#echo "$line<br>";
		if (!preg_match("/^[A-Z0-9_.]*$/", $line)){
			throw new Exception("Line($line) is not in correct format");
		}
		$line_count--;
		if($line_count < 0){
			throw new Exception("List is longer then 100");
		}
	}

	$query = "INSERT INTO mygenelist (listname, created, modified, userid) values(:listname, NOW(), NOW(), :userid)";
	$query_params = array(':listname' =>  $filename, ':userid' => $_SESSION['user']['id']);

	$stmt = $db->prepare($query);
	$result = $stmt->execute($query_params);
}
catch (Exception $e){
	echo json_encode("<span style='color:red; font-weight: bold'>ERROR: ". $e->getMessage() ."</span><br>");
}

header("Location: /mydata/");

?>
