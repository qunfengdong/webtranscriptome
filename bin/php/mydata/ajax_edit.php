<?php

$filename = $_GET['file'];

$dir = getcwd();
$par = dirname($dir);
$path = $par . "/tmp/genelist/";

$file = $path.$filename;
$html = "<form action='/mydata/ajax_update.php' method='get'>
Name of the file:<br>
<input type='text' name='newname' value='". preg_replace("/^[0-9]+\./", "", $filename) ."'>
<input type='hidden' name='oldname' value='". preg_replace("/^[0-9]+\./", "", $filename) ."'>
<br><br>
File contents:<br>
<textarea name='list'>";

$fp = fopen($file, 'r');
$list = array();

while (!feof($fp)) {
	array_push($list, trim(fgets($fp)));
}

$html .= implode("\n", $list). "</textarea><br><input type='submit' value='Update' class='btn btn-success'>
</form>
";
#echo $string;

echo json_encode($html);

?>
