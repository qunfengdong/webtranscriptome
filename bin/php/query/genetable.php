<?php
require ("../mysql/database_connect.php");
require("../settings/descriptions.php");

$chr = $_GET['chr'];
$start = $_GET['start'];
$end = $_GET['end'];
$user = $_GET['user'];

#echo '()';

$query = "SELECT gff.gene_id as id, gff.chr, gff.start, gff.end";

if($available_description){
	$query .= ',gene_descriptions.description ';
	$query .= " FROM gff left join gene_descriptions ON gff.gene_id = gene_descriptions.gene_id Where ";
}
else {
	$query .= ',gene_descriptions.description ';
	$query .= " FROM gff WHERE ";
}

$query .= "chr like :chr
		AND start >= :start
		AND end <= :end
		ORDER BY gff.gene_id
		";

#$query_params = array(':chr' => $_POST['chr'], ':start' => $_POST['start'], ':end' => $_POST['end']);
$query_params = array(':chr' => $chr, ':start' => $start, ':end' => $end);

try {
	$stmt = $db->prepare($query);
	$result = $stmt->execute($query_params);
	#$result = $stmt->execute();
}
catch(PDOException $ex) {
	die("Failed to run query: " . $ex->getMessage());
}

$result = $stmt->fetchAll();
$total = sizeof($result);

#$html =  "<span>". $total . " entries present. </span><br><br>";


if($total == 0){
	$html =  $div_start. "<span>". $total . " entries present. </span>". $div_end;
	echo json_encode($html);
	exit;
}

if($total > 500){
	$html =  $div_start. "<span>". $total . " entries present. Too large to be listed here.</span>". $div_end;
	echo json_encode($html);
	exit;
}

$div_start = "Genes (Chr $chr:$start..$end)";

$html = '';
#$html .= '<form method="POST" action="/profile_display.php">
$html .= '<table class="table table-bordered">';
$html .= '<tr>';
$html .= '<th><input type="checkbox" onclick=\'genelist_selectall(this, "genelist[]")\'></th>';
$html .= '<th>Location</th>';
$html .= '<th>Gene id</th>';
if($available_description){
	$html .= '<th>Gene description</th>';
}
#$html .= '<th>Ortholog</th>';
$html .= '</tr>';

$myhash = array();
foreach($result as $row){
	if(! array_key_exists($row['id'], $myhash)){
		$myhash[$row['id']] = array();
		$myhash[$row['id']]['ortholog'] = array();
	}
	$myhash[$row['id']]['location'] = array($row['id'], $row['chr'], $row['start'],$row['end']);
	$myhash[$row['id']]['description'] = $row['description'];

}

foreach($myhash as $i => $id){
	$test .= "<tr>";
	$test .= "<td><input name='genelist[]' type='checkbox' value='". $i ."' checked='checked'></td>";
	$test .= "<td>chr". $myhash[$i]['location'][1] .":".$myhash[$i]['location'][2]."..".$myhash[$i]['location'][3]."</td>";
	#$test .= "<td><a href='http://www.maizeinflorescence.org/jbrowse/index.html?data=maize&loc=chr". $myhash[$i]['location'] ."' target='_blank' style='color: teal'>". $i . "</a></td>";
	$test .= "<td>".link_jbrowse($myhash[$i]['location'][0], $myhash[$i]['location'][1], $myhash[$i]['location'][2], $myhash[$i]['location'][3])."</td>";
	if($available_description){
		if(empty($myhash[$i]['description'])){
			$test .= "<td>NA</td>";
		} else {
			$test .= "<td>". $myhash[$i]['description'] . "</td>";
		}
	}
	$test .= "</td></tr>";
}
#echo $test;

$ent =  "<span>". count($myhash) . " entries present. </span><br><br>";
$html = $div_start . $ent . $html . $test;

$html .= '</table>';

if(empty($_SESSION['user'])) {
	$html .= '<div class="col-sm-3 col-lg-2"><button class="btn btn-primary btn-block" title="For registered users only" disabled>Save list</button></div>';
}
else {
	$html .= '<div class="col-sm-3 col-lg-2"><button class="btn btn-primary btn-block" onclick="get_save_list()">Save list</button></div>';
}

$html .= '
		<span id="spinner" style="display: none"><img src="/img/ajax-loader1.gif"></span>
		';

echo json_encode($html);

#echo $result;

function link_jbrowse($id, $chr, $start, $end){
	$html .= '<a href="#">' . $id . '</a>';
	return $html;
}
?>
