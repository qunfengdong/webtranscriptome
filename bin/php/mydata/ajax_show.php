<?php

$filename = $_GET['file'];

$dir = getcwd();
$par = dirname($dir);
$path = $par . "/tmp/genelist/";

$file = $path.$filename;
$html = "<pre>";

$fp = fopen($file, 'r');
$list = array();

while (!feof($fp)) {
	$line = fgets($fp);
	$html .= $line;
	#echo "$line<br>";
	if(!empty($line)){
		array_push($list, rtrim($line));
	}
}
$html .= '</pre>';

$header = '
	<div class="box box-info">
		<div class="box-header">
			<h3 class="box-title">'. preg_replace("/^[0-9]+\./", "", $filename) .'</h3>
			<!-- tools box -->
			<div class="pull-right box-tools">
       	<button class="btn btn-info btn-sm" data-widget="collapse" data-toggle="tooltip" title="Collapse" onclick="box_collapse(this)"><i class="fa fa-minus"></i></button>
				<button class="btn btn-info btn-sm" data-widget="remove" data-toggle="tooltip" title="Remove" onclick="box_close(this)"><i class="fa fa-times"></i></button>
			</div>
		</div>
	  <div class="box-body">';

$string = implode("\\n", $list);
$string1 = implode("','", $list);

#echo $string;

$buttons = '
    </div><!-- close box-body -->
<input type="hidden" id="list" value="\''.$string1.'\'">
<div class="box-footer">
	<div class="row">
	<div class="col-sm-3 col-lg-2"><button class="btn btn-primary btn-block" onclick="profile_display(\''.$string.'\')">Profile Display</button></div>
	<div class="col-sm-3 col-lg-2"><button class="btn btn-primary btn-block" onclick="rnaseq_show([\''.$string1.'\'])">RNAseq</button></div>
	<div class="col-sm-3 col-lg-2"><button class="btn btn-primary btn-block" onclick="chipseq_show([\''.$string1.'\'])">ChIPseq</button></div>
	<div class="col-sm-3 col-lg-2"><button class="btn btn-primary btn-block" onclick="snp_show([\''.$string1.'\'])">SNPs</button></div>
	<div class="col-sm-3 col-lg-2"><button class="btn btn-primary btn-block" onclick="ortholog_show([\''.$string1.'\'])">Orthologs</button></div>
	<div class="col-sm-3 col-lg-2"><button class="btn btn-primary btn-block" onclick="gene_description(\''.$string.'\')">Gene description</button></div>
	<div class="col-sm-3 col-lg-2"><button class="btn btn-primary btn-block" onclick="goterms(\''.$string.'\')">GO terms</button></div>
	<!-- <div class="col-sm-3 col-lg-2"><button class="btn btn-primary btn-block" onclick="plantgsea(\''.$string.'\')">PlantGSEA <small style="color: red">New</small></button></div> -->
	<div class="col-sm-3 col-lg-2"><button class="btn btn-primary btn-block" onclick="pathways(\''.$string.'\')">Pathways <small style="color: red">New</small></button></div>
	</div>
	<span id="spinner" style="display: none"><img src="/img/ajax-loader1.gif"></span>
</div>
		';

$end = '</div><!-- close row -->';

echo json_encode($header . $html. $buttons. $end);

?>


