<!DOCTYPE html>
<?php

	include("includes/config.php");
	include("includes/function.php");
	//include("delivery_function.php");
	// header("Cache-Control: no cache");
	// session_cache_limiter("private_no_expire");
	session_start();
	if(!isset($_SESSION['login_user'])) {
		header("location: login.php");
	}

	if(isset($_REQUEST['post_delivery_id'])){
		$_SESSION['delivery_id'] = $_REQUEST['post_delivery_id'];
	}

	$user_query = $db->prepare("SELECT * FROM users WHERE username = ?");
	$user_query->bind_param('s', $_SESSION['login_user']);
	$user_query->execute();
	$result = $user_query->get_result();
	$user = $result->fetch_assoc();

	$office = $user['office'];
	$position = $user['position'];

	$delivery_id = $_SESSION['delivery_id'];
	$search_sql = "SELECT * FROM delivery WHERE delivery_id = '$delivery_id'";
	$search_result = mysqli_query($db, $search_sql);
	$delivery_row = mysqli_fetch_assoc($search_result);

?>
<html>
<head>
<?php
	if($office == 'delta'){
		echo "<title>Pending Orders - Delivery Report - Quality Star Concrete Products, Inc.</title>";
	}else{
		echo "<title>Pending Orders - Delivery Report - Starcrete Manufacturing Corporation</title>";
	}
?>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
	<link href="css_ext/sidebar.css" rel="stylesheet">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js_ext/sidebar.js"></script>
<script>

	function compareValues(number) {
		
		// var balance = document.getElementById('hidden_quantity').value;
		var submit = document.getElementById('submit');
		// var letters = /^[0-9a-zA-Z]+$/; 

		setTimeout(function () {
			if(isNaN(number)){
				submit.disabled = true;
				// a.style.display = "block";
			}else{
				submit.disabled = false;
				// a.style.display = "none";
			}
		}, 0);
		// alert(ordered);
	}
</script>
<style>
html, body {
	margin:0;
	padding:0;
	height:100%;
}

#wrapper {
	min-height:78%;
	position:relative;
}

#content {
	margin: 37px auto auto auto;
	width: 40%;
	padding-bottom: 20px; /* Height of the footer element */
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
<body onload="compareValues('');">
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
			<span style="font-size:25px; color: white;"><a href="delivery.php" style="color: white;">Delivery Report</a> > Update DR No.</span>
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
			<form action="delivery_update.php" method="post" class="form-inline" onsubmit="return confirm('Do you really want to submit the form?');">
			<div class="row">
				<!-- <p id="demo"></p> -->
				<div class="col-md-12" style="text-align: center; background-color: #0884e4; color: white; padding: 10px; margin: -15px 0 15px 0;"><h3><strong>Details</strong></h3>
				</div>
			</div>
			<div class="row" style="margin-left: 10px; margin-bottom: 5px;">
				<div class="col-md-6">
					<div class="form-group">
						<label>DR No.</label>
						<input type="text" name="update_delivery_receipt_no" value="<?php echo $delivery_row['delivery_receipt_no'] ?>" class="form-control" required>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label>Gate Pass</label>
						<input type="text" name="update_gate_pass" value="<?php echo $delivery_row['gate_pass'] ?>" class="form-control" required>
					</div>
				</div>
			</div>
			<div class="row" style="margin-left: 10px; margin-bottom: 5px;">
				<div class="col-md-6">
					<div class="form-group">
						<label for="item_no">Current Item</label>
						<input type="text" name="item_no" value="<?php echo $delivery_row['item_no']; ?>" class="form-control" readonly>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label>Quantity</label>
						<input type="text" name="update_quantity" value="<?php echo str_replace(',','',$delivery_row['quantity']); ?>" class="form-control" onkeyup="compareValues(this.value)" required>
					</div>
				</div>
			</div>
			<div class="row" style="margin-left: 10px; margin-bottom: 5px;">
				<div class="col-md-6">
					<div class="form-group">
						<label>New Item</label>
						<input list="update_items" name="update_item" size="20" class="form-control">
						<datalist id="update_items">
<?php
	$item_sql = "SELECT item_no FROM batch_list
			WHERE item_no != '".$row['item_no']."' ORDER BY item_no ASC";
	$result1 = mysqli_query($db, $item_sql);
	foreach($result1 as $row1){
									echo "<option value='" . $row1['item_no'] . "'>" . $row1['item_no'] . "</option>";
	}
?>
						</datalist>
					</div>
				</div>
			</div>
			<hr>
			<div class="row">
				<div class="col-md-12">
					<input type="submit" name="submit" id="submit" value="Submit" class="btn btn-primary btn-block">
					<a href="delivery.php" class="btn btn-warning btn-block">Cancel</a>
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

		$new_delivery_receipt_no = mysqli_real_escape_string($db, $_POST['update_delivery_receipt_no']);
		$new_quantity = mysqli_real_escape_string($db, $_POST['update_quantity']);
		$new_gate_pass = mysqli_real_escape_string($db, $_POST['update_gate_pass']);
		
		$datetime = date("Y/m/d H:i:s");

		if(isset($_POST['update_item']) && $_POST['update_item'] != ''){
			$new_item_no = mysqli_real_escape_string($db, $_POST['update_item']);
		}else{
			$new_item_no = mysqli_real_escape_string($db, $_POST['item_no']);
		}
		$sql = "UPDATE delivery 
				SET item_no = '$new_item_no', delivery_receipt_no = '$new_delivery_receipt_no', quantity = '$new_quantity', gate_pass = '$new_gate_pass' 
				WHERE delivery_id = '$delivery_id' AND office = '$office'";

		// $sql = "UPDATE delivery SET item_no = '$new_item_no' WHERE delivery_id = '$delivery_id' AND delivery_receipt_no = '$delivery_receipt_no' AND fk_po_id = '$fk_po_id' AND office = '$office'";

		if($new_delivery_receipt_no != $delivery_row['delivery_receipt_no'] && $new_item_no == $delivery_row['item_no'] && $new_gate_pass == $delivery_row['gate_pass'] && $new_quantity == $delivery_row['quantity']){
			// update DR No. only
			$history = "INSERT INTO history(table_report, transaction_type, item_no, detail, history_date, office) VALUES('Delivery','Update Delivery Order' ,'$new_item_no','Update DR No. ".$delivery_row['delivery_receipt_no']." to $new_delivery_receipt_no','$datetime','$office')";

		}else if($new_item_no != $delivery_row['item_no'] && $new_delivery_receipt_no == $delivery_row['delivery_receipt_no'] && $new_gate_pass == $delivery_row['gate_pass'] && $new_quantity == $delivery_row['quantity']){
			// update item only
			$history = "INSERT INTO history(table_report, transaction_type, item_no, detail, history_date, office) VALUES('Delivery','Update Delivery Order' ,'$new_item_no','Update item ".$delivery_row['item_no']." to $new_item_no under DR No. ".$delivery_row['delivery_receipt_no']."','$datetime','$office')";

		}else if($new_quantity == $delivery_row['quantity'] && $new_item_no != $delivery_row['item_no'] && $new_delivery_receipt_no == $delivery_row['delivery_receipt_no'] && $new_gate_pass == $delivery_row['gate_pass']){
			// update item quantity only
			$history = "INSERT INTO history(table_report, transaction_type, item_no, detail, history_date, office) VALUES('Delivery','Update Delivery Order' ,'$new_item_no','Update ".$delivery_row['item_no']." quantity of ".$delivery_row['quantity']." to $new_quantity pcs under DR No. ".$delivery_row['delivery_receipt_no']."','$datetime','$office')";

		}else if($new_gate_pass != $delivery_row['gate_pass'] && $new_delivery_receipt_no == $delivery_row['delivery_receipt_no'] && $new_item_no == $delivery_row['item_no'] && $new_quantity == $delivery_row['quantity']){
			// update gatepass only
			$history = "INSERT INTO history(table_report, transaction_type, item_no, detail, history_date, office) VALUES('Delivery','Update Delivery Order' ,'$new_item_no','Update gate pass no. ".$delivery_row['gate_pass']." to $new_gate_pass under DR No. ".$delivery_row['delivery_receipt_no']."','$datetime','$office')";

		}else if($new_delivery_receipt_no != $delivery_row['delivery_receipt_no'] && $new_item_no != $delivery_row['item_no'] && $new_gate_pass == $delivery_row['gate_pass'] && $new_quantity == $delivery_row['quantity']){
			// update dr no and item only
			$history = "INSERT INTO history(table_report, transaction_type, item_no, detail, history_date, office) VALUES('Delivery','Update Delivery Order' ,'$new_item_no','Update DR No. ".$delivery_row['delivery_receipt_no']." to $new_delivery_receipt_no and item ".$delivery_row['item_no']." to $new_item_no','$datetime','$office')";

		}else if($new_delivery_receipt_no != $delivery_row['delivery_receipt_no'] && $new_item_no == $delivery_row['item_no'] && $new_gate_pass == $delivery_row['gate_pass'] && $new_quantity != $delivery_row['quantity']){
			// update dr no and quantity only
			$history = "INSERT INTO history(table_report, transaction_type, item_no, detail, history_date, office) VALUES('Delivery','Update Delivery Order' ,'$new_item_no','Update DR No. ".$delivery_row['delivery_receipt_no']." to $new_delivery_receipt_no and quantity to $new_quantity pcs','$datetime','$office')"; 

		}else if($new_delivery_receipt_no != $delivery_row['delivery_receipt_no'] && $new_item_no == $delivery_row['item_no'] && $new_gate_pass != $delivery_row['gate_pass'] && $new_quantity == $delivery_row['quantity']){
			// update dr no and gate pass only
			$history = "INSERT INTO history(table_report, transaction_type, item_no, detail, history_date, office) VALUES('Delivery','Update Delivery Order' ,'$new_item_no','Update DR No. ".$delivery_row['delivery_receipt_no']." to $new_delivery_receipt_no and gate pass no. ".$delivery_row['gate_pass']." to $new_gate_pass','$datetime','$office')"; 

		}else if($new_item_no != $delivery_row['item_no'] && $new_delivery_receipt_no == $delivery_row['delivery_receipt_no'] && $new_gate_pass == $delivery_row['gate_pass'] && $new_quantity != $delivery_row['quantity']){
			// update item and quantity only
			$history = "INSERT INTO history(table_report, transaction_type, item_no, detail, history_date, office) VALUES('Delivery','Update Delivery Order' ,'$new_item_no','Update ".$delivery_row['item_no']." to $new_item_no and quantity to $new_quantity pcs under DR No. ".$delivery_row['delivery_receipt_no']."','$datetime','$office')";

		}else if($new_item_no != $delivery_row['item_no'] && $new_delivery_receipt_no == $delivery_row['delivery_receipt_no'] && $new_gate_pass != $delivery_row['gate_pass'] && $new_quantity == $delivery_row['quantity']){
			// update item and gate pass only
			$history = "INSERT INTO history(table_report, transaction_type, item_no, detail, history_date, office) VALUES('Delivery','Update Delivery Order' ,'$new_item_no','Update ".$delivery_row['item_no']." to $new_item_no and gate pass no. ".$delivery_row['gate_pass']." to $new_gate_pass under DR No. ".$delivery_row['delivery_receipt_no']."','$datetime','$office')";

		}else if($new_item_no == $delivery_row['item_no'] && $new_delivery_receipt_no == $delivery_row['delivery_receipt_no'] && $new_gate_pass != $delivery_row['gate_pass'] && $new_quantity != $delivery_row['quantity']){
			// update gatepass and quantity only
			$history = "INSERT INTO history(table_report, transaction_type, item_no, detail, history_date, office) VALUES('Delivery','Update Delivery Order' ,'$new_item_no','Update gate pass no. ".$delivery_row['gate_pass']." to $new_gate_pass and quantity of ".$delivery_row['item_no']." to $new_quantity pcs under DR No. ".$delivery_row['delivery_receipt_no']."','$datetime','$office')";

		}else if($new_item_no != $delivery_row['item_no'] && $new_delivery_receipt_no != $delivery_row['delivery_receipt_no'] && $new_gate_pass == $delivery_row['gate_pass'] && $new_quantity != $delivery_row['quantity']){
			// update dr no, item and quantity only
			$history = "INSERT INTO history(table_report, transaction_type, item_no, detail, history_date, office) VALUES('Delivery','Update Delivery Order' ,'$new_item_no','Update DR No. ".$delivery_row['delivery_receipt_no']." to $new_delivery_receipt_no, item ".$delivery_row['item_no']." to $new_item_no and quantity to $new_quantity pcs','$datetime','$office')";

		}else if($new_item_no != $delivery_row['item_no'] && $new_delivery_receipt_no != $delivery_row['delivery_receipt_no'] && $new_gate_pass != $delivery_row['gate_pass'] && $new_quantity == $delivery_row['quantity']){
			// update dr no, item and gate pass only
			$history = "INSERT INTO history(table_report, transaction_type, item_no, detail, history_date, office) VALUES('Delivery','Update Delivery Order' ,'$new_item_no','Update DR No. ".$delivery_row['delivery_receipt_no']." to $new_delivery_receipt_no, item ".$delivery_row['item_no']." to $new_item_no and gate pass no. ".$delivery_row['gate_pass']." to $new_gate_pass','$datetime','$office')";

		}else if($new_item_no == $delivery_row['item_no'] && $new_delivery_receipt_no != $delivery_row['delivery_receipt_no'] && $new_gate_pass != $delivery_row['gate_pass'] && $new_quantity != $delivery_row['quantity']){
			// update dr. quantity and gate pass only
			$history = "INSERT INTO history(table_report, transaction_type, item_no, detail, history_date, office) VALUES('Delivery','Update Delivery Order' ,'$new_item_no','Update DR No. ".$delivery_row['delivery_receipt_no']." to $new_delivery_receipt_no, quantity to $new_quantity pcs and gate pass no. ".$delivery_row['gate_pass']." to $new_gate_pass','$datetime','$office')";

		}else if($new_item_no != $delivery_row['item_no'] && $new_delivery_receipt_no == $delivery_row['delivery_receipt_no'] && $new_gate_pass != $delivery_row['gate_pass'] && $new_quantity != $delivery_row['quantity']){
			// update item, quantity and gate pass only
			$history = "INSERT INTO history(table_report, transaction_type, item_no, detail, history_date, office) VALUES('Delivery','Update Delivery Order' ,'$new_item_no','Update item ".$delivery_row['item_no']." to $new_item_no, quantity to $new_quantity pcs and gate pass no. ".$delivery_row['gate_pass']." to $new_gate_pass under DR No. ".$delivery_row['delivery_receipt_no']."','$datetime','$office')";

		}else if($new_item_no != $delivery_row['item_no'] && $new_delivery_receipt_no != $delivery_row['delivery_receipt_no'] && $new_gate_pass != $delivery_row['gate_pass'] && $new_quantity != $delivery_row['quantity']){
			// update all fields
			$history = "INSERT INTO history(table_report, transaction_type, item_no, detail, history_date, office) VALUES('Delivery','Update Delivery Order' ,'$new_item_no','Update DR No. ".$delivery_row['delivery_receipt_no']." to $new_delivery_receipt_no, item ".$delivery_row['item_no']." to $new_item_no, quantity to $new_quantity pcs and gate pass no. ".$delivery_row['gate_pass']." to $new_gate_pass','$datetime','$office')";

		}
		
		if(mysqli_query($db, $sql)){
			if(isset($history)){
				// echo $history;
				mysqli_query($db, $history);
				echo "<script>alert('Delivery No. has been updated'); window.location.href='delivery.php'</script>";
			}else{
				echo "<script> alert('No changes has been made');window.location.href='delivery.php'</script>";
			}
		}else{
			phpAlert('Something went wrong. Please try again.');
			echo "<meta http-equiv='refresh' content='0'>";
		}
	}


?>