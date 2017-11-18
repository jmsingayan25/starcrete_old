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

?>
<html>
<head>
<?php
	if($office == 'delta'){
		echo "<title>Stock Report - Quality Star Concrete Products, Inc.</title>";
	}else{
		echo "<title>Stock Report - Starcrete Manufacturing Corporation</title>";
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
	min-height: 550px;
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
  height: 390px;
  /*width: 99%;*/
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

#myBtn {
  display: none;
  position: fixed;
  bottom: 70px;
  right: 10px;
  z-index: 99;
  border: none;
  outline: none;
  background-color: red;
  color: white;
  cursor: pointer;
  padding: 15px;
  border-radius: 10px;
}

#myBtn:hover {
  background-color: #555;
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
<body>
	<div id="mySidenav" class="sidenav">
		<a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
		<a href="index.php">Home</a>
		<hr>
		<!-- <a href="stock.php">Stock Report</a> -->
<!-- <?php
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
?> -->
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
			<span style="font-size:25px; cursor:pointer; color: white;">Stock Report</span>
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

	<div id="wrapper" onclick="closeNav();">
		<div id="content">
			<div class="row" style="margin: 0px;">
<?php 
	if($office == 'head'){
?>

				<div class="col-md-5 col-md-offset-1">
					<table class="table table-striped">
						<thead>
							<tr>
								<th colspan="3">Bravo</th>
							</tr>
							<tr>
				                <th class="col-md-1">Item</th>
				                <th class="col-md-1">Stock</th>
				                <th class="col-md-1">Last Update</th>
			              	</tr>
						</thead>
						<tbody>
<?php



	$sql = "SELECT s.item_no, CONCAT(FORMAT(s.stock,0),' ',l.volume) as stock, s.office, s.last_update 
			FROM item_stock s, item_list l
            WHERE s.item_no = l.item_no
            AND s.office = 'bravo' 
            ORDER BY s.item_no ASC";

    $result = mysqli_query($db,$sql);
	if(mysqli_num_rows($result) > 0){
		while($row = mysqli_fetch_array($result)){
			$date = date_create($row['last_update']);
?>
							<tr>
								<td class='col-md-1'><strong><?php echo $row['item_no']; ?></strong></td>
								<td class='col-md-1'><strong><?php echo $row['stock']; ?></strong></td>
								<td class='col-md-1'><strong><?php echo date_format($date,("m/d/y g:i A")); ?></strong></td>
							</tr>
<?php
      	}
    }else{
?>
							<tr>
								<td style='width: 1500px; height: 395px; background: white; border: none; text-align:center; 
							vertical-align:middle;'><h4><p class='text-muted'>No data found</p></h4></td>
							</tr>
<?php
    }
?>
						</tbody>
					</table>
				</div>
				<div class="col-md-5">
					<table class="table table-striped">
						<thead>
							<tr>
								<th colspan="3">Delta</th>
							</tr>
							<tr>
				                <th class="col-md-1">Item</th>
				                <th class="col-md-1">Stock</th>
				                <th class="col-md-1">Last Update</th>
			              	</tr>
						</thead>
						<tbody>
<?php



	$sql = "SELECT s.item_no, CONCAT(FORMAT(s.stock,0),' ',l.volume) as stock, s.office, s.last_update 
			FROM item_stock s, item_list l
            WHERE s.item_no = l.item_no
            AND s.office = 'delta' 
            ORDER BY s.item_no ASC";

    $result = mysqli_query($db,$sql);
	if(mysqli_num_rows($result) > 0){
		while($row = mysqli_fetch_array($result)){
			$date = date_create($row['last_update']);
?>
							<tr>
								<td class='col-md-1'><strong><?php echo $row['item_no']; ?></strong></td>
								<td class='col-md-1'><strong><?php echo $row['stock']; ?></strong></td>
								<td class='col-md-1'><strong><?php echo date_format($date,("m/d/y g:i A")); ?></strong></td>
							</tr>
<?php
      	}
    }else{
?>
							<tr>
								<td style='width: 1500px; height: 395px; background: white; border: none; text-align:center; 
							vertical-align:middle;'><h4><p class='text-muted'>No data found</p></h4></td>
							</tr>
<?php
    }
?>
						</tbody>
					</table>
				</div>
<?php
	}else{
?>
				<div class="col-md-6 col-md-offset-3">
					<table class="table table-striped">
						<thead>
							<tr>
				                <th class="col-md-1">Item</th>
				                <th class="col-md-1">Stock</th>
				                <th class="col-md-1">Last Update</th>
			              	</tr>
						</thead>
						<tbody style="height: 430px;">
<?php



	$sql = "SELECT s.item_no, CONCAT(FORMAT(s.stock,0),' ',l.volume) as stock, s.office, s.last_update 
			FROM item_stock s, item_list l
            WHERE s.item_no = l.item_no
            AND s.office = '$office' 
            ORDER BY s.item_no ASC";

    $result = mysqli_query($db,$sql);
	if(mysqli_num_rows($result) > 0){
		while($row = mysqli_fetch_array($result)){
			$date = date_create($row['last_update']);
?>
							<tr>
								<td class='col-md-1'><strong><?php echo $row['item_no']; ?></strong></td>
								<td class='col-md-1'><strong><?php echo $row['stock']; ?></strong></td>
								<td class='col-md-1'><strong><?php echo date_format($date,("m/d/y g:i A")); ?></strong></td>
							</tr>
<?php
      	}
    }else{
?>
							<tr>
								<td style='width: 1500px; height: 395px; background: white; border: none; text-align:center; 
							vertical-align:middle;'><h4><p class='text-muted'>No data found</p></h4></td>
							</tr>
<?php
    }
?>
						</tbody>
					</table>
				</div>
<?php
	}
?>
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


