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

	if($office != 'head') {
		header("location: index.php");
	}
?>
<html>
<head>
<?php
	if($office == 'delta'){
		echo "<title>Pending Orders - Purchase Order Aggregates - Quality Star Concrete Products, Inc.</title>";
	}else{
		echo "<title>Pending Orders - Purchase Order Aggregates - Starcrete Manufacturing Corporation</title>";
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
  /*width: 99%;*/
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
	min-height: 491px;
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

/* reset our lists to remove bullet points and padding */
.mainmenu, .submenu {
  list-style: none;
  padding: 0;
  margin: 0;
}

/* when hovering over a .mainmenu item,
  display the submenu inside it.
  we're changing the submenu's max-height from 0 to 200px;
*/
.mainmenu li:hover .submenu {
  display: block;
  max-height: 200px;
}

/*
  we now overwrite the background-color for .submenu links only.
  CSS reads down the page, so code at the bottom will overwrite the code at the top.
*/
.submenu a {
  background-color: #1f97f9;
}

/* this is the initial state of all submenus.
  we set it to max-height: 0, and hide the overflowed content.
*/
.submenu {
  overflow: hidden;
  max-height: 0;
  -webkit-transition: all 0.5s ease-out;
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
			<span style="font-size:25px; color: white;">Purchase Order Aggregates</span>
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

	<div id="wrapper" onclick="closeNav();">
		<div id="content">
			<form action="purchase_order_aggregates.php" method="post" class="form-inline">
			<div class="row" style="margin: 0px; margin-bottom: 5px;">
				<div class="col-md-5">
					
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
						<!-- <input type="date" name="date_view" class="form-control" value="<?php if(isset($_POST['date_view'])) { echo htmlentities ($_POST['date_view']); }?>">
						<input type="submit" name="search_table" id="search_table" value="Search" class="btn btn-primary"> -->
					<!-- </form> -->
				</div>
			</div>
			<div class="row" style="margin: 0px; margin-bottom: 5px;">
				<div class="col-md-6">
					<label for="start_date">Start Date:</label><input type="date" name="start_date" class="form-control" value="<?php if(isset($_POST['start_date'])) { echo htmlentities ($_POST['start_date']); }?>">
					<label for="end_date">End Date:</label><input type="date" name="end_date" class="form-control" value="<?php if(isset($_POST['end_date'])) { echo htmlentities ($_POST['end_date']); }?>">
					<input type="submit" name="search_table" id="search_table" value="Search" class="btn btn-primary">
				</div>
				<div class="col-md-3 col-md-offset-3">
					<div class="pull-right">
						<!-- <button type="button" onclick="location.href='purchase_order_aggregates_form.php';" class="btn btn-success" style="float: right;"><span class='glyphicon glyphicon-plus'></span> Add Transaction</button> -->
						<div class="dropdown">
							<a href="purchase_order_aggregates_form.php" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> Add Transaction</a>
							<button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
<!-- <?php
	if($office == 'head'){
		echo "Transaction / View Reports";
	}else{
		echo "View Reports";
	}
?> -->
							View Reports <span class="caret"></span></button>
							<ul class="dropdown-menu pull-right">
<!-- <?php
	if($office == 'head' && $position == 'secretary'){
?>
								<li class="dropdown-header">Transaction</li>
								<li><a href="purchase_order_aggregates_form.php">Add Transaction</a></li>
								<li class="divider"></li>
<?php
	}
?> -->
								<li class="dropdown-header">View Reports</li>
								<li>
									<a href="purchase_order_aggregates.php">Pending Orders 
										<span class="badge">
<?php 
	if($office == 'head'){
		echo getCountPending($db,'bravo') + getCountPending($db,'delta'); 
	}else{
		echo getCountPending($db, $office);
	}
?>

										</span>
									</a>
								</li>
								<li><a href="purchase_order_aggregates_success.php">Received Orders</a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			</form>
			<div class="row" style="margin: 0px; margin-bottom: 5px">
				<div class="col-md-12">
					<div class="filterable">
<?php
	if(isset($_POST['search_table'])){
		if($office == 'head'){
			if(isset($_POST['radioOffice'])){
				$search_plant = $_POST['radioOffice'];
			}
		}else{
			$search_plant = $office;
		}
		// if($_POST['date_view'] == ''){
		// 	$string_date = "";
		// }else{
		// 	$date = $_POST['date_view'];
		// 	$string_date = "AND DATE_FORMAT(date_po_aggregates,'%Y-%m-%d') = '$date'";
		// }
		
		// $date_view = date_create($date);

		if($_POST['end_date'] == ''){
			$end_date = date("Y-m-d");
		}else{
			$end_date = $_POST['end_date'];
		}
		if($_POST['start_date'] == ''){
			// $string_date = "";
			$string_date = "AND DATE_FORMAT(date_po_aggregates,'%Y-%m-%d') <= '$end_date'";
		}else{
			$date = $_POST['start_date'];
			// $string_date = "AND DATE_FORMAT(date_po_aggregates,'%Y-%m-%d') = '$date'";
			$string_date = "AND DATE_FORMAT(date_po_aggregates,'%Y-%m-%d') BETWEEN '$date' AND '$end_date'";
		}
?>
					<table class="table table-striped">
						<thead>
							<!-- <tr>
								<th colspan="10" style="text-align: left;">
									Date: <?php echo date_format($date_view,"F d, Y"); ?>
								</th>
							</tr> -->
							<!-- <tr>
								<th class="col-md-1">P.O. #</th>
				     			<th class="col-md-1">Item</th>
				     			<th class="col-md-1">Balance</th>
				     			<th class="col-md-1">Received</th>
								<th class="col-md-1">Price</th>
								<th class="col-md-2">Supplier</th>
								<th class="col-md-1">Date Ordered</th>
								<th class="col-md-1">Status</th>
							</tr> -->
							<!-- <tr>
								<th colspan="8"><h3>Pending Orders</h3></th>
							</tr> -->
							<tr class="filterable">
								<th colspan="1" style="text-align: left;">Plant: <?php echo ucfirst($search_plant); ?></th>
								<th colspan="7">
									<button class="btn btn-default btn-xs btn-filter" style="float: right;"><span class="glyphicon glyphicon-filter"></span> Filter</button>
								</th>
							</tr>
							<tr class="filters">
		                        <th class="col-md-1"><input type="text" class="form-control" placeholder="P.O. No." disabled></th>
		                        <th class="col-md-1">Item</th>
		                        <th class="col-md-1">Balance</th>
		                        <th class="col-md-1">Received</th>
		                        <th class="col-md-1">Price</th>
								<th class="col-md-2">Supplier</th>
		                       	<th class="col-md-1"><input type="text" class="form-control" placeholder="Date Ordered" disabled></th>
								<th class="col-md-1">Status</th>
							</tr>
						</thead>
						<tbody>
<?php
		if($office == 'head'){
			$string = " AND office = '$search_plant'";
		}else{
			$string = " AND office = '$office'";
		}

		$query = "SELECT p.purchase_order_aggregates_no, p.item_no, CONCAT(FORMAT(p.quantity,0),' ',l.truck) as detail, FORMAT(p.price,0) as price , l.unit, p.supplier_name, DATE_FORMAT(p.date_po_aggregates,'%m/%d/%y') as date_po_aggregates1, p.remarks, p.office, CONCAT(FORMAT(p.received,0), ' ', l.truck) as received 
					FROM purchase_order_aggregates p, item_list l
					WHERE p.item_no = l.item_no AND p.remarks = 'Pending' ".$string." ".$string_date."
					ORDER BY date_po_aggregates DESC, purchase_order_aggregates_no DESC";
					// echo $query;
		$result = mysqli_query($db, $query);
		if(mysqli_num_rows($result) > 0){
			while($row = mysqli_fetch_assoc($result)){
				// $date = date_create($row['date_po_aggregates']);
?>
							<tr>
								<td class='col-md-1'><strong><?php echo $row['purchase_order_aggregates_no']; ?></strong></td>
								<td class='col-md-1'><strong><?php echo $row['item_no']; ?></strong></td>
								<td class='col-md-1'><strong><?php echo $row['detail']; ?></strong></td>
								<td class='col-md-1'><strong><?php echo $row['received']; ?></strong></td>
								<td class='col-md-1'>
									<strong><?php echo "&#8369 ".$row['price']."/";
								if($row['unit'] == 'm3'){
									echo "m&sup3;";
								}else{
									echo $row['unit'];
								}
		?>	
									</strong>
								</td>
								<td class='col-md-2'><strong><?php echo $row['supplier_name']; ?></strong></td>
								<td class='col-md-1'><strong><?php echo $row['date_po_aggregates1'] ?></strong></td>
<?php
							if($row['remarks'] == "Success"){
?>
								<td class='col-md-1' style="background: green; color: white;"><strong><?php echo $row['remarks']; ?></strong></td>
<?php
							}else if($row['remarks'] == "Pending"){
?>
								<td class='col-md-1'><strong><?php echo $row['remarks']; ?></strong></td>
<?php
							}else{
?>
								<td class='col-md-1' style="background: orange; color: white;"><strong><?php echo $row['remarks']; ?></strong></td>
<?php
							}
?>
							</tr>

<?php
			}
		}else{
?>
							<tr>
								<td style='width: 1500px; height: 300px; background: white; border: none; text-align:center; 
				vertical-align:middle;'><h4><p class='text-muted'>No data found</p></h4></td>
							</tr>
<?php
		}
?>
						</tbody>
					</table>
				
<?php
	}else{
		// if(isset($_POST['radioOffice'])){
		// 	$search_plant = $_POST['radioOffice'];
		// }else{
		// 	$search_plant = 'bravo';
		// }
		// $date = date("Y-m-d");
		// $date_view = date_create($date);

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
								<th colspan="10" style="text-align: left;">
									Date: <?php echo date_format($date_view,"F d, Y"); ?>
								</th>
							</tr> -->
							<!-- <tr>
								<th class="col-md-1">P.O. #</th>
				     			<th class="col-md-1">Item</th>
				     			<th class="col-md-1">Balance</th>
				     			<th class="col-md-1">Received</th>
								<th class="col-md-1">Price</th>
								<th class="col-md-2">Supplier</th>
								<th class="col-md-1">Date Ordered</th>
								<th class="col-md-1">Status</th>
							</tr> -->
							<!-- <tr>
								<th colspan="8"><h3>Pending Orders</h3></th>
							</tr> -->
							<tr class="filterable">
								<th colspan="1" style="text-align: left;">Plant: <?php echo ucfirst($search_plant); ?></th>
								<th colspan="7">
									<button class="btn btn-default btn-xs btn-filter" style="float: right;"><span class="glyphicon glyphicon-filter"></span> Filter</button>
								</th>
							</tr>
							<tr class="filters">
		                        <th class="col-md-1"><input type="text" class="form-control" placeholder="P.O. No." disabled></th>
		                        <th class="col-md-1">Item</th>
		                        <th class="col-md-1">Balance</th>
		                        <th class="col-md-1">Received</th>
		                        <th class="col-md-1">Price</th>
								<th class="col-md-2">Supplier</th>
		                       	<th class="col-md-1"><input type="text" class="form-control" placeholder="Date Ordered" disabled></th>
								<th class="col-md-1">Status</th>
							</tr>
						</thead>
						<tbody>
<?php
		if($office == 'head'){
			$string = " AND office = '$search_plant'";
		}else{
			$string = " AND office = '$office'";
		}

		$query = "SELECT p.purchase_order_aggregates_no, p.item_no, CONCAT(FORMAT(p.quantity,0),' ',l.truck) as detail, FORMAT(p.price,0) as price, l.unit, p.supplier_name, DATE_FORMAT(p.date_po_aggregates,'%m/%d/%y') as date_po_aggregates1, p.remarks, p.office, CONCAT(FORMAT(p.received,0), ' ', l.truck) as received 
					FROM purchase_order_aggregates p, item_list l
					WHERE p.item_no = l.item_no AND p.remarks = 'Pending' ".$string."				
					ORDER BY date_po_aggregates DESC, purchase_order_aggregates_no DESC";
					// echo $query;
		$result = mysqli_query($db, $query);
		if(mysqli_num_rows($result) > 0){
			while($row = mysqli_fetch_assoc($result)){
				// $date = date_create($row['date_po_aggregates']);
?>
							<tr>
								<td class='col-md-1'><strong><?php echo $row['purchase_order_aggregates_no']; ?></strong></td>
								<td class='col-md-1'><strong><?php echo $row['item_no']; ?></strong></td>
								<td class='col-md-1'><strong><?php echo $row['detail']; ?></strong></td>
								<td class='col-md-1'><strong><?php echo $row['received']; ?></strong></td>
								<td class='col-md-1'>
									<strong><?php echo "&#8369 ".$row['price']."/";
								if($row['unit'] == 'm3'){
									echo "m&sup3;";
								}else{
									echo $row['unit'];
								}
		?>	
									</strong>
								</td>
								<td class='col-md-2'><strong><?php echo $row['supplier_name']; ?></strong></td>
								<td class='col-md-1'><strong><?php echo $row['date_po_aggregates1'] ?></strong></td>
<?php
							if($row['remarks'] == "Success"){
?>
								<td class='col-md-1' style="background: green; color: white;"><strong><?php echo $row['remarks']; ?></strong></td>
<?php
							}else if($row['remarks'] == "Pending"){
?>
								<td class='col-md-1'><strong><?php echo $row['remarks']; ?></strong></td>
<?php
							}else{
?>
								<td class='col-md-1' style="background: orange; color: white;"><strong><?php echo $row['remarks']; ?></strong></td>
<?php
							}
?>
							</tr>

<?php
			}
		}else{
?>
							<tr>
								<td style='width: 1500px; height: 300px; background: white; border: none; text-align:center; 
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