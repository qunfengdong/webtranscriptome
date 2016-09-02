<?php

$dir = getcwd();
$par = dirname($dir);
$path = $par."/tmp/genelist/";

$list = $_GET['list'];
$arr = explode("\n", $list);
$count = sizeof($arr);

if($count > 24){
	$html = "<span>ERROR: The number of ids should be atleast 25. You submitted only $count ids.</span>";
	echo json_encode($html);
	exit;
}

try{

	$filename = generateRandomString();
	$file = $path . $filename ;
	if(file_exists($file)){
		$file .= "_". date('Ymd_His');
		$filename .= "_". date('Ymd_His');
	}
	#echo $program;

	$fp = fopen($file, 'w');
	$line_count = 100;
	#echo "verifying: $file<br>";
	foreach($arr as $id) {
		#echo "$line<br>";
		if (!preg_match("/^[A-Z0-9_.]*$/", $id)){
			throw new Exception("Line($line) is not in correct format");
		}
		$line_count--;
		if($line_count < 0){
			throw new Exception("List is longer then 100");
		}
		fwrite($fp, "$id\n");
	}
	fclose($fp);
}
catch (Exception $e){
	echo json_encode("<span style='color:red; font-weight: bold'>ERROR: ". $e->getMessage() ."</span><br>");
}

echo json_encode($filename);

#header("Location: /mydata.php");
function generateRandomString($length = 10) {
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, strlen($characters) - 1)];
	}
	return $randomString;
}

?>
