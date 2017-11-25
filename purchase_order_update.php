<!DOCTYPE html>
<?php
	
	include("includes/config.php");
	include("includes/function.php");
	// header("Cache-Control: no cache");
	// session_cache_limiter("private_no_expire");
	session_start();
	if(!isset($_SESSION['login_user'])) {
		header("location: login.php");
	}

	if(isset($_REQUEST['post_purchase_id'])){
		$_SESSION['purchase_id'] = $_POST['post_purchase_id'];
	}

	$user_query = $db->prepare("SELECT * FROM users WHERE username = ?");
	$user_query->bind_param('s', $_SESSION['login_user']);
	$user_query->execute();
	$result = $user_query->get_result();
	$user = $result->fetch_assoc();

	$office = $user['office'];
	$position = $user['position'];

	$purchase_id = $_SESSION['purchase_id'];
	$search_sql = "SELECT * FROM purchase_order WHERE purchase_id = '$purchase_id'";
	$search_result = mysqli_query($db, $search_sql);
	$purchase_row = mysqli_fetch_assoc($search_result);

?>
<html>
<head>
<?php
	if($office == 'delta'){
		echo "<title>Update Order - Purchase Order - Quality Star Concrete Products, Inc.</title>";
	}else{
		echo "<title>Update Order - Purchase Order - Starcrete Manufacturing Corporation</title>";
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
html, body {
	margin:0;
	padding:0;
	height:100%;
}

#wrapper {
	min-height:79%;
	position:relative;
}

#content {
	margin: 37px auto auto auto;
	width: 40%;
	padding-bottom:20px; /* Height of the footer element */
	border: 1px solid #bababa;
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

#box{
	position: absolute;
	min-height: 510px;
	z-index: 15;
	top: 50%;
	left: 50%;
	margin: -25px 0 0 -300px;
	width: 600px;
    padding: 15px;
    border: 1px solid #bababa;
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
	<nav class="navbar navbar-default" id="secondary-nav" style="background-color: #0884e4; margin-bottom: 10px;">
		<div class="container-fluid">
			<span style="font-size:30px; cursor:pointer; color: white;" onclick="openNav();">&#9776;</span>
			<span style="font-size:25px; color: white;"><a href="purchase_order.php" style="color: white;">Purchase Order</a> > Update</span>
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
		<div id="content">
			<form action="purchase_order_update.php" method="post" class="form-inline">
			<div class="row">
				<div class="col-md-12" style="text-align: center; background-color: #0884e4; color: white; padding: 10px; margin: -15px 0 15px 0;"><h3><strong>Update Form</strong></h3>
				</div>
			</div>
			<div class="row" style="margin-left: 10px; margin-bottom: 5px;">
				<div class="col-md-6">
					<div class="form-group">
						<label for="po_no">P.O. No.</label>
						<input type="text" id="update_po_no" name="update_po_no" value="<?php echo $purchase_row['purchase_order_no']; ?>" class="form-control" autocomplete="off" required>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="item">Current Item</label>
						<input type="text" id="item_no" name="item_no" value="<?php echo $purchase_row['item_no']; ?>" class="form-control" autocomplete="off" readonly>
					</div>
				</div>
			</div>
			<div class="row" style="margin-left: 10px; margin-bottom: 5px;">
				<div class="col-md-6">
					<div class="form-group">
						<label for="item">New Item</label>
						<br>
						<input list="item_nos" name="update_item_no" class="form-control">
						<datalist id="item_nos">
<?php
	$sql = "SELECT item_no FROM batch_list ORDER BY item_no ASC";
	$result = mysqli_query($db, $sql);
	foreach($result as $row){
									echo "<option value='" . $row['item_no'] . "'>" . $row['item_no'] . "</option>";
	}
?>
						</datalist>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="quantity">Quantity</label>
						<input type="text" id="update_quantity" name="update_quantity" class="form-control" autocomplete="off" value="<?php echo $purchase_row['quantity']; ?>" required>
					</div>
				</div>
			</div>
			<!-- <div class="row">
				<div class="col-md-12" style="text-align: center; background-color: #0884e4; color: white; padding: 5px;"><strong>Items</strong>
				</div>
			</div> -->
			<!-- <div class="row" style="text-align: center; background-color: white; padding: 5px;">
				<div class="col-md-6">
					<label for="item">Item</label>
				</div>
				<div class="col-md-6">
					<label for="quantity">Quantity</label>
				</div>
			</div>
			<div class="row">
				<table id="item_table" align="center">
					<tr id="row1" style="text-align: center;">
						<td class="col-md-6">
							<div class="form-group">
								<input list="item_nos" name="item_no" class="form-control" value="<?php echo $purchase_row['item_no']; ?>">
									<datalist id="item_nos">
<?php
	$sql = "SELECT item_no FROM batch_list ORDER BY item_no ASC";
	$result = mysqli_query($db, $sql);
	foreach($result as $row){
									echo "<option value='" . $row['item_no'] . "'>" . $row['item_no'] . "</option>";
	}
?>
									</datalist>
							</div>
						</td>
						<td class="col-md-6">
							<div class="form-group">
								<input type="text" id="quantity" name="quantity" class="form-control" autocomplete="off" value="<?php echo $purchase_row['quantity']; ?>" required>
							</div>
						</td>
					</tr>
				</table>
			</div> -->
			<hr>
			<div class="row">
				<div class="col-md-12">
					<input type="submit" name="submit" value="Submit" class="btn btn-primary btn-block">
					<a href="purchase_order.php" class="btn btn-warning btn-block">Cancel</a>
				</div>
			</div>
			</form>
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

		$update_purchase_order_no = mysqli_real_escape_string($db, $_POST['update_po_no']);
		$update_quantity = mysqli_real_escape_string($db, $_POST['update_quantity']);

		if(isset($_POST['update_item_no']) && $_POST['update_item_no'] != ''){
			$update_item_no = mysqli_real_escape_string($db, $_POST['update_item_no']);
		}else{
			$update_item_no = mysqli_real_escape_string($db, $_POST['item_no']);
		}

		$datetime = date("Y/m/d H:i:s");
		$plant = ucfirst($purchase_row['office']);

		// $reply = array('post' => $_POST);
		// echo json_encode($reply);

		$sql_update = "UPDATE purchase_order SET purchase_order_no = '$purchase_order_no', item_no = '$item_no', quantity = '$quantity' WHERE purchase_id = '$purchase_id'";

		if($update_purchase_order_no != $purchase_row['purchase_order_no'] && $update_item_no == $purchase_row['item_no'] && $update_quantity == $purchase_row['quantity']){
			// update po no only
			$history = "INSERT INTO history(table_report, transaction_type, detail, history_date, office) 
		 					VALUES('Purchase Order','Update P.O. Detail','$plant update P.O. No. ".$purchase_row['purchase_order_no']." details','$datetime','".$purchase_row['office']."')";
		}else if($update_purchase_order_no == $purchase_row['purchase_order_no'] && $update_item_no != $purchase_row['item_no'] && $update_quantity == $purchase_row['quantity'])
			// update item only
		}else if($update_purchase_order_no == $purchase_row['purchase_order_no'] && $update_item_no == $purchase_row['item_no'] && $update_quantity != $purchase_row['quantity']){
			// update quantity only
		}else if($update_purchase_order_no != $purchase_row['purchase_order_no'] && $update_item_no != $purchase_row['item_no'] && $update_quantity == $purchase_row['quantity']){
			// update po no and item only
		}else if($update_purchase_order_no != $purchase_row['purchase_order_no'] && $update_item_no == $purchase_row['item_no'] && $update_quantity != $purchase_row['quantity']){
			// update po no and quantity only
		}else if($update_purchase_order_no == $purchase_row['purchase_order_no'] && $update_item_no != $purchase_row['item_no'] && $update_quantity != $purchase_row['quantity']){
			// update item and quantity only
		}else if($update_purchase_order_no != $purchase_row['purchase_order_no'] && $update_item_no != $purchase_row['item_no'] && $update_quantity != $purchase_row['quantity']){
			// update all fields
		}



		// echo "<script> alert('Purchase Order No. succesfully updated')</script>";
		// if(mysqli_query($db, $sql_update) && mysqli_query($db, $history)){
		// 	echo "<script> alert('Purchase Order No. succesfully updated'); 
		// 					window.location.href='purchase_order.php'
		// 		  </script>";
		// }else{
		// 	phpAlert('Something went wrong. Please try again.');
		// 	echo "<meta http-equiv='refresh' content='0'>";
		// }

		// echo $sql_update."<br>";
		// echo $history;
	}

?>