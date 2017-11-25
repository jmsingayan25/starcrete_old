<!DOCTYPE html>
<?php

	include("includes/config.php");
	include("includes/function.php");
	include("includes/batch_function.php");
	//include("received_function.php");

	session_start();
	if(!isset($_SESSION['login_user'])) {
		header("location: login.php");
	}

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
	if($office == 'delta'){
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
	
</script>
<style>

#wrapper {
	min-height:83%;
	position:relative;
}

#content {
	margin: 0 auto;
	min-height: 480px;
	padding-bottom: 20px; /* Height of the footer element */
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
	min-height: 491px
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

#type1, #type2, #type3, #cement1, #cement2, #cement3{
	color: black;
}
#cement1, #cement2, #cement3{
	width: 25%
}
</style>
</head>
<body onload="sortBatch('');checkComment();">
	<div id="mySidenav" class="sidenav">
		<a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
		<a href="index.php">Home</a>
		<hr>
<!-- 		<a href="stock.php">Stock Report</a>
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
		<a href="batch_plant.php">Batch Report</a>
<?php
	}
?>
		<a href="diesel.php">Diesel Report</a> -->
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
	if($office == 'delta'){
		echo "Quality Star Concrete Products, Inc.";
	}else{
		echo "Starcrete Manufacturing Corporation";
	}
?>
				</a>
			</div>
		</div>
	</nav>

	<nav class="navbar navbar-default" id="secondary-nav" style="background-color: #0884e4; margin-bottom: 10px;">
		<div class="container-fluid">
			<span style="font-size:30px; cursor:pointer; color: white;" onclick="openNav();">&#9776;</span>
			<span style="font-size:25px; cursor:pointer; color: white;">Batch Page</span>
			<ul class="nav navbar-nav navbar-right">
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" style="color: white; background-color: #0884e4;">Welcome! <strong><?php echo ucfirst($user['firstname']); ?></strong><span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="logout.php">Logout</a></li>
					</ul>
				</li>
			</ul>
		</div>
	</nav>

	<input type="hidden" name="office" id="office" value="<?php echo $office; ?>">
	<input type="hidden" name="position" id="position" value="<?php echo $position; ?>">
	
	<div id="wrapper" onclick="closeNav();">
		<div id="content">
			<div class="row" style="margin: 0px; margin-bottom: 5px;">
				<div class="col-md-3 col-md-offset-1">
					<form method="post" action="batch_plant_report.php" name="form_batch" class="form-inline">
						<input type="date" name="date_view" class="form-control" value="<?php if(isset($_POST['date_view'])) { echo htmlentities ($_POST['date_view']); }?>">
						<input type="submit" name="submit" id="submit" value="Search" class="btn btn-primary">
					</form>
				</div>
				<div class="col-md-4 col-md-offset-3">
					<div class="pull-right">
						<div class="dropdown">
							<a href="batch_form_production.php" class="btn btn-success">Add Actual Production</a>
							<button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">View Reports
							<span class="caret"></span></button>
							<ul class="dropdown-menu pull-right">
								<!-- <li class="dropdown-header">Production</li>
								<li><a href="batch_form_production.php">Add Actual Production</a></li>
								<li class="divider"></li> -->
								<li class="dropdown-header">View Reports</li>
								<li><a href="batch_plant_report.php">Daily Production</a></li>
								<li><a href="batch_plant_report_view.php?option=total_cement_day">Total cement used per day</a></li>
								<li><a href="batch_plant_report_view.php?option=total_batch">Total batch and cement used</a></li>
								<li><a href="batch_plant_report_view.php?option=output_batch">Output per batch</a></li>
								<li><a href="batch_plant_report_view.php?option=monthly_output">Monthly production output</a></li>
								<li><a href="batch_plant_report_view.php?option=monthly_delivery">Monthly delivered CHB</a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<div class="row" style="margin: 0px;">
				<div class="col-md-10 col-md-offset-1">
<?php 
	if(isset($_POST['submit'])){

		if($_POST['date_view'] == ''){
			$date = date("Y-m-d");
		}else{
			$date = $_POST['date_view'];
		}
		
		$date_view = date_create($date);

		$rows = array();
		$select_sql = "SELECT item_no FROM batch_list
						ORDER BY item_no ASC";
		$select_result = mysqli_query($db, $select_sql);
		while($row = mysqli_fetch_assoc($select_result)){
			$rows[] = $row;
		}
?>	
				<form method="post" action="batch_plant_report.php" onsubmit="return confirm('Do you really want to submit the form?');">
				<table class="table table-bordered table-striped" style="text-align: center;">
						<thead>
							<tr>
								<th colspan="7" style="text-align: left;">
									Date: <?php echo date_format($date_view,"F d, Y"); ?>
									<input type="hidden" name="hidden_date_view" value="<?php echo $date; ?>">		
								</th>					
							</tr>
							<tr>
								<th colspan="1" rowspan="3" style="vertical-align: middle; text-align: center;">Time</th>
								<th colspan="2">Machine No. M-1</th>
								<th colspan="2">Machine No. M-3a</th>
								<th colspan="2">Machine No. M-3b</th>
							</tr>
							<tr>
								<th colspan="2">Cement (kg): 
<?php 
	if(getBatchCement($db,$office,'M-1',$date) != ""){
							echo getBatchCement($db,$office,'M-1',$date); 
	}else{
?>
									<input type="text" name="cement1" id="cement1" required>
<?php
	}
?>
								</th>
								<th colspan="2">Cement (kg): 
<?php
	if(getBatchCement($db,$office,'M-3a',$date) != ""){
							echo getBatchCement($db,$office,'M-3a',$date); 
	}else{
?>
									<input type="text" name="cement2" id="cement2" required>
<?php
	}
?>								
								</th>
								<th colspan="2">Cement (kg): 
<?php
	if(getBatchCement($db,$office,'M-3b',$date) != ""){
							echo getBatchCement($db,$office,'M-3b',$date); 
	}else{
?>
									<input type="text" name="cement3" id="cement3" required>
<?php
	}
?>	
								</th>
							</tr>
							<tr>
								<th colspan="2">Type: 
<?php
	if(getBatchType($db,$office,'M-1',$date) != ""){
							echo getBatchType($db,$office,'M-1',$date); 
	}else{
?>
									<!-- <input type="text" name="type1" id="type1" required> -->
									<select name="type1" id="type1">
										<option value="">Select</option>
<?php
		foreach($rows as $row) {
									echo "<option value='".$row['item_no']."'>".$row['item_no']."</option>";
		}

?>


									</select>
<?php
	}
?>								
								</th>
								<th colspan="2">Type: 
<?php
	if(getBatchType($db,$office,'M-3a',$date) != ""){
							echo getBatchType($db,$office,'M-3a',$date); 
	}else{
?>
									<select name="type2" id="type2">
										<option value="">Select</option>
<?php
		foreach($rows as $row) {
									echo "<option value='".$row['item_no']."'>".$row['item_no']."</option>";
		}

?>


									</select>
<?php
	}
?>								
								</th>
								<th colspan="2">Type: 
<?php
	if(getBatchType($db,$office,'M-3b',$date) != ""){
							echo getBatchType($db,$office,'M-3b',$date); 
	}else{
?>
									<select name="type3" id="type3">
										<option value="">Select</option>
<?php
		foreach($rows as $row) {
									echo "<option value='".$row['item_no']."'>".$row['item_no']."</option>";
		}

?>


									</select>
<?php
	}
?>								
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
								<input type="hidden" name="time1[]" value="06:00:00">
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-1',$date,'6') != ""){
									echo getBatch($db,$office,'M-1',$date,'6');
	}else{
?>
									<input type="text" name="batch1[]" id="batch1" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-1',$date,'6') != ""){
									echo getComment($db,$office,'M-1',$date,'6');
	}else{
?>
									<input type="text" name="comment1[]" id="comment1" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-3a',$date,'6') != ""){
									echo getBatch($db,$office,'M-3a',$date,'6');
	}else{
?>
									<input type="text" name="batch2[]" id="batch2" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-3a',$date,'6') != ""){
									echo getComment($db,$office,'M-3a',$date,'6');
	}else{
?>
									<input type="text" name="comment2[]" id="comment2" class="form-control">
<?php		
	}
?>									
								</td>
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-3b',$date,'6') != ""){
									echo getBatch($db,$office,'M-3b',$date,'6');
	}else{
?>
									<input type="text" name="batch3[]" id="batch3" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-3b',$date,'6') != ""){
									echo getComment($db,$office,'M-3b',$date,'6');
	}else{
?>
									<input type="text" name="comment3[]" id="comment3" class="form-control">
<?php		
	}
?>									
								</td>
							</tr>
							<tr>
								<td class="col-md-1">7:00 - 8:00</td>
								<input type="hidden" name="time1[]" value="07:00:00">
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-1',$date,'7') != ""){
									echo getBatch($db,$office,'M-1',$date,'7');
	}else{
?>
									<input type="text" name="batch1[]" id="batch1" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-1',$date,'7') != ""){
									echo getComment($db,$office,'M-1',$date,'7');
	}else{
?>
									<input type="text" name="comment1[]" id="comment1" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-3a',$date,'7') != ""){
									echo getBatch($db,$office,'M-3a',$date,'7');
	}else{
?>
									<input type="text" name="batch2[]" id="batch2" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-3a',$date,'7') != ""){
									echo getComment($db,$office,'M-3a',$date,'7');
	}else{
?>
									<input type="text" name="comment2[]" id="comment2" class="form-control">
<?php		
	}
?>									
								</td>
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-3b',$date,'7') != ""){
									echo getBatch($db,$office,'M-3b',$date,'7');
	}else{
?>
									<input type="text" name="batch3[]" id="batch3" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-3b',$date,'7') != ""){
									echo getComment($db,$office,'M-3b',$date,'7');
	}else{
?>
									<input type="text" name="comment3[]" id="comment3" class="form-control">
<?php		
	}
?>									
								</td>
							</tr>
							<tr>
								<td class="col-md-1">8:00 - 9:00</td>
								<input type="hidden" name="time1[]" value="08:00:00">
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-1',$date,'8') != ""){
									echo getBatch($db,$office,'M-1',$date,'8');
	}else{
?>
									<input type="text" name="batch1[]" id="batch1" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-1',$date,'8') != ""){
									echo getComment($db,$office,'M-1',$date,'8');
	}else{
?>
									<input type="text" name="comment1[]" id="comment1" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-3a',$date,'8') != ""){
									echo getBatch($db,$office,'M-3a',$date,'8');
	}else{
?>
									<input type="text" name="batch2[]" id="batch2" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-3a',$date,'8') != ""){
									echo getComment($db,$office,'M-3a',$date,'8');
	}else{
?>
									<input type="text" name="comment2[]" id="comment2" class="form-control">
<?php		
	}
?>									
								</td>
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-3b',$date,'8') != ""){
									echo getBatch($db,$office,'M-3b',$date,'8');
	}else{
?>
									<input type="text" name="batch3[]" id="batch3" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-3b',$date,'8') != ""){
									echo getComment($db,$office,'M-3b',$date,'8');
	}else{
?>
									<input type="text" name="comment3[]" id="comment3" class="form-control">
<?php		
	}
?>									
								</td>
							</tr>
							<tr>
								<td class="col-md-1">9:00 - 10:00</td>
								<input type="hidden" name="time1[]" value="09:00:00">
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-1',$date,'9') != ""){
									echo getBatch($db,$office,'M-1',$date,'9');
	}else{
?>
									<input type="text" name="batch1[]" id="batch1" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-1',$date,'9') != ""){
									echo getComment($db,$office,'M-1',$date,'9');
	}else{
?>
									<input type="text" name="comment1[]" id="comment1" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-3a',$date,'9') != ""){
									echo getBatch($db,$office,'M-3a',$date,'9');
	}else{
?>
									<input type="text" name="batch2[]" id="batch2" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-3a',$date,'9') != ""){
									echo getComment($db,$office,'M-3a',$date,'9');
	}else{
?>
									<input type="text" name="comment2[]" id="comment2" class="form-control">
<?php		
	}
?>									
								</td>
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-3b',$date,'9') != ""){
									echo getBatch($db,$office,'M-3b',$date,'9');
	}else{
?>
									<input type="text" name="batch3[]" id="batch3" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-3b',$date,'9') != ""){
									echo getComment($db,$office,'M-3b',$date,'9');
	}else{
?>
									<input type="text" name="comment3[]" id="comment3" class="form-control">
<?php		
	}
?>									
								</td>
							</tr>
							<tr>
								<td class="col-md-1">10:00 - 11:00</td>
								<input type="hidden" name="time1[]" value="10:00:00">
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-1',$date,'10') != ""){
									echo getBatch($db,$office,'M-1',$date,'10');
	}else{
?>
									<input type="text" name="batch1[]" id="batch1" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-1',$date,'10') != ""){
									echo getComment($db,$office,'M-1',$date,'10');
	}else{
?>
									<input type="text" name="comment1[]" id="comment1" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-3a',$date,'10') != ""){
									echo getBatch($db,$office,'M-3a',$date,'10');
	}else{
?>
									<input type="text" name="batch2[]" id="batch2" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-3a',$date,'10') != ""){
									echo getComment($db,$office,'M-3a',$date,'10');
	}else{
?>
									<input type="text" name="comment2[]" id="comment2" class="form-control">
<?php		
	}
?>									
								</td>
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-3b',$date,'10') != ""){
									echo getBatch($db,$office,'M-3b',$date,'10');
	}else{
?>
									<input type="text" name="batch3[]" id="batch3" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-3b',$date,'10') != ""){
									echo getComment($db,$office,'M-3b',$date,'10');
	}else{
?>
									<input type="text" name="comment3[]" id="comment3" class="form-control">
<?php		
	}
?>									
								</td>
							</tr>
							<tr>
								<td class="col-md-1">11:00 - 12:00</td>
								<input type="hidden" name="time1[]" value="11:00:00">
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-1',$date,'11') != ""){
									echo getBatch($db,$office,'M-1',$date,'11');
	}else{
?>
									<input type="text" name="batch1[]" id="batch1" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-1',$date,'11') != ""){
									echo getComment($db,$office,'M-1',$date,'11');
	}else{
?>
									<input type="text" name="comment1[]" id="comment1" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-3a',$date,'11') != ""){
									echo getBatch($db,$office,'M-3a',$date,'11');
	}else{
?>
									<input type="text" name="batch2[]" id="batch2" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-3a',$date,'11') != ""){
									echo getComment($db,$office,'M-3a',$date,'11');
	}else{
?>
									<input type="text" name="comment2[]" id="comment2" class="form-control">
<?php		
	}
?>									
								</td>
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-3b',$date,'11') != ""){
									echo getBatch($db,$office,'M-3b',$date,'11');
	}else{
?>
									<input type="text" name="batch3[]" id="batch3" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-3b',$date,'11') != ""){
									echo getComment($db,$office,'M-3b',$date,'11');
	}else{
?>
									<input type="text" name="comment3[]" id="comment3" class="form-control">
<?php		
	}
?>									
								</td>
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
								<input type="hidden" name="time1[]" value="12:00:00">
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-1',$date,'12') != ""){
									echo getBatch($db,$office,'M-1',$date,'12');
	}else{
?>
									<input type="text" name="batch1[]" id="batch1" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-1',$date,'12') != ""){
									echo getComment($db,$office,'M-1',$date,'12');
	}else{
?>
									<input type="text" name="comment1[]" id="comment1" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-3a',$date,'12') != ""){
									echo getBatch($db,$office,'M-3a',$date,'12');
	}else{
?>
									<input type="text" name="batch2[]" id="batch2" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-3a',$date,'12') != ""){
									echo getComment($db,$office,'M-3a',$date,'12');
	}else{
?>
									<input type="text" name="comment2[]" id="comment2" class="form-control">
<?php		
	}
?>									
								</td>
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-3b',$date,'12') != ""){
									echo getBatch($db,$office,'M-3b',$date,'12');
	}else{
?>
									<input type="text" name="batch3[]" id="batch3" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-3b',$date,'12') != ""){
									echo getComment($db,$office,'M-3b',$date,'12');
	}else{
?>
									<input type="text" name="comment3[]" id="comment3" class="form-control">
<?php		
	}
?>									
								</td>
							</tr>
							<tr>
								<td class="col-md-1">1:00 - 2:00</td>
								<input type="hidden" name="time1[]" value="13:00:00">
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-1',$date,'13') != ""){
									echo getBatch($db,$office,'M-1',$date,'13');
	}else{
?>
									<input type="text" name="batch1[]" id="batch1" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-1',$date,'13') != ""){
									echo getComment($db,$office,'M-1',$date,'13');
	}else{
?>
									<input type="text" name="comment1[]" id="comment1" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-3a',$date,'13') != ""){
									echo getBatch($db,$office,'M-3a',$date,'13');
	}else{
?>
									<input type="text" name="batch2[]" id="batch2" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-3a',$date,'13') != ""){
									echo getComment($db,$office,'M-3a',$date,'13');
	}else{
?>
									<input type="text" name="comment2[]" id="comment2" class="form-control">
<?php		
	}
?>									
								</td>
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-3b',$date,'13') != ""){
									echo getBatch($db,$office,'M-3b',$date,'13');
	}else{
?>
									<input type="text" name="batch3[]" id="batch3" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-3b',$date,'13') != ""){
									echo getComment($db,$office,'M-3b',$date,'13');
	}else{
?>
									<input type="text" name="comment3[]" id="comment3" class="form-control">
<?php		
	}
?>									
								</td>
							</tr>
							<tr>
								<td class="col-md-1">2:00 - 3:00</td>
								<input type="hidden" name="time1[]" value="14:00:00">
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-1',$date,'14') != ""){
									echo getBatch($db,$office,'M-1',$date,'14');
	}else{
?>
									<input type="text" name="batch1[]" id="batch1" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-1',$date,'14') != ""){
									echo getComment($db,$office,'M-1',$date,'14');
	}else{
?>
									<input type="text" name="comment1[]" id="comment1" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-3a',$date,'14') != ""){
									echo getBatch($db,$office,'M-3a',$date,'14');
	}else{
?>
									<input type="text" name="batch2[]" id="batch2" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-3a',$date,'14') != ""){
									echo getComment($db,$office,'M-3a',$date,'14');
	}else{
?>
									<input type="text" name="comment2[]" id="comment2" class="form-control">
<?php		
	}
?>									
								</td>
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-3b',$date,'14') != ""){
									echo getBatch($db,$office,'M-3b',$date,'14');
	}else{
?>
									<input type="text" name="batch3[]" id="batch3" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-3b',$date,'14') != ""){
									echo getComment($db,$office,'M-3b',$date,'14');
	}else{
?>
									<input type="text" name="comment3[]" id="comment3" class="form-control">
<?php		
	}
?>									
								</td>
							</tr>
							<tr>
								<td class="col-md-1">3:00 - 4:00</td>
								<input type="hidden" name="time1[]" value="15:00:00">
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-1',$date,'15') != ""){
									echo getBatch($db,$office,'M-1',$date,'15');
	}else{
?>
									<input type="text" name="batch1[]" id="batch1" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-1',$date,'15') != ""){
									echo getComment($db,$office,'M-1',$date,'15');
	}else{
?>
									<input type="text" name="comment1[]" id="comment1" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-3a',$date,'15') != ""){
									echo getBatch($db,$office,'M-3a',$date,'15');
	}else{
?>
									<input type="text" name="batch2[]" id="batch2" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-3a',$date,'15') != ""){
									echo getComment($db,$office,'M-3a',$date,'15');
	}else{
?>
									<input type="text" name="comment2[]" id="comment2" class="form-control">
<?php		
	}
?>									
								</td>
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-3b',$date,'15') != ""){
									echo getBatch($db,$office,'M-3b',$date,'15');
	}else{
?>
									<input type="text" name="batch3[]" id="batch3" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-3b',$date,'15') != ""){
									echo getComment($db,$office,'M-3b',$date,'15');
	}else{
?>
									<input type="text" name="comment3[]" id="comment3" class="form-control">
<?php		
	}
?>									
								</td>
							</tr>
							<tr>
								<td class="col-md-1">4:00 - 5:00</td>
								<input type="hidden" name="time1[]" value="16:00:00">
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-1',$date,'16') != ""){
									echo getBatch($db,$office,'M-1',$date,'16');
	}else{
?>
									<input type="text" name="batch1[]" id="batch1" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-1',$date,'16') != ""){
									echo getComment($db,$office,'M-1',$date,'16');
	}else{
?>
									<input type="text" name="comment1[]" id="comment1" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-3a',$date,'16') != ""){
									echo getBatch($db,$office,'M-3a',$date,'16');
	}else{
?>
									<input type="text" name="batch2[]" id="batch2" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-3a',$date,'16') != ""){
									echo getComment($db,$office,'M-3a',$date,'16');
	}else{
?>
									<input type="text" name="comment2[]" id="comment2" class="form-control">
<?php		
	}
?>									
								</td>
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-3b',$date,'16') != ""){
									echo getBatch($db,$office,'M-3b',$date,'16');
	}else{
?>
									<input type="text" name="batch3[]" id="batch3" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-3b',$date,'16') != ""){
									echo getComment($db,$office,'M-3b',$date,'16');
	}else{
?>
									<input type="text" name="comment3[]" id="comment3" class="form-control">
<?php		
	}
?>									
								</td>
							</tr>
							<tr>
								<td class="col-md-1">5:00 - 6:00</td>
								<input type="hidden" name="time1[]" value="17:00:00">
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-1',$date,'17') != ""){
									echo getBatch($db,$office,'M-1',$date,'17');
	}else{
?>
									<input type="text" name="batch1[]" id="batch1" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-1',$date,'17') != ""){
									echo getComment($db,$office,'M-1',$date,'17');
	}else{
?>
									<input type="text" name="comment1[]" id="comment1" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-3a',$date,'17') != ""){
									echo getBatch($db,$office,'M-3a',$date,'17');
	}else{
?>
									<input type="text" name="batch2[]" id="batch2" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-3a',$date,'17') != ""){
									echo getComment($db,$office,'M-3a',$date,'17');
	}else{
?>
									<input type="text" name="comment2[]" id="comment2" class="form-control">
<?php		
	}
?>									
								</td>
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-3b',$date,'17') != ""){
									echo getBatch($db,$office,'M-3b',$date,'17');
	}else{
?>
									<input type="text" name="batch3[]" id="batch3" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-3b',$date,'17') != ""){
									echo getComment($db,$office,'M-3b',$date,'17');
	}else{
?>
									<input type="text" name="comment3[]" id="comment3" class="form-control">
<?php		
	}
?>									
								</td>
							</tr>
							<tr>
								<td class="col-md-1">6:00 - 7:00</td>
								<input type="hidden" name="time1[]" value="18:00:00">
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-1',$date,'18') != ""){
									echo getBatch($db,$office,'M-1',$date,'18');
	}else{
?>
									<input type="text" name="batch1[]" id="batch1" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-1',$date,'18') != ""){
									echo getComment($db,$office,'M-1',$date,'18');
	}else{
?>
									<input type="text" name="comment1[]" id="comment1" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-3a',$date,'18') != ""){
									echo getBatch($db,$office,'M-3a',$date,'18');
	}else{
?>
									<input type="text" name="batch2[]" id="batch2" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-3a',$date,'18') != ""){
									echo getComment($db,$office,'M-3a',$date,'18');
	}else{
?>
									<input type="text" name="comment2[]" id="comment2" class="form-control">
<?php		
	}
?>									
								</td>
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-3b',$date,'18') != ""){
									echo getBatch($db,$office,'M-3b',$date,'18');
	}else{
?>
									<input type="text" name="batch3[]" id="batch3" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-3b',$date,'18') != ""){
									echo getComment($db,$office,'M-3b',$date,'18');
	}else{
?>
									<input type="text" name="comment3[]" id="comment3" class="form-control">
<?php		
	}
?>									
								</td>
							</tr>
							<tr>
								<td class="col-md-1">7:00 - 8:00</td>
								<input type="hidden" name="time1[]" value="19:00:00">
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-1',$date,'19') != ""){
									echo getBatch($db,$office,'M-1',$date,'19');
	}else{
?>
									<input type="text" name="batch1[]" id="batch1" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-1',$date,'19') != ""){
									echo getComment($db,$office,'M-1',$date,'19');
	}else{
?>
									<input type="text" name="comment1[]" id="comment1" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-3a',$date,'19') != ""){
									echo getBatch($db,$office,'M-3a',$date,'19');
	}else{
?>
									<input type="text" name="batch2[]" id="batch2" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-3a',$date,'19') != ""){
									echo getComment($db,$office,'M-3a',$date,'19');
	}else{
?>
									<input type="text" name="comment2[]" id="comment2" class="form-control">
<?php		
	}
?>									
								</td>
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-3b',$date,'19') != ""){
									echo getBatch($db,$office,'M-3b',$date,'19');
	}else{
?>
									<input type="text" name="batch3[]" id="batch3" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-3b',$date,'19') != ""){
									echo getComment($db,$office,'M-3b',$date,'19');
	}else{
?>
									<input type="text" name="comment3[]" id="comment3" class="form-control">
<?php		
	}
?>									
								</td>
							</tr>
							<tr>
								<td class="col-md-1">8:00 - 9:00</td>
								<input type="hidden" name="time1[]" value="20:00:00">
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-1',$date,'20') != ""){
									echo getBatch($db,$office,'M-1',$date,'20');
	}else{
?>
									<input type="text" name="batch1[]" id="batch1" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-1',$date,'20') != ""){
									echo getComment($db,$office,'M-1',$date,'20');
	}else{
?>
									<input type="text" name="comment1[]" id="comment1" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-3a',$date,'20') != ""){
									echo getBatch($db,$office,'M-3a',$date,'20');
	}else{
?>
									<input type="text" name="batch2[]" id="batch2" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-3a',$date,'20') != ""){
									echo getComment($db,$office,'M-3a',$date,'20');
	}else{
?>
									<input type="text" name="comment2[]" id="comment2" class="form-control">
<?php		
	}
?>									
								</td>
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-3b',$date,'20') != ""){
									echo getBatch($db,$office,'M-3b',$date,'20');
	}else{
?>
									<input type="text" name="batch3[]" id="batch3" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-3b',$date,'20') != ""){
									echo getComment($db,$office,'M-3b',$date,'20');
	}else{
?>
									<input type="text" name="comment3[]" id="comment3" class="form-control">
<?php		
	}
?>									
								</td>
							</tr>
							<tr>
								<td class="col-md-1">9:00 - 10:00</td>
								<input type="hidden" name="time1[]" value="21:00:00">
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-1',$date,'21') != ""){
									echo getBatch($db,$office,'M-1',$date,'21');
	}else{
?>
									<input type="text" name="batch1[]" id="batch1" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-1',$date,'21') != ""){
									echo getComment($db,$office,'M-1',$date,'21');
	}else{
?>
									<input type="text" name="comment1[]" id="comment1" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-3a',$date,'21') != ""){
									echo getBatch($db,$office,'M-3a',$date,'21');
	}else{
?>
									<input type="text" name="batch2[]" id="batch2" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-3a',$date,'21') != ""){
									echo getComment($db,$office,'M-3a',$date,'21');
	}else{
?>
									<input type="text" name="comment2[]" id="comment2" class="form-control">
<?php		
	}
?>									
								</td>
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-3b',$date,'21') != ""){
									echo getBatch($db,$office,'M-3b',$date,'21');
	}else{
?>
									<input type="text" name="batch3[]" id="batch3" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-3b',$date,'21') != ""){
									echo getComment($db,$office,'M-3b',$date,'21');
	}else{
?>
									<input type="text" name="comment3[]" id="comment3" class="form-control">
<?php		
	}
?>									
								</td>
							</tr>
							<tr>
								<td class="col-md-1">10:00 - 11:00</td>
								<input type="hidden" name="time1[]" value="22:00:00">
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-1',$date,'22') != ""){
									echo getBatch($db,$office,'M-1',$date,'22');
	}else{
?>
									<input type="text" name="batch1[]" id="batch1" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-1',$date,'22') != ""){
									echo getComment($db,$office,'M-1',$date,'22');
	}else{
?>
									<input type="text" name="comment1[]" id="comment1" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-3a',$date,'22') != ""){
									echo getBatch($db,$office,'M-3a',$date,'22');
	}else{
?>
									<input type="text" name="batch2[]" id="batch2" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-3a',$date,'22') != ""){
									echo getComment($db,$office,'M-3a',$date,'22');
	}else{
?>
									<input type="text" name="comment2[]" id="comment2" class="form-control">
<?php		
	}
?>									
								</td>
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-3b',$date,'22') != ""){
									echo getBatch($db,$office,'M-3b',$date,'22');
	}else{
?>
									<input type="text" name="batch3[]" id="batch3" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-3b',$date,'22') != ""){
									echo getComment($db,$office,'M-3b',$date,'22');
	}else{
?>
									<input type="text" name="comment3[]" id="comment3" class="form-control">
<?php		
	}
?>									
								</td>
							</tr>
							<tr>
								<td class="col-md-1">11:00 - 12:00</td>
								<input type="hidden" name="time1[]" value="23:00:00">
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-1',$date,'23') != ""){
									echo getBatch($db,$office,'M-1',$date,'23');
	}else{
?>
									<input type="text" name="batch1[]" id="batch1" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-1',$date,'23') != ""){
									echo getComment($db,$office,'M-1',$date,'23');
	}else{
?>
									<input type="text" name="comment1[]" id="comment1" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-3a',$date,'23') != ""){
									echo getBatch($db,$office,'M-3a',$date,'23');
	}else{
?>
									<input type="text" name="batch2[]" id="batch2" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-3a',$date,'23') != ""){
									echo getComment($db,$office,'M-3a',$date,'23');
	}else{
?>
									<input type="text" name="comment2[]" id="comment2" class="form-control">
<?php		
	}
?>									
								</td>
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-3b',$date,'23') != ""){
									echo getBatch($db,$office,'M-3b',$date,'23');
	}else{
?>
									<input type="text" name="batch3[]" id="batch3" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-3b',$date,'23') != ""){
									echo getComment($db,$office,'M-3b',$date,'23');
	}else{
?>
									<input type="text" name="comment3[]" id="comment3" class="form-control">
<?php		
	}
?>									
								</td>
							</tr>
							<tr>
								<th class="col-md-1">Total</th>
								<th colspan="2">
<?php
	if($office == 'bravo'){
		$string_office = " AND office = 'bravo'";
	}else if($office == 'delta'){
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
	if($office == 'bravo'){
		$string_office = " AND office = 'bravo'";
	}else if($office == 'delta'){
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
	if($office == 'bravo'){
		$string_office = " AND office = 'bravo'";
	}else if($office == 'delta'){
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
<?php
	if($office == 'bravo'){
		$string_office = " AND office = 'bravo'";
	}else if($office == 'delta'){
		$string_office = " AND office = 'delta'";
	}else{
		$string_office = "";
	}

	$button_sql = "SELECT DATE_FORMAT(batch_date,'%m/%d/%Y') as prod_date
					FROM batch 
					WHERE DATE_FORMAT(batch_date,'%m/%d/%Y') = DATE_FORMAT('$date','%m/%d/%Y')".$string_office;

	$button_result = mysqli_query($db,$button_sql);
	if(mysqli_num_rows($button_result) == 0){
?>
								<tr>
									<td colspan="7"><input type="submit" name="add_batch" value="Submit" class="btn btn-success" style="width: 400px;"></td>
								</tr>
<?php
	}
?>
							</tbody>
						</table>
						</form>
<?php
	}else{
		$date = date("Y-m-d");
		$date_view = date_create($date);

		$rows = array();
		$select_sql = "SELECT item_no FROM batch_list
						ORDER BY item_no ASC";
		$select_result = mysqli_query($db, $select_sql);
		while($row = mysqli_fetch_assoc($select_result)){
			$rows[] = $row;
		}
?>
						<form method="post" action="batch_plant_report.php" onsubmit="return confirm('Do you really want to submit the form?');">
						<table class="table table-bordered table-striped" style="text-align: center;">
						<thead>
							<tr>
								<th colspan="7" style="text-align: left;">
									Date: <?php echo date_format($date_view,"F d, Y"); ?>
									<input type="hidden" name="hidden_date_view" value="<?php echo $date; ?>">		
								</th>					
							</tr>
							<tr>
								<th colspan="1" rowspan="3" style="vertical-align: middle; text-align: center;">Time</th>
								<th colspan="2">Machine No. M-1</th>
								<th colspan="2">Machine No. M-3a</th>
								<th colspan="2">Machine No. M-3b</th>
							</tr>
							<tr>
								<th colspan="2">Cement (kg): 
<?php 
	if(getBatchCement($db,$office,'M-1',$date) != ""){
							echo getBatchCement($db,$office,'M-1',$date); 
	}else{
?>
									<input type="text" name="cement1" id="cement1" required>
<?php
	}
?>
								</th>
								<th colspan="2">Cement (kg): 
<?php
	if(getBatchCement($db,$office,'M-3a',$date) != ""){
							echo getBatchCement($db,$office,'M-3a',$date); 
	}else{
?>
									<input type="text" name="cement2" id="cement2" required>
<?php
	}
?>								
								</th>
								<th colspan="2">Cement (kg): 
<?php
	if(getBatchCement($db,$office,'M-3b',$date) != ""){
							echo getBatchCement($db,$office,'M-3b',$date); 
	}else{
?>
									<input type="text" name="cement3" id="cement3" required>
<?php
	}
?>	
								</th>
							</tr>
							<tr>
								<th colspan="2">Type: 
<?php
	if(getBatchType($db,$office,'M-1',$date) != ""){
							echo getBatchType($db,$office,'M-1',$date); 
	}else{
?>
									<!-- <input type="text" name="type1" id="type1" class="form-control" required> -->
									<select name="type1" id="type1">
										<option value="">Select</option>
<?php
		foreach($rows as $row) {
									echo "<option value='".$row['item_no']."'>".$row['item_no']."</option>";
		}

?>


									</select>
<?php
	}
?>								
								</th>
								<th colspan="2">Type: 
<?php
	if(getBatchType($db,$office,'M-3a',$date) != ""){
							echo getBatchType($db,$office,'M-3a',$date); 
	}else{
?>
									<select name="type2" id="type2">
										<option value="">Select</option>
<?php
		foreach($rows as $row) {
									echo "<option value='".$row['item_no']."'>".$row['item_no']."</option>";
		}

?>


									</select>
<?php
	}
?>								
								</th>
								<th colspan="2">Type: 
<?php
	if(getBatchType($db,$office,'M-3b',$date) != ""){
							echo getBatchType($db,$office,'M-3b',$date); 
	}else{
?>
									<select name="type3" id="type3">
										<option value="">Select</option>
<?php
		foreach($rows as $row) {
									echo "<option value='".$row['item_no']."'>".$row['item_no']."</option>";
		}

?>


									</select>
<?php
	}
?>								
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
								<input type="hidden" name="time1[]" value="06:00:00">
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-1',$date,'6') != ""){
									echo getBatch($db,$office,'M-1',$date,'6');
	}else{
?>
									<input type="text" name="batch1[]" id="batch1" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-1',$date,'6') != ""){
									echo getComment($db,$office,'M-1',$date,'6');
	}else{
?>
									<input type="text" name="comment1[]" id="comment1" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-3a',$date,'6') != ""){
									echo getBatch($db,$office,'M-3a',$date,'6');
	}else{
?>
									<input type="text" name="batch2[]" id="batch2" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-3a',$date,'6') != ""){
									echo getComment($db,$office,'M-3a',$date,'6');
	}else{
?>
									<input type="text" name="comment2[]" id="comment2" class="form-control">
<?php		
	}
?>									
								</td>
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-3b',$date,'6') != ""){
									echo getBatch($db,$office,'M-3b',$date,'6');
	}else{
?>
									<input type="text" name="batch3[]" id="batch3" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-3b',$date,'6') != ""){
									echo getComment($db,$office,'M-3b',$date,'6');
	}else{
?>
									<input type="text" name="comment3[]" id="comment3" class="form-control">
<?php		
	}
?>									
								</td>
							</tr>
							<tr>
								<td class="col-md-1">7:00 - 8:00</td>
								<input type="hidden" name="time1[]" value="07:00:00">
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-1',$date,'7') != ""){
									echo getBatch($db,$office,'M-1',$date,'7');
	}else{
?>
									<input type="text" name="batch1[]" id="batch1" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-1',$date,'7') != ""){
									echo getComment($db,$office,'M-1',$date,'7');
	}else{
?>
									<input type="text" name="comment1[]" id="comment1" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-3a',$date,'7') != ""){
									echo getBatch($db,$office,'M-3a',$date,'7');
	}else{
?>
									<input type="text" name="batch2[]" id="batch2" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-3a',$date,'7') != ""){
									echo getComment($db,$office,'M-3a',$date,'7');
	}else{
?>
									<input type="text" name="comment2[]" id="comment2" class="form-control">
<?php		
	}
?>									
								</td>
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-3b',$date,'7') != ""){
									echo getBatch($db,$office,'M-3b',$date,'7');
	}else{
?>
									<input type="text" name="batch3[]" id="batch3" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-3b',$date,'7') != ""){
									echo getComment($db,$office,'M-3b',$date,'7');
	}else{
?>
									<input type="text" name="comment3[]" id="comment3" class="form-control">
<?php		
	}
?>									
								</td>
							</tr>
							<tr>
								<td class="col-md-1">8:00 - 9:00</td>
								<input type="hidden" name="time1[]" value="08:00:00">
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-1',$date,'8') != ""){
									echo getBatch($db,$office,'M-1',$date,'8');
	}else{
?>
									<input type="text" name="batch1[]" id="batch1" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-1',$date,'8') != ""){
									echo getComment($db,$office,'M-1',$date,'8');
	}else{
?>
									<input type="text" name="comment1[]" id="comment1" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-3a',$date,'8') != ""){
									echo getBatch($db,$office,'M-3a',$date,'8');
	}else{
?>
									<input type="text" name="batch2[]" id="batch2" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-3a',$date,'8') != ""){
									echo getComment($db,$office,'M-3a',$date,'8');
	}else{
?>
									<input type="text" name="comment2[]" id="comment2" class="form-control">
<?php		
	}
?>									
								</td>
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-3b',$date,'8') != ""){
									echo getBatch($db,$office,'M-3b',$date,'8');
	}else{
?>
									<input type="text" name="batch3[]" id="batch3" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-3b',$date,'8') != ""){
									echo getComment($db,$office,'M-3b',$date,'8');
	}else{
?>
									<input type="text" name="comment3[]" id="comment3" class="form-control">
<?php		
	}
?>									
								</td>
							</tr>
							<tr>
								<td class="col-md-1">9:00 - 10:00</td>
								<input type="hidden" name="time1[]" value="09:00:00">
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-1',$date,'9') != ""){
									echo getBatch($db,$office,'M-1',$date,'9');
	}else{
?>
									<input type="text" name="batch1[]" id="batch1" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-1',$date,'9') != ""){
									echo getComment($db,$office,'M-1',$date,'9');
	}else{
?>
									<input type="text" name="comment1[]" id="comment1" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-3a',$date,'9') != ""){
									echo getBatch($db,$office,'M-3a',$date,'9');
	}else{
?>
									<input type="text" name="batch2[]" id="batch2" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-3a',$date,'9') != ""){
									echo getComment($db,$office,'M-3a',$date,'9');
	}else{
?>
									<input type="text" name="comment2[]" id="comment2" class="form-control">
<?php		
	}
?>									
								</td>
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-3b',$date,'9') != ""){
									echo getBatch($db,$office,'M-3b',$date,'9');
	}else{
?>
									<input type="text" name="batch3[]" id="batch3" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-3b',$date,'9') != ""){
									echo getComment($db,$office,'M-3b',$date,'9');
	}else{
?>
									<input type="text" name="comment3[]" id="comment3" class="form-control">
<?php		
	}
?>									
								</td>
							</tr>
							<tr>
								<td class="col-md-1">10:00 - 11:00</td>
								<input type="hidden" name="time1[]" value="10:00:00">
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-1',$date,'10') != ""){
									echo getBatch($db,$office,'M-1',$date,'10');
	}else{
?>
									<input type="text" name="batch1[]" id="batch1" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-1',$date,'10') != ""){
									echo getComment($db,$office,'M-1',$date,'10');
	}else{
?>
									<input type="text" name="comment1[]" id="comment1" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-3a',$date,'10') != ""){
									echo getBatch($db,$office,'M-3a',$date,'10');
	}else{
?>
									<input type="text" name="batch2[]" id="batch2" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-3a',$date,'10') != ""){
									echo getComment($db,$office,'M-3a',$date,'10');
	}else{
?>
									<input type="text" name="comment2[]" id="comment2" class="form-control">
<?php		
	}
?>									
								</td>
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-3b',$date,'10') != ""){
									echo getBatch($db,$office,'M-3b',$date,'10');
	}else{
?>
									<input type="text" name="batch3[]" id="batch3" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-3b',$date,'10') != ""){
									echo getComment($db,$office,'M-3b',$date,'10');
	}else{
?>
									<input type="text" name="comment3[]" id="comment3" class="form-control">
<?php		
	}
?>									
								</td>
							</tr>
							<tr>
								<td class="col-md-1">11:00 - 12:00</td>
								<input type="hidden" name="time1[]" value="11:00:00">
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-1',$date,'11') != ""){
									echo getBatch($db,$office,'M-1',$date,'11');
	}else{
?>
									<input type="text" name="batch1[]" id="batch1" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-1',$date,'11') != ""){
									echo getComment($db,$office,'M-1',$date,'11');
	}else{
?>
									<input type="text" name="comment1[]" id="comment1" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-3a',$date,'11') != ""){
									echo getBatch($db,$office,'M-3a',$date,'11');
	}else{
?>
									<input type="text" name="batch2[]" id="batch2" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-3a',$date,'11') != ""){
									echo getComment($db,$office,'M-3a',$date,'11');
	}else{
?>
									<input type="text" name="comment2[]" id="comment2" class="form-control">
<?php		
	}
?>									
								</td>
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-3b',$date,'11') != ""){
									echo getBatch($db,$office,'M-3b',$date,'11');
	}else{
?>
									<input type="text" name="batch3[]" id="batch3" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-3b',$date,'11') != ""){
									echo getComment($db,$office,'M-3b',$date,'11');
	}else{
?>
									<input type="text" name="comment3[]" id="comment3" class="form-control">
<?php		
	}
?>									
								</td>
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
								<input type="hidden" name="time1[]" value="12:00:00">
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-1',$date,'12') != ""){
									echo getBatch($db,$office,'M-1',$date,'12');
	}else{
?>
									<input type="text" name="batch1[]" id="batch1" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-1',$date,'12') != ""){
									echo getComment($db,$office,'M-1',$date,'12');
	}else{
?>
									<input type="text" name="comment1[]" id="comment1" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-3a',$date,'12') != ""){
									echo getBatch($db,$office,'M-3a',$date,'12');
	}else{
?>
									<input type="text" name="batch2[]" id="batch2" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-3a',$date,'12') != ""){
									echo getComment($db,$office,'M-3a',$date,'12');
	}else{
?>
									<input type="text" name="comment2[]" id="comment2" class="form-control">
<?php		
	}
?>									
								</td>
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-3b',$date,'12') != ""){
									echo getBatch($db,$office,'M-3b',$date,'12');
	}else{
?>
									<input type="text" name="batch3[]" id="batch3" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-3b',$date,'12') != ""){
									echo getComment($db,$office,'M-3b',$date,'12');
	}else{
?>
									<input type="text" name="comment3[]" id="comment3" class="form-control">
<?php		
	}
?>									
								</td>
							</tr>
							<tr>
								<td class="col-md-1">1:00 - 2:00</td>
								<input type="hidden" name="time1[]" value="13:00:00">
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-1',$date,'13') != ""){
									echo getBatch($db,$office,'M-1',$date,'13');
	}else{
?>
									<input type="text" name="batch1[]" id="batch1" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-1',$date,'13') != ""){
									echo getComment($db,$office,'M-1',$date,'13');
	}else{
?>
									<input type="text" name="comment1[]" id="comment1" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-3a',$date,'13') != ""){
									echo getBatch($db,$office,'M-3a',$date,'13');
	}else{
?>
									<input type="text" name="batch2[]" id="batch2" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-3a',$date,'13') != ""){
									echo getComment($db,$office,'M-3a',$date,'13');
	}else{
?>
									<input type="text" name="comment2[]" id="comment2" class="form-control">
<?php		
	}
?>									
								</td>
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-3b',$date,'13') != ""){
									echo getBatch($db,$office,'M-3b',$date,'13');
	}else{
?>
									<input type="text" name="batch3[]" id="batch3" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-3b',$date,'13') != ""){
									echo getComment($db,$office,'M-3b',$date,'13');
	}else{
?>
									<input type="text" name="comment3[]" id="comment3" class="form-control">
<?php		
	}
?>									
								</td>
							</tr>
							<tr>
								<td class="col-md-1">2:00 - 3:00</td>
								<input type="hidden" name="time1[]" value="14:00:00">
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-1',$date,'14') != ""){
									echo getBatch($db,$office,'M-1',$date,'14');
	}else{
?>
									<input type="text" name="batch1[]" id="batch1" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-1',$date,'14') != ""){
									echo getComment($db,$office,'M-1',$date,'14');
	}else{
?>
									<input type="text" name="comment1[]" id="comment1" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-3a',$date,'14') != ""){
									echo getBatch($db,$office,'M-3a',$date,'14');
	}else{
?>
									<input type="text" name="batch2[]" id="batch2" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-3a',$date,'14') != ""){
									echo getComment($db,$office,'M-3a',$date,'14');
	}else{
?>
									<input type="text" name="comment2[]" id="comment2" class="form-control">
<?php		
	}
?>									
								</td>
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-3b',$date,'14') != ""){
									echo getBatch($db,$office,'M-3b',$date,'14');
	}else{
?>
									<input type="text" name="batch3[]" id="batch3" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-3b',$date,'14') != ""){
									echo getComment($db,$office,'M-3b',$date,'14');
	}else{
?>
									<input type="text" name="comment3[]" id="comment3" class="form-control">
<?php		
	}
?>									
								</td>
							</tr>
							<tr>
								<td class="col-md-1">3:00 - 4:00</td>
								<input type="hidden" name="time1[]" value="15:00:00">
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-1',$date,'15') != ""){
									echo getBatch($db,$office,'M-1',$date,'15');
	}else{
?>
									<input type="text" name="batch1[]" id="batch1" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-1',$date,'15') != ""){
									echo getComment($db,$office,'M-1',$date,'15');
	}else{
?>
									<input type="text" name="comment1[]" id="comment1" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-3a',$date,'15') != ""){
									echo getBatch($db,$office,'M-3a',$date,'15');
	}else{
?>
									<input type="text" name="batch2[]" id="batch2" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-3a',$date,'15') != ""){
									echo getComment($db,$office,'M-3a',$date,'15');
	}else{
?>
									<input type="text" name="comment2[]" id="comment2" class="form-control">
<?php		
	}
?>									
								</td>
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-3b',$date,'15') != ""){
									echo getBatch($db,$office,'M-3b',$date,'15');
	}else{
?>
									<input type="text" name="batch3[]" id="batch3" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-3b',$date,'15') != ""){
									echo getComment($db,$office,'M-3b',$date,'15');
	}else{
?>
									<input type="text" name="comment3[]" id="comment3" class="form-control">
<?php		
	}
?>									
								</td>
							</tr>
							<tr>
								<td class="col-md-1">4:00 - 5:00</td>
								<input type="hidden" name="time1[]" value="16:00:00">
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-1',$date,'16') != ""){
									echo getBatch($db,$office,'M-1',$date,'16');
	}else{
?>
									<input type="text" name="batch1[]" id="batch1" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-1',$date,'16') != ""){
									echo getComment($db,$office,'M-1',$date,'16');
	}else{
?>
									<input type="text" name="comment1[]" id="comment1" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-3a',$date,'16') != ""){
									echo getBatch($db,$office,'M-3a',$date,'16');
	}else{
?>
									<input type="text" name="batch2[]" id="batch2" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-3a',$date,'16') != ""){
									echo getComment($db,$office,'M-3a',$date,'16');
	}else{
?>
									<input type="text" name="comment2[]" id="comment2" class="form-control">
<?php		
	}
?>									
								</td>
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-3b',$date,'16') != ""){
									echo getBatch($db,$office,'M-3b',$date,'16');
	}else{
?>
									<input type="text" name="batch3[]" id="batch3" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-3b',$date,'16') != ""){
									echo getComment($db,$office,'M-3b',$date,'16');
	}else{
?>
									<input type="text" name="comment3[]" id="comment3" class="form-control">
<?php		
	}
?>									
								</td>
							</tr>
							<tr>
								<td class="col-md-1">5:00 - 6:00</td>
								<input type="hidden" name="time1[]" value="17:00:00">
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-1',$date,'17') != ""){
									echo getBatch($db,$office,'M-1',$date,'17');
	}else{
?>
									<input type="text" name="batch1[]" id="batch1" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-1',$date,'17') != ""){
									echo getComment($db,$office,'M-1',$date,'17');
	}else{
?>
									<input type="text" name="comment1[]" id="comment1[]" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-3a',$date,'17') != ""){
									echo getBatch($db,$office,'M-3a',$date,'17');
	}else{
?>
									<input type="text" name="batch2[]" id="batch2" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-3a',$date,'17') != ""){
									echo getComment($db,$office,'M-3a',$date,'17');
	}else{
?>
									<input type="text" name="comment2[]" id="comment2" class="form-control">
<?php		
	}
?>									
								</td>
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-3b',$date,'17') != ""){
									echo getBatch($db,$office,'M-3b',$date,'17');
	}else{
?>
									<input type="text" name="batch3[]" id="batch3" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-3b',$date,'17') != ""){
									echo getComment($db,$office,'M-3b',$date,'17');
	}else{
?>
									<input type="text" name="comment3[]" id="comment3" class="form-control">
<?php		
	}
?>									
								</td>
							</tr>
							<tr>
								<td class="col-md-1">6:00 - 7:00</td>
								<input type="hidden" name="time1[]" value="18:00:00">
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-1',$date,'18') != ""){
									echo getBatch($db,$office,'M-1',$date,'18');
	}else{
?>
									<input type="text" name="batch1[]" id="batch1" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-1',$date,'18') != ""){
									echo getComment($db,$office,'M-1',$date,'18');
	}else{
?>
									<input type="text" name="comment1[]" id="comment1" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-3a',$date,'18') != ""){
									echo getBatch($db,$office,'M-3a',$date,'18');
	}else{
?>
									<input type="text" name="batch2[]" id="batch2" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-3a',$date,'18') != ""){
									echo getComment($db,$office,'M-3a',$date,'18');
	}else{
?>
									<input type="text" name="comment2[]" id="comment2" class="form-control">
<?php		
	}
?>									
								</td>
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-3b',$date,'18') != ""){
									echo getBatch($db,$office,'M-3b',$date,'18');
	}else{
?>
									<input type="text" name="batch3[]" id="batch3" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-3b',$date,'18') != ""){
									echo getComment($db,$office,'M-3b',$date,'18');
	}else{
?>
									<input type="text" name="comment3[]" id="comment3" class="form-control">
<?php		
	}
?>									
								</td>
							</tr>
							<tr>
								<td class="col-md-1">7:00 - 8:00</td>
								<input type="hidden" name="time1[]" value="19:00:00">
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-1',$date,'19') != ""){
									echo getBatch($db,$office,'M-1',$date,'19');
	}else{
?>
									<input type="text" name="batch1[]" id="batch1" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-1',$date,'19') != ""){
									echo getComment($db,$office,'M-1',$date,'19');
	}else{
?>
									<input type="text" name="comment1[]" id="comment1" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-3a',$date,'19') != ""){
									echo getBatch($db,$office,'M-3a',$date,'19');
	}else{
?>
									<input type="text" name="batch2[]" id="batch2" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-3a',$date,'19') != ""){
									echo getComment($db,$office,'M-3a',$date,'19');
	}else{
?>
									<input type="text" name="comment2[]" id="comment2" class="form-control">
<?php		
	}
?>									
								</td>
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-3b',$date,'19') != ""){
									echo getBatch($db,$office,'M-3b',$date,'19');
	}else{
?>
									<input type="text" name="batch3[]" id="batch3" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-3b',$date,'19') != ""){
									echo getComment($db,$office,'M-3b',$date,'19');
	}else{
?>
									<input type="text" name="comment3[]" id="comment3" class="form-control">
<?php		
	}
?>									
								</td>
							</tr>
							<tr>
								<td class="col-md-1">8:00 - 9:00</td>
								<input type="hidden" name="time1[]" value="20:00:00">
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-1',$date,'20') != ""){
									echo getBatch($db,$office,'M-1',$date,'20');
	}else{
?>
									<input type="text" name="batch1[]" id="batch1" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-1',$date,'20') != ""){
									echo getComment($db,$office,'M-1',$date,'20');
	}else{
?>
									<input type="text" name="comment1[]" id="comment1" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-3a',$date,'20') != ""){
									echo getBatch($db,$office,'M-3a',$date,'20');
	}else{
?>
									<input type="text" name="batch2[]" id="batch2" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-3a',$date,'20') != ""){
									echo getComment($db,$office,'M-3a',$date,'20');
	}else{
?>
									<input type="text" name="comment2[]" id="comment2" class="form-control">
<?php		
	}
?>									
								</td>
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-3b',$date,'20') != ""){
									echo getBatch($db,$office,'M-3b',$date,'20');
	}else{
?>
									<input type="text" name="batch3[]" id="batch3" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-3b',$date,'20') != ""){
									echo getComment($db,$office,'M-3b',$date,'20');
	}else{
?>
									<input type="text" name="comment3[]" id="comment3" class="form-control">
<?php		
	}
?>									
								</td>
							</tr>
							<tr>
								<td class="col-md-1">9:00 - 10:00</td>
								<input type="hidden" name="time1[]" value="21:00:00">
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-1',$date,'21') != ""){
									echo getBatch($db,$office,'M-1',$date,'21');
	}else{
?>
									<input type="text" name="batch1[]" id="batch1" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-1',$date,'21') != ""){
									echo getComment($db,$office,'M-1',$date,'21');
	}else{
?>
									<input type="text" name="comment1[]" id="comment1" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-3a',$date,'21') != ""){
									echo getBatch($db,$office,'M-3a',$date,'21');
	}else{
?>
									<input type="text" name="batch2[]" id="batch2" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-3a',$date,'21') != ""){
									echo getComment($db,$office,'M-3a',$date,'21');
	}else{
?>
									<input type="text" name="comment2[]" id="comment2" class="form-control">
<?php		
	}
?>									
								</td>
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-3b',$date,'21') != ""){
									echo getBatch($db,$office,'M-3b',$date,'21');
	}else{
?>
									<input type="text" name="batch3[]" id="batch3" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-3b',$date,'21') != ""){
									echo getComment($db,$office,'M-3b',$date,'21');
	}else{
?>
									<input type="text" name="comment3[]" id="comment3" class="form-control">
<?php		
	}
?>									
								</td>
							</tr>
							<tr>
								<td class="col-md-1">10:00 - 11:00</td>
								<input type="hidden" name="time1[]" value="22:00:00">
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-1',$date,'22') != ""){
									echo getBatch($db,$office,'M-1',$date,'22');
	}else{
?>
									<input type="text" name="batch1[]" id="batch1" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-1',$date,'22') != ""){
									echo getComment($db,$office,'M-1',$date,'22');
	}else{
?>
									<input type="text" name="comment1[]" id="comment1" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-3a',$date,'22') != ""){
									echo getBatch($db,$office,'M-3a',$date,'22');
	}else{
?>
									<input type="text" name="batch2[]" id="batch2" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-3a',$date,'22') != ""){
									echo getComment($db,$office,'M-3a',$date,'22');
	}else{
?>
									<input type="text" name="comment2[]" id="comment2" class="form-control">
<?php		
	}
?>									
								</td>
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-3b',$date,'22') != ""){
									echo getBatch($db,$office,'M-3b',$date,'22');
	}else{
?>
									<input type="text" name="batch3[]" id="batch3" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-3b',$date,'22') != ""){
									echo getComment($db,$office,'M-3b',$date,'22');
	}else{
?>
									<input type="text" name="comment3[]" id="comment3" class="form-control">
<?php		
	}
?>									
								</td>
							</tr>
							<tr>
								<td class="col-md-1">11:00 - 12:00</td>
								<input type="hidden" name="time1[]" value="23:00:00">
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-1',$date,'23') != ""){
									echo getBatch($db,$office,'M-1',$date,'23');
	}else{
?>
									<input type="text" name="batch1[]" id="batch1" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-1',$date,'23') != ""){
									echo getComment($db,$office,'M-1',$date,'23');
	}else{
?>
									<input type="text" name="comment1[]" id="comment1" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-3a',$date,'23') != ""){
									echo getBatch($db,$office,'M-3a',$date,'23');
	}else{
?>
									<input type="text" name="batch2[]" id="batch2" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-3a',$date,'23') != ""){
									echo getComment($db,$office,'M-3a',$date,'23');
	}else{
?>
									<input type="text" name="comment2[]" id="comment2" class="form-control">
<?php		
	}
?>									
								</td>
								<td class="col-md-1">
<?php
	if(getBatch($db,$office,'M-3b',$date,'23') != ""){
									echo getBatch($db,$office,'M-3b',$date,'23');
	}else{
?>
									<input type="text" name="batch3[]" id="batch3" class="form-control">
<?php		
	}
?>
								</td>
								<td class="col-md-1">
<?php
	if(getComment($db,$office,'M-3b',$date,'23') != ""){
									echo getComment($db,$office,'M-3b',$date,'23');
	}else{
?>
									<input type="text" name="comment3[]" id="comment3" class="form-control">
<?php		
	}
?>									
								</td>
							</tr>
							<tr>
								<th class="col-md-1">Total</th>
								<th colspan="2">
<?php
	if($office == 'bravo'){
		$string_office = " AND office = 'bravo'";
	}else if($office == 'delta'){
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
	if($office == 'bravo'){
		$string_office = " AND office = 'bravo'";
	}else if($office == 'delta'){
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
	if($office == 'bravo'){
		$string_office = " AND office = 'bravo'";
	}else if($office == 'delta'){
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
<?php
	if($office == 'bravo'){
		$string_office = " AND office = 'bravo'";
	}else if($office == 'delta'){
		$string_office = " AND office = 'delta'";
	}else{
		$string_office = "";
	}

	$button_sql = "SELECT DATE_FORMAT(batch_date,'%m/%d/%Y') as prod_date
					FROM batch 
					WHERE DATE_FORMAT(batch_date,'%m/%d/%Y') = DATE_FORMAT('$date','%m/%d/%Y')".$string_office;

	$button_result = mysqli_query($db,$button_sql);
	if(mysqli_num_rows($button_result) == 0){
?>
							<tr>
								<td colspan="7"><input type="submit" name="add_batch" value="Submit" class="btn btn-success" style="width: 400px;"></td>
							</tr>
<?php
	}
?>
							
						</tbody>
					</table>
					</form>
<?php
	}
?>			
				</div>
			</div>
		</div>
		<div id="extra" style="height: 70px;"></div>
		<div id="footer">
<?php
	if($office == 'delta'){
		echo "<h4>Quality Star Concrete Products, Inc.</h4>";
	}else{
		echo "<h4>Starcrete Manufacturing Corporation</h4>";
	}
?>
		</div>	
	</div>
</body>
</html>

<?php
	if(isset($_POST['add_batch'])){	//input for batches per hour

		$machine1 = "M-1";
		$machine2 = "M-3a";
		$machine3 = "M-3b";
		$item1 = mysqli_real_escape_string($db, $_POST['type1']);
		$item2 = mysqli_real_escape_string($db, $_POST['type2']);
		$item3 = mysqli_real_escape_string($db, $_POST['type3']);
		$cement1 = mysqli_real_escape_string($db, $_POST['cement1']);
		$cement2 = mysqli_real_escape_string($db, $_POST['cement2']);
		$cement3 = mysqli_real_escape_string($db, $_POST['cement3']);
		$batches1 = mysqli_real_escape_string($db, $_POST['batch1']);
		$batches2 = mysqli_real_escape_string($db, $_POST['batch2']);
		$batches3 = mysqli_real_escape_string($db, $_POST['batch3']);
		$comment1 = mysqli_real_escape_string($db, $_POST['comment1']);
		$comment2 = mysqli_real_escape_string($db, $_POST['comment2']);
		$comment3 = mysqli_real_escape_string($db, $_POST['comment3']);
		$date = date_create(mysqli_real_escape_string($db, $_POST['hidden_date_view']));
		$datetime = date_format($date,"Y-m-d");
		$time = mysqli_real_escape_string($db, $_POST['time1']);

		for($i = 0; $i < count($batches1); $i++){
			if($batches1[$i] == ""){
				$batches1[$i] = "0";
			}
			if($comment1[$i] == ""){
				$comment1[$i] = "No comment";
			}

			$fulldate = $datetime." ".$time[$i]; 
			$batch_query1 = "INSERT INTO batch(machine_no, item_no, cement, batch_count, batch_date, comment, office)
							VALUES('$machine1','$item1','$cement1','$batches1[$i]','$fulldate','$comment1[$i]','$office')";

			$cement_total1 = $batches1[$i] * $cement1;
			$cement_update1 = "UPDATE item_stock SET stock = stock - '$cement_total1', last_update = '$fulldate'
								WHERE office = '$office' AND item_no = 'Cement'";

			// echo $batch_query1;
			// echo $cement_update1;				
			if(mysqli_query($db, $batch_query1) && mysqli_query($db, $cement_update1)){
				// phpAlert("Batch successfully added!!");
				echo "<meta http-equiv='refresh' content='0'>";
			}else{
				phpAlert("Something went wrong!!");
			}
		}

		for($i = 0; $i < count($batches2); $i++){
			if($batches2[$i] == ""){
				$batches2[$i] = "0";
			}
			if($comment2[$i] == ""){
				$comment2[$i] = "No comment";
			}
			$fulldate = $datetime." ".$time[$i]; 
			$batch_query2 = "INSERT INTO batch(machine_no, item_no, cement, batch_count, batch_date, comment, office)
							VALUES('$machine2','$item2','$cement2','$batches2[$i]','$fulldate','$comment2[$i]','$office')";

			$cement_total2 = $batches2[$i] * $cement2;
			$cement_update2 = "UPDATE item_stock SET stock = stock - '$cement_total2', last_update = '$fulldate'
								WHERE office = '$office' AND item_no = 'Cement'";

			// echo $batch_query2;
			// echo $cement_update2;				
			if(mysqli_query($db, $batch_query2) && mysqli_query($db, $cement_update2)){
				// phpAlert("Batch successfully added!!");
				echo "<meta http-equiv='refresh' content='0'>";
			}else{
				phpAlert("Something went wrong!!");
			}
		}

		for($i = 0; $i < count($batches3); $i++){
			if($batches3[$i] == ""){
				$batches3[$i] = "0";
			}
			if($comment3[$i] == ""){
				$comment3[$i] = "No comment";
			}
			$fulldate = $datetime." ".$time[$i]; 
			$batch_query3 = "INSERT INTO batch(machine_no, item_no, cement, batch_count, batch_date, comment, office)
							VALUES('$machine3','$item3','$cement3','$batches3[$i]','$fulldate','$comment3[$i]','$office')";

			$cement_total3 = $batches3[$i] * $cement3;
			$cement_update3 = "UPDATE item_stock SET stock = stock - '$cement_total3', last_update = '$fulldate'
								WHERE office = '$office' AND item_no = 'Cement'";

			// echo $batch_query3;
			// echo $cement_update3;				
			if(mysqli_query($db, $batch_query3) && mysqli_query($db, $cement_update3)){
				// phpAlert("Batch successfully added!!");
				echo "<meta http-equiv='refresh' content='0'>";
			}else{
				phpAlert("Something went wrong!!");
			}
		}
	}


?>