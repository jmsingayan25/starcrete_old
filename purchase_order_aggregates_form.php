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
		echo "<title>Order Form - Purchase Order Aggregates - Quality Star Concrete Products, Inc.</title>";
	}else{
		echo "<title>Order Form - Purchase Order Aggregates - Starcrete Manufacturing Corporation</title>";
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
	
	$(function() {
        
        var $form = $( "#form" );
        var $input = $form.find( "#quantity, #price");

        $input.on( "keyup", function( event ) {
            
            
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

	function newClient(value) { // Call to ajax function
    	var a = document.getElementById("new_client_field");

    	if(value == 'new')
    		a.style.display = 'block';
    	else
    		a.style.display = 'none';
	}

	function getType(value){

		if(value == 'Cement'){
			// alert('Cement');
		}else if(value == 'Diesel'){
			// alert('Diesel');
		}else if(value == 'S1'){
			// alert('S1');
		}else if(value == '3/8'){
			// alert('3/8');
		}else if(value == 'White sand'){
			// alert('White sand');
		}else if(value == 'Lahar'){
			// alert('Lahar');
		}
	}

</script>
<style>
th, footer {
    background-color: #0884e4;
    color: white;
}
#content {
	min-height: 457px;
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
	min-height: 450px;
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
			<span style="font-size:25px; color: white;">Purchase Order Aggregates > Order Form </span>
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
	<div class="row" style="margin: 0px;">
		<div class="col-md-12">
			<button type="button" onclick="location.href='purchase_order_aggregates.php';" class="btn btn-default" style="float: left;"><span class="glyphicon glyphicon-arrow-left"></span> Back to Purchase Order Aggregates Page</button>
		</div>
	</div>
	<div class="row" style="margin: 0px;">
		<div id="content">
			<div class="col-md-6 col-md-offset-3">
			<form action="purchase_order_aggregates_form.php" method="post" id="form">
				<div id="box">
					<div class="row">
						<div class="col-md-12" style="text-align: center; background-color: #0884e4; color: white; padding: 10px; margin: -15px 0 15px 0;"><h3><strong>Purchase Order Aggregates Form</strong></h3>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="po_no">Purchase Order #</label>
								<input type="text" id="po_no" name="po_no" class="form-control" autocomplete="off" required>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="supplier">Supplier</label>
								<input type="text" id="supplier" name="supplier" class="form-control" autocomplete="off" required>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="plant">For Plant</label><br>
								<!-- <select type="text" id="plant" name="plant" class="form-control" required>
									<option value="">Select</option>
									<option value="Delta">Delta</option>
									<option value="Bravo">Bravo</option>
								</select> -->
								<input type="radio" name="plant" value="bravo" checked> Bravo<br>
								<input type="radio" name="plant" value="delta"> Delta
							</div>
						</div>
						<div class="col-md-6">
						</div>
					</div>
					<div class="row">
						<div class="col-md-12" style="text-align: center; background-color: #0884e4; color: white; padding: 5px;"><strong>Items</strong>
						</div>
					</div>
					<div class="row" style="text-align: center; background-color: white; padding: 5px;">
						<div class="col-md-4">
							<label for="item_no">Item</label>
						</div>
						<div class="col-md-4">
							<label for="quantity">Quantity</label>
						</div>
						<div class="col-md-4">
							<label for="price">Price</label>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
<?php 
	$sql = "SELECT DISTINCT item_no FROM item_list WHERE item_no NOT IN (SELECT item_no FROM batch_list)";
	$result = mysqli_query($db, $sql);
?>
								<select id="item_no" name="item_no" class="form-control" onchange="getType(this.value);">
									<option value=''>Select</option>
									<?php foreach($result as $row){
									    echo "<option value='" . $row['item_no'] . "'>" . $row['item_no'] . "</option>";
										}
									?>
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">

								<input type="text" id="quantity" name="quantity" class="form-control" autocomplete="off" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<input type="text" id="price" name="price" class="form-control" autocomplete="off" style="display: inline-block;" required>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<input type="submit" name="submit" value="Submit" class="btn btn-primary btn-block">
							<input type="reset" name="Reset" class="btn btn-warning btn-block">
						</div>
					</div>
				</div>
			</form>
			</div>
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

		$po_no = mysqli_real_escape_string($db, $_POST['po_no']);
		$item = mysqli_real_escape_string($db, $_POST['item_no']);
		$quantity = str_replace( ',', '', mysqli_real_escape_string($db, $_POST['quantity']) );
		$price = str_replace( ',', '', mysqli_real_escape_string($db, $_POST['price']) );
		$received = 0;
		$plant = mysqli_real_escape_string($db, $_POST['plant']);
		$supplier = mysqli_real_escape_string($db, $_POST['supplier']);
		$datetime = date("Y/m/d H:i:s");
		// $pending = "Pending";
		// $table = "Purchase Order Aggregates";
		// $transaction = "Issued P.O. No.";
		// $sentence = "Head Office ordered $quantity ".getTruck($db,$item)." of $item to $supplier with P.O no. $po_no for $plant";

		$query = "INSERT INTO purchase_order_aggregates(item_no, quantity, received, price, office, date_po_aggregates, supplier_name, purchase_order_aggregates_no, remarks)
					VALUES ('$item','$quantity','0','$price','$plant','$datetime','$supplier','$po_no','Pending')";

		$history_query = "INSERT INTO history(table_report, transaction_type, item_no, detail, history_date, office) 
							VALUES('Purchase Order Aggregates','Issued P.O. No.','$item','Head Office ordered $quantity ".getTruck($db,$item)." of $item to $supplier with P.O no. $po_no for $plant','$datetime','$plant')";
		// echo $query;
		// echo $history_query;

		// $query = $db->prepare("INSERT INTO purchase_order_aggregates(item_no, quantity, received, price, office, date_po_aggregates, supplier_name, purchase_order_aggregates_no, remarks) VALUES(?,?,?,?,?,?,?,?,?)");
		// $query->bind_param('ssissssss',$item,$quantity,$received,$price,$plant,$datetime,$supplier,$po_no,$pending);
		// $query->execute();

		// $history_query = $db->prepare("INSERT INTO history(table_report, transaction_type, item_no, detail, history_date, office) VALUES(?,?,?,?,?,?)");
		// $history_query->bind_param('ssssss',$table,$transaction,$item,$sentence,$datetime,$plant);
		// $history_query->execute();

		if(mysqli_query($db,$query) && mysqli_query($db,$history_query)){
			phpAlert("Order completed!!");
			echo "<meta http-equiv='refresh' content='0'>";
		}else{
			phpAlert("Something went wrong!!");
		}
	}

?>