<?php
require ("../mysql/database_connect.php");

$up = $_GET['up'];
$id = $_GET['id'];

$ids = explode("\n", $id);
#echo $ids[1];

#$query = "SELECT pro.*,gene_descriptions.description from test_promoter_$up as pro left join gene_descriptions on pro.id = gene_descriptions.id where pro.id in ('". implode("', '", $ids) ."')";
$query = "SELECT pro.* from promoter_$up as pro where pro.gene_id in ('". implode("', '", $ids) ."')";
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

$html = '
<button class="btn btn-primary" round" onclick="getMotifs()">Get Motifs</button>
<span id="spinner" style="display: none"><img src="/img/ajax-loader1.gif"></span>
<span id="motif_result"></span>
<br><br>
<input type=hidden id=textarea_ids value="'.$id.'">
<table class="table table-bordered" style"width: 500px">
	<tr>
		<th><input type=checkbox onchange="check_uncheck()" id="checkbox_checkall"></th>
		<th>Gene Id</th>
		<th>Gene Coordinates</th>
		<th>Region</th>
		<th>Gene Sequence</th>
	<tr>
		';

foreach($result as $row){
	$html .= "<tr>";
	$html .= '<td valign="top"><input type=checkbox value="'. $row['gene_id'] .'XXX'.$row['region'].'" name=selected_ids checked="checked" onchange=checkbox_checkall_uncheck()></td>';
	$html .= '<td valign="top">' . link_jbrowse($row['gene_id'], $row['chr'], $row['start'], $row['end']) .'</td>';
	$html .= '<td valign="top">' . $row['start'] . '..' . $row['end'] . '</td>';
	$html .= '<td valign="top">' . $row['region'] . '</td>';
	$new_string = chunk_split($row['seq']);
	$html .= '<td><pre>' . $new_string . '</pre></td>';
	$html .= '</tr>';
}

$html .= '</table>';

echo json_encode($html);

function link_jbrowse($id, $chr, $start, $end){
	$html = '<a href="';
	if (isset($_SESSION) && ($_SESSION['user']['level'] == 1)){
		$html .= $private;
	} else {
		$html .= $public;
	}
	$html .= '?data=maize&loc=chr'.$chr.':'.$start.'..'.$end.'" target="_blank">' . $id . '</a>';
	return $html;
}

?>
