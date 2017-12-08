<!DOCTYPE html>
<?php

	include("includes/config.php");
	include("includes/function.php");

	session_start();
	if(!isset($_SESSION['login_user'])) {
		header("location: login.php");
	}

	$user_query = "SELECT * FROM users WHERE username = '".$_SESSION['login_user']."'";
	$result = mysqli_query($db, $user_query);
	$user = mysqli_fetch_assoc($result);

	$office = $user['office'];
	$position = $user['position'];
	$radio_value = "";

?>
<html>
<head>
<?php
	if($office == 'delta'){
		echo "<title>Order Form - Purchase Order - Quality Star Concrete Products, Inc.</title>";
	}else{
		echo "<title>Order Form - Purchase Order - Starcrete Manufacturing Corporation</title>";
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
	
    $(function() {
        
        // var $form = $( "#form" );
        // var $input = $form.find( "#quantity" );

        // $input.on( "keyup", function( event ) {

	 	var $form = $( "#form" );
        var $input = $form.find( "#quantity" );

        $form.on( "keyup", "#quantity", function( event ) {
            
            
            // When user select text in the document, also abort.
            var selection = window.getSelection().toString();
            if ( selection !== '' ) {
                return;
            }
            
            // When the arrow keys are pressed, abort.
            if ( $.inArray( event.keyCode, [38,40,37,39] ) !== -1 ) {
                return;
            }
            
            
            var $this = $( this );
            
            // Get the value.
            var input = $this.val();
            
            var input = input.replace(/[\D\s\._\-]+/g, "");
                    input = input ? parseInt( input, 10 ) : 0;

                    $this.val( function() {
                        return ( input === 0 ) ? "" : input.toLocaleString( "en-US" );
                    } );
        } );      
    });

	function add_row(){
	 $rowno=$("#item_table tr").length;
	 $rowno=$rowno+1;
	 $("#item_table tr:last").after("<tr id='row"+$rowno+"' style='text-align: center;'><td class='col-md-4'><div class='form-group'><input list='item_nos' name='item_no[]' class='form-control'><datalist id='item_nos'><?php
	$sql = "SELECT item_no FROM batch_list ORDER BY item_no ASC";
	$result = mysqli_query($db, $sql);
	foreach($result as $row){
									echo "<option value='" . $row['item_no'] . "'>" . $row['item_no'] . "</option>";
	}
?></datalist></div></td><td class='col-md-4'><div class='form-group'><input type='text' id='quantity' name='quantity[]' class='form-control' required></div></td><td class='col-md-4'><div class='form-group'><input type='button' value='Remove' class='btn btn-primary btn-md' onclick=delete_row('row"+$rowno+"')></div></td></tr>");
	}

	function delete_row(rowno){
	 $('#'+rowno).remove();
	}

	function newClient(value) { // Call to ajax function
    	var a = document.getElementById("new_client_field");

    	if(value == 'new')
    		a.style.display = 'block';
    	else
    		a.style.display = 'none';
	}

	function selectOffice(number){

		<?php $radio_value ?> = number;
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
<body onSubmit="return confirm('Submit all data?')">
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
			<span style="font-size:25px; color: white;"><a href="purchase_order.php" style="color: white;">Purchase Order</a> > Purchase Order Form </span>
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

	<div id="wrapper">
		<div id="header">
			<input type="hidden" name="office" id="office" value="<?php echo $office; ?>">
			<input type="hidden" name="position" id="position" value="<?php echo $position; ?>">
			<input type="hidden" name="hidden_radio" id="hidden_radio">
			<!-- <div class="row" style="margin: 0px; margin-bottom: 5px;">
				<div class="col-md-12">
					<button type="button" onclick="location.href='purchase_order.php';" class="btn btn-default" style="float: left;"><span class="glyphicon glyphicon-arrow-left"></span> Back to Purchase Order Page</button>
				</div>
			</div> -->
		</div>
		<div id="content">
			<form action="purchase_order_form.php" method="post" id="form">
				<div class="row">
					<div class="col-md-12" style="text-align: center; background-color: #0884e4; color: white; padding: 10px; margin: -15px 0 15px 0;"><h3><strong>Purchase Order Form</strong></h3>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="po_no">P.O. No.</label>
							<input type="text" id="po_no" name="po_no" class="form-control" autocomplete="off" required>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="client">Client Name</label>
							<select id="client" name="client" class="form-control" style="width: 100%;" onchange="newClient(this.value)" required>
								<option value="">Select</option>
<?php
	$sql = "SELECT client_name FROM client ORDER BY client_name ASC";
	$result = mysqli_query($db, $sql);
	while ($row = mysqli_fetch_assoc($result)) {
		echo "<option value='".$row['client_name']."'>".$row['client_name']."</option>";
	}
?>
								<option value="new">Add New Client</option>
							</select>
<!-- 								<input list="clients" name="client" class="form-control">
								<datalist id="clients" onchange="newClient(this.value)">
									<option value="">Select</option>
<?php
	$sql = "SELECT client_name FROM client ORDER BY client_name ASC";
	$result = mysqli_query($db, $sql);
	while ($row = mysqli_fetch_assoc($result)) {
		echo "<option value='".$row['client_name']."'>".$row['client_name']."</option>";
	}
?>
									<option value="new">Add Client</option>
								</datalist> -->
							<div id="new_client_field" style="display: none; margin-top: 5px;">
								<input type="text" id="new_client" name="new_client" class="form-control" autocomplete="off" style="margin-top: 5px; width: 100%;" placeholder="Enter Client Name">
								<input type="text" id="new_address" name="new_address" class="form-control" autocomplete="off" style="margin-top: 5px; width: 100%;" placeholder="Enter Address">
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="contact">Contact Person</label>
							<input type="text" id="contact" name="contact" class="form-control" autocomplete="off" required>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="contact_no">Contact Number</label>
							<input type="number" id="contact_no" name="contact_no" class="form-control" autocomplete="off" required>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="plant">Choose Plant</label><br>
							<!-- <select type="text" id="plant" name="plant" class="form-control" autocomplete="off" required>
								<option value=''>Select</option>
								<option value="Bravo">Bravo</option>
								<option value="Delta">Delta</option>
							</select> -->
							<input type="radio" name="plant" value="bravo" onclick="selectOffice(this.value);" checked> Bravo<br>
							<input type="radio" name="plant" value="delta" onclick="selectOffice(this.value);"> Delta
						</div>
					</div>
					<div class="col-md-6"></div>
				</div>
				<div class="row">
					<div class="col-md-12" style="text-align: center; background-color: #0884e4; color: white; padding: 5px;"><strong>Items</strong>
					</div>
				</div>
				<div class="row" style="text-align: center; background-color: white; padding: 5px;">
					<div class="col-md-4">
						<label for="item">Item</label>
					</div>
					<div class="col-md-4">
						<label for="quantity">Quantity</label>
					</div>
					<div class="col-md-4">
						<label for=""></label>
					</div>
				</div>
				<div class="row">
					<table id="item_table" align="center">
						<tr id="row1" style="text-align: center;">
							<td class="col-md-4">
								<div class="form-group">
<!-- 									<select id="item_no" name="item_no[]" class="form-control">
										<option value="">Select</option>
<?php
	$sql = "SELECT item_no FROM batch_list ORDER BY item_no ASC";
	$result = mysqli_query($db, $sql);
	foreach($result as $row){
									echo "<option value='" . $row['item_no'] . "'>" . $row['item_no'] . "</option>";
	}
?>
										</select> -->
								<input list="item_nos" name="item_no[]" class="form-control">
								<datalist id="item_nos">
<?php

	// if(isset($_POST['hidden_radio'])){
	// 	$item_office = $_POST['hidden_radio'];
	// }else{
	// 	$item_office = "bravo";
	// }
	// echo $radio_value;

	$sql = "SELECT item_no FROM batch_list ORDER BY item_no ASC";
	$result = mysqli_query($db, $sql);
	foreach($result as $row){
									echo "<option value='" . $row['item_no'] . "'>" . number_format(getStock($db, $row['item_no'], $radio_value)) . " pcs left</option>";
	}
?>
									</datalist>
								</div>
							</td>
							<td class="col-md-4">
								<div class="form-group">
									<input type="text" id="quantity" name="quantity[]" class="form-control" autocomplete="off" required>
								</div>
							</td>
							<td class="col-md-4">
								<div class="form-group">
									<input type="button" onclick="add_row();" class='btn btn-primary btn-md' autocomplete="off" value="Add Item">
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

		$max = "SELECT MAX(purchase_unique_id) as id
				FROM purchase_order";

		$max_result = mysqli_query($db, $max);
		if(mysqli_num_rows($max_result) > 0){
			$row = mysqli_fetch_assoc($max_result);
			$purchase_unique_id = $row['id'] + 1;
		}else{
			$purchase_unique_id = 1;
		}

		$po_no = mysqli_real_escape_string($db, $_POST['po_no']);
		if($_POST['client'] == 'new'){
			$client = mysqli_real_escape_string($db, $_POST['new_client']);
			$new_address = mysqli_real_escape_string($db, $_POST['new_address']);

			$insert_client = "INSERT INTO client(client_name, address) VALUES('$client','$new_address')";

			mysqli_query($db, $insert_client);

		}else{
			$client = mysqli_real_escape_string($db, $_POST['client']);
		}

		$sql = "SELECT address FROM client WHERE client_name = '$client'";
		$result = mysqli_query($db, $sql);
		$row = mysqli_fetch_assoc($result);

		$address = $row['address'];
		$item = mysqli_real_escape_string($db, $_POST['item_no']);
		$quantity = str_replace( ',', '', mysqli_real_escape_string($db, $_POST['quantity']));
		$datetime = date("Y/m/d H:i:s");
		
		$contact_person = mysqli_real_escape_string($db, $_POST['contact']);
		$contact_no = mysqli_real_escape_string($db, $_POST['contact_no']);
		$plant = mysqli_real_escape_string($db, $_POST['plant']);

		for($i = 0; $i < count($item); $i++){
			if($item[$i] != "" && $quantity[$i] != ""){
				$insert_purchase_order = "INSERT INTO purchase_order(purchase_unique_id, purchase_order_no, client_name, item_no, quantity, balance, address, contact_person, contact_no, date_purchase, office, remarks) 
									VALUES('$purchase_unique_id','$po_no','$client','$item[$i]','$quantity[$i]','$quantity[$i]','$address','$contact_person','$contact_no','$datetime','$plant','Pending')";

				$history_query = "INSERT INTO history(table_report, transaction_type, item_no, detail, history_date, office) 
							VALUES('Purchase Order','Issued P.O. No.','$item[$i]','$client ordered ".number_format($quantity[$i])." pcs of $item[$i] with P.O. No. $po_no','$datetime','$plant')";

				// echo $insert_purchase_order;
				// echo $history_query;

				if(mysqli_query($db, $insert_purchase_order) && mysqli_query($db, $history_query)){
					phpAlert("$item[$i] successfully added!!");
					echo "<meta http-equiv='refresh' content='0'>";
				}else{
					phpAlert('Something went wrong. Please try again.');
				}
			}
		}
	}
?>