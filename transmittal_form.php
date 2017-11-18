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
?>
<html>
<head>
<?php
	if($office == 'delta'){
		echo "<title>Transfer Form - Transmittal - Quality Star Concrete Products, Inc.</title>";
	}else{
		echo "<title>Transfer Form - Transmittal - Starcrete Manufacturing Corporation</title>";
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

	function add_row(){
	 $rowno=$("#item_table tr").length;
	 $rowno=$rowno+1;
	 $("#item_table tr:last").after("<tr id='row"+$rowno+"' style='text-align: center;'><td class='col-md-3'><div class='form-group'><input type='text' id='item' name='item[]' class='form-control' required></div></td><td class='col-md-3'><div class='form-group'><input type='text' id='quantity' name='quantity[]' class='form-control' required></div></td><td class='col-md-3'><div class='form-group'><input type='text' id='purpose' name='purpose[]' class='form-control' required></div></td><td class='col-md-3'><div class='form-group'><input type='button' value='Remove' class='btn btn-primary btn-md' onclick=delete_row('row"+$rowno+"')></div></td></tr>");
	}

	function delete_row(rowno){
	 $('#'+rowno).remove();
	}
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

th{
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
			<span style="font-size:25px; color: white;">Transmittal > Transmittal Form </span>
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
			<input type="hidden" name="office" id="office" value="<?php echo $office; ?>">
			<input type="hidden" name="position" id="position" value="<?php echo $position; ?>">
			<div class="row" style="margin: 0px;">
				<div class="col-md-12">
					<button type="button" onclick="location.href='transmittal.php';" class="btn btn-default" style="float: left;"><span class="glyphicon glyphicon-arrow-left"></span> Back to Transmittal</button>
				</div>
			</div>
		</div>
		<div id="content">
			<form action="transmittal_form.php" method="post">
				<div class="row">
					<div class="col-md-12" style="text-align: center; background-color: #0884e4; color: white; padding: 10px; margin: -15px 0 15px 0;"><h3><strong>Transmittal Form</strong></h3></div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="transmittal_no">Transmittal No.</label>
							<input type="text" id="transmittal_no" name="transmittal_no" class="form-control" required>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="plant">To Office</label>
							<select id="plant" name="plant" class="form-control" required>
								<option value="">Select</option>
<?php
	$sql = "SELECT DISTINCT office FROM users WHERE office != '$office' ORDER BY office ASC";
	$result = mysqli_query($db, $sql);
	while($row = mysqli_fetch_assoc($result)){

		echo "<option value='".$row['office']."'>";

		if($row['office'] == 'head'){
			$row['office'] = 'Head Office';
		}

		echo ucfirst($row['office'])."</option>";
	}
?>
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="purpose">Delivered by</label>
							<input type="text" id="delivered_by" name="delivered_by" class="form-control" required>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12" style="text-align: center; background-color: #0884e4; color: white; padding: 5px;"><strong>Items</strong>
					</div>
				</div>
				<div class="row" style="text-align: center; background-color: white; padding: 5px;">
					<div class="col-md-3">
						<label for="item">Item</label>
					</div>
					<div class="col-md-3">
						<label for="quantity">Quantity</label>
					</div>
					<div class="col-md-3">
						<label for="purpose">Purpose</label>
					</div>
					<div class="col-md-3">
						<label for=""></label>
					</div>
				</div>
				<div class="row">
					<table id="item_table" align="center">
						<tr id="row1" style="text-align: center;">
							<td class="col-md-3">
								<div class="form-group">
									<input type="text" id="item" name="item[]" class="form-control" required>
								</div>
							</td>
							<td class="col-md-3">
								<div class="form-group">
									<input type="text" id="quantity" name="quantity[]" class="form-control" required>
								</div>
							</td>
							<td class="col-md-3">
								<div class="form-group">
									<input type="text" id="purpose" name="purpose[]" class="form-control" required>
								</div>
							</td>
							<td class="col-md-3">
								<div class="form-group">
									<input type="button" onclick="add_row();" class='btn btn-primary btn-md' value="Add Item">
								</div>
							</td>
						</tr>
					</table>
				</div>
				<div class="row">
					<div class="col-md-12">
						<input type="submit" name="submit" value="Submit" class="btn btn-primary btn-block">
						<input type="reset" name="Reset" class="btn btn-warning btn-block">
					</div>
				</div>
			</form>
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
	if(isset($_POST['submit'])){

		$max = "SELECT MAX(transmittal_unique_id) as id
				FROM transmittal";

		$max_result = mysqli_query($db, $max);
		if(mysqli_num_rows($max_result) > 0){
			$row = mysqli_fetch_assoc($max_result);
			$transmittal_unique_id = $row['id'] + 1;
		}else{
			$transmittal_unique_id = 1;
		}
		$transmittal_no = mysqli_real_escape_string($db, $_POST['transmittal_no']);
		$plant = mysqli_real_escape_string($db, ucfirst($_POST['plant']));
		$from_office = ucfirst($office);
		$item = mysqli_real_escape_string($db, $_POST['item']);
		$quantity = mysqli_real_escape_string($db, $_POST['quantity']);
		$purpose = mysqli_real_escape_string($db, $_POST['purpose']);
		$delivered_by = mysqli_real_escape_string($db, $_POST['delivered_by']);
		$datetime = date("Y/m/d H:i:s");

		for($i = 0; $i < count($item); $i++){
			if($item[$i] != "" && $quantity[$i] != ""){
				$sql = "INSERT INTO transmittal(transmittal_no, transmittal_unique_id, office, from_office, item_no, quantity, purpose, delivered_by, transmittal_date, remarks) VALUES('$transmittal_no','$transmittal_unique_id','$plant','$from_office','$item[$i]','$quantity[$i]','$purpose[$i]','$delivered_by','$datetime','Pending')";

				$history = "INSERT INTO history(table_report, transaction_type, item_no, detail, history_date, office)
							VALUES('Transmittal','Transferred','$item[$i]','$delivered_by from $from_office delivered $quantity[$i] pcs of $item[$i] to $plant','$datetime','$plant')";

				// echo $sql;
				// echo $history;
				if(mysqli_query($db, $sql) && mysqli_query($db, $history)){
					phpAlert("$item[$i] successfully added!!!");
					echo "<meta http-equiv='refresh' content='0'>";
				}else{
					phpAlert("Something went wrong!!");
				}
			}
		}
	}
?>