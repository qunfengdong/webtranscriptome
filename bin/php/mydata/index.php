<?php
require ("../mysql/database_connect.php");
require ("../user/verify.php");
?>
<?php require ('../template/header.php'); ?>

<section class="content-header">
	<h1>My gene list</h1>
</section>

<section class="content">
	<div class="box box-success">
		<div class="box-header">
			<h3 class="box-title">Files</h3>
		</div>
		<div class="box-body">
			<button onclick="upload_box()" class="btn btn-default">Upload File</button>

<p>
<h3>Files uploaded so far</h3>
<div id="gene_list">
<?php
$query = "SELECT listname FROM mygenelist where userid = ". $_SESSION['user']['id'] ." order by listname";

#echo $query;

try {
	$stmt = $db->prepare($query);
	$result = $stmt->execute();
}
catch(PDOException $ex) {
	die("Failed to run query: " . $ex->getMessage());
}

$result = $stmt->fetchAll();
$total = sizeof($result);
$html = "<p>Total ($total), Maximum limit: 10</p><p><table>";

foreach($result as $row){
	$html .= "<tr>";
	$html .= "<td style='padding: 5px'>".preg_replace("/^[0-9]+\./", "", $row['listname'])."</td>";
	$html .= "<td style='padding: 5px'><button class=\"btn btn-success\" onclick='show(\"".$row['listname']."\")'>show</button></td>";
	$html .= "<td style='padding: 5px'><button class=\"btn btn-primary\" onclick='edit(\"".$row['listname']."\")'>edit</button></td>";
	$html .= "<td style='padding: 5px'><button class=\"btn btn-danger\" onclick='deletefile(this, \"".$row['listname']."\")'>delete</button></td>";
	$html .= "</tr>";
}
$html .= "</table></p>";

echo $html;
?>
</div>
</p>
		</div>
	</div><!-- box -->
	<div id="next"></div>
	<div id="result" style="overflow:auto"></div>

	<div class="row">

<div id="saveas_div" style="position:fixed; width:300px; height:170px; z-index:15; top:50%; left:50%; margin:-100px 0 0 -150px; display: none">
<div class="row">

		<div class="col-md-12">

			<div class="box box-success box-solid" style="box-shadow: 0px 0px 5px black">
				<div class="box-header">
					<h3 class="box-title">Upload File</h3>
					<div class="pull-right box-tools">
						<button class="btn btn-success btn-sm" onclick="saveas_close()"><i class="fa fa-times"></i></button>
					</div>
				</div>
				<div class="box-body">
					<form action="/mydata/ajax_save.php" method="post" enctype="multipart/form-data">
						Upload gene list file:
						<input type="file" name="file">
					<div id="saveas_info">
					</div>
				</div>
				<div class="box-footer">
					<input type=submit value="Upload" class="btn btn-success">
					</form>
				</div>
			</div>
		</div>
</div>
</div>

<div id="edit_div" style="position:fixed; width:300px; height:170px; z-index:15; top:50%; left:50%; margin:-100px 0 0 -150px; display: none">
<div class="row">

		<div class="col-md-12">

			<div class="box box-success box-solid" style="box-shadow: 0px 0px 5px black">
				<div class="box-header">
					<h3 class="box-title">Edit File</h3>
					<div class="pull-right box-tools">
						<button class="btn btn-success btn-sm" onclick="edit_close()"><i class="fa fa-times"></i></button>
					</div>
				</div>
				<div class="box-body">
					<div id="edit_info">
					</div>
				</div>
			</div>
		</div>
</div>
</div>

	</div>


</section>

<script src="/js/jquery-1.10.2.min.js" type="text/javascript"></script>
<script type="text/javascript" src="/js/mydata.js"></script>

<?php require ('../template/footer.php'); ?>