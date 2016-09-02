<?php
require ("../mysql/database_connect.php");
require("memeprogram.php");

$up = $_GET['up'];
$idlist = $_GET['ids'];

$list = explode("\n", $idlist);
$i = array();
foreach($list as $l){
	#echo $l, "<br>";
	$e = explode("XXX", $l);
	#echo $e, "<br>";
	#array_push($i, $e[0]);
	$i[$e[0]] = $e[1];
}
#var_dump(array_keys($i));

#echo "<br><br>";

if(sizeof($i) < 2){
	echo json_encode("ERROR: Minimum number of ids is 2");
	exit;
}

#$query = "SELECT pro.*,gene_descriptions.description from test_promoter_$up as pro left join gene_descriptions on pro.id = gene_descriptions.id where pro.id in ('". implode("', '", $ids) ."')";
$query = "SELECT pro.* from promoter_$up as pro where pro.gene_id in ('". implode("', '", array_keys($i)) ."')";
#echo $query;
#exit;

try {
	$stmt = $db->prepare($query);
	$result = $stmt->execute();
	#$result = $stmt->execute();
}
catch(PDOException $ex) {
	die("Failed to run cluster query: " . $ex->getMessage());
}

$result = $stmt->fetchAll();
$count = $stmt->rowCount();

if($count == 0){
	$html = "<span style='color:red'>No entries found.</span><br><br>";
	echo json_encode($html);
	return;
}

$dir = getcwd();
$par = dirname($dir);

#`find /var/www/html/maize/tmp/meme -mtime +1 -type d -delete`;
#`find /var/www/html/maize/tmp/meme -mtime +1 -type f -delete`;

$id = md5(uniqid(rand(), true));

$html = '';

foreach($result as $row){
	if(in_array($row['gene_id']."XXX".$row['region'], $list)){
		$html .= ">". $row['gene_id'] ."_".$row['region']."\n";
		$html .= $row['seq'] ."\n";
	}
}

#echo $html;
#exit;

$file = $par."/tmp/meme/".$id;
$out = rtrim($file) . ".out";

file_put_contents($file, $html);
`$memeprogram -dna $file -o $out`;

$html = "<a href='/tmp/meme/$id.out/meme.html' target='_blank'><button class=\"btn btn-success\">Meme Output</button></a>";
#echo "<br>a href='/tmp/meme/$id.out/meme.html'</br>";


echo json_encode($html);

?>
