<?php 
require ("../mysql/database_connect.php");
require ('../settings/errordiv.php');

$chr = $_GET['chr'];
$start = $_GET['start'];
$end = $_GET['end'];
$user = $_GET['user'];

#echo "$string<br><br>";
#var_dump($_GET);
#echo "<br><br>";

#$query = "select *, (summit - 4335095) as diff from ra1_peaks where chr like ";
$query = "select gff.gene_id as id,
gff.chr, 
gff.start as genestart, 
gff.end as geneend,
pathway.enzyme_name,
pathway.reaction_id, 
pathway.reaction_name,
pathway.ec,
pathway.pathway_id,
pathway.pathway_name 
FROM gff
LEFT JOIN pathway on gff.gene_id = pathway.gene_id
Where gff.gene_id = pathway.gene_id
AND gff.chr = $chr
AND start >= $start
AND end <= $end";

#$query_params = array(':list' => $string);
#echo $query;

try {
	$stmt = $db->prepare($query);
	#$result = $stmt->execute($query_params);
	$result = $stmt->execute();
}
catch(PDOException $ex) {
	echo callouterrorjson("Failed to run query: " . $ex->getMessage());
	exit;
}
	
$result = $stmt->fetchAll();
$total = sizeof($result);

$html = "";

#include 'shared_functions.php';
#$div_start = tab_header("Pathways (version 2.2)");
#$div_end = tab_end();

if($total == 0){
	$html .=  "0 entries present.";
	echo calloutwarningjson($html);
	exit;
}

$html =  "<span>". $total . " entries present. </span><br>";

$pre = "";
$cl = "d0";
$html .= '<table class="table table-bordered">';
$html .= '<thead>';
$html .= '<tr>';
$html .= '<th title="Maize gene id">Maize_gene_id</th>';
#$html .= '<th title="Gene description">Gene_description</th>';
$html .= '<th title="Enzyme name">Enzyme name</th>';
$html .= '<th title="Reaction id">Reaction id</th>';
$html .= '<th title="Reaction name">Reaction name</th>';
$html .= '<th title="EC">EC</th>';
$html .= '<th title="Pathway ID">Pathway ID</th>';
$html .= '<th title="Pathway name">Pathway name</th>';
$html .= '</tr>';
$html .= '</thead>';
$html .= '<tbody>';


foreach($result as $row){
	if($pre != $row['id']){
		$cl = ($cl == "d0") ? "d1" : "d0";
		$pre = $row['id'];
	}
	$html .= '<style type="text/css">
tr.d0 td {
	background-color: #E0FFFF; color: black;
}
tr.d1 td {
	background-color: #FFFFF0; color: black;
}
</style>
			<tr class="'. $cl .'">';
	#$html .= "<td><input name='chipgenelist[]' type='checkbox' value='". $row['id'] ."'></td>";
	$html .= "<td><a href='http://www.maizeinflorescence.org/jbrowse/index.html?data=maize&loc=chr".$row['chr'].":".$row['genestart']."..".$row['geneend']."' target='_blank' style='color: teal'>". $row['id']. "<a></td>";
	
	#if(empty($row['description'])){
	#	$html .= "<td>NA</td>";
	#} else {	
	#	$html .= "<td>". $row['description'] . "</td>";
	#}
	$html .= "<td>". $row['enzyme_name']. "</td>";
	$html .= "<td><a href='http://pathway.gramene.org/MAIZE/NEW-IMAGE?type=REACTION&object=". $row['reaction_id']. "' target='_blank'>". $row['reaction_id']. "</td>";
	if(empty($row['reaction_name'])){
		$html .= "<td>NA</td>";
	} else {
		$html .= "<td>". $row['reaction_name']. "</td>";
	}
	$html .= "<td><a href='http://pathway.gramene.org/MAIZE/NEW-IMAGE?type=EC-NUMBER&object=".$row['ec']."' target='_blank'>".$row['ec']."</a></td>";
	$html .= "<td><a href='http://pathway.iplantcollaborative.org/MAIZE/new-image?object=". $row['pathway_id']."' target='_blank'>". $row['pathway_id']."</a></td>";
	$html .= "<td>". $row['pathway_name']. "</td>";
	$html .= "</tr>";
}

$html .= '</tbody></table><br>';
#$html .= '<button class="large button teal round" onclick="get_chipseq_rnaseq(\'chipgenelist[]\')">RNAseq</button><br><br>';
#$html .= '<span id="rnaseq_table_output" style="display:none; color: green">RNAseq output in the new tab</span><br>';

echo json_encode($html);

#echo $result;

?>