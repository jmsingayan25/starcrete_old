
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

	if(isset($_POST['transferred']) && isset($_POST['pass_transmittal_no']) && isset($_POST['pass_from_office']) && isset($_POST['pass_to_office']) && isset($_POST['pass_id'])){
		
		$_SESSION['transferred'] = $_POST['transferred'];
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
		echo "<title>Transfer Item - Transmittal - Quality Star Concrete Products, Inc.</title>";
	}else{
		echo "<title>Transfer Item - Transmittal - Starcrete Manufacturing Corporation</title>";
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
	min-height: 517px;
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
  width: 98%;
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

.modal_input td{
	padding: 10px;
	border: 1px solid #d1d1d1;
}

.row.content {
	min-height: 491px
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
		<a href="batch.php">Batch Report</a>
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
	<nav class="navbar navbar-default" id="secondary-nav" style="background-color: #0884e4; margin-bottom: 10px; vertical-align: middle;">
		<div class="container-fluid">
			<span style="font-size:30px; cursor:pointer; color: white;" onclick="openNav();">&#9776;</span>
			<span style="font-size:25px; color: white;"><a href="transmittal_transfer.php" style="color: white;">Transferred Item</a> > List of Items</span>
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

	<div id="wrapper" onclick="closeNav()">
		<div id="header">
			<!-- <div class="row" style="margin: 0px;">
				<div class="col-md-12">
					<button type="button" onclick="location.href='transmittal_transfer.php';" class="btn btn-default" style="margin-bottom: 10px; float: left;"><span class="glyphicon glyphicon-arrow-left"></span> Back to Transferred Items</button>	
				</div>
			</div> -->
		</div>
		<div id="content">
			<div class="row" style="margin: 0px;">
				<form action="transmittal_received_item.php" method="post" class="form-inline">
					<div class="col-md-8 col-md-offset-2">
						<table class="table table-striped">
							<thead>
								<tr>
									<th class="col-md-1"></th>
									<th class="col-md-1">Transmittal No. <?php echo $transmittal_no; ?></th>
									<th class="col-md-1">Transfer To: 
									<?php
										if($to_office == 'Head' || $to_office == 'head'){
											echo 'Head Office';
										}else{
											echo ucfirst($to_office);
										}
									?>
									</th>
									<th class="col-md-1"></th>
								</tr>
								<tr>
									<th class="col-md-1">#</th>
									<th class="col-md-1">Item</th>
									<th class="col-md-1">Quantity</th>
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
									<td class="col-md-1"><strong><?php echo $row['remarks']; ?></strong></td>
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
