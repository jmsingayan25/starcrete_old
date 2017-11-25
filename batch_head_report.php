<!DOCTYPE html>
<?php

	include("includes/config.php");
	include("includes/function.php");
	include("includes/batch_function.php");

	session_start();
	if(!isset($_SESSION['login_user'])) {
		header("location: login.php");
	}

	if(isset($_GET['hidden_office'])){
		$_SESSION['post'] = $_GET['hidden_office'];
	}else{
		$_SESSION['post'] = "bravo";
	}

	$hidden_office = $_SESSION['post'];
	
	$user_query = $db->prepare("SELECT * FROM users WHERE username = ?");
	$user_query->bind_param('s', $_SESSION['login_user']);
	$user_query->execute();
	$result = $user_query->get_result();
	$user = $result->fetch_assoc();

	$office = $user['office'];
	$position = $user['position'];
?>
<html>
<head>
<?php
	if($hidden_office == 'delta'){
		echo "<title>Daily Production Report - Quality Star Concrete Products, Inc.</title>";
	}else{
		echo "<title>Daily Production Report - Starcrete Manufacturing Corporation</title>";
	}
?>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
	<link href="css_ext/sidebar.css" rel="stylesheet">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js_ext/sidebar.js"></script>

<script>
	
	$(document).ready(function() {
		$('#myModal6').on('hidden.bs.modal', function (e) {
			window.location.reload();
		});
	});

	function onLoadSubmit() {
		document.form_batch.submit();
	}
	function sortBatch(str){
		var office = document.getElementById('office').value;

	    if(window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
	      xmlhttp=new XMLHttpRequest();
	    }else{// code for IE6, IE5
	      xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	    }
	    xmlhttp.onreadystatechange=function(){
	      if(xmlhttp.readyState==4 && xmlhttp.status==200){
	        document.getElementById("batch_result").innerHTML=xmlhttp.responseText;
	      }
	    }
	    xmlhttp.open("GET","batch_result.php?date="+str+"&office="+office,true);
	    xmlhttp.send();
	}

	function batchOption(str){
		var office = document.getElementById('office').value;

		if(window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
	      xmlhttp=new XMLHttpRequest();
	    }else{// code for IE6, IE5
	      xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	    }
	    xmlhttp.onreadystatechange=function(){
	      if(xmlhttp.readyState==4 && xmlhttp.status==200){
	        document.getElementById("modal_result").innerHTML=xmlhttp.responseText;
	      }
	    }
	   	xmlhttp.open("GET","batch_result.php?option="+str+"&office="+office,true);
	    xmlhttp.send();
	}
</script>
<style>
/*#myModal2 .modal-body{
	height: 490px;
}*/

.modal-body{
	height: 500px;
}
#wrapper {
	min-height:83%;
	position:relative;
}

#content {
	margin: 0 auto;
	min-height: 442px;
	padding-bottom:20px; /* Height of the footer element */
	padding-right: 15px;
	padding-left: 15px;
	padding-top: 15px;
}

#footer {
	width:100%;
	position:absolute;
	bottom:0;
	left:0;
	background-color: #0884e4;
    color: white;
    text-align: center; 
    padding: 10px;
}
/*.table tbody{
  overflow-y: scroll;
  height: 290px;
  position: absolute;
  border:1px solid #cecece;
}*/
.table td {
   border-bottom: 1px solid #bababa;
   border-right: 1px solid #d1d1d1;
   border-left: 1px solid #d1d1d1;
}
.table td, th {
    text-align: center;
}

th, footer {
    background-color: #0884e4;
    color: white;
}

.modal_input td{
	padding: 10px;
	border: 1px solid #d1d1d1;
}

/*.row.content {
	min-height: 1500px
}*/
#nav {
	position: absolute;
	left: 0px;
	width: 100%;
	display: flex;
	justify-content: center;
}
/* Remove the navbar's default margin-bottom and rounded borders */ 
.navbar {
  margin-bottom: 0;
  border-radius: 0;
}

#box{
	position: absolute;
	min-height: 300px;
	z-index: 15;
	top: 50%;
	left: 50%;
	margin: -25px 0 0 -300px;
	width: 600px;
    padding: 15px;
    border: 1px solid #bababa;
}

/* reset our lists to remove bullet points and padding */
.mainmenu, .submenu {
  list-style: none;
  padding: 0;
  margin: 0;
}

/* when hovering over a .mainmenu item,
  display the submenu inside it.
  we're changing the submenu's max-height from 0 to 200px;
*/
.mainmenu li:hover .submenu {
  display: block;
  max-height: 200px;
}

/*
  we now overwrite the background-color for .submenu links only.
  CSS reads down the page, so code at the bottom will overwrite the code at the top.
*/
.submenu a {
  background-color: #1f97f9;
}

/* this is the initial state of all submenus.
  we set it to max-height: 0, and hide the overflowed content.
*/
.submenu {
  overflow: hidden;
  max-height: 0;
  -webkit-transition: all 0.5s ease-out;
}
</style>
</head>
<body onload="sortBatch('');onLoadSubmit();">
	<div id="mySidenav" class="sidenav">
		<a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
		<a href="index.php">Home</a>
		<hr>
		<!-- <a href="stock.php">Stock Report</a> -->
<?php
	if($office == 'head'){
?>		
		<form action="batch_head_report.php" method="post">
		<ul class="mainmenu">
			<li><a href="#">Batch Report</a>
				<ul class="submenu">
					<li>
						<a href="batch_head_report.php?hidden_office=bravo" type="submit" name="action"><span class='glyphicon glyphicon-menu-right'></span> Bravo</a>
					</li>
					<li>
						<a href="batch_head_report.php?hidden_office=delta" type="submit" name="action"><span class='glyphicon glyphicon-menu-right'></span> Delta</a>
					</li>
				</ul>
			</li>
		</ul>
		</form>
<?php
	}else{
?>
		<a href="batch_plant_report.php">Batch Report</a>
<?php
	}
?>
		<!-- <a href="diesel.php">Diesel Report</a> -->
<?php
	if($position != 'warehouseman')
		echo "<a href='purchase_order.php'>Issued Purchase Order</a>"
?>
<!-- 		<a href='purchase_order.php'>Issued Purchase Order</a> -->
		<a href="delivery.php">Issued Delivery Receipt</a>
		<!-- <a href='purchase_order_aggregates.php'>Issued Purchase Order Aggregates</a> -->
		<!-- <a href="received.php">Received Order</a> -->
		<!-- <a href="transmittal.php">Transmittal</a> -->
		<hr>
		<a href="#">About Us</a>
	</div>
	<nav class="navbar navbar-default" id="primary-nav" style="background-color: white;">
		<div class="container">
			<div class="navbar-header">
				<a id="nav" class="navbar-brand" href="index.php" style="font-size:40px; font-family: Haettenschweiler;">
<?php
	if($hidden_office == 'delta'){
		echo "Quality Star Concrete Products, Inc.";
	}else{
		echo "Starcrete Manufacturing Corporation";
	}
?>
				</a>
			</div>
		</div>
	</nav>
	<nav class="navbar navbar-default" id="secondary-nav" style="background-color: #0884e4; margin-bottom: 10px; ">
		<div class="container-fluid">
			<span style="font-size:30px; cursor:pointer; color: white;" onclick="openNav();">&#9776;</span>
			<span style="font-size:25px; color: white;"><?php echo ucfirst($hidden_office); ?> Daily Production Report </span>
			<ul class="nav navbar-nav navbar-right">
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" style="color: white; background-color: #0884e4;"> <strong>Welcome! <?php echo ucfirst($user['firstname']); ?></strong><span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="logout.php">Logout</a></li>
					</ul>
				</li>
			</ul>
		</div>
	</nav>

	<div id="wrapper" onclick="closeNav();">
		<!-- <div id="header">
			<input type="hidden" name="office" id="office" value="<?php echo $hidden_office; ?>">
			<input type="hidden" name="position" id="position" value="<?php echo $position; ?>">
			<div class="row" style="margin: 0px;">
				<div class="col-md-12">
					<button type="button" onclick="location.href='batch.php';" class="btn btn-default" style="float: left;"><span class="glyphicon glyphicon-arrow-left"></span> Back to Batch Report</button>

					<form action="batch_head_report_list.php" method="post">
						<button type='submit' name="action" class='btn btn-info btn-md' style='float: right;'>
							List of <?php echo ucfirst($hidden_office); ?> Reports <span class='glyphicon glyphicon-arrow-right'></span> 
						</button>
						<input type="hidden" name="hidden_office" value="<?php echo $hidden_office; ?>">
					</form>
					<button type='button' class='btn btn-info btn-md' style='float: right;' onclick="batchOption('');"><span class='glyphicon glyphicon-eye-open'></span> View <?php echo ucfirst($office); ?> Reports</button>
				</div>
			</div>
		</div> -->
		<div id="content">
			<div class="row" style="margin-bottom: 5px;">
				<div class="col-md-3 col-md-offset-1">
					<form method="post" action="batch_head_report.php" name="form_batch" id="form_batch" class="form-inline">
						<input type="date" name="date_view" class="form-control" value="<?php if(isset($_POST['date_view'])) { echo htmlentities ($_POST['date_view']); }?>">
						<input type="submit" name="submit" id="submit" value="Search" class="btn btn-primary">
					</form>
				</div>
				<div class="col-md-1 col-md-offset-6">
					<div class="pull-right">
					<!-- <form action="batch_head_report_list.php" method="post">
						<button type='submit' name="action" class='btn btn-info btn-md' style='float: right;'>
							List of <?php echo ucfirst($hidden_office); ?> Reports <span class='glyphicon glyphicon-arrow-right'></span> 
						</button>
						<input type="hidden" name="hidden_office" value="<?php echo $hidden_office; ?>">
					</form> -->
					<div class="dropdown">
						<button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">View Reports
						<span class="caret"></span></button>
						<ul class="dropdown-menu">
							<li class="dropdown-header">View Reports</li>
							<li><a href="batch_head_report.php">Daily Production</a></li>
							<li><a href="batch_head_report_view.php?option=total_cement_day&office=<?php echo $hidden_office; ?>">Total cement used per day</a></li>
							<li><a href="batch_head_report_view.php?option=total_batch&office=<?php echo $hidden_office; ?>">Total batch and cement used</a></li>
							<li><a href="batch_head_report_view.php?option=output_batch&office=<?php echo $hidden_office; ?>">Output per batch</a></li>
							<li><a href="batch_head_report_view.php?option=monthly_output&office=<?php echo $hidden_office; ?>">Monthly production output</a></li>
							<li><a href="batch_head_report_view.php?option=monthly_delivery&office=<?php echo $hidden_office; ?>">Monthly delivered CHB</a></li>
						</ul>
					</div>
					</div>
				</div>	
			</div>
			<div class="row">
				<div class="col-md-10 col-md-offset-1">
<?php 
	if(isset($_POST['submit'])){

		if($_POST['date_view'] == ''){
			$date = date("Y-m-d");
		}else{
			$date = $_POST['date_view'];
		}
		
		$date_view = date_create($date);
?>	
					<table class="table table-bordered table-striped" style="text-align: center;">
						<thead>
							<tr>
								<th colspan="7" style="text-align: left;">Date: <?php echo date_format($date_view,"F d, Y"); ?></th>					
							</tr>
							<tr>
								<th colspan="1" rowspan="3" style="vertical-align: middle; text-align: center;">Time</th>
								<th colspan="2">Machine No. M-1</th>
								<th colspan="2">Machine No. M-3a</th>
								<th colspan="2">Machine No. M-3b</th>
							</tr>
							<tr>
								<th colspan="2">Cement (kg): <?php echo getBatchCement($db,$hidden_office,'M-1',$date); ?></th>
								<th colspan="2">Cement (kg): <?php echo getBatchCement($db,$hidden_office,'M-3a',$date); ?></th>
								<th colspan="2">Cement (kg): <?php echo getBatchCement($db,$hidden_office,'M-3b',$date); ?></th>
							</tr>
							<tr>
								<th colspan="2">
									Type: <?php echo getBatchType($db,$hidden_office,'M-1',$date); ?>
								</th>
								<th colspan="2">
									Type: <?php echo getBatchType($db,$hidden_office,'M-3a',$date); ?>
								</th>
								<th colspan="2">
									Type: <?php echo getBatchType($db,$hidden_office,'M-3b',$date); ?>
								</th>
							</tr>
							<tr>
								<th class="col-md-1">AM</th>
								<th class="col-md-1">Batch</th>
								<th class="col-md-1">Comment</th>
								<th class="col-md-1">Batch</th>
								<th class="col-md-1">Comment</th>
								<th class="col-md-1">Batch</th>
								<th class="col-md-1">Comment</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td class="col-md-1">6:00 - 7:00</td>
								<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-1',$date,'6'); ?></td>
								<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-1',$date,'6'); ?></td>
								<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-3a',$date,'6'); ?></td>
								<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-3a',$date,'6'); ?></td>
								<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-3b',$date,'6'); ?></td>
								<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-3b',$date,'6'); ?></td>
							</tr>
							<tr>
								<td class="col-md-1">7:00 - 8:00</td>
								<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-1',$date,'7'); ?></td>
								<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-1',$date,'7'); ?></td>
								<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-3a',$date,'7'); ?></td>
								<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-3a',$date,'7'); ?></td>
								<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-3b',$date,'7'); ?></td>
								<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-3b',$date,'7'); ?></td>
							</tr>
							<tr>
								<td class="col-md-1">8:00 - 9:00</td>
								<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-1',$date,'8'); ?></td>
								<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-1',$date,'8'); ?></td>
								<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-3a',$date,'8'); ?></td>
								<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-3a',$date,'8'); ?></td>
								<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-3b',$date,'8'); ?></td>
								<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-3b',$date,'8'); ?></td>
							</tr>
							<tr>
								<td class="col-md-1">9:00 - 10:00</td>
								<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-1',$date,'9'); ?></td>
								<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-1',$date,'9'); ?></td>
								<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-3a',$date,'9'); ?></td>
								<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-3a',$date,'9'); ?></td>
								<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-3b',$date,'9'); ?></td>
								<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-3b',$date,'9'); ?></td>
							</tr>
							<tr>
								<td class="col-md-1">10:00 - 11:00</td>
								<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-1',$date,'10'); ?></td>
								<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-1',$date,'10'); ?></td>
								<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-3a',$date,'10'); ?></td>
								<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-3a',$date,'10'); ?></td>
								<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-3b',$date,'10'); ?></td>
								<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-3b',$date,'10'); ?></td>
							</tr>
							<tr>
								<td class="col-md-1">11:00 - 12:00</td>
								<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-1',$date,'11'); ?></td>
								<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-1',$date,'11'); ?></td>
								<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-3a',$date,'11'); ?></td>
								<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-3a',$date,'11'); ?></td>
								<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-3b',$date,'11'); ?></td>
								<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-3b',$date,'11'); ?></td>
							</tr>
							<tr>
								<th class="col-md-1">PM</th>
								<th class="col-md-1">Batch</th>
								<th class="col-md-1">Comment</th>
								<th class="col-md-1">Batch</th>
								<th class="col-md-1">Comment</th>
								<th class="col-md-1">Batch</th>
								<th class="col-md-1">Comment</th>
							</tr>
							<tr>
								<td class="col-md-1">12:00 - 1:00</td>
								<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-1',$date,'12'); ?></td>
								<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-1',$date,'12'); ?></td>
								<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-3a',$date,'12'); ?></td>
								<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-3a',$date,'12'); ?></td>
								<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-3b',$date,'12'); ?></td>
								<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-3b',$date,'12'); ?></td>
							</tr>
							<tr>
								<td class="col-md-1">1:00 - 2:00</td>
								<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-1',$date,'13'); ?></td>
								<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-1',$date,'13'); ?></td>
								<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-3a',$date,'13'); ?></td>
								<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-3a',$date,'13'); ?></td>
								<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-3b',$date,'13'); ?></td>
								<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-3b',$date,'13'); ?></td>
							</tr>
							<tr>
								<td class="col-md-1">2:00 - 3:00</td>
								<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-1',$date,'14'); ?></td>
								<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-1',$date,'14'); ?></td>
								<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-3a',$date,'14'); ?></td>
								<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-3a',$date,'14'); ?></td>
								<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-3b',$date,'14'); ?></td>
								<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-3b',$date,'14'); ?></td>
							</tr>
							<tr>
								<td class="col-md-1">3:00 - 4:00</td>
								<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-1',$date,'15'); ?></td>
								<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-1',$date,'15'); ?></td>
								<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-3a',$date,'15'); ?></td>
								<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-3a',$date,'15'); ?></td>
								<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-3b',$date,'15'); ?></td>
								<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-3b',$date,'15'); ?></td>
							</tr>
							<tr>
								<td class="col-md-1">4:00 - 5:00</td>
								<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-1',$date,'16'); ?></td>
								<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-1',$date,'16'); ?></td>
								<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-3a',$date,'16'); ?></td>
								<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-3a',$date,'16'); ?></td>
								<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-3b',$date,'16'); ?></td>
								<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-3b',$date,'16'); ?></td>
							</tr>
							<tr>
								<td class="col-md-1">5:00 - 6:00</td>
								<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-1',$date,'17'); ?></td>
								<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-1',$date,'17'); ?></td>
								<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-3a',$date,'17'); ?></td>
								<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-3a',$date,'17'); ?></td>
								<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-3b',$date,'17'); ?></td>
								<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-3b',$date,'17'); ?></td>
							</tr>
							<tr>
								<td class="col-md-1">6:00 - 7:00</td>
								<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-1',$date,'18'); ?></td>
								<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-1',$date,'18'); ?></td>
								<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-3a',$date,'18'); ?></td>
								<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-3a',$date,'18'); ?></td>
								<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-3b',$date,'18'); ?></td>
								<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-3b',$date,'18'); ?></td>
							</tr>
							<tr>
								<td class="col-md-1">7:00 - 8:00</td>
								<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-1',$date,'19'); ?></td>
								<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-1',$date,'19'); ?></td>
								<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-3a',$date,'19'); ?></td>
								<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-3a',$date,'19'); ?></td>
								<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-3b',$date,'19'); ?></td>
								<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-3b',$date,'19'); ?></td>
							</tr>
							<tr>
								<td class="col-md-1">8:00 - 9:00</td>
								<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-1',$date,'20'); ?></td>
								<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-1',$date,'20'); ?></td>
								<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-3a',$date,'20'); ?></td>
								<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-3a',$date,'20'); ?></td>
								<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-3b',$date,'20'); ?></td>
								<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-3b',$date,'20'); ?></td>
							</tr>
							<tr>
								<td class="col-md-1">9:00 - 10:00</td>
								<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-1',$date,'21'); ?></td>
								<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-1',$date,'21'); ?></td>
								<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-3a',$date,'21'); ?></td>
								<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-3a',$date,'21'); ?></td>
								<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-3b',$date,'21'); ?></td>
								<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-3b',$date,'21'); ?></td>
							</tr>
							<tr>
								<td class="col-md-1">10:00 - 11:00</td>
								<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-1',$date,'22'); ?></td>
								<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-1',$date,'22'); ?></td>
								<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-3a',$date,'22'); ?></td>
								<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-3a',$date,'22'); ?></td>
								<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-3b',$date,'22'); ?></td>
								<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-3b',$date,'22'); ?></td>
							</tr>
							<tr>
								<td class="col-md-1">11:00 - 12:00</td>
								<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-1',$date,'23'); ?></td>
								<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-1',$date,'23'); ?></td>
								<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-3a',$date,'23'); ?></td>
								<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-3a',$date,'23'); ?></td>
								<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-3b',$date,'23'); ?></td>
								<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-3b',$date,'23'); ?></td>
							</tr>
							<tr>
								<th class="col-md-1">Total</th>
								<th colspan="2">
<?php
	if($hidden_office == 'bravo'){
		$string_office = " AND office = 'bravo'";
	}else if($hidden_office == 'delta'){
		$string_office = " AND office = 'delta'";
	}else{
		$string_office = "";
	}

	$sql_machine1 = "SELECT DATE_FORMAT(batch_date,'%m/%d/%Y') as prod_date, SUM(batch_count) as total 
						FROM batch 
						WHERE DATE_FORMAT(batch_date,'%m/%d/%Y') = DATE_FORMAT('$date','%m/%d/%Y') AND machine_no = 'M-1'".$string_office."
						GROUP BY machine_no, item_no, prod_date 
						ORDER BY prod_date DESC, machine_no";

	$result = mysqli_query($db,$sql_machine1);
	$row = mysqli_fetch_assoc($result);

		if($row['total'] == "0" || $row['total'] == ""){
			echo "0";
		}else{
			 echo $row['total'];
		}
?>
								</th>
								<th colspan="2">
<?php
	if($hidden_office == 'bravo'){
		$string_office = " AND office = 'bravo'";
	}else if($hidden_office == 'delta'){
		$string_office = " AND office = 'delta'";
	}else{
		$string_office = "";
	}

	$sql_machine1 = "SELECT DATE_FORMAT(batch_date,'%m/%d/%Y') as prod_date, SUM(batch_count) as total 
						FROM batch 
						WHERE DATE_FORMAT(batch_date,'%m/%d/%Y') = DATE_FORMAT('$date','%m/%d/%Y') AND machine_no = 'M-3a'".$string_office."
						GROUP BY machine_no, item_no, prod_date 
						ORDER BY prod_date DESC, machine_no";

	$result = mysqli_query($db,$sql_machine1);
	$row = mysqli_fetch_assoc($result);

		if($row['total'] == "0" || $row['total'] == ""){
			echo "0";
		}else{
			 echo $row['total'];
		}
?>
								</th>
								<th colspan="2">
<?php
	if($hidden_office == 'bravo'){
		$string_office = " AND office = 'bravo'";
	}else if($hidden_office == 'delta'){
		$string_office = " AND office = 'delta'";
	}else{
		$string_office = "";
	}

	$sql_machine1 = "SELECT DATE_FORMAT(batch_date,'%m/%d/%Y') as prod_date, SUM(batch_count) as total 
						FROM batch 
						WHERE DATE_FORMAT(batch_date,'%m/%d/%Y') = DATE_FORMAT('$date','%m/%d/%Y') AND machine_no = 'M-3b'".$string_office."
						GROUP BY machine_no, item_no, prod_date 
						ORDER BY prod_date DESC, machine_no";

	$result = mysqli_query($db,$sql_machine1);
	$row = mysqli_fetch_assoc($result);

		if($row['total'] == "0" || $row['total'] == ""){
			echo "0";
		}else{
			 echo $row['total'];
		}
?>
								</th>
							</tr>
						</tbody>
					</table>
<?php
	}else{
		$date = date("Y-m-d");
		$date_view = date_create($date);
?>
				<table class="table table-bordered" style="text-align: center;">
				<thead>
					<tr>
						<th colspan="7" style="text-align: left;">Date: <?php echo date_format($date_view,"F d, Y"); ?></th>					
					</tr>
					<tr>
						<th colspan="1" rowspan="3" style="vertical-align: middle; text-align: center;">Time</th>
						<th colspan="2">Machine No. M-1</th>
						<th colspan="2">Machine No. M-3a</th>
						<th colspan="2">Machine No. M-3b</th>
					</tr>
					<tr>
						<th colspan="2">Cement (kg): <?php echo getBatchCement($db,$hidden_office,'M-1',$date); ?></th>
						<th colspan="2">Cement (kg): <?php echo getBatchCement($db,$hidden_office,'M-3a',$date); ?></th>
						<th colspan="2">Cement (kg): <?php echo getBatchCement($db,$hidden_office,'M-3b',$date); ?></th>
					</tr>
					<tr>
						<th colspan="2">
							Type: <?php echo getBatchType($db,$hidden_office,'M-1',$date); ?>
						</th>
						<th colspan="2">
							Type: <?php echo getBatchType($db,$hidden_office,'M-3a',$date); ?>
						</th>
						<th colspan="2">
							Type: <?php echo getBatchType($db,$hidden_office,'M-3b',$date); ?>
						</th>
					</tr>
					<tr>
						<th class="col-md-1">AM</th>
						<th class="col-md-1">Batch</th>
						<th class="col-md-1">Comment</th>
						<th class="col-md-1">Batch</th>
						<th class="col-md-1">Comment</th>
						<th class="col-md-1">Batch</th>
						<th class="col-md-1">Comment</th>
					</tr>
				</thead>
				<tbody style="overflow-y: hidden; width: 97%">
					<tr>
						<td class="col-md-1">6:00 - 7:00</td>
						<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-1',$date,'6'); ?></td>
						<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-1',$date,'6'); ?></td>
						<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-3a',$date,'6'); ?></td>
						<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-3a',$date,'6'); ?></td>
						<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-3b',$date,'6'); ?></td>
						<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-3b',$date,'6'); ?></td>
					</tr>
					<tr>
						<td class="col-md-1">7:00 - 8:00</td>
						<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-1',$date,'7'); ?></td>
						<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-1',$date,'7'); ?></td>
						<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-3a',$date,'7'); ?></td>
						<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-3a',$date,'7'); ?></td>
						<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-3b',$date,'7'); ?></td>
						<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-3b',$date,'7'); ?></td>
					</tr>
					<tr>
						<td class="col-md-1">8:00 - 9:00</td>
						<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-1',$date,'8'); ?></td>
						<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-1',$date,'8'); ?></td>
						<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-3a',$date,'8'); ?></td>
						<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-3a',$date,'8'); ?></td>
						<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-3b',$date,'8'); ?></td>
						<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-3b',$date,'8'); ?></td>
					</tr>
					<tr>
						<td class="col-md-1">9:00 - 10:00</td>
						<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-1',$date,'9'); ?></td>
						<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-1',$date,'9'); ?></td>
						<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-3a',$date,'9'); ?></td>
						<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-3a',$date,'9'); ?></td>
						<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-3b',$date,'9'); ?></td>
						<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-3b',$date,'9'); ?></td>
					</tr>
					<tr>
						<td class="col-md-1">10:00 - 11:00</td>
						<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-1',$date,'10'); ?></td>
						<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-1',$date,'10'); ?></td>
						<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-3a',$date,'10'); ?></td>
						<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-3a',$date,'10'); ?></td>
						<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-3b',$date,'10'); ?></td>
						<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-3b',$date,'10'); ?></td>
					</tr>
					<tr>
						<td class="col-md-1">11:00 - 12:00</td>
						<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-1',$date,'11'); ?></td>
						<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-1',$date,'11'); ?></td>
						<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-3a',$date,'11'); ?></td>
						<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-3a',$date,'11'); ?></td>
						<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-3b',$date,'11'); ?></td>
						<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-3b',$date,'11'); ?></td>
					</tr>
					<tr>
						<th class="col-md-1">PM</th>
						<th class="col-md-1">Batch</th>
						<th class="col-md-1">Comment</th>
						<th class="col-md-1">Batch</th>
						<th class="col-md-1">Comment</th>
						<th class="col-md-1">Batch</th>
						<th class="col-md-1">Comment</th>
					</tr>
					<tr>
						<td class="col-md-1">12:00 - 1:00</td>
						<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-1',$date,'12'); ?></td>
						<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-1',$date,'12'); ?></td>
						<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-3a',$date,'12'); ?></td>
						<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-3a',$date,'12'); ?></td>
						<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-3b',$date,'12'); ?></td>
						<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-3b',$date,'12'); ?></td>
					</tr>
					<tr>
						<td class="col-md-1">1:00 - 2:00</td>
						<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-1',$date,'13'); ?></td>
						<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-1',$date,'13'); ?></td>
						<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-3a',$date,'13'); ?></td>
						<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-3a',$date,'13'); ?></td>
						<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-3b',$date,'13'); ?></td>
						<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-3b',$date,'13'); ?></td>
					</tr>
					<tr>
						<td class="col-md-1">2:00 - 3:00</td>
						<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-1',$date,'14'); ?></td>
						<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-1',$date,'14'); ?></td>
						<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-3a',$date,'14'); ?></td>
						<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-3a',$date,'14'); ?></td>
						<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-3b',$date,'14'); ?></td>
						<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-3b',$date,'14'); ?></td>
					</tr>
					<tr>
						<td class="col-md-1">3:00 - 4:00</td>
						<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-1',$date,'15'); ?></td>
						<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-1',$date,'15'); ?></td>
						<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-3a',$date,'15'); ?></td>
						<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-3a',$date,'15'); ?></td>
						<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-3b',$date,'15'); ?></td>
						<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-3b',$date,'15'); ?></td>
					</tr>
					<tr>
						<td class="col-md-1">4:00 - 5:00</td>
						<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-1',$date,'16'); ?></td>
						<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-1',$date,'16'); ?></td>
						<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-3a',$date,'16'); ?></td>
						<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-3a',$date,'16'); ?></td>
						<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-3b',$date,'16'); ?></td>
						<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-3b',$date,'16'); ?></td>
					</tr>
					<tr>
						<td class="col-md-1">5:00 - 6:00</td>
						<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-1',$date,'17'); ?></td>
						<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-1',$date,'17'); ?></td>
						<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-3a',$date,'17'); ?></td>
						<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-3a',$date,'17'); ?></td>
						<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-3b',$date,'17'); ?></td>
						<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-3b',$date,'17'); ?></td>
					</tr>
					<tr>
						<td class="col-md-1">6:00 - 7:00</td>
						<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-1',$date,'18'); ?></td>
						<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-1',$date,'18'); ?></td>
						<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-3a',$date,'18'); ?></td>
						<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-3a',$date,'18'); ?></td>
						<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-3b',$date,'18'); ?></td>
						<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-3b',$date,'18'); ?></td>
					</tr>
					<tr>
						<td class="col-md-1">7:00 - 8:00</td>
						<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-1',$date,'19'); ?></td>
						<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-1',$date,'19'); ?></td>
						<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-3a',$date,'19'); ?></td>
						<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-3a',$date,'19'); ?></td>
						<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-3b',$date,'19'); ?></td>
						<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-3b',$date,'19'); ?></td>
					</tr>
					<tr>
						<td class="col-md-1">8:00 - 9:00</td>
						<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-1',$date,'20'); ?></td>
						<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-1',$date,'20'); ?></td>
						<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-3a',$date,'20'); ?></td>
						<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-3a',$date,'20'); ?></td>
						<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-3b',$date,'20'); ?></td>
						<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-3b',$date,'20'); ?></td>
					</tr>
					<tr>
						<td class="col-md-1">9:00 - 10:00</td>
						<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-1',$date,'21'); ?></td>
						<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-1',$date,'21'); ?></td>
						<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-3a',$date,'21'); ?></td>
						<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-3a',$date,'21'); ?></td>
						<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-3b',$date,'21'); ?></td>
						<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-3b',$date,'21'); ?></td>
					</tr>
					<tr>
						<td class="col-md-1">10:00 - 11:00</td>
						<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-1',$date,'22'); ?></td>
						<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-1',$date,'22'); ?></td>
						<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-3a',$date,'22'); ?></td>
						<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-3a',$date,'22'); ?></td>
						<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-3b',$date,'22'); ?></td>
						<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-3b',$date,'22'); ?></td>
					</tr>
					<tr>
						<td class="col-md-1">11:00 - 12:00</td>
						<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-1',$date,'23'); ?></td>
						<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-1',$date,'23'); ?></td>
						<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-3a',$date,'23'); ?></td>
						<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-3a',$date,'23'); ?></td>
						<td class="col-md-1"><?php echo getBatch($db,$hidden_office,'M-3b',$date,'23'); ?></td>
						<td class="col-md-1"><?php echo getComment($db,$hidden_office,'M-3b',$date,'23'); ?></td>
					</tr>
					<tr>
						<th class="col-md-1">Total</th>
						<th colspan="2">
<?php
	if($hidden_office == 'bravo'){
		$string_office = " AND office = 'bravo'";
	}else if($hidden_office == 'delta'){
		$string_office = " AND office = 'delta'";
	}else{
		$string_office = "";
	}

	$sql_machine1 = "SELECT DATE_FORMAT(batch_date,'%m/%d/%Y') as prod_date, SUM(batch_count) as total 
						FROM batch 
						WHERE DATE_FORMAT(batch_date,'%m/%d/%Y') = DATE_FORMAT('$date','%m/%d/%Y') AND machine_no = 'M-1'".$string_office."
						GROUP BY machine_no, item_no, prod_date 
						ORDER BY prod_date DESC, machine_no";

	$result = mysqli_query($db,$sql_machine1);
	$row = mysqli_fetch_assoc($result);

		if($row['total'] == "0" || $row['total'] == ""){
			echo "0";
		}else{
			 echo $row['total'];
		}
?>
						</th>
						<th colspan="2">
<?php
	if($hidden_office == 'bravo'){
		$string_office = " AND office = 'bravo'";
	}else if($hidden_office == 'delta'){
		$string_office = " AND office = 'delta'";
	}else{
		$string_office = "";
	}

	$sql_machine1 = "SELECT DATE_FORMAT(batch_date,'%m/%d/%Y') as prod_date, SUM(batch_count) as total 
						FROM batch 
						WHERE DATE_FORMAT(batch_date,'%m/%d/%Y') = DATE_FORMAT('$date','%m/%d/%Y') AND machine_no = 'M-3a'".$string_office."
						GROUP BY machine_no, item_no, prod_date 
						ORDER BY prod_date DESC, machine_no";

	$result = mysqli_query($db,$sql_machine1);
	$row = mysqli_fetch_assoc($result);

		if($row['total'] == "0" || $row['total'] == ""){
			echo "0";
		}else{
			 echo $row['total'];
		}
?>
						</th>
						<th colspan="2">
<?php
	if($hidden_office == 'bravo'){
		$string_office = " AND office = 'bravo'";
	}else if($hidden_office == 'delta'){
		$string_office = " AND office = 'delta'";
	}else{
		$string_office = "";
	}

	$sql_machine1 = "SELECT DATE_FORMAT(batch_date,'%m/%d/%Y') as prod_date, SUM(batch_count) as total 
						FROM batch 
						WHERE DATE_FORMAT(batch_date,'%m/%d/%Y') = DATE_FORMAT('$date','%m/%d/%Y') AND machine_no = 'M-3b'".$string_office."
						GROUP BY machine_no, item_no, prod_date 
						ORDER BY prod_date DESC, machine_no";

	$result = mysqli_query($db,$sql_machine1);
	$row = mysqli_fetch_assoc($result);

		if($row['total'] == "0" || $row['total'] == ""){
			echo "0";
		}else{
			 echo $row['total'];
		}
?>
						</th>
					</tr>
				</tbody>
			</table>
<?php
	}
?>				
				</div>
			</div>
		</div>
		<div id="extra" style="height: 70px;"></div>
		<div id="footer">
<?php
	if($hidden_office == 'delta'){
		echo "<h4>Quality Star Concrete Products, Inc.</h4>";
	}else{
		echo "<h4>Starcrete Manufacturing Corporation</h4>";
	}
?>
		</div>
	</div>
</body>
</html>