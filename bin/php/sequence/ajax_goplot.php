<?php

$name = $_GET['id'];

require("../settings/goatools.php");

#echo $name;

$dir = getcwd();
$par = dirname($dir);

$goatools = $par . "/tmp/goatools";
$genelist = $par . "/tmp/genelist";
$data = $par;

$obo = $data."/tmp/goatools/gene_ontology.1_2.obo";
$basicobo = $data."/tmp/goatools/go-basic.obo";

#`find find /var/www/html/maize/tmp/meme/. -mtime +5 -exec rm {} \;`
#`find $goatools/. -mtime +1 -type f -delete`;

$id = md5(uniqid(rand(), true));

$output = "$goatools/$id";
$sample =  "$genelist/$name";
$population = "$data/$gopopulation";
$association = "$data/$goassociation";

#echo ("$output<br>");
#echo ("$sample<br>");
#echo ("$population<br>");
#echo ("$association<br>");

if(! file_exists($obo)){
	#echo "cp $data/data/gene_ontology.1_2.obo $obo <br>";
	`cp $data/data/gene_ontology.1_2.obo $obo`;
}

if(! file_exists($basicobo)){
	#echo "cp $data/data/go-basic.obo $basicobo <br>";
	`cp $data/data/go-basic.obo $basicobo`;
}


try{
	chdir($goatools);
	#echo "$goatools<br>";
	`plot_go_term.py --term=$name ; cp GO_lineage.png $id`;
	#echo "plot_go_term.py --term=$name ; cp GO_lineage.png $id <br>";
}
catch(PDOException $ex) {
	die("Failed to run cluster query: " . $ex->getMessage());
}

?>
<img src="<?php echo "/tmp/goatools/$id" ?>">
