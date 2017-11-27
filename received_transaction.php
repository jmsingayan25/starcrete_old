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
		echo "<title>Pending Orders - Quality Star Concrete Products, Inc.</title>";
	}else{
		echo "<title>Pending Orders - Starcrete Manufacturing Corporation</title>";
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
	
	$(document).ready(function() {
		$('#myModal').on('hidden.bs.modal', function () {
		    $('.modal-body').find("input,textarea,select").val('').end();
		});
	});
	
	function officeOption(str){
		if(window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest();
		}else{// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function(){
			if(xmlhttp.readyState==4 && xmlhttp.status==200){
				document.getElementById("search_pending_result").innerHTML=xmlhttp.responseText;
			}
		}

		xmlhttp.open("GET","received_transaction_result.php?pending_delivery="+str,true);
		xmlhttp.send();
	}
</script>
<style>
#wrapper {
	min-height:83%;
	position:relative;
}

#content {
	margin: 0 auto;
	min-height: 820px;
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
  height: 500px;
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
<body onload="officeOption('');">
	<div id="mySidenav" class="sidenav">
		<a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
		<a href="index.php">Home</a>
		<hr>
<!-- 		<a href="stock_report.php">Stock Report</a>
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
			<span style="font-size:25px; color: white;"><a href="received.php" style="color: white;">Received Order</a> > Pending Orders</span>
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
			
		</div>
		<div id="content">
			<form action="received_transaction.php" method="post" class="form-inline">
			<div class="row" style="margin: 0px; margin-bottom: 5px;">
				<div class="col-md-4">
<?php
	if($office == 'head'){
?>
					<label for="search">Plant: </label>
					<label class="radio-inline">
						<input type="radio" name="radioOffice" value="bravo" <?php if(isset($_POST['radioOffice']) && $_POST['radioOffice'] == 'bravo') { echo "checked"; }?> checked>Bravo <span class="badge"><?php echo getAggCountPendingOffice($db,'bravo'); ?> Order(s)</span>
					</label>
					<label class="radio-inline">
						<input type="radio" name="radioOffice" value="delta" <?php if(isset($_POST['radioOffice']) && $_POST['radioOffice'] == 'delta') { echo "checked"; }?>>Delta <span class="badge"><?php echo getAggCountPendingOffice($db,'delta'); ?> Order(s)</span>
					</label>
<?php
	}
?>	
				</div>			
			</div>
			<div class="row" style="margin: 0px; margin-bottom: 5px;">
				<div class="col-md-6">
					<label for="start_date">Start Date:</label><input type="date" name="start_date" class="form-control" value="<?php if(isset($_POST['start_date'])) { echo htmlentities ($_POST['start_date']); }?>">
					<label for="end_date">End Date:</label><input type="date" name="end_date" class="form-control" value="<?php if(isset($_POST['end_date'])) { echo htmlentities ($_POST['end_date']); }?>">
					<input type="submit" name="search_received" id="search_received" value="Search" class="btn btn-primary">
				</div>
				<!-- <div class="col-md-1 col-md-offset-5">
					<div class="pull-right">
						<button type="button" onclick="location.href='received.php';" class="btn btn-default"><span class="glyphicon glyphicon-arrow-left"></span> Back to Received Order</button>
					</div>
				</div> -->
				
			</div>
			</form>
			<div class="row" style="margin: 0px;">
				<div class="col-md-12">
<?php
	if(isset($_POST['search_received'])){
		// if(isset($_POST['radioOffice'])){
		// 	$search_plant = $_POST['radioOffice'];
		// }
		// if($_POST['date_view'] == ''){
		// 	$string_date = "";
		// }else{
		// 	$date = $_POST['date_view'];
		// 	$string_date = "AND DATE_FORMAT(p.date_po_aggregates,'%Y-%m-%d') = '$date'";
		// }
		if($office == 'head'){
			if(isset($_POST['radioOffice'])){
				$search_plant = $_POST['radioOffice'];
			}
		}else{
			$search_plant = $office;
		}
		if($_POST['end_date'] == ''){
			$end_date = date("Y-m-d");
		}else{
			$end_date = $_POST['end_date'];
		}
		if($_POST['start_date'] == ''){
			$string_date = "AND DATE_FORMAT(date_po_aggregates,'%Y-%m-%d') <= '$end_date'";
		}else{
			$date = $_POST['start_date'];
			$string_date = "AND DATE_FORMAT(date_po_aggregates,'%Y-%m-%d') BETWEEN '$date' AND '$end_date'";
		}
?>					
					<table class="table table-striped">
						<thead>
							<!-- <tr>
								<th colspan="7"><h3>Pending Orders</h3></th>
							</tr> -->
							<tr class="filterable">
								<th colspan="1" style="text-align: left;">Plant: <?php echo ucfirst($search_plant); ?></th>
								<th colspan="6">
									<button class="btn btn-default btn-xs btn-filter" style="float: right;"><span class="glyphicon glyphicon-filter"></span> Filter</button>
								</th>
							</tr>
							<tr>
								<th class="col-md-1">P.O. no.</th>
								<th class="col-md-1">Item</th>
								<th class="col-md-1">Remaining</th>
								<th class="col-md-1">Received</th>
								<th class="col-md-2">Supplier</th>
								<th class="col-md-1">Date Ordered</th>
								<th class="col-md-1">Action</th>
							</tr>
						</thead>
						<tbody>
<?php

	if($office == 'head'){
		$string = " AND p.office = '$search_plant'";
	}else{
		$string = " AND p.office = '$office'";
	}

	$query = "SELECT p.purchase_order_aggregates_id, p.purchase_order_aggregates_no, p.item_no, CONCAT(p.quantity,' ',l.truck) as detail, p.supplier_name, DATE_FORMAT(p.date_po_aggregates,'%m/%d/%y') as date_po_aggregates1, p.remarks, p.office, CONCAT(p.received, ' ', l.truck) as received 
				FROM purchase_order_aggregates p, item_list l
				WHERE p.item_no = l.item_no 
				AND p.remarks = 'Pending' 
				AND p.quantity != 0 ".$string." ".$string_date."
				ORDER BY date_po_aggregates DESC, purchase_order_aggregates_no DESC";
				// echo $query;
	$result = mysqli_query($db, $query);
	$count = 1;
	if(mysqli_num_rows($result) > 0){
		while($row = mysqli_fetch_assoc($result)){
			// $date = date_create($row['date_po_aggregates']);

?>
							<tr>
								<td class='col-md-1'><strong><?php echo $row['purchase_order_aggregates_no']; ?></strong></td>
								<td class='col-md-1'><strong><?php echo $row['item_no']; ?></strong></td>
								<td class='col-md-1'><strong><?php echo $row['detail']; ?></strong></td>
								<td class='col-md-1'><strong><?php echo $row['received']; ?></strong></td>
								<td class='col-md-2'><strong><?php echo $row['supplier_name']; ?></strong></td>
								<td class='col-md-1'><strong><?php echo $row['date_po_aggregates1']; ?></strong></td>

			<?php if($office == 'head'){ ?>
								<td class='col-md-1'><strong>Pending</strong></td>
			<?php }else{ ?>
								<td class='col-md-1'><button type='button' class='btn btn-success btn-sm' data-toggle='modal' data-target='#myModal<?php echo $count;?>' style='float: center'>Issue DR No.</button>
			<?php } ?>
						<!-- Modal -->
								<div class="modal fade" id="myModal<?php echo $count;?>" role="dialog">
									<div class="modal-dialog modal-sm">

									<!-- Modal content-->
									<form action="received_transaction.php" method="post">
										<div class="modal-content">
											<div class="modal-header">
												<!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
												<h4 class="modal-title">Purchase Order # <?php echo $row['purchase_order_aggregates_no'] ?></h4>
											</div>
											<div class="modal-body" style="text-align: left;">
												<input type="hidden" id="po_no" name="po_no" class="form-control" value="<?php echo $row['purchase_order_aggregates_no'] ?>">
												<input type="hidden" id="item_no" name="item_no" class="form-control" value="<?php echo $row['item_no'] ?>">
												Item: <strong><?php echo $row['item_no'] ?></strong> 
												<div class="form-group">
													<label for="volume">
														Remaining (<?php echo number_format((float)getPurchaseAggQuantity($db, $row['purchase_order_aggregates_no'], $row['item_no'], $row['office'], $row['purchase_order_aggregates_id']))." ".getTruck($db, $row['item_no']); ?>) <br>
														Quantity received (<?php 
														if(getUnit($db, $row['item_no']) == 'm3'){
															echo "m&sup3;";
														}else{
															echo getUnit($db, $row['item_no']);
														}
							
														if(getTruck($db, $row['item_no']) == 'liter'){
															echo "";
														}else{
															echo " per ". getTruck($db, $row['item_no']); 
														} 
														?>)	
													</label>
													<input type="text" id="volume" name="volume" class="form-control"  required>
												</div>
												<div class="form-group">
													<label for="delivery">Delivery No.</label>
													<input type="text" id="delivery" name="delivery" class="form-control"  required>
												</div>
												<div class="form-group">
													<label for="truck">Truck No.</label>
													<input type="text" id="truck" name="truck" class="form-control"  required>
												</div>								
											</div>
											<div class="modal-footer">
												<input type="submit" name="submit" value="Submit" class="btn btn-primary">
												<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
											</div>
										</div>
									</form>
									</div>
								</div>
								</td>
							</tr>
<?php
		$count++;
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
<?php
	}else{
		if($office == 'head'){
			if(isset($_POST['radioOffice'])){
			$search_plant = $_POST['radioOffice'];
			}else{
				$search_plant = 'bravo';
			}
		}else{
			$search_plant = $office;
		}
?>
					<table class="table table-striped">
						<thead>
							<!-- <tr>
								<th colspan="7"><h3>Pending Orders</h3></th>
							</tr> -->
							<tr class="filterable">
								<th colspan="1" style="text-align: left;">Plant: <?php echo ucfirst($search_plant); ?></th>
								<th colspan="6">
									<button class="btn btn-default btn-xs btn-filter" style="float: right;"><span class="glyphicon glyphicon-filter"></span> Filter</button>
								</th>
							</tr>
							<tr>
								<th class="col-md-1">P.O. no.</th>
								<th class="col-md-1">Item</th>
								<th class="col-md-1">Remaining</th>
								<th class="col-md-1">Received</th>
								<th class="col-md-2">Supplier</th>
								<th class="col-md-1">Date Ordered</th>
								<th class="col-md-1">Action</th>
							</tr>
						</thead>
						<tbody>
<?php

	if($office == 'head'){
		$string = " AND p.office = '$search_plant'";
	}else{
		$string = " AND p.office = '$office'";
	}

	$query = "SELECT p.purchase_order_aggregates_id, p.purchase_order_aggregates_no, p.item_no, CONCAT(p.quantity,' ',l.truck) as detail, p.supplier_name, DATE_FORMAT(p.date_po_aggregates,'%m/%d/%y') as date_po_aggregates1, p.remarks, p.office, CONCAT(p.received, ' ', l.truck) as received 
				FROM purchase_order_aggregates p, item_list l
				WHERE p.item_no = l.item_no 
				AND p.remarks = 'Pending' 
				AND p.quantity != 0 ".$string."
				ORDER BY date_po_aggregates DESC, purchase_order_aggregates_no DESC";
				// echo $query;
	$result = mysqli_query($db, $query);
	$count = 1;
	if(mysqli_num_rows($result) > 0){
		while($row = mysqli_fetch_assoc($result)){
			// $date = date_create($row['date_po_aggregates']);

?>
							<tr>
								<td class='col-md-1'><strong><?php echo $row['purchase_order_aggregates_no']; ?></strong></td>
								<td class='col-md-1'><strong><?php echo $row['item_no']; ?></strong></td>
								<td class='col-md-1'><strong><?php echo $row['detail']; ?></strong></td>
								<td class='col-md-1'><strong><?php echo $row['received']; ?></strong></td>
								<td class='col-md-2'><strong><?php echo $row['supplier_name']; ?></strong></td>
								<td class='col-md-1'><strong><?php echo $row['date_po_aggregates1']; ?></strong></td>

			<?php if($office == 'head'){ ?>
								<td class='col-md-1'><strong>Pending</strong></td>
			<?php }else{ ?>
								<td class='col-md-1'><button type='button' class='btn btn-success btn-sm' data-toggle='modal' data-target='#myModal<?php echo $count;?>' style='float: center'>Issue DR No.</button>
			<?php } ?>
						<!-- Modal -->
								<div class="modal fade" id="myModal<?php echo $count;?>" role="dialog">
									<div class="modal-dialog modal-sm">

									<!-- Modal content-->
									<form action="received_transaction.php" method="post">
										<div class="modal-content">
											<div class="modal-header">
												<!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
												<h4 class="modal-title">Purchase Order # <?php echo $row['purchase_order_aggregates_no'] ?></h4>
											</div>
											<div class="modal-body" style="text-align: left;">
												<input type="hidden" id="po_no" name="po_no" class="form-control" value="<?php echo $row['purchase_order_aggregates_no'] ?>">
												<input type="hidden" id="item_no" name="item_no" class="form-control" value="<?php echo $row['item_no'] ?>">
												Item: <strong><?php echo $row['item_no'] ?></strong> 
												<div class="form-group">
													<label for="volume">
														<!-- Remaining (<?php echo number_format((float)getPurchaseAggQuantity($db, $row['purchase_order_aggregates_no'], $row['item_no'], $row['office'], $row['purchase_order_aggregates_id']))." ".getTruck($db, $row['item_no']); ?>) <br> -->
														Quantity received (<?php 
														if(getUnit($db, $row['item_no']) == 'm3'){
															echo "m&sup3;";
														}else{
															echo getUnit($db, $row['item_no']);
														}
							
														if(getTruck($db, $row['item_no']) == 'liter'){
															echo "";
														}else{
															echo " per ". getTruck($db, $row['item_no']); 
														} 
														?>)	
													</label>
													<input type="text" id="volume" name="volume" class="form-control"  required>
												</div>
												<div class="form-group">
													<label for="delivery">Delivery No.</label>
													<input type="text" id="delivery" name="delivery" class="form-control"  required>
												</div>
												<div class="form-group">
													<label for="truck">Truck No.</label>
													<input type="text" id="truck" name="truck" class="form-control"  required>
												</div>								
											</div>
											<div class="modal-footer">
												<input type="submit" name="submit" value="Submit" class="btn btn-primary">
												<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
											</div>
										</div>
									</form>
									</div>
								</div>
								</td>
							</tr>
<?php
		$count++;
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

<?php
	}
?>
				</div>
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

		$po_no = mysqli_real_escape_string($db, $_POST['po_no']);
		$item = mysqli_real_escape_string($db, $_POST['item_no']);

		if($item == 'Diesel'){
			$balance = mysqli_real_escape_string($db, $_POST['volume']);
		}else{
			$balance = 1;
		}
		$delivery = mysqli_real_escape_string($db, $_POST['delivery']);
		$truck = mysqli_real_escape_string($db, $_POST['truck']);
		$volume = mysqli_real_escape_string($db, $_POST['volume']);
		$datetime = date("Y-m-d H:i:s");
		$po_agg_id = getPurchaseAggId($db, $po_no, $item, $office);
		// $plant = getPurchaseAggPlant($db, $po_no, $item, $po_agg_id);
		$supplier = getPurchaseAggSupplier($db, $po_no, $item, $office, $po_agg_id);

		$query_received = "INSERT INTO received(po_aggregates_no_received, item_no, delivery_no_received, truck_no, volume, office, supplier_name, date_po_aggregates, remarks)
							VALUES ('$po_no','$item','$delivery','$truck','$volume','$office','$supplier','$datetime','Received')";

		if($item == 'Diesel'){
			if(getPurchaseAggQuantity($db, $po_no, $item, $office, $po_agg_id) > 0){
				$po_aggregates_query = "UPDATE purchase_order_aggregates 
										SET received = received + '$balance', quantity = quantity - $balance
										WHERE item_no = '$item' 
										AND purchase_order_aggregates_no = '$po_no' 
										AND office = '$office'
										AND purchase_order_aggregates_id = '".$po_agg_id."'";
				mysqli_query($db, $po_aggregates_query);
			}

			if(getPurchaseAggQuantity($db, $po_no, $item, $office, $po_agg_id) == 0){
				$success = "UPDATE purchase_order_aggregates 
							SET remarks = 'Success'
							WHERE item_no = '$item' 
							AND office = '$office'
							AND purchase_order_aggregates_no = '$po_no' 
							AND purchase_order_aggregates_id = '".$po_agg_id."'";
				mysqli_query($db, $success);
			}

			$diesel_query = "SELECT item_no FROM item_stock WHERE item_no = '$item' AND office = '$office'";
			if(mysqli_num_rows($result) > 0){
				$stock = "UPDATE item_stock SET stock = stock + '$balance', last_update = '$datetime' 
							WHERE item_no = '$item' AND office = '$office'";
			}else{
				$stock = "INSERT INTO item_stock(item_no, stock, office, last_update) 
							VALUES('$item','$balance','$office','$datetime')";
			}
			mysqli_query($db, $stock);

			$insert_diesel = "INSERT INTO diesel(office, quantity_in, balance, truck_no, operator, delivery_date)
								VALUES('$office','$balance','".getStock($db, $item, $office)."','$truck','$supplier','$datetime')";

			$history_query = "INSERT INTO history(table_report, transaction_type, item_no, detail, history_date, office) 
						VALUES('Received','Received ','$item','$office received $balance ".getTruck($db, $item)." of $item from $supplier with P.O no. $po_no and DR. no. $delivery','$datetime','$office')";

			// echo $stock;
			// echo $query_received;
			// echo $po_aggregates_query;
			// echo $success;
			// echo $insert_diesel;
			// echo $history_query;
			if(mysqli_query($db, $query_received) && mysqli_query($db, $insert_diesel) && mysqli_query($db, $history_query)){
				phpAlert("Item received successfully!!");
				echo "<meta http-equiv='refresh' content='0'>";
			}else{
				phpAlert("Something went wrong!!");
			}
		}else{

			$po_aggregates_query = "UPDATE purchase_order_aggregates 
   									SET received = received + '$balance', quantity = quantity - '$balance' 
   									WHERE item_no = '$item' 
   									AND purchase_order_aggregates_no = '$po_no' 
   									AND purchase_order_aggregates_id = '$po_agg_id'
   									AND office = '$office'";
   			mysqli_query($db, $po_aggregates_query);

	  		if(getPurchaseAggQuantity($db, $po_no, $item, $office, $po_agg_id) == 0){
	  			$po_aggregates_status = "UPDATE purchase_order_aggregates 
	  									SET remarks = 'Success'
	  									WHERE item_no = '$item' 
	  									AND purchase_order_aggregates_no = '$po_no'
	  									AND purchase_order_aggregates_id = '$po_agg_id'
	  									AND office = '$office'";
	  									echo $po_aggregates_status;
	  			mysqli_query($db, $po_aggregates_status);
	  		}

	  		if($item == 'Cement'){

	  			$sql = "SELECT item_no, stock FROM item_stock
	  					WHERE item_no = 'Cement' AND office = '$office'";
	  			$cement = $volume * 40;
	  			$result = mysqli_query($db, $sql);
	  			if(mysqli_num_rows($result) > 0){
	  				$stock_update = "UPDATE item_stock SET stock = stock + '$cement', last_update = '$datetime' 
	  									WHERE item_no = 'Cement' AND office = '$office'";
	  			}else{
	  				$stock_update = "INSERT INTO item_stock(item_no, stock, office, last_update) 
	  								VALUES('Cement','$cement','$office','$datetime')";
	  			}
	  			// echo $stock_update;
	  			mysqli_query($db, $stock_update);	
	  		}

	  		$history_query = "INSERT INTO history(table_report, transaction_type, item_no, detail, history_date, office) 
							VALUES('Received','Received DR No.','$item','".ucfirst($office)." received $balance ".getTruck($db, $item)." of $item from $supplier with P.O no. $po_no and DR. no. $delivery','$datetime','$office')";

			// echo $query_received ."<br>";				
			// echo $po_aggregates_query ."<br>";
			// echo $stock_update ."<br>";
			// echo $history_query ."<br>";
			if(mysqli_query($db, $query_received) && mysqli_query($db, $history_query)){
				phpAlert("Item received successfully!!");
				echo "<meta http-equiv='refresh' content='0'>";
			}else{
				phpAlert("Something went wrong!!");
			}
		}
	}

?>