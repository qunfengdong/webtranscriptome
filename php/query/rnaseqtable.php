<?php
require ("../mysql/database_connect.php");

$chr = $_GET['chr'];
$start = $_GET['start'];
$end = $_GET['end'];
$user = $_GET['user'];

$query = "SELECT gff.gene_id as id, gff.chr, gff.start, gff.end,
	rnaseq.gene, rnaseq.locus, rnaseq.sample_1, rnaseq.sample_2, rnaseq.status, rnaseq.value_1, rnaseq.value_2, rnaseq.l2fc, rnaseq.test_stat, rnaseq.p_value, rnaseq.q_value, rnaseq.significant
	FROM gff, rnaseq
	WHERE chr like :chr
	AND start >= :start
	AND end <= :end
	AND gff.gene_id = rnaseq.gene_id
	AND rnaseq.significant like 'yes'
	ORDER BY gff.gene_id
";

$query_params = array(':chr' => $chr, ':start' => $start, ':end' => $end);

try {
	$stmt = $db->prepare($query);
	$result = $stmt->execute($query_params);
}
catch(PDOException $ex) {
	die("Failed to run query: " . $ex->getMessage());
}

$result = $stmt->fetchAll();
$total = sizeof($result);

#echo "($total) <br><br>";
$div_end = "
</div><!-- close box-body -->
</div><!-- close box-success -->
</div><!-- close col-md-12 -->
</div><!-- close row -->
";


$div_start = 'RNAseq ('. sizeof($list).' genes)';

if($total == 0){
	#$div_start = tab_header("RNAseq (". sizeof($list)." genes) (Filter: $series_info[0]: $series_info[1] vs $series_info[2])" );
	$html =  "<span>". $total . " entries present. </span><br>";
	echo json_encode($html);
	exit;
}

#if(($first == '') && ($second == '')){
if($series == ''){
	$html =  "<span>". $total . " entries present. </span><br>";
	$html .= '<table class="table table-bordered">';
}
#elseif (($first != '') && ($second != '')) {
elseif ($series != '') {
	$html =  "<span>". $total . " entries present. </span><br><br>";
	#$html .= "Series: $first<br>";
	$html .= "Series: <strong> $series_info[0] </strong><br>";
	#$html .= "Comparison: $second<br>";
	$html .= "Comparison: <strong>$series_info[1] vs $series_info[2] </strong><br>";
	$html .= '<table class="table table-bordered" id="'.$first.'">';
	$div_start = tab_header("RNAseq (". sizeof($list)." genes) (Filter: $series_info[0]: $series_info[1] vs $series_info[2])" );
}

#$html .= '<thead>';
$html .= '<tr>';
#$html .= '<th><input type="checkbox" onclick=\'genelist_selectall(this, "rnagenelist[]")\'></th>';
$html .= '<th title="Gene id">Gene id</th>';
$html .= '<th title="">Gene</th>';
$html .= '<th>Locus</th>';
$html .= '<th title="subject 1 in comparison">sample_1</th>';
$html .= '<th title="subject 2 in comparison">sample_2</th>';
$html .= '<th title="whether the gene was tested in differential expression analysis - requires at least 50 reads mapping to the locus">test_status</th>';
$html .= '<th title="log fold change">log2(fold_change)</th>';
$html .= '<th title="corrected p-value">p_value</th>';
$html .= '<th title="corrected p-value">q_value</th>';
$html .= '<th title="whether the gene was called significant based on qval < 0.05 and 10% FDR">significant</th>';
$html .= '</tr>';
#$html .= '</thead>';
#$html .= '<tbody>';

$pre = "";
$cl = "d0";

foreach($result as $row){
	if($pre != $row['id']){
		$cl = ($cl == "d0") ? "d1" : "d0";
		$pre = $row['id'];
	}
	$html .= '
<style type="text/css">
tr.d0 td {
	background-color: #E0FFFF; color: black;
}
tr.d1 td {
	background-color: #FFFFF0; color: black;
}
</style>
			<tr class="'. $cl .'">';
	#$html .= "<td><input name='rnagenelist[]' type='checkbox' value='". $row['id'] ."'><br>";
	#$html .= "<td><a href='http://www.maizeinflorescence.org/jbrowse/index.html?data=maize&loc=chr".$row['chr'].":".$row['start']."..".$row['end']."' target='_blank' style='color: teal'>". $row['id']. "</a></td>";
	$html .= "<td>".link_jbrowse($row['id'], $row['chr'], $row['start'], $row['end'])."</td>";
	$html .= "<td>". $row['gene']. "</td>";
	$html .= "<td>". $row['locus']. "</td>";
	$html .= "<td>". $row['sample_1']. "</td>";
	$html .= "<td>". $row['sample_2']. "</td>";
	$html .= "<td>". $row['test_stat']. "</td>";
	$html .= "<td>". $row['l2fc']. "</td>";
	$html .= "<td>". $row['p_value']. "</td>";
	$html .= "<td>". $row['q_value']. "</td>";
	$html .= "<td>". $row['significant']. "</td>";
	$html .= "</tr>";
}

$html .= '</table>';

echo json_encode($html);

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