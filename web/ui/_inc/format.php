<?php

function highlightPage($configPage, $this) {
	if(strcmp($configPage, $this) === 0) {
		echo 'class="active"';
	}
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="Chris Partridge">

	<title>dnstrace - <?php echo $configPage; ?></title>
	
	<link rel="icon" href="https://<?php echo $configWhoami; ?>/favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="https://<?php echo $configWhoami; ?>/favicon.ico" type="image/x-icon" />

	<!-- Bootstrap Core CSS -->
	<link href="https://<?php echo $configWhoami; ?>/ui/_vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

	<!-- MetisMenu CSS -->
	<link href="https://<?php echo $configWhoami; ?>/ui/_vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

	<!-- Custom CSS -->
	<link href="https://<?php echo $configWhoami; ?>/ui/_css/sb-admin-2.css" rel="stylesheet">

	<!-- Custom Fonts -->
	<link href="https://<?php echo $configWhoami; ?>/ui/_vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>

<body>
	<div id="wrapper">
		<!-- Navigation -->
		<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
			<div class="navbar-header">
				<a class="navbar-brand" href="https://<?php echo $configWhoami; ?>/ui/home/">dnstrace UI</a>
			</div>
			<!-- /.navbar-header -->

			<ul class="nav navbar-top-links navbar-right">
			
			</ul>
			<!-- /.navbar-top-links -->

			<div class="navbar-default sidebar" role="navigation">
				<div class="sidebar-nav navbar-collapse">
					<ul class="nav" id="side-menu">
						<li>
							<a <?php highlightPage($configPage, "Home"); ?> href="https://<?php echo $configWhoami; ?>/ui/home/"><i class="fa fa-file-text-o fa-fw"></i> Home</a>
						</li>
						<li>
							<a <?php highlightPage($configPage, "Create Graph"); ?> href="https://<?php echo $configWhoami; ?>/ui/create-graph/"><i class="fa fa-edit fa-fw"></i> Create Graph</a>
						</li>
					</ul>
				</div>
				<!-- /.sidebar-collapse -->
			</div>
			<!-- /.navbar-static-side -->
		</nav>