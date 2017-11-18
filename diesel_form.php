<!DOCTYPE html>
<?php

	include("includes/config.php");
	include("includes/function.php");
	// include("includes/batch_function.php");

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
		echo "<title>Delivery Form - Diesel Report - Quality Star Concrete Products, Inc.</title>";
	}else{
		echo "<title>Delivery Form - Diesel Report - Starcrete Manufacturing Corporation</title>";
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
	
	function warning(value){
		var balance = document.getElementById('balance').value;
		var a = document.getElementById('getWarning');

		if(balance <= '1500'){
			a.style.display = "block";
		}else{
			a.style.display = "none";
		}
	}

	function compareValue(value){
		var balance = document.getElementById('balance').value;
		var e = document.getElementById('submit');
		var a = document.getElementById('warning');

		setTimeout(function () {
			if(+value > +balance || balance == '0'){
				e.disabled = true;
				a.style.display = "block";
			}else{
				e.disabled = false;
				a.style.display = "none";
			}
		}, 0);
	}

</script>
<style>

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
	min-height: 300px;
	z-index: 15;
	top: 50%;
	left: 50%;
	margin: -25px 0 0 -300px;
	width: 600px;
    padding: 15px;
    border: 1px solid #bababa;
}

#warning, #getWarning{
	display: none;
}
</style>
</head>
<body onload="compareValue('');warning('');">
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
			<span style="font-size:25px; color: white;">Diesel Report > Diesel Form </span>
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
	<div class="row" style="margin: 0px; margin-bottom: 30px;">
		<div class="col-md-12">
			<button type="button" onclick="location.href='diesel.php';" class="btn btn-default" style="float: left;"><span class="glyphicon glyphicon-arrow-left"></span> Back to Diesel Page</button>
		</div>
	</div>
	<div class="row" style="margin: 0px; height: 427px;">
		<div class="col-md-12">
			<form action="diesel_form.php" method="post">
				<div id="box">
					<div class="row">
						<div class="col-md-12" style="text-align: center; background-color: #0884e4; color: white; padding: 10px; margin: -15px 0 15px 0;"><h3><strong>Diesel Form</strong></h3></div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
							<label for="balance">Stock</label>
<?php 
	if(getStock($db, 'Diesel', $office) > 0){ 
?>
							<input type='text' value='<?php echo getStock($db, 'Diesel', $office); ?> liters' class='form-control' disabled>
									<input type='hidden' id='balance' name='balance' value='<?php echo getStock($db, 'Diesel', $office); ?>' class='form-control' disabled>
									<div class='isa_warning' id='getWarning' style='color: #9F6000; background-color: #FEEFB3;'>
								     <i class='glyphicon glyphicon glyphicon-warning-sign'></i>
								     Warning! Stock is in critical level
								</div>
<?php
	}else{
?>
							<input type='text' id='balance' name='balance' value='Out of stock' class='form-control' disabled>
<?php
	}
?>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="liter">Quantity (OUT)</label>
								<input type="number" id="liter" name="liter" min="1" max="<?php echo getStock($db, 'Diesel', $office); ?>" class="form-control" autocomplete="off" onkeyup="compareValue(this.value)" required>
								<div class="isa_warning" id="warning" style="color: #9F6000; background-color: #FEEFB3;">
								     <i class="glyphicon glyphicon glyphicon-warning-sign"></i>
								     Warning! Value exceeds over stock
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="destination">Destination</label>
								<input type="text" id="destination" name="destination" class="form-control" autocomplete="off" required>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="truck_no">Truck no.</label>
								<input type="text" id="truck_no" name="truck_no" class="form-control" autocomplete="off" required>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="operator">Operator</label>
								<input type="text" id="operator" name="operator" class="form-control" autocomplete="off" required>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<input type="submit" id="submit" name="submit" value="Add" class="btn btn-primary btn-block">
							<input type="reset" name="Reset" class="btn btn-warning btn-block">
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
<footer class="footer" style="text-align: center; padding:10px;">
<?php
	if($office == 'delta'){
		echo "<h4>Quality Star Concrete Products, Inc.</h4>";
	}else{
		echo "<h4>Starcrete Manufacturing Corporation</h4>";
	}
?>
</footer>		
</body>
</html>
<?php

	if(isset($_POST['submit'])){

		$liter = mysqli_real_escape_string($db, $_POST['liter']);
		$destination = mysqli_real_escape_string($db, $_POST['destination']);
		$truck = mysqli_real_escape_string($db, $_POST['truck_no']);
		$operator = mysqli_real_escape_string($db, $_POST['operator']);
		$datetime = date("Y-m-d H:i:s");
		
		// $stock = "SELECT stock FROM item_stock WHERE item_no = 'Diesel' AND office = '$office'";
		// $result = mysqli_query($db, $stock);
		// $row = mysqli_fetch_assoc($result);	

		$diesel_update = "UPDATE item_stock SET stock = stock - '$liter', last_update = '$datetime' WHERE item_no = 'Diesel' AND office = '$office'";
		
		if(mysqli_query($db, $diesel_update)){
			$stock = "SELECT stock FROM item_stock WHERE item_no = 'Diesel' AND office = '$office'";
			$result = mysqli_query($db, $stock);
			$row = mysqli_fetch_assoc($result);	

			$query = "INSERT INTO diesel(office, quantity_out, balance, destination, truck_no, operator, delivery_date)
					VALUES ('$office','$liter','".$row['stock']."','$destination','$truck','$operator','$datetime')";

			$history = "INSERT INTO history(table_report, transaction_type, item_no, detail, history_date, office) VALUES('Diesel','Delivered','Diesel','Truck no. $truck operated by $operator delivered $liter liters of diesel to $destination','$datetime', '$office')";

			if(mysqli_query($db, $query) && mysqli_query($db, $history)){
				phpAlert("Transaction complete!!");
				echo "<meta http-equiv='refresh' content='0'>";
			}else{
				phpAlert("Something went wrong!!");
			}
		}else{
			phpAlert("Something went wrong!!");
		}
	}
?>