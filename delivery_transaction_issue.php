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

	if(isset($_REQUEST['post_delivery_purchase_id'])){
		$_SESSION['delivery_purchase_id'] = $_REQUEST['post_delivery_purchase_id'];
	}

	$user_query = $db->prepare("SELECT * FROM users WHERE username = ?");
	$user_query->bind_param('s', $_SESSION['login_user']);
	$user_query->execute();
	$result = $user_query->get_result();
	$user = $result->fetch_assoc();

	$office = $user['office'];
	$position = $user['position'];

	$purchase_id = $_SESSION['delivery_purchase_id'];
	$search_sql = "SELECT * FROM purchase_order WHERE purchase_id = '$purchase_id'";
	$search_result = mysqli_query($db, $search_sql);
	$purchase_row = mysqli_fetch_assoc($search_result);

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
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
	<link href="css_ext/sidebar.css" rel="stylesheet">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js_ext/sidebar.js"></script>
<script>

	var timer = null;

	function goAway() {
	    clearTimeout(timer);
	    timer = setTimeout(function() {
	        window.location.reload(true);
	    }, 60000);
	}

	window.addEventListener('mousemove', goAway, true);
	window.addEventListener('keypress', goAway, true);

	goAway();

</script>
<style>
	
html, body {
	margin:0;
	padding:0;
	height:100%;
}

#wrapper {
	min-height:83%;
	position:relative;
}

#content {
	margin: 0 auto;
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
			<span style="font-size:25px; color: white;"><a href="delivery_transaction.php" style="color: white;">Delivery Transaction</a> > DR No. Issue</span>
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
			<form action="delivery_transaction_issue.php" method="post" class="form-inline">
			<div class="row">
				<div class="col-md-12" style="text-align: center; background-color: #0884e4; color: white; padding: 10px; margin: -15px 0 15px 0;"><h3><strong>DR No. Issue Form</strong></h3>
				</div>
			</div>
			<div class="row" style="margin-left: 10px; margin-bottom: 5px;">
				<div class="col-md-6">
					<div class="form-group">
						<label for="po_no">P.O. No.</label>
						<input type="text" id="po_no" name="po_no" value="<?php echo $purchase_row['purchase_order_no']; ?>" class="form-control" readonly>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="item_no">Item No.</label>
						<input type="text" id="item_no" name="item_no" value="<?php echo $purchase_row['item_no']; ?>" class="form-control" readonly>
					</div>
				</div>
			</div>
			<div class="row" style="margin-left: 10px; margin-bottom: 5px;">
				<div class="col-md-6">
					<div class="form-group">
						<label for="dr_no">DR No.</label>
						<input type="text" id="dr_no" name="dr_no" class="form-control" autocomplete="off" required>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="quantity">
							Balance: <?php echo number_format((float)$purchase_row['balance'])." pcs"; ?>
							<!-- Stock: <?php if(getStock($db, $purchase_row['item_no'], $purchase_row['office']) == NULL || getStock($db, $purchase_row['item_no'], $purchase_row['office']) == ''){
									echo "0 pcs";
								}else{
									echo number_format((float)getStock($db, $purchase_row['item_no'], $purchase_row['office']))." pcs";
								}  
							?> -->
						</label>
						<input type="hidden" id="stock" name="stock" 
						value="<?php if(getStock($db, $purchase_row['item_no'], $purchase_row['office']) == NULL || getStock($db, $purchase_row['item_no'], $purchase_row['office']) == ''){
									echo "0 pcs";
								}else{
									echo number_format((float)getStock($db, $purchase_row['item_no'], $purchase_row['office']))." pcs";
								}  
							?>">
						<input type="text" id="quantity" name="quantity" class="class_quantity form-control" autocomplete="off" placeholder="Pieces to be delivered" required>
					</div>
				</div>
			</div>
			<div class="row" style="margin-left: 10px;">
				<div class="col-md-6">
					<div class="form-group">
						<label for="gate_pass_no">Gate Pass No.</label>
						<input type="text" id="gate_pass_no" name="gate_pass_no" class="form-control" autocomplete="off" required>
					</div>
				</div>
			</div>
			<hr>
			<div class="row">
				<div class="col-md-12">
					<input type="submit" name="submit" value="Submit" class="btn btn-primary btn-block">
					<a href="delivery_transaction.php" class="btn btn-warning btn-block">Cancel</a>
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

		$purchase_order_no = $purchase_row['purchase_order_no'];
		$item_no = $purchase_row['item_no'];
		$delivery_no = $_POST['dr_no'];
		$quantity = $_POST['quantity'];
		$gate_pass_no = $_POST['gate_pass_no'];

		$reply = array('post' => $_POST, 'row' => $purchase_row);

		echo json_encode($reply);
	}

?>