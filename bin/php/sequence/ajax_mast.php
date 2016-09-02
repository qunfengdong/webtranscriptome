<?php
require ("../mysql/database_connect.php");

$up = $_GET['up'];
$id = $_GET['id'];
$cb = $_GET['cb'];

$ids = explode("\n", $id);
#$cbs = explode("\n", $cb);
#echo $ids[1];

$exp = array();

foreach($cb as $cbs){
	array_push($exp, " name like \"%$cbs%\" ");
}

#$exp_list = implode(" OR ", $exp);
#echo $exp_list . "<br>";

$query = "SELECT pro.*, gff.* from chipseq_peaks as pro, gff where gff.gene_id in ('". implode("', '", $ids) ."') AND pro.start >= (gff.start + 500) AND pro.end >= (gff.end  + 500) order by gff.gene_id";
echo $query;
exit;

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
<button class="btn btn-primary" round" onclick="getMast()">Motif scanning using MAST</button>
<button class="btn btn-primary" round" onclick="getMeme()">Motif Discovery using Meme</button>
<span id="spinner" style="display: none"><img src="/img/ajax-loader1.gif"></span>
<span id="motif_result"></span>
<br><br>
<input type=hidden id=textarea_ids value="'.$id.'">
<table class="table table-bordered" style"width: 500px">
	<tr>
		<th><input type=checkbox onchange="check_uncheck()" id="checkbox_checkall" ></th>
		<th>Peak Info</th>
		<th>Peak Sequence</th>
	<tr>
		';

foreach($result as $row){
	$html .= "<tr>";
	$html .= '<td valign="top"><input type=checkbox value="'. $row['name'] .'" name=selected_ids onchange=checkbox_checkall_uncheck() checked=checked></td>';
	$html .= '<td valign="top"><span class="small text-muted">Gene ID:</span> ' . link_jbrowse($row['id'], $row['chr'], $row['start'], $row['end']) . '<br />';
	$html .= '<span class="small text-muted">Chromosome:</span> ' . $row['chr'] . '<br />';
	$html .= '<span class="small text-muted">Peak position:</span> ' . $row['start'] .'...'. $row['end'] . '<br />';
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
