<?php
require ("../mysql/database_connect.php");
require ('../settings/errordiv.php');

$gene = $_GET['gene'];

$query = "SELECT * from cluster where gene_id like :gene";
$query_params = array(':gene' => $gene);
try {
	$stmt = $db->prepare($query);
	$result = $stmt->execute($query_params);
	#$result = $stmt->execute();
}
catch(PDOException $ex) {
	echo errordiv("Failed to run query: " . $ex->getMessage());
	exit;
}

$result = $stmt->fetch();
$cluster = $result['cluster'];

if($cluster == ''){
	echo warningdiv('No entries found.');
	exit;
}

$query = "SELECT cluster.gene_id, cluster.cluster, gene_descriptions.description
FROM cluster left join gene_descriptions on cluster.gene_id = gene_descriptions.gene_id
WHERE cluster = :cluster
ORDER by description desc";

$query_params = array(':cluster' => $cluster);
try {
	$stmt = $db->prepare($query);
	$result = $stmt->execute($query_params);
	#$result = $stmt->execute();
}
catch(PDOException $ex) {
	echo errordiv("Failed to run query: " . $ex->getMessage());
	exit;
}

$result = $stmt->fetchAll();
$total = sizeof($result);

$html = "$gene belongs to cluster $cluster <br>";
$html .= "$total genes belong to cluster $cluster <br>";

$html .= '
		<br>
		<form method="POST" action="profile_display.php">
		<input type="submit" value="Profile Display" class="btn btn-primary">
	<table class="table table-bordered">
	<tr>
		<th></th>
		<th>Gene Id</th>
		<th>Gene Description</th>
		<th>Cluster</th>
		';


$html .= $table;
$html .= '</tr>';

foreach($result as $row){
	$html .= '<tr>';
	$html .= '<td><input type="checkbox" name="gene_id_list[]" value="'. $row['gene_id'] .'" /></td>';
	$html .= '<td><a href="http://maizegdb.org/cgi-bin/displaygenemodelrecord.cgi?id=' . $row['gene_id'] . '" target="_blank">' . $row['gene_id'] . '</a></td>';
	if(empty($row['description'])){
		$html .= "<td>NA</td>";
	} else {
		$html .= "<td>". $row['description'] . "</td>";
	}
	$html .= '<td>' . $row['cluster'] . '</td>';
	$html .= '</tr>';
}

$html .= '</table>
		</form>
';

$divstart = '<div class="box box-success"><div class="box-body">';
$divend = '</div></div>';
$html = $divstart . $html . $divend;

echo json_encode($html);

?>
