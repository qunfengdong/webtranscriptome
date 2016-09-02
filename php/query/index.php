<?php
require ('../template/header.php');
require ('../settings/errordiv.php');
?>

<section class="content-header">
  <h1>Interval Query</h1>
</section>

<section class="content">
	<div class="box">
		<div class="box-header"><h3 class="box-title"><i class="fa fa-info-circle"></i> Information</h3></div>
		<div class="box-body">
			<p>Lorem ipsum dolor sit amet, vim rebum sapientem ad, usu cu aperiri mandamus consequat. An sumo facer nam. Ius et sint dissentiunt. Quodsi assentior his id. Eu ius rebum commodo, ne debitis mentitum menandri pro.</p>
		</div>
	</div>
	<div class="box box-info">
		<div class="box-body">


<?php

function getchr(){
  require ("../mysql/database_connect.php");
  $query = "select chr from gff group by chr";
  try {
  	$stmt = $db->prepare($query);
  	$result = $stmt->execute();
  }
  catch(PDOException $e) {
  	echo callouterror($e->getMessage());
  	die;
  }
  $result = $stmt->fetchAll();
  $chrom = array();
  foreach($result as $row){
    array_push($chrom, $row['chr']);
  }
  return $chrom;
}

function get_drop_down(){
  $html = '<select id="cart_chr">';
  $chroms = getchr();
  foreach ($chroms as $chrom){
    $html .= '<option value="'.$chrom.'">'.$chrom.'</option>';
  }
  $html .= '</select>';
  return $html;
}

function get_form(){
  $html = <<<EOT
<input type="text" id="cart_start" placeholder="start" value="1">
<input type="text" id="cart_end" placeholder="end" value="50000">
<button class="btn btn-success" onclick="start_search()">Search</button>
EOT;
  return $html;
}

?>


			<p>Interval search using chromosome number, start and end position of the chromosome.</p>
			<p>
				<?php echo get_drop_down() ?>
				<?php echo get_form() ?>
			</p>

		</div><!-- box-body -->
	</div><!-- box -->

<script src="/js/jquery-1.10.2.min.js" type="text/javascript"></script>
<script src="/js/shared.js" type="text/javascript"></script>
<script src="/js/query.js" type="text/javascript"></script>

	<div class="box box-success" style="display: none" id="qtabs">
		<div class="box-body">

<div class="tabs" id="tabs">
    <ul class="tab-links">
        <li class="active"><a href="#tab1">Gene list</a></li>
        <li><a href="#tab2">RNA-seq</a></li>
        <li><a href="#tab3">ChIP-seq</a></li>
        <li><a href="#tab4">Pathway</a></li>
        <li><a href="#tab5">Ortholog</a></li>
    </ul>

    <div class="tab-content">
        <div id="tab1" class="tab active"></div>
        <div id="tab2" class="tab"></div>
        <div id="tab3" class="tab"></div>
        <div id="tab4" class="tab"></div>
        <div id="tab5" class="tab"></div>
	</div>
	</div>
	</div><!-- box-body -->
</div><!-- box -->
</section>

<div id="saveas_div" style="position:fixed; width:300px; height:170px; z-index:15; top:50%; left:50%; margin:-100px 0 0 -150px; display: none">
<div class="row">

		<div class="col-md-12">

			<div class="box box-success box-solid" style="box-shadow: 0px 0px 5px black">
				<div class="box-header">
					<h3 class="box-title">Save as</h3>
					<div class="pull-right box-tools">
						<button class="btn btn-success btn-sm" onclick="saveas_close()"><i class="fa fa-times"></i></button>
					</div>
				</div>
				<div class="box-body">
					<input type="text" id="saveas_text">.txt
					<div id="saveas_info">
					</div>
				</div>
				<div class="box-footer">
					<button onclick="saveas_save()">Save</button>
					<button onclick="saveas_close()">Cancel</button>
				</div>
			</div>
		</div>
</div>
</div>


<style>
/*----- Tabs -----*/
.tabs {
    width:100%;
    display:inline-block;
}

    /*----- Tab Links -----*/
    /* Clearfix */
    .tab-links:after {
        display:block;
        clear:both;
        content:'';
    }

    .tab-links li {
        margin:0px 5px;
        float:left;
        list-style:none;
        border: 1px solid black;
    }

        .tab-links a {
            padding:9px 15px;
            display:inline-block;
            border-radius:3px 3px 0px 0px;
            background:#7FB5DA;
            font-size:16px;
            font-weight:600;
            color:#4c4c4c;
            transition:all linear 0.15s;
        }

        .tab-links a:hover {
            background:#a7cce5;
            text-decoration:none;
        }

    li.active a, li.active a:hover {
        background:#fff;
        color:#4c4c4c;
    }

    /*----- Content of Tabs -----*/
    .tab-content {
        padding:15px;
        border-radius:3px;
        //box-shadow:-1px 1px 1px rgba(0,0,0,0.15);
        background:#fff;
    }

        .tab {
            display:none;
        }

        .tab.active {
            display:block;
        }
</style>
<script>
jQuery(document).ready(function() {
    jQuery('.tabs .tab-links a').on('click', function(e)  {
        var currentAttrValue = jQuery(this).attr('href');

        // Show/Hide Tabs
        jQuery('.tabs ' + currentAttrValue).show().siblings().hide();

        // Change/remove current tab to active
        jQuery(this).parent('li').addClass('active').siblings().removeClass('active');

        e.preventDefault();
    });
});
</script>

<?php require ('../template/footer.php'); ?>