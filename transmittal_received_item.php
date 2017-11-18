<!DOCTYPE html>
<?php

	include("includes/config.php");
	include("includes/function.php");

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

	if(isset($_POST['received']) && isset($_POST['pass_transmittal_no']) && isset($_POST['pass_from_office']) && isset($_POST['pass_to_office']) && isset($_POST['pass_id'])){
		
		$_SESSION['received'] = $_POST['received'];
		$_SESSION['pass_transmittal_no'] = $_POST['pass_transmittal_no'];
		$_SESSION['pass_from_office'] = $_POST['pass_from_office'];
		$_SESSION['pass_to_office'] = $_POST['pass_to_office'];
		$_SESSION['pass_id'] = $_POST['pass_id'];
	}

		$transmittal_no = $_SESSION['pass_transmittal_no'];
		$from_office = $_SESSION['pass_from_office'];
		$to_office = $_SESSION['pass_to_office'];
		$unique_id = $_SESSION['pass_id'];
	
?>
<html>
<head>
<?php
	if($office == 'delta'){
		echo "<title>Received Item - Transmittal - Quality Star Concrete Products, Inc.</title>";
	}else{
		echo "<title>Received Item - Transmittal - Starcrete Manufacturing Corporation</title>";
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

<style>
#wrapper {
	min-height:83%;
	position:relative;
}

#content {
	margin: 0 auto;
	min-height: 506px;
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
.table tbody{
  overflow-y: scroll;
  height: 350px;
  /*width: 886px;*/
  position: absolute;
  border:1px solid #cecece;
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
.row.content {
	min-height: 491px;
}
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

</style>
</head>
<body>
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
	<nav class="navbar navbar-default" id="secondary-nav" style="background-color: #0884e4; margin-bottom: 10px; vertical-align: middle;">
		<div class="container-fluid">
			<!-- <span style="font-size:30px; cursor:pointer; color: white;" onclick="openNav();">&#9776;</span> -->
			<span style="font-size:25px; color: white;">Transmittal > List of Items</span>
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

	<div id="wrapper">
		<div id="header">
			<div class="row" style="margin: 0px;">
				<div class="col-md-12">
					<button type="button" onclick="location.href='transmittal.php';" class="btn btn-default" style="margin-bottom: 10px; float: left;"><span class="glyphicon glyphicon-arrow-left"></span> Back to Transmittal</button>
				</div>
			</div>
		</div>
		<div id="content">
			<div class="row">
				<form action="transmittal_received_item.php" method="post">
					<div class="col-md-8 col-md-offset-2">
						<table class="table table-striped">
							<thead>
								<tr>
									<th colspan="2">Transmittal No. <?php echo $transmittal_no; ?></th>
									<th colspan="1"></th>
									<th colspan="2">Transfer From: 
									<?php
										if($from_office == 'Head' || $from_office == 'head'){
											echo 'Head Office';
										}else{
											echo ucfirst($from_office);
										}
									?>
									</th>
								</tr>
								<tr>
									<th class="col-md-1">#</th>
									<th class="col-md-1">Item</th>
									<th class="col-md-1">Quantity</th>
									<th class="col-md-1">Purpose</th>
									<th class="col-md-1">Status</th>
								</tr>
							</thead>
							<tbody>
<?php
		$sql = "SELECT * FROM transmittal 
				WHERE transmittal_no = '$transmittal_no' 
				AND from_office = '$from_office' 
				AND office = '$to_office'
				AND transmittal_unique_id = '$unique_id'";

		$result = mysqli_query($db, $sql);
		$count = 1;
		while($row = mysqli_fetch_assoc($result)){
?>
								<tr>
									<td class="col-md-1"><strong><?php echo $count; ?></strong></td>
									<td class="col-md-1"><strong><?php echo $row['item_no']; ?></strong></td>
									<td class="col-md-1"><strong><?php echo $row['quantity']; ?></strong></td>
									<td class="col-md-1"><strong><?php echo $row['purpose']; ?></strong></td>
									<td class="col-md-1">
<?php
						if($row['remarks'] == 'Pending'){
?>
										<button type="submit" id="submit" name="submit" value="<?php echo $row['transmittal_id']; ?>" class="btn btn-success btn-sm" onclick="return confirm('Confirm <?php echo $row['item_no']; ?> received?')">Received</button>
<?php
						}else{
?>
										<strong><?php echo $row['remarks']; ?></strong>
<?php
						}
?>
									</td>
								</tr>
<?php
		$count++;
		}
?>
							</tbody>
						</table>	
					</div>
				</form>
			</div>
		</div>
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
	
	if(isset($_POST['submit'])){
		$transmittal_id = $_POST['submit'];
		$datetime = date("Y-m-d H:i:s");

		$sql = "UPDATE transmittal SET remarks = 'Received', transmittal_date = '$datetime' 
				WHERE transmittal_id = '$transmittal_id'";

		$query = "SELECT * FROM transmittal WHERE transmittal_id = '$transmittal_id'";
		$result = mysqli_query($db, $query);
		$row = mysqli_fetch_assoc($result);

		$history = "INSERT INTO history(table_report, transaction_type, item_no, detail, history_date, office)
					VALUES('Transmittal','Received','".$row['item_no']."','".$row['delivered_by']." from ". $row['from_office']." transferred ".$row['quantity']." of ".$row['item_no']." to ".$row['office']."','$datetime','".$row['office']."')";
		// echo $history;
		// echo $sql;
		if(mysqli_query($db, $sql) && mysqli_query($db, $history)){
			echo "<meta http-equiv='refresh' content='0'>";
		}
	}
?>