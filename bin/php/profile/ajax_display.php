<?php 
require ("../mysql/database_connect.php");
require("../settings/descriptions.php");

$list = $_GET['list'];
$id = explode("\n", $_GET['id']);
$id = array_diff($id, array(''));
$start = $_GET['start'];
$log = $_GET['log'];

$offset = 20;
$position = $start * $offset;

$table = '';
$mysql = array();
foreach($list as $col){
	array_push($mysql, "round(rnaseq_expression.$col, 2) as $col");
	$table .= "<th>". $col ."</th>";
}

$select = "SELECT rnaseq_expression.gene_id as id, gff.chr, gff.start, gff.end, ";
if($available_description){
	$select .= 'gene_descriptions.description, ';
}
if(sizeof($mysql) == 1){
	$select .= $mysql[0];
} else {
	$select .= implode(', ', $mysql);
}

$from = " FROM rnaseq_expression ";
if($available_description){
	$from .= " LEFT JOIN gene_descriptions ON rnaseq_expression.gene_id = gene_descriptions.gene_id ";
}
$from .= " LEFT JOIN gff ON rnaseq_expression.gene_id = gff.gene_id ";

$where .= " WHERE ";

if($id[0] != ' '){
	$where .= ' ( rnaseq_expression.gene_id REGEXP ".*'. implode(".*|.*", $id).'.*" )';
}

$limit .= " limit $position, $offset";

$query = "Select count(*) $from $where";
#echo "<br><br>". $query . "<br><br>";

try {
	$stmt = $db->prepare($query);
	#$result = $stmt->execute($query_params);
	$result = $stmt->execute();
}
catch(PDOException $ex) {
	die("Failed to run query: <br>$query<br>" . $ex->getMessage());
}

$total = $stmt->fetchColumn();
#echo $total . "<br><br>";


$query = "$select $from $where $limit";
#echo "<br><br>". $query . "<br><br>";
#echo "<br><br>". $select . "<br><br>";

try {
	$stmt = $db->prepare($query);
	#$result = $stmt->execute($query_params);
	$result = $stmt->execute();
}
catch(PDOException $ex) {
	die("Failed to run query: <br>$query<br>" . $ex->getMessage());
}
$result = $stmt->fetchAll();
#$total = sizeof($result);


if($total < 1){
	#$html = "<div class='row'><div class='col-md-12'><span style='color:red'>No entries found. </span></div></div>";
	$html = diverr("ERROR", "<span style='color:red'>No entries found. </span>", "");
	echo json_encode($html);
	return;
}

#$html =  "<span>".$start ." - ". $offset ." - ".  $position ."</span><br><br>";
$list_start = $position + 1;
$list_end = (($position + $offset) < $total) ? ($position + $offset) : $total;

#$html =  "<span>Listing $list_start to $list_end of $total entries. </span><br><br>";

$header = '';

if($start < 1){
	#$html .=  "<button class=\"btn bg-olive btn-sm\" onclick=\"updatetable(0)\">&larr; Previous</button>";
	$header .=  "<button class=\"btn bg-olive btn-sm\" onclick=\"updatetable(0)\">&larr; Previous</button>";
} else {
	#$html .=  "<button class=\"btn bg-olive btn-sm\" onclick=\"updatetable(". ($start - 1) .")\">&larr; Previous</button>";
	$header .=  "<button class=\"btn bg-olive btn-sm\" onclick=\"updatetable(". ($start - 1) .")\">&larr; Previous</button>";
}

#$html .= "&nbsp;";
$header .= "&nbsp;";

if($position > ($total - $offset)){
	#$html .=  "<button class=\"btn bg-olive btn-sm\">Next &rarr;</button>";
	$header .=  "<button class=\"btn bg-olive btn-sm\">Next &rarr;</button>";
} else {
	#$html .=  "<button class=\"btn bg-olive btn-sm\" onclick=\"updatetable(". ($start + 1) .")\">Next &rarr;</button>";
	$header .=  "<button class=\"btn bg-olive btn-sm\" onclick=\"updatetable(". ($start + 1) .")\">Next &rarr;</button>";
}

$header .=  "&nbsp;&nbsp;<span>Listing $list_start to $list_end of $total entries. </span>";
$hhtml = '';

function arr_del($arr, $el){
	$a = array();
	$a[0] = $el;
	return array_diff($arr, $a);
}

if(sizeof($id) > 0){ 
	$rem = $id;
	foreach($result as $row){
		$rem = arr_del($rem, $row['id']);
	}
	if(sizeof($rem) > 0){
		$html .= "<br><br><div class='well well-sm'>No Information for: <br>";
		$html .= implode("<br>", $rem);
		$html .= "</div>";
	}
}

#echo "(",$log, ")<br><br>";
#echo "(",$id[0], ")<br><br>";
#echo "(",empty($id), ")<br><br>";

$html .= '
<table id="display_resulttable" class="table table-bordered">
	<thead>
	<tr>
		<th></th>
		<th>Gene Id</th>
		';

if($available_description){
	$html .= '<th>Gene description</th>';
}

$html .= $table . '</tr></thead><tbody>';

## If there are no ids provided
if(empty($id)){
	foreach($result as $row){
		$html .= '<tr>';
		$html .= '<th><input type="checkbox" id="geneselect" /></th>';
		$html .= '<th>'.$row['id'].'</th>';
		$html .= '<th>' . $row['description'] . '</th>';
		foreach($list as $col){
			if(($log == 1) && ($row[$col] != 0)){
				$html .= (empty($row[$col])) ? "<td>NA</td>": "<td>". log($row[$col]) ."</td>";
			} else {
				$html .= (empty($row[$col])) ? "<td>NA</td>": "<td>". $row[$col] ."</td>";
			}
		}
		$html .= '</tr>';
	}
}

## if there is a specific list of ids provided
else {
	$idslist = array();
	foreach($result as $row){
		$exp = '<tr>';
		$exp .= '<th><input type="checkbox" id="geneselect" /></th>';
		$exp .= '<th>'.$row['id'].'</th>';
		$exp .= '<th>' . $row['description'] . '</th>';
		foreach($list as $col){
			if(($log == 1) && ($row[$col] != 0)){
				$exp .= (empty($row[$col])) ? "<td>NA</td>": "<td>". log($row[$col]) ."</td>";
			} else {
				$exp .= (empty($row[$col])) ? "<td>NA</td>": "<td>". $row[$col] ."</td>";
			}
		}
		$exp .= '</tr>';
		$idslist[$row['id']] = $exp;
	}
	
	foreach($id as $geneid){
		#echo $geneid,"<br>";
		#echo $idslist[$geneid];
		$html .= $idslist[$geneid];
	}
}


$html .= '</tbody></table>';

$footer = '<button onclick="getGeneList()" class="btn btn-success">Combine graph</button>
<button onclick="getAllGeneList()" class="btn btn-success">Combine all graph</button><br><br>
<div id ="display_canvas"><br></div>
';

$divstart = '<div class="box box-success"><div class="box-body">';
$divend = '</div></div>';
$html = $divstart . $header. $html. $footer . $divend;

#echo $html;
echo json_encode($html);

?>
