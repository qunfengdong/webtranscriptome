<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
<title>RNAseqChIPseqWeb</title>
<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />

<!-- Ionicons -->
<link href="//code.ionicframework.com/ionicons/1.5.2/css/ionicons.min.css" rel="stylesheet" type="text/css" />

<!-- Morris chart -->
<link href="/css/morris/morris.css" rel="stylesheet" type="text/css" />

<!-- jvectormap -->
<link href="/css/jvectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />

<!-- Date Picker -->
<link href="/css/datepicker/datepicker3.css" rel="stylesheet" type="text/css" />

<!-- Daterange picker -->
<link href="/css/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" />

<!-- bootstrap wysihtml5 - text editor -->
<link href="/css/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css" rel="stylesheet" type="text/css" />

<!-- Theme style -->
<link href="/css/AdminLTE.css" rel="stylesheet" type="text/css" />

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
<![endif]-->

	<?php
$dir = getcwd();
if($_SERVER['DOCUMENT_ROOT'] == $dir){
	require ("mysql/database_connect.php");
}
else {
	require ("../mysql/database_connect.php");
}
?>
</head>
<body class="skin-blue">
	<!-- header logo: style can be found in header.less -->

<header class="header">
	<a href="index.php" class="logo">RNAseqChIPseqWeb</a>

	<!-- Header Navbar: style can be found in header.less -->
	<nav class="navbar navbar-static-top" role="navigation">
		<!--  <a href="index.html" class="logo" style="width: 300px">Maize Inflorescence Project</a> -->
		<!-- Sidebar toggle button-->
		<a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
			<span class="sr-only">Toggle navigation</span>
    	<span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </a>

		<div class="navbar-right">
			<ul class="nav navbar-nav">

			<!-- Messages: style can be found in dropdown.less-->
			<li class="dropdown messages-menu">
				<a href="/index.php">
    	  	<i class="fa fa-home"></i>
    	 		<span>Home</span>
    	 	</a>
    	</li>

    	<!-- Notifications: style can be found in dropdown.less -->
    	<li class="dropdown notifications-menu">
    		<!-- Notifications: style can be found in dropdown.less -->
<li class="dropdown notifications-menu">
	<a href="#" class="dropdown-toggle" data-toggle="dropdown">
  	<i class="fa fa-info"></i> About <i class="caret"></i>
  </a>
  <ul class="dropdown-menu">
    <li>
    <!-- inner menu: contains the actual data -->
    <ul class="menu">
    	<li>
    		<a href="/about.php"><i class="ion ion-ios7-people info"></i> About </a>
    	</li>
    	<li>
    		<a href="/info.php"><i class="fa fa-info success"></i> Info </a>
    	</li>
	    <li>
	    	<a href="/team.php"><i class="fa fa-users warning"></i> Team </a>
  	  </li>
  	  <li>
	    	<a href="/links.php"><i class="fa fa-external-link info"></i> Links </a>
  	  </li>
		</ul>
		</li>
	</ul>
</li>

    	</li>

    	<!-- User Account: style can be found in dropdown.less -->
    	<li class="dropdown user user-menu">

    		<?php if(isset($_SESSION['user'])) { ?>

<a href="#" class="dropdown-toggle" data-toggle="dropdown">
	<i class="glyphicon glyphicon-user"></i>
	<span>
		<?php echo $_SESSION['user']['firstname'] ." ". $_SESSION['user']['lastname']; ?>
		<i class="caret"></i>
	</span>
</a>
<ul class="dropdown-menu">
	<!-- User image -->
	<li class="user-header bg-light-blue">
		<img src="/img/default_avatar.gif" class="img-circle" alt="User Image" />
		<p>
			<?php echo $_SESSION['user']['firstname'] ." ". $_SESSION['user']['lastname']; ?>
			<small><?php echo $_SESSION['user']['role'] ?></small>
			<small><?php echo $_SESSION['user']['institute'] ?></small>
		</p>
	</li>
	<!-- Menu Footer-->
	<li class="user-footer">
		<div class="pull-left">
			<a href="/user/profile.php" class="btn btn-default btn-flat">Profile</a>
		</div>
		<div class="pull-right">
			<a href="/user/logout.php" class="btn btn-default btn-flat">Sign out</a>
		</div>
	</li>
</ul>

<?php } else { ?>

<a href="/user/login.php" class="dropdown-toggle" >
	<i class="glyphicon glyphicon-user"></i>
	<span>
		Login
	</span>
</a>

<?php } ?>
    	</li>

    	</ul>
  </div>
  </nav>
 </header>


	<div class="wrapper row-offcanvas row-offcanvas-left">
		<!-- Left side column. contains the logo and sidebar -->

		<aside class="left-side sidebar-offcanvas">

<!-- sidebar: style can be found in sidebar.less -->
<style>
.sidebar {
	list-style: none;
}
.sidebar > li{
	padding: 0 0 2px 0;
	margin-left: -10px;
}
</style>

<?php
$restricted = '';
if (isset($_SESSION) && ($_SESSION['user']['level'] == 1)){
	$restricted = '';
} else {
	$restricted = '<small class="badge pull-right bg-red">Restricted</small>';
}
?>

<section class="sidebar">
	<!-- sidebar menu: : style can be found in sidebar.less -->
	<ul class="sidebar-menu">
		<li>
			<a href="#">
				<i class="fa fa-bar-chart-o"></i>
				<span>Profile</span>
			</a>
			<ul class="sidebar">
				<li><a href="/profile/display.php"><i class="fa fa-angle-double-right"></i> Profile Display</a></li>
				<li><a href="/profile/search.php"><i class="fa fa-angle-double-right"></i> Profile Search </a></li>
				<li><a href="/profile/cluster.php"><i class="fa fa-angle-double-right"></i> Hierarchical Cluster</a></li>
				<li><a href="/profile/heatmap.php"><i class="fa fa-angle-double-right"></i> Heatmap</a></li>
			</ul>
		</li>
		<li>
			<a href="#">
				<i class="fa fa-tasks"></i>
				<span>Sequence Analysis</span>
			</a>
			<ul class="sidebar">
				<li><a href="/sequence/promoter.php"><i class="fa fa-angle-double-right"></i> Promoter Analysis </a></li>
				<li><a href="/sequence/chipseqpeak.php"><i class="fa fa-angle-double-right"></i> ChIP-seq Analysis </a></li>
				<li><a href="/sequence/goterm.php"><i class="fa fa-angle-double-right"></i> GO Term <?php echo $restricted ?></a></li>
				<li><a href="/sequence/blast.php"><i class="fa fa-angle-double-right"></i> BLAST search </a></li>
				<li><a href="/sequence/pathway.php"><i class="fa fa-angle-double-right"></i> Pathway analysis </a></li>
			</ul>
		</li>
		<li>
			<a href="#">
				<i class="fa fa-table"></i>
				<span>Database</span>
			</a>
			<ul class="sidebar">
				<li><a href="/query/network.php"><i class="fa fa-angle-double-right"></i> Gene Network</a></li>
				<li><a href="/query/genelist.php"><i class="fa fa-angle-double-right"></i> Gene list Query</a></li>
				<li><a href="/query/"><i class="fa fa-angle-double-right"></i> Interval Query</a></li>
				<li><a href="/query/keyword.php"><i class="fa fa-angle-double-right"></i> Keyword Search </a></li>
			</ul>
		</li>
		<li>
			<a href="/jbrowse?data=maize&loc=chr5%3A14064161..14082560&tracks=GFF%2Cqtl%2Ctophat_ear_base_bigwig%2Ctophat_ear_mid_bigwig%2Ctophat_ear_tip_bigwig&highlight=" target="_blank">
			<i class="fa fa-laptop"></i> Genome Browser</a>
		</li>
		<li>
			<a href="/mydata">
				<i class="fa fa-angle-double-right"></i> <span>MyGene</span> <?php echo $restricted ?>
			</a>
		</li>
		<li>
			<a href="sop.php">
				<i class="fa fa-file-text"></i> <span>SOP</span>
			</a>
		</li>
		<li>
			<a href="data.php">
				<i class="fa fa-folder"></i> <span>Download</span>
			</a>
		</li>
		<li>
			<a href="mailto: qdong@luc.edu">
				<i class="fa fa-envelope"></i> <span>Contact</span>
			</a>
		</li>
	</ul>
</section>
<!-- /.sidebar -->

</aside>


		<!-- Right side column. Contains the navbar and content of the page -->
		<aside class="right-side">

