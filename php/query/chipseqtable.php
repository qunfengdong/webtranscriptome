<?php
require ("../mysql/database_connect.php");
require ('../settings/errordiv.php');

$chr = $_GET['chr'];
$start = $_GET['start'];
$end = $_GET['end'];
$user = $_GET['user'];

$query = "SELECT gff.gene_id as id
	FROM gff
	WHERE chr like :chr
	AND start >= :start
	AND end <= :end
	ORDER BY gff.gene_id
";

$query_params = array(':chr' => $chr, ':start' => $start, ':end' => $end);

try {
	$stmt = $db->prepare($query);
	$result = $stmt->execute($query_params);
}
catch(PDOException $ex) {
	echo callouterrorjson("Failed to run query: " . $ex->getMessage());
	exit;
}

$list = array();
$result = $stmt->fetchAll();
foreach ($result as $row){
	array_push($list, $row['id']);
}

$string = "'" . implode("','", $list) . "'";

$offset = $_GET['offset'];
if($offset == ''){
	$offset = 3000;
}

$query = "select gff.gene_id as id,
gff.chr,
gff.start as genestart,
gff.end as geneend,
gff.length,
chipseq.start,
chipseq.end,
chipseq.len,
chipseq.summit,
chipseq.tags,
chipseq.pvalue,
chipseq.foldenrichment,
chipseq.fdr,
(chipseq.summit - gff.start) as diff
FROM chipseq, gff
WHERE chipseq.chr = gff.chr
AND (chipseq.summit + chipseq.start) >= (gff.start - $offset)
AND (chipseq.summit + chipseq.start) <= (gff.end + $offset)
";

if(is_array($list)){
	$query .= " AND gff.gene_id in ($string) ";
} else {
	$query .= " AND gff.gene_id IN ($list) ";
}

$query .= "
ORDER BY id,diff";

#echo "$query<br>";

try {
	$stmt = $db->prepare($query);
	$result = $stmt->execute();
}
catch(PDOException $ex) {
	echo callouterrorjson("Failed to run query: " . $ex->getMessage());
	exit;
}

$result = $stmt->fetchAll();
$total = sizeof($result);

$html = "";

if($total == 0){
	$html .=  "0 entries present.";
	echo calloutwarningjson($html);
	exit;
}

$html =  "<span>". $total . " entries present. </span><br>";

if($_GET['offset'] == ''){
	$html .= "<input type='text' name='chipseqoffset' value='$offset'>";
	$html .= '&nbsp;<button class="btn btn-warning" onclick="filter_chipseq()">Change Interval</button><br>';
	$html .= '<input type="hidden" name="chipseqstring" value="'. $string .'">';
	$html .= '<table class="table table-bordered" id="ChIPseqTable">';
} else {
	$html .= '<br>Interval: &#xb1;' . $offset;
	$html .= '<table class="table table-bordered" id="'. $offset .'">';
}

$html .= '<thead>';
$html .= '<tr>';
#$html .= '<th><input type="checkbox" onclick=\'chipseq_selectall(this, "chipgenelist[]")\'>';
$html .= '<th title="Maize gene id">Id</th>';
$html .= '<th title="Gene start position">Gene_start</th>';
$html .= '<th title="Gene end position">Gene_end</th>';
$html .= '<th title="Peak end position">Difference</th>';
$html .= '<th title="Peak end position">Peak_start</th>';
$html .= '<th title="Peak end position">Peak_end</th>';
$html .= '<th title="Peak name">Peak_length</th>';
$html .= '<th title="Peak start position">Peak_summit</th>';
$html .= '<th title="Peak end position">tags</th>';
$html .= '<th title="Peak end position">pvalue</th>';
$html .= '<th title="Peak end position">foldenrichment</th>';
$html .= '<th title="Peak end position">fdr</th>';
#$html .= '<th title="Peak summit relative to peak start">Peak_summit</th>';
#$html .= '<th title="Peak length">Peak_length</th>';
#$html .= '<th title="Position difference between peak summit and gene start">Summit_difference</th>';
$html .= '</tr>';
$html .= '</thead>';
$html .= '<tbody>';

$pre = "";
$cl = "d0";

foreach($result as $row){
	if($pre != $row['id']){
		$cl = ($cl == "d0") ? "d1" : "d0";
		$pre = $row['id'];
	}
	$html .= '<style type="text/css">
tr.d0 td {
	background-color: #E0FFFF; color: black;
	border: 1px solid black;
}
tr.d1 td {
	background-color: #FFFFF0; color: black;
	border: 1px solid black;
}
</style>
			<tr class="'. $cl .'">';
	#$html .= "<td><input name='chipgenelist[]' type='checkbox' value='". $row['id'] ."'></td>";
	#$html .= "<td><a href='http://www.maizeinflorescence.org/jbrowse/index.html?data=maize&loc=chr".$row['chr'].":".$row['genestart']."..".$row['geneend']."&tracks=GFF%2Cchipseq_ear1_MACS_wiggle%2Cchipseq_tassel1_MACS_wiggle%2Cchipseq_tassel23_combine_MACS_wiggle' target='_blank' style='color: teal'>". $row['id']. "<a></td>";
	$html .= "<td>".link_jbrowse($row['id'], $row['chr'], $row['start'], $row['end'])."</td>";
	$html .= "<td>". $row['genestart']. "</td>";
	$html .= "<td>". $row['geneend']. "</td>";
	$html .= "<td>". $row['diff']. "</td>";
	$html .= "<td>". $row['start']. "</td>";
	$html .= "<td>". $row['end']. "</td>";
	$html .= "<td>". $row['len']. "</td>";
	$html .= "<td>". $row['summit']. "</td>";
	$html .= "<td>". $row['tags']. "</td>";
	$html .= "<td>". $row['pvalue']. "</td>";
	$html .= "<td>". $row['foldenrichment']. "</td>";
	$html .= "<td>". $row['fdr']. "</td>";
	$html .= "</tr>";
}

$html .= '</tbody></table><br>';
#$html .= '<button class="large button teal round" onclick="get_chipseq_rnaseq(\'chipgenelist[]\')">RNAseq</button><br><br>';
#$html .= '<span id="rnaseq_table_output" style="display:none; color: green">RNAseq output in the new tab</span><br>';



echo json_encode($div_start . $html . $div_end);

#echo $result;
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