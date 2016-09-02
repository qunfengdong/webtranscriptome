<?php
require ("../mysql/database_connect.php");

$name = $_GET['name'];
$list = $_GET['list'];
$pop = $_GET['pop'];
#$pop = "population.txt";
#$pop = "default";

$dir = getcwd();
$par = dirname($dir);
$genelist = $par."/tmp/genelist";

#echo $name;
#echo "<br>----------------<br>";
#echo $list;
#echo "<br>----------------<br>";

$arr_ids = array();

if($name){
	$out = `cat $genelist/$name`;
	$arr_ids = explode("\n", $out); 
} else {
	$arr_ids = explode("\n", $list);
}

#echo $arr_ids[0];
$count_list_genes = sizeof($arr_ids);
if(empty($arr_ids[-1])){
	array_pop($arr_ids);
}
#echo $count_list_genes, "<br>";

$wherein = '("'. implode('", "', $arr_ids) .'")';

if($pop != "default"){ 
	#echo $wherein;

	$data_out = `cat $genelist/$pop`;
	$data_arr_ids = explode("\n", $data_out);

	if(empty($data_arr_ids[-1])){
		array_pop($data_arr_ids);
	}

	$data_wherein = '("'. implode('", "', $data_arr_ids) .'")';
	$data_query = "create temporary table rnaseqchipseq.zzz as select * from  pathway where gene_id in $data_wherein";
	
	#echo $data_query, "<br>";
	try{
		$stmt = $db->prepare($data_query);
		$result = $stmt->execute();
		#$result = $stmt->execute($query_params);
	}
	catch(PDOException $ex) {
		die("Failed to run cluster query: " . $ex->getMessage());
	}
	
	$query = "SELECT a.gene_id, a.pathway_name, b.c
	from pathway as a
	inner join (select pathway_name, count(*) as c from rnaseqchipseq.zzz group by pathway_name) b
	on a.pathway_name = b.pathway_name
	where a.gene_id in $wherein";
	$query_params = array(':wherein' => $wherein);
}
else {

	#$query = "SELECT gene_name, pathway_name from rnaseqchipseq where gene_id in $wherein";
	$query = "SELECT a.gene_id, a.pathway_name, b.c
	from pathway as a
	inner join (select pathway_name, count(*) as c from pathway group by pathway_name) b
	on a.pathway_name = b.pathway_name
	where a.gene_id in $wherein";
	#$query_params = array(':wherein' => $wherein);
	#print($query);
}


#echo $query, "<br>";

try{
	$stmt = $db->prepare($query);
	$result = $stmt->execute();
}
catch(PDOException $ex) {
	die("Failed to run cluster query: " . $ex->getMessage());
}

$result = $stmt->fetchAll();
$total = sizeof($result);
#echo $total, "<br>";

if($total == 0){
	$html = "<div class='alert alert-danger alert-dismissable'>INFO: No Pathway information present for this list of gene ids.</div><br>";
	echo json_encode($html);
	exit;
}


$html = "<table>";

$count_pathway = array();
if($pop != "default"){
	$count_total_genes = sizeof($data_arr_ids);
} else {
	$count_total_genes = 39329;
}

foreach($result as $row){
	if(! array_key_exists($row['pathway_name'], $count_pathway)){
		$count_pathway[$row['pathway_name']] = array();
		$count_pathway[$row['pathway_name']]['list_count'] = 0;
	}
	$html .= "<tr><td>".$row['gene_name']."</td><td>".$row['pathway_name']."</td><td>".$row['c']."</td></tr>";
	$count_pathway[$row['pathway_name']]['list_count'] += 1;
	$count_pathway[$row['pathway_name']]['gene_count'] = $row['c'];
}
$html .= "</table>";

#var_dump($count_pathway);

#echo $html;
#echo "<br>----------------<br>";
$arrval = array();

foreach($count_pathway as $k => $v){
	#echo "<br>>>";
	#echo $k;
	foreach($count_pathway[$k] as $kk => $vv){
		#echo " -- ". $kk ."(".$vv.")<br>";
	}
	$ok = "http://localhost/brew/phyper.html?";
	$ok .= "mylistcount=".$count_pathway[$k]["list_count"]."&";
	$ok .= "mylisttotal=$count_list_genes&";
	$ok .= "pathwaycount=".$count_pathway[$k]["gene_count"]."&";
	$ok .= "genecount=$count_total_genes&";
	#echo("$ok<br><br>");
	$out = file_get_contents($ok);
	$count_pathway[$k]['phyper'] = $out;
	array_push($arrval, $out);
	#echo "$out<br>";
	#var_dump($arrval);
	#echo "<br>";
}

#echo "<br>----------------<br>";
#echo $ok;

#echo "<br>----------------<br>";
$ok = "http://localhost/brew/padjust.html?val=".implode(",",$arrval);
#echo $ok, "<br>";
$out = file_get_contents($ok);
#echo $out;

$arrout = explode("___", $out);
$i = 0;
foreach($count_pathway as $k => $v){
	$count_pathway[$k]['padjust'] = $arrout[$i];
	$i +=1;
}
#echo "<br>----------------<br>";


$html = '<table class="table table-bordered">
<tr>
<th>Gene ID</th>
<th>Pathway name</th>
<th>One-sided Fisher\'s exact test p-value</th>
<th>Multiple test corrected p-value</th>
</tr>
';

foreach($result as $row){
	$html .= "<tr><td>".$row['gene_id'];
	$html .= "</td><td>".$row['pathway_name'];
	$html .= "</td><td>".$count_pathway[$row['pathway_name']]['phyper'];
	$html .= "</td><td>".$count_pathway[$row['pathway_name']]['padjust'];
	$html .= "</td></tr>";
}
$html .= "</table>";

#echo $html;
#echo "<br>----------------<br>";

if($pop != "default"){
try{
	$stmt = $db->prepare("drop table rnaseqchipseq.zzz");
	#$result = $stmt->execute();
	#$result = $stmt->execute($query_params);
}
catch(PDOException $ex) {
	#die("Failed to run cluster query: " . $ex->getMessage());
}
}

$divstart = '<div class="box box-success"><div class="box-body">';
$divend = '</div></div>';
$html = $divstart . $html . $divend;

echo json_encode($html);

?>
