<?php
require ("../mysql/database_connect.php");
require ('../settings/errordiv.php');

$list = $_GET['list'];
$arr = explode("\n", $list);
$ids = implode("|", $arr);
#echo $list;

$query = "SELECT gff.gene_id, gene_descriptions.description, gotable.godesc as go_desc
FROM gene_descriptions, gff
LEFT JOIN gotable
ON (gotable.gene_id = gff.id)
WHERE (
gene_descriptions.description RLIKE (\"$ids\")
OR gotable.godesc RLIKE (\"$ids\")
)
AND gene_descriptions.id = gff.id";

$query = "SELECT gff.gene_id, gene_descriptions.description, gotable.godesc as go_desc
FROM gene_descriptions, gff, gotable
WHERE
gotable.id = gff.id AND
(
gene_descriptions.description RLIKE (\"$ids\")
OR gotable.godesc RLIKE (\"$ids\")
)
AND gene_descriptions.id = gff.gene_id";

$query = "SELECT gff.gene_id, gene_descriptions.description, gff.chr, gff.start, gff.end
FROM gene_descriptions, gff
WHERE gene_descriptions.description RLIKE (\"$ids\")
AND gene_descriptions.gene_id = gff.gene_id";

#echo "$query<br><br>";

try {
	$stmt = $db->prepare($query);
	$result = $stmt->execute();
}
catch(PDOException $ex) {
	echo errordiv("Failed to run query: " . $ex->getMessage());
	exit;
}

$result = $stmt->fetchAll();
$total = sizeof($result);

if($total == 0){
	echo warningdiv('No entries found.');
	exit;
}

$html = '<table class="table table-bordered">';
$html .= '<tr>';
$html .= '<th>Maize_gene_id</th>';
$html .= '<th>Gene_description</th>';
#$html .= '<th>GO_description</th>';
$html .= '</tr>';

foreach($result as $row){
	$html .= '<tr>';
	$html .= "<td><a href='/jbrowse/index.html?data=maize&loc=chr".$row['chr'].":".$row['start']."..".$row['end']."' target='_blank' style='color: teal'>". $row['gene_id'] .'</td>';
	if(empty($row['description'])){
		$html .= "<td>NA</td>";
	} else {
		$html .= "<td>". $row['description'] . "</td>";
	}
#	if(empty($row['go_desc'])){
#		$html .= "<td>NA</td>";
#	} else {
#		$html .= "<td>". $row['go_desc'] . "</td>";
#	}
	$html .= '</tr>';
}

$html .= '</table>';

$divstart = '<div class="box box-success"><div class="box-body">';
$divend = '</div></div>';
$html = $divstart . $html . $divend;

echo json_encode($html);

?>