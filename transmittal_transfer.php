<!DOCTYPE html>
<?php

	include("includes/config.php");
	include("includes/function.php");
	//include("received_function.php");

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
		echo "<title>Transfer Item List - Transmittal - Quality Star Concrete Products, Inc.</title>";
	}else{
		echo "<title>Transfer Item List - Transmittal - Starcrete Manufacturing Corporation</title>";
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

	$(document).ready(function() {
		$('#myModal').on('hidden.bs.modal', function () {
		    $('.modal-body').find("input,textarea,select").val('').end();
		});
	});
	
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
  width: 99%;
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
	min-height: 1200px
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
			<!-- <span style="font-size:30px; cursor:pointer; color: white;" onclick="openNav();">&#9776;</span> -->
			<span style="font-size:25px; cursor:pointer; color: white;">Transmittal > Transferred Items</span>
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
			<div class="row" style="margin: 0px; margin-bottom: 5px;">
				<div class="col-md-4">
					<div class="btn-group" role="group">
						<button type='button' class='btn btn-default' onclick="location.href='transmittal.php';"><span class='glyphicon glyphicon-arrow-left'></span> Back to Transmittal Page</button>
					</div>
				</div>
				<div class="col-md-3 col-md-offset-5">
					<div class="pull-right">
					<form action="transmittal_transfer.php" method="post" class="form-inline">
						<input type="date" name="date_view" class="form-control" value="<?php if(isset($_POST['date_view'])) { echo htmlentities ($_POST['date_view']); }?>">
						<input type="submit" name="search_table" id="search_table" value="Search" class="btn btn-primary">
					</form>
					</div>
				</div>
			</div>
			<div class="row" style="margin: 0px;">
				<div class="col-md-12">
					<div class="filterable">
<?php
	if(isset($_POST['search_table'])){
		if($_POST['date_view'] == ''){
			$string_date = "";
		}else{
			$date = $_POST['date_view'];
			$string_date = " AND DATE_FORMAT(transmittal_date,'%Y-%m-%d') = '$date'";
		}
?>
					<table class="table table-striped">
						<thead>
							<tr>
								<th colspan="12"><h3>Transferred Items</h3></th>
				     		</tr>
							<tr class="filterable">
								<th colspan="6">
									<button class="btn btn-default btn-xs btn-filter" style="float: right;"><span class="glyphicon glyphicon-filter"></span> Filter</button>
								</th>
							</tr>
				     		<tr class="filters">
				     			<th class="col-md-1"><input type="text" class="form-control" placeholder="Transmittal #" disabled></th>
								<th class="col-md-1">To Office</th>
								<th class="col-md-1">Item</th>
								<th class="col-md-2">Delivered by</th>
								<th class="col-md-1">Date</th>
								<th class="col-md-1"><input type="text" class="form-control" placeholder="Status" disabled></th>
							</tr>
				     	</thead>
				     	<tbody>
<?php
	$sql = "SELECT transmittal_no, office, transmittal_unique_id, delivered_by, transmittal_date, remarks, from_office, count(transmittal_id) as count 
			FROM transmittal 
			WHERE from_office = '$office' 
			AND office != '$office'".$string_date."
			GROUP BY transmittal_no, office, from_office, delivered_by, transmittal_unique_id 
			ORDER BY transmittal_date DESC";

	$result = mysqli_query($db, $sql);
	if(mysqli_num_rows($result) > 0){
		while ($row = mysqli_fetch_assoc($result)) {
			$date = date_create($row['transmittal_date']);

			$count_sql = "SELECT count(transmittal_id) as count FROM transmittal 
			WHERE transmittal_unique_id = '".$row['transmittal_unique_id']."' AND remarks = 'Received' AND office = '".$row['office']."' AND from_office = '$office'";

			$count_sql_result = mysqli_query($db, $count_sql);
			$count = mysqli_num_rows($count_sql_result);
			$row1 = mysqli_fetch_assoc($count_sql_result);
?>

							<tr>
								<td class="col-md-1"><strong><?php echo $row['transmittal_no'] ?></strong></td>
								<td class="col-md-1">
									<strong>
								<?php 
									if($row['office'] == 'Head' || $row['office'] == 'head'){
										echo "Head Office";
									}else{
										echo ucfirst($row['office']);
									}
								?>
									</strong>
								</td>
								<td class="col-md-1">
									<form action="transmittal_transfer_item.php" method="post">
										<input type="submit" name="transferred" value="View <?php echo $row['count'] ?> Item(s)" class="btn btn-success btn-sm">
		       							<input type="hidden" name="pass_transmittal_no" value="<?php echo $row['transmittal_no']; ?>">
		       							<input type="hidden" name="pass_from_office" value="<?php echo $row['from_office']; ?>">
		       							<input type="hidden" name="pass_to_office" value="<?php echo $row['office']; ?>">
		       							<input type="hidden" name="pass_id" value="<?php echo $row['transmittal_unique_id']; ?>">
									</form>
								</td>
								<td class="col-md-2"><strong><?php echo $row['delivered_by'] ?></strong></td>
								<td class="col-md-1"><strong><?php echo date_format($date,'m/d/y g:i A') ?></strong></td>
<?php
					if($row['count'] == $row1['count']){
						echo "<td class='col-md-1'><strong>Received</strong></td>";
					}else{
						echo "<td class='col-md-1'><strong>Pending</strong></td>";
					}
?>
							</tr>
<?php
			}
		}else{
?>
							<tr>
								<td style='width: 1500px; height: 340px; background: white; border: none; text-align:center; 
		    vertical-align:middle;'><h4><p class='text-muted'>No data found</p></h4></td>
		    				</tr>
<?php
		}
?>
						</tbody>
				     </table>
<?php
	}else{
?>			   
					<table class="table table-striped">
						<thead>
							<tr>
								<th colspan="12"><h3>Transferred Items</h3></th>
				     		</tr>
				     		<tr class="filterable">
								<th colspan="6">
									<button class="btn btn-default btn-xs btn-filter" style="float: right;"><span class="glyphicon glyphicon-filter"></span> Filter</button>
								</th>
							</tr>
				     		<tr class="filters">
				     			<th class="col-md-1"><input type="text" class="form-control" placeholder="Transmittal #" disabled></th>
								<th class="col-md-1">To Office</th>
								<th class="col-md-1">Item</th>
								<th class="col-md-2">Delivered by</th>
								<th class="col-md-1">Date</th>
								<th class="col-md-1"><input type="text" class="form-control" placeholder="Status" disabled></th>
							</tr>
				     	</thead>
				     	<tbody>
<?php
	$sql = "SELECT transmittal_no, transmittal_unique_id, office, delivered_by, transmittal_date, remarks, from_office, count(transmittal_id) as count 
			FROM transmittal 
			WHERE from_office = '$office' 
			AND office != '$office'
			GROUP BY transmittal_no, office, from_office, delivered_by, transmittal_unique_id 
			ORDER BY transmittal_date DESC";
	
	$result = mysqli_query($db, $sql);
	if(mysqli_num_rows($result) > 0){
		while ($row = mysqli_fetch_assoc($result)) {
			$date = date_create($row['transmittal_date']);

			$count_sql = "SELECT count(transmittal_id) as count FROM transmittal 
			WHERE transmittal_unique_id = '".$row['transmittal_unique_id']."' AND remarks = 'Received' AND office = '".$row['office']."' AND from_office = '$office'";

			$count_sql_result = mysqli_query($db, $count_sql);
			$count = mysqli_num_rows($count_sql_result);
			$row1 = mysqli_fetch_assoc($count_sql_result);
?>

							<tr>
								<td class="col-md-1"><strong><?php echo $row['transmittal_no'] ?></strong></td>
								<td class="col-md-1">
									<strong>
								<?php 
									if($row['office'] == 'Head' || $row['office'] == 'head'){
										echo "Head Office";
									}else{
										echo ucfirst($row['office']);
									}
								?>
									</strong>
								</td>
								<td class="col-md-1">
									<form action="transmittal_transfer_item.php" method="post">
										<input type="submit" name="transferred" value="View <?php echo $row['count'] ?> Item(s)" class="btn btn-success btn-sm">
		       							<input type="hidden" name="pass_transmittal_no" value="<?php echo $row['transmittal_no']; ?>">
		       							<input type="hidden" name="pass_from_office" value="<?php echo $row['from_office']; ?>">
		       							<input type="hidden" name="pass_to_office" value="<?php echo $row['office']; ?>">
		       							<input type="hidden" name="pass_id" value="<?php echo $row['transmittal_unique_id']; ?>">
									</form>
								</td>
								<td class="col-md-2"><strong><?php echo $row['delivered_by'] ?></strong></td>
								<td class="col-md-1"><strong><?php echo date_format($date,'m/d/y g:i A') ?></strong></td>
<?php
					if($row['count'] == $row1['count']){
						echo "<td class='col-md-1'><strong>Received</strong></td>";
					}else{
						echo "<td class='col-md-1'><strong>Pending</strong></td>";
					}
?>
							</tr>
<?php
			}
		}else{
?>
							<tr>
								<td style='width: 1500px; height: 340px; background: white; border: none; text-align:center; 
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
	