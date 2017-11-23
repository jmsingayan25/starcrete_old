<!DOCTYPE html>
<?php
	
	include("includes/config.php");
	include("includes/function.php");
	// include("delivery_function.php");
	// header("Cache-Control: no cache");
	// session_cache_limiter("private_no_expire");
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
		echo "<title>Delivered Orders - Delivery Report - Quality Star Concrete Products, Inc.</title>";
	}else{
		echo "<title>Delivered Orders - Delivery Report - Starcrete Manufacturing Corporation</title>";
	}
?>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="stylesheet" href="css_ext/sidebar.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script src="js_ext/sidebar.js" type="text/javascript" ></script>

<script>

	// var time = new Date().getTime();
	// $(document.body).bind("mousemove keypress", function(e) {
	// 	time = new Date().getTime();
	// });

	// function refresh() {
	// 	if(new Date().getTime() - time >= 60000) 
	// 		window.location.reload(true);
	// 	else 
	// 		setTimeout(refresh, 10000);
 //    }

 //    setTimeout(refresh, 10000);

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


	$(document).ready(function(){
	    $('.filterable .btn-filter').click(function(){
	        var $panel = $(this).parents('.filterable'),
	        $filters = $panel.find('.filters input'),
	        $tbody = $panel.find('.table tbody');
	        if ($filters.prop('disabled') == true) {
	            $filters.prop('disabled', false);
	            $filters.first().focus();
	        } else {
	            $filters.val('').prop('disabled', true);
	            $tbody.find('.no-result').remove();
	            $tbody.find('tr').show();
	        }
	    });

	    $('.filterable .filters input').keyup(function(e){
	        /* Ignore tab key */
	        var code = e.keyCode || e.which;
	        if (code == '9') return;
	        /* Useful DOM data and selectors */
	        var $input = $(this),
	        inputContent = $input.val().toLowerCase(),
	        $panel = $input.parents('.filterable'),
	        column = $panel.find('.filters th').index($input.parents('th')),
	        $table = $panel.find('.table'),
	        $rows = $table.find('tbody tr');
	        /* Dirtiest filter function ever ;) */
	        var $filteredRows = $rows.filter(function(){
	            var value = $(this).find('td').eq(column).text().toLowerCase();
	            return value.indexOf(inputContent) === -1;
	        });
	        /* Clean previous no-result if exist */
	        $table.find('tbody .no-result').remove();
	        /* Show all rows, hide filtered ones (never do that outside of a demo ! xD) */
	        $rows.show();
	        $filteredRows.hide();
	        /* Prepend no-result row if all rows are filtered */
	        if ($filteredRows.length === $rows.length) {
	            $table.find('tbody').prepend($('<tr class="no-result text-center"><td  style="width: 1500px; height: 300px;background: white; border: none; text-align:center; vertical-align:middle;"><h4><p class="text-muted">No data found</p></h4></td></tr>'));
	        }
	    });
	});

	function disabledButton(){
		var order = document.getElementById('hidden_quantity_order').value;
		var stock = document.getElementById('hidden_stock').value;
		var button = document.getElementById('delivered');

		setTimeout(function () {
			if(+order > +stock || +stock <= '0'){
				button.disabled = true;
			}else{
				button.disabled = false;
			}
		}, 0);
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
  /*width: 98%;*/
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

.filterable .panel-heading .pull-right {
    margin-top: -20px;
}
.filterable .filters input[disabled] {
    background-color: transparent;
    text-align: center;
    border: none;
    cursor: auto;
    box-shadow: none;
    padding: 0;
    height: auto;
}
.filterable .filters input[disabled]::-webkit-input-placeholder {
    color: white;
}
.filterable .filters input[disabled]::-moz-placeholder {
     color: white;
}
.filterable .filters input[disabled]:-ms-input-placeholder {
     color: white;
}

</style>
</head>
<body onload="disabledButton();">
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
			<span style="font-size:25px; color: white;"><a href="delivery.php" style="color: white;">Delivery Report</a> > Delivered Orders</span>
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

	<div id="wrapper" onclick="closeNav();">
		<div id="content">
			<form action="delivery_order.php" method="post" class="form-inline">
			<div class="row" style="margin: 0px; margin-bottom: 5px;">
				<div class="col-md-7">
					<div class="pull-left">
					
<?php
	if($office == 'head'){
?>
						<label for="search">Plant: </label>
						<label class="radio-inline">
							<input type="radio" name="radioOffice" value="bravo" <?php if(isset($_POST['radioOffice']) && $_POST['radioOffice'] == 'bravo') { echo "checked"; }?> checked>Bravo
						</label>
						<label class="radio-inline">
							<input type="radio" name="radioOffice" value="delta" <?php if(isset($_POST['radioOffice']) && $_POST['radioOffice'] == 'delta') { echo "checked"; }?>>Delta
						</label>
<?php
	}
?>	
						
					
					</div>
				</div>
			</div>
			<div class="row" style="margin: 0px; margin-bottom: 5px;">
				<div class="col-md-6">
					<label for="start_date">Start Date:</label><input type="date" name="start_date" class="form-control" value="<?php if(isset($_POST['start_date'])) { echo htmlentities ($_POST['start_date']); }?>">
					<label for="end_date">End Date:</label><input type="date" name="end_date" class="form-control" value="<?php if(isset($_POST['end_date'])) { echo htmlentities ($_POST['end_date']); }?>">
					<input type="submit" name="search_received" id="search_received" value="Search" class="btn btn-primary">
				</div>
				<div class="col-md-3 col-md-offset-3">
					<!-- <a href='delivery.php' class='btn btn-default'><span class="glyphicon glyphicon-arrow-left"></span> Back to Delivery Report</a> -->
					<div class="pull-right">
<?php
	if($office != 'head'){
		$string = " AND office = '$office'";
	}else{
		$string = "";
	}

	$sql = "SELECT purchase_order_no FROM purchase_order WHERE balance != 0".$string;
	$result = mysqli_query($db, $sql);
	$count = mysqli_num_rows($result);

?>
						<div class="dropdown">
<?php
	if($count > 0){
?>
							<a href='delivery_transaction.php' class="btn btn-danger">Issue DR No. <span class='badge'><?php echo $count; ?></span></a>
<?php
	}else{
?>
							<a href='delivery_transaction.php' class="btn btn-success" readonly>Issue DR No. <span class='badge'><?php echo $count; ?></span></a>
<?php
	}
?>
							<button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">View Reports
							<span class="caret"></span></button>
							<ul class="dropdown-menu dropdown-menu-right">
<!-- 								<li class="dropdown-header">Order</li>
								<li>
<?php
	if($count > 0){
?>
									<a href='delivery_transaction.php' class="btn-danger">Issue DR No. <span class='badge'><?php echo $count; ?></span></a>
<?php
	}else{
?>
									<a href='delivery_transaction.php' class="btn-success" readonly>Issue DR No. <span class='badge'><?php echo $count; ?></span></a>
<?php
	}
?>
								</li> -->
								<li class="dropdown-header">Reports</li>
								<li>
<?php
	if($office == 'head'){
?>
								<a href="delivery.php">On Delivery Orders <span class="badge"><?php echo getDeliveryCountOnDeliveryOffice($db, 'bravo') + getDeliveryCountOnDeliveryOffice($db, 'delta'); ?></span></a>
<?php
	}else{
?>
								<a href="delivery.php">On Delivery Orders <span class="badge"><?php echo getDeliveryCountOnDeliveryOffice($db, $office); ?></span></a>
<?php
	}
?>								
								</li>
								<li><a href='delivery_backload.php'>Backload Orders</a></li>
								<li><a href='delivery_order.php'>Delivered Orders</a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			</form>
			<div class="row" style="margin: 0px;">
				<div class="col-md-12">
					<div class="filterable">
<?php
	if(isset($_POST['search_received'])){
		if($office == 'head'){
			if(isset($_POST['radioOffice'])){
				$search_plant = $_POST['radioOffice'];
			}else{
				$search_plant = 'bravo';
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
			// $string_date = "";
			$string_date = "AND DATE_FORMAT(date_delivery,'%Y-%m-%d') <= '$end_date'";
		}else{
			$date = $_POST['start_date'];
			// $string_date = "AND DATE_FORMAT(date_delivery,'%Y-%m-%d') = '$date'";
			$string_date = "AND DATE_FORMAT(date_delivery,'%Y-%m-%d') BETWEEN '$date' AND '$end_date'";
		}
?>
					<table class="table table-striped">
						<thead>
							<!-- <tr>
								<th colspan="11"><h3>Delivered Orders</h3></th>
							</tr> -->
							<tr class="filterable">
								<th colspan="1">Plant: <?php echo ucfirst($search_plant); ?></th>
								<th colspan="7">
									<button class="btn btn-default btn-xs btn-filter" style="float: right;"><span class="glyphicon glyphicon-filter"></span> Filter</button>
								</th>
							</tr>
							<tr class="filters">
								<th class="col-md-1"><input type="text" class="form-control" placeholder="DR No." disabled></th>
		                        <th class="col-md-1"><input type="text" class="form-control" placeholder="P.O. No." disabled></th>
		                        <th class="col-md-1"><input type="text" class="form-control" placeholder="Item" disabled></th>
		                        <th class="col-md-1">Quantity</th>
		                        <th class="col-md-2"><input type="text" class="form-control" placeholder="Client Name" disabled></th>
		                        <th class="col-md-2">Address</th>
		                        <th class="col-md-1"><input type="text" class="form-control" placeholder="Date Delivered" disabled></th>
		                        <th class="col-md-1">Status</th>
							</tr>
						</thead>
						<tbody>
<?php
		if($office == 'head'){
			$string = " WHERE office = '$search_plant'";
		}else{
			$string = " WHERE office = '$office'";
		}

		$query = "SELECT *, FORMAT(quantity,0) as quantity, DATE_FORMAT(date_delivery,'%m/%d/%y') as date_delivery   
					FROM delivery ".$string." ".$string_date."
					AND remarks = 'Delivered' 
					ORDER BY date_delivery DESC, delivery_receipt_no DESC, office DESC";
					// echo $query;
		$result = mysqli_query($db, $query);
		if(mysqli_num_rows($result) > 0){
			while($row = mysqli_fetch_assoc($result)){
				// $date = date_create($row['date_delivery']);
?>	
							<tr>
								<td class='col-md-1'><strong><?php echo $row['delivery_receipt_no']; ?></strong></td>
								<td class='col-md-1' style="cursor: pointer;" title="Click here to view transactions under P.O. No. <?php echo $row['po_no_delivery'] ?>" onclick="window.location='delivery_po_order_no_details.php?fk_no=<?php echo $row['fk_po_id']; ?>&po_no_delivery=<?php echo $row['po_no_delivery'] ?>&office=<?php echo $row['office']; ?>'"><strong><?php echo $row['po_no_delivery']; ?></strong></td>
								<td class='col-md-1'><strong><?php echo $row['item_no']; ?></strong></td>
								<td class='col-md-1'><strong><?php echo $row['quantity'].' pcs'; ?></strong></td>
								<td class='col-md-2'><strong><?php echo $row['client_name']; ?></strong></td>
								<td class='col-md-2'><strong><?php echo $row['address']; ?></strong></td>
								<td class='col-md-1'><strong><?php echo $row['date_delivery']; ?></strong></td>
								<td class='col-md-1' style="background-color: green; color: white"><strong><?php echo $row['remarks']; ?></strong></td>
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
								<th colspan="11"><h3>Delivered Orders</h3></th>
							</tr> -->
							<tr class="filterable">
								<th colspan="1">Plant: <?php echo ucfirst($search_plant); ?></th>
								<th colspan="7">
									<button class="btn btn-default btn-xs btn-filter" style="float: right;"><span class="glyphicon glyphicon-filter"></span> Filter</button>
								</th>
							</tr>
							<tr class="filters">
								<th class="col-md-1"><input type="text" class="form-control" placeholder="DR No." disabled></th>
		                        <th class="col-md-1"><input type="text" class="form-control" placeholder="P.O. No." disabled></th>
		                        <th class="col-md-1"><input type="text" class="form-control" placeholder="Item" disabled></th>
		                        <th class="col-md-1">Quantity</th>
		                        <th class="col-md-2"><input type="text" class="form-control" placeholder="Client Name" disabled></th>
		                        <th class="col-md-2">Address</th>
		                        <th class="col-md-1"><input type="text" class="form-control" placeholder="Date Delivered" disabled></th>
		                        <th class="col-md-1">Status</th>
							</tr>
						</thead>
						<tbody>
<?php
		if($office == 'head'){
			$string = " WHERE office = '$search_plant'";
		}else{
			$string = " WHERE office = '$office'";
		}

		$query = "SELECT *, FORMAT(quantity,0) as quantity, DATE_FORMAT(date_delivery,'%m/%d/%y') as date_delivery  
					FROM delivery ".$string." 
					AND remarks = 'Delivered' 
					ORDER BY date_delivery DESC, delivery_receipt_no DESC, office DESC";

		$result = mysqli_query($db, $query);
		if(mysqli_num_rows($result) > 0){
			while($row = mysqli_fetch_assoc($result)){
				// $date = date_create($row['date_delivery']);
?>	
							<tr>
								<td class='col-md-1'><strong><?php echo $row['delivery_receipt_no']; ?></strong></td>
								<td class='col-md-1' style="cursor: pointer;" title="Click here to view transactions under P.O. No. <?php echo $row['po_no_delivery'] ?>" onclick="window.location='delivery_po_order_no_details.php?fk_no=<?php echo $row['fk_po_id']; ?>&po_no_delivery=<?php echo $row['po_no_delivery'] ?>&office=<?php echo $row['office']; ?>'"><strong><?php echo $row['po_no_delivery']; ?></strong></td>
								<td class='col-md-1'><strong><?php echo $row['item_no']; ?></strong></td>
								<td class='col-md-1'><strong><?php echo $row['quantity'].' pcs'; ?></strong></td>
								<td class='col-md-2'><strong><?php echo $row['client_name']; ?></strong></td>
								<td class='col-md-2'><strong><?php echo $row['address']; ?></strong></td>
								<td class='col-md-1'><strong><?php echo $row['date_delivery']; ?></strong></td>
								<td class='col-md-1' style="background-color: green; color: white"><strong><?php echo $row['remarks']; ?></strong></td>
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
<?php
	}
?>					
					</div>
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
