<?php 
require ("../mysql/database_connect.php");

$chr = $_GET['chr'];
$start = $_GET['start'];
$end = $_GET['end'];
$genus = $_GET['genus'];

#echo var_dump($genus);
#echo "<br>";

$string = '';
if(is_array($list)){
	$st = "'" . implode("','", $list) . "'";
	$string = " gff.gene_id in ($st) ";
} else {
	$string = " gff.gene_id IN ($list) ";
}

$query = "select distinct gff.gene_id as id, gene_descriptions.description";

include ("../settings/ortholog.php");

foreach ($ortholog as $gen){
	$query .= ", ortholog.". $gen;
	$query .= ", ortholog.". $gen ."_desc";
}


$query .= " FROM gff
LEFT JOIN gene_descriptions
ON gff.gene_id = gene_descriptions.gene_id
LEFT JOIN ortholog
ON gff.gene_id = ortholog.gene_id
WHERE
chr like $chr
AND start >= $start
AND end <= $end
";

#$query_params = array(':chr' => $chr, ':start' => $start, ':end' => $end);

#echo $query;

#exit;

try {
	$stmt = $db->prepare($query);
	$result = $stmt->execute();
}
catch(PDOException $ex) {
	die("Failed to run query: " . $ex->getMessage());
}
	

$result = $stmt->fetchAll();
$total = sizeof($result);

$myhash = array();
foreach($result as $row){
	if(! array_key_exists($row['id'], $myhash)){
		$myhash[$row['id']] = array();
		foreach ($genus as $gen){
			$gdesc = $gen."_desc";
			$myhash[$row['id']][$gen] = array();
		}
	}
	#echo "<span style='color:red'>".var_dump($myhash), "</span><br>________<br>";
	$myhash[$row['id']]['description'] = $row['description'];

	#echo "<span style='color:red'>".var_dump($myhash[$row['id']]['ortholog']), "</span><br>________<br>";
	foreach ($genus as $gen){
		$gdesc = $gen."_desc";
		$at = $row[$gen] ." <span style='color:#800000'>". $row[$gdesc] ."</span>";
		if ($row[$gen] != NULL){
			if(! in_array($at, $myhash[$row['id']][$gen])){
				array_push($myhash[$row['id']][$gen], $at);
			}
		}
	}
	#echo var_dump($myhash), "<br>________<br>";
	#echo "<br>===========================================<br>";
}

$html = "
	<input type='hidden' name=list value='". $string ."' >
";


if($total == 0){
	$html .=  "<span>". $total . " entries present. </span><br>";
	echo json_encode($html);
	exit;
}

$check = "
	<span>Select additional Orthologs</span><br>
	<table class='table table-bordered'>
	<tr>";

foreach ($ortholog as $gen){
	$check .= "<td><label style='font-style:italic'><input type='checkbox' name='orthologlist' value='$gen' ". checking($gen, $ortholog) ."> $gen</label></td>";
}

$check .= "
	</tr>
	</table><br>
	<button class=\"btn btn-primary\" onclick='get_ortholog_table_only()'>Orthologs</button>
	<br><br>
";

#$html =  "<span>". $total . " entries present. </span><br>";

#$html .= '&nbsp;<button class="large button teal round" onclick="filter_chipseq()">Change Interval</button><br>';
#$html .= '<input type="hidden" name="list" value="'. $string .'">';

#$html .= $check;

$html .=  "<div id='ortholog_table' style='overflow: auto'><span>". count($myhash) . " entries present. </span><br><br>";

$html .= '<table class="table table-bordered">';

$html .= '<thead>';
$html .= '<tr>';
$html .= "<th title='Maize gene id'>Maize_gene_id</th>";
$html .= '<th title="Gene description">Gene_description</th>';

foreach ($ortholog as $gen){
	$html .= '<th><span style="font-style:italic">' . $gen . '</span></th>';
}

$html .= '</tr>';
$html .= '</thead>';
$html .= '<tbody>';


#foreach($myhash as $i => $id){
#	$test .= "<tr>";
#	$test .= "<td>". $i ."</td>";
#	if(empty($myhash[$i]['description'])){
#		$test .= "<td>NA</td>";
#	} else {
#		$test .= "<td>". $myhash[$i]['description'] . "</td>";
#	}
#	foreach ($ortholog as $gen){
#		$gdesc = $gen."_desc";
#		$arr = $myhash[$i][$gen];
#		$test .= '<td>'. implode($arr, "<br>") .'</td>';
#	}
#	$test .= "</tr>";
#}

#$html .= $test;

foreach($result as $row){
	#$html .= "<td><a href='http://www.maizeinflorescence.org/jbrowse/index.html?data=maize&loc=chr".$row['chr'].":".$row['genestart']."..".$row['geneend']."' target='_blank' style='color: teal'>". $row['id']. "<a></td>";
	$html .= "<td>". $row['id']. "</td>";
	if(empty($row['description'])){
		$html .= "<td>NA</td>";
	} else {
		$html .= "<td>". $row['description'] . "</td>";
	}
	foreach ($ortholog as $gen){
		$gdesc = $gen."_desc";
		$html .= "<td>".$row[$gen]."<br>";
		$html .= $row[$gdesc] .'</td>';
	}
	
	$html .= "</tr>";
}

$html .= '</tbody></table></div><br>';

#echo $html;

echo json_encode($html);
#echo json_encode($div_start . $html . $div_end);

function checking($ele, $arr){
	if(in_array($ele, $arr)){
		return "checked='checked'";
	} else {
		return "";
	}
}

#echo $result;

?>