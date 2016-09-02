<?php

require("../settings/goatools.php");

$name = $_GET['name'];
$pop = $_GET['pop'];

$dir = getcwd();
$par = dirname($dir);

$goatools = $par . "/tmp/goatools";
$genelist = $par . "/tmp/genelist";
$data = $par;

#echo($goatools. "<br>");
#echo($genelist. "<br>");
#echo($data. "<br>");

`find $goatools/. -mtime +1 -type f -delete`;

$id = md5(uniqid(rand(), true));

$output = "$goatools/$id";

$err = "$goatools/$id.err";
$sample =  "$genelist/$name";
if($pop == "default"){
	$population = "$data/$gopopulation";
} else {
	$population = "$genelist/$pop";
}

$association = "$data/$goassociation";
$obo = "$data/data/go-basic.obo";

try{
	#echo("/usr/local/bin/find_enrichment.py --obo $obo $sample $population $association > $output 2> $err");
	$xx = exec($goprogram . " --obo $obo $sample $population $association > $output 2> $err");
}
catch(Exception $ex) {
	echo("Failed to run cluster query: " . $ex->getMessage());
}

$o = `cat $err`;
$pattern = '/ERROR: .*/';
preg_match($pattern, $o, $matches, PREG_OFFSET_CAPTURE);
if(!empty($matches[0][0])){
	$html = "<span style='color: red'>".$matches[0][0]."</span>";
	echo json_encode($html);
	exit;
}


$count = `cat $sample | wc -l`;
$count = trim($count);
#echo "<br><br>$sample<br><br>";
#echo "<br><br>$output<br><br>";
#echo "(".trim($count).")<br>";

if($count < 24){
	$html = "<span style='color: red'>ERROR: The number of ids should be at least 25.</span>";
	echo json_encode($html);
	exit;
}


$fp = file($output);

$html = '<table class="table table-bordered">';
foreach($fp as $line){
	if(($line[0] != "G") && ($line[0] != "O")){
		#echo $line, "<br>";
		continue;
	}
	$array = explode("\t", $line);
	array_pop($array);
	array_pop($array);
	$id = array_shift($array);
	if($id == "GO"){
		$link = $id;
	} else {
		$link = "<a href='/sequence/ajax_goplot.php?id=$id' target=\"_blank\">$id</a>";
	}
	$html .= "<tr><td>$link</td><td>". implode("</td><td>", $array) ."</td></tr>";
	#$html .= "<tr><td>". implode("</td><td>", $array) ."</td></tr>";
}
$html .= '</table>';

#		The "e" in the "Enrichment" column means "enriched" - the concentration of GO term in the study group is significantly higher than those in the population. The "p" stands for "purified" - significantly lower concentration of the GO term in the study group than in the population.
#		';

#echo "<br>a href='/tmp/meme/$id.out/meme.html'</br>";

#echo "------------------<br><br><br>";

#echo $html;

$divstart = '<div class="box box-success"><div class="box-body">';
$divend = '</div></div>';
$html = $divstart . $html . $divend;

echo json_encode($html);

?>
