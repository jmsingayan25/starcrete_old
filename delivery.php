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

	if(!isset($_GET['page']) || $_GET['page'] == ''){
		$_GET['page'] = 0;
	}

	if(isset($_POST['radioOffice'])){
		$_SESSION['radioOffice'] = $_POST['radioOffice'];
	}

	if(isset($_POST['start_date'])){
		$_SESSION['start_date'] = $_POST['start_date'];
	}

	if(isset($_POST['end_date'])){
		$_SESSION['end_date'] = $_POST['end_date'];
	}

	if(!isset($_SESSION['radioOffice']) || !isset($_SESSION['start_date']) || !isset($_SESSION['end_date'])){
		$_SESSION['radioOffice'] = 'bravo';
		$_SESSION['start_date'] = '';
		$_SESSION['end_date'] = '';
	}

	$_POST['radioOffice'] = $_SESSION['radioOffice'];
	$_POST['start_date'] = $_SESSION['start_date'];
	$_POST['end_date'] = $_SESSION['end_date'];

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
		echo "<title>Delivery Report - Quality Star Concrete Products, Inc.</title>";
	}else{
		echo "<title>Delivery Report - Starcrete Manufacturing Corporation</title>";
	}
?>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="stylesheet" href="css_ext/sidebar.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script src="js_ext/sidebar.js" type="text/javascript"></script>

<script>

	$(document).ready(function() {
		$('#myModal').on('hidden.bs.modal', function () {
		    $('.modal-body').find("input,textarea,select").val('').end();
		});
	});

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
	            $table.find('tbody').prepend($('<tr class="no-result text-center"><td colspan="11" style="min-height: 100%; background: white; text-align:center; vertical-align:middle;"><h4><p class="text-muted">No data found</p></h4></td></tr>'));
	        }
	    });
	});

	function disabledButton(){
		var order = document.getElementById('hidden_quantity_order').value;
		var stock = document.getElementById('hidden_stock').value;
		var button = document.getElementById('delivered');

		setTimeout(function () {
			if(+order > +stock || +stock <= 0){
				button.disabled = true;
			}else{
				button.disabled = false;
			}
		}, 0);
	}

</script>
<style>
html, body {
   margin:0;
   padding:0;
   height:100%;
}
#wrapper {
	min-height:82%;
	position:relative;
}

#content {
	margin: 0 auto;
	/*min-height: 750px;*/
	padding-bottom:50px; /* Height of the footer element */
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
/*.table tbody{
  overflow-y: scroll;
  height: 500px;
  position: absolute;
  width: 98%;
  border:1px solid #cecece;
}*/
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

hr{
	margin-top: 0px; 
	/*margin-bottom: 0px;*/
}
.modal_input td{
	padding: 10px;
	border: 1px solid #d1d1d1;
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
			<span style="font-size:25px; color: white;">Delivery Report</span>
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
			<form action="delivery.php" method="post" class="form-inline">
			<div class="row" style="margin: 0px; margin-bottom: 5px;">
				<div class="col-md-6">
<?php
	if($office == 'head'){
?>
						<label for="search">Plant: </label>
						<label class="radio-inline">
							<input type="radio" name="radioOffice" value="bravo" <?php if(isset($_POST['radioOffice']) && $_POST['radioOffice'] == 'bravo') { echo "checked"; }?> checked>Bravo <span class="badge"><?php echo getDeliveryCountOnDeliveryOffice($db,'bravo'); ?> Order(s)</span>
						</label>
						<label class="radio-inline">
							<input type="radio" name="radioOffice" value="delta" <?php if(isset($_POST['radioOffice']) && $_POST['radioOffice'] == 'delta') { echo "checked"; }?>>Delta <span class="badge"><?php echo getDeliveryCountOnDeliveryOffice($db,'delta'); ?> Order(s)</span>
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
					<input type="submit" name="search_pending" id="search_pending" value="Search" class="btn btn-primary">
				</div>
				<div class="col-md-3 col-md-offset-3">
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
	if(isset($_POST['search_pending'])){
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
			$end_date = "";
		}else{
			$end_date = $_POST['end_date'];
		}

		if($_POST['start_date'] == ''){
			$start_date = "";
		}else{
			$start_date = $_POST['start_date'];
		}

		if($_POST['start_date'] == '' && $_POST['end_date'] == ''){
			$string_date = "";
		}else if($_POST['start_date'] == '' && $_POST['end_date'] != ''){
			$string_date = "AND DATE_FORMAT(date_delivery,'%Y-%m-%d') <= '$end_date'";
		}else if($_POST['start_date'] != '' && $_POST['end_date'] == ''){
			$string_date = "AND DATE_FORMAT(date_delivery,'%Y-%m-%d') >= '$start_date'";		
		}else{
			$string_date = "AND DATE_FORMAT(date_delivery,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";
		}
?>
					<table class="table table-striped">
						<thead>
							<!-- <tr>
								<th colspan="11"><h3>On Delivery Orders</h3></th>
							</tr> -->
							<tr class="filterable">
								<th colspan="1">Plant: <?php echo ucfirst($search_plant); ?></th>
								<th colspan="10">
									<button class="btn btn-default btn-xs btn-filter" style="float: right;"><span class="glyphicon glyphicon-filter"></span> Filter</button>
								</th>
							</tr>
							<tr class="filters">
								<th class="col-md-1"><input type="text" class="form-control" placeholder="DR No." disabled></th>
		                        <th class="col-md-1"><input type="text" class="form-control" placeholder="P.O. No." disabled></th>
		                        <th class="col-md-1"><input type="text" class="form-control" placeholder="Item" disabled></th>
		                        <th class="col-md-1">Quantity</th>
		                        <th class="col-md-1"><input type="text" class="form-control" placeholder="Client Name" disabled></th>
		                       	<th class="col-md-1"><input type="text" class="form-control" placeholder="Address" disabled></th>
		                        <th class="col-md-1">Contact</th>
		                        <th class="col-md-1">Contact No.</th>
		                        <th class="col-md-1">Gate Pass</th>
		                        <th class="col-md-1"><input type="text" class="form-control" placeholder="Date Issued" disabled></th>
<?php
		if($office == 'head'){
?>
								<th class="col-md-1">Status</th>
<?php
		}else{
?>
								<th class="col-md-1">Option</th>
<?php			
		}
?>
							</tr>
						</thead>
						<tbody>
<?php
	if($office == 'head'){
		$string = " WHERE office = '$search_plant'";
	}else{
		$string = " WHERE office = '$office'";
	}

	$sql = "select * from delivery".$string." ".$string_date." AND remarks = 'On Delivery'";
	// echo $sql;
	$sql_result = mysqli_query($db, $sql); 
	$total = mysqli_num_rows($sql_result);

	$adjacents = 3;
	$targetpage = "delivery.php"; //your file name
	$limit = 10; //how many items to show per page
	$page = $_GET['page'];

	if($page){ 
		$start = ($page - 1) * $limit; //first item to display on this page
	}else{
		$start = 0;
	}

	/* Setup page vars for display. */
	if ($page == 0) $page = 1; //if no page var is given, default to 1.
	$prev = $page - 1; //previous page is current page - 1
	$next = $page + 1; //next page is current page + 1
	$lastpage = ceil($total/$limit); //lastpage.
	$lpm1 = $lastpage - 1; //last page minus 1

	/* CREATE THE PAGINATION */
	$counter = 0;
	$pagination = "";
	if($lastpage > 1){ 
		$pagination .= "<div class='pagination1'> <ul class='pagination'>";
		if ($page > $counter+1) {
			$pagination.= "<li class='page-item'><a class='page-link' href=\"$targetpage?page=$prev&radioOffice=$search_plant&start_date=$start_date&end_date=$end_date\"><<</a></li>"; 
		}

		if ($lastpage < 7 + ($adjacents * 2)) { 
			for ($counter = 1; $counter <= $lastpage; $counter++){
				if ($counter == $page)
				$pagination.= "<li class='page-item active'><a class='page-link' href='#'>$counter</a></li>";
				else
				$pagination.= "<li class='page-item'><a class='page-link' href=\"$targetpage?page=$counter&radioOffice=$search_plant&start_date=$start_date&end_date=$end_date\">$counter</a></li>"; 
			}
		}
		elseif($lastpage > 5 + ($adjacents * 2)){ //enough pages to hide some
			//close to beginning; only hide later pages
			if($page < 1 + ($adjacents * 2)) {
				for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++){
					if ($counter == $page)
					$pagination.= "<li class='page-item active'><a class='page-link' href='#'>$counter</a></li>";
					else
					$pagination.= "<li class='page-item'><a class='page-link' href=\"$targetpage?page=$counter&radioOffice=$search_plant&start_date=$start_date&end_date=$end_date\">$counter</a></li>"; 
				}
				$pagination.= "<li class='page-item'><a class='page-link' href='#'>...</a></li>";
				$pagination.= "<li class='page-item'><a class='page-link' href=\"$targetpage?page=$lpm1&radioOffice=$search_plant&start_date=$start_date&end_date=$end_date\">$lpm1</a></li>";
				$pagination.= "<li class='page-item'><a class='page-link' href=\"$targetpage?page=$lastpage&radioOffice=$search_plant&start_date=$start_date&end_date=$end_date\">$lastpage</a></li>"; 
			}
			//in middle; hide some front and some back
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)){
				$pagination.= "<li class='page-item'><a class='page-link' href=\"$targetpage?page=1&radioOffice=$search_plant&start_date=$start_date&end_date=$end_date\">1</a></li>";
				$pagination.= "<li class='page-item'><a class='page-link' href=\"$targetpage?page=2&radioOffice=$search_plant&start_date=$start_date&end_date=$end_date\">2</a></li>";
				$pagination.= "<li class='page-item'><a class='page-link' href='#'>...</a></li>";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++){
					if ($counter == $page)
					$pagination.= "<li class='page-item active'><a class='page-link' href='#'>$counter</a></li>";
					else
					$pagination.= "<li class='page-item'><a class='page-link' href=\"$targetpage?page=$counter&radioOffice=$search_plant&start_date=$start_date&end_date=$end_date\">$counter</a></li>"; 
				}
				$pagination.= "<li class='page-item'><a class='page-link' href='#'>...</a></li>";
				$pagination.= "<li class='page-item'><a class='page-link' href=\"$targetpage?page=$lpm1&radioOffice=$search_plant&start_date=$start_date&end_date=$end_date\">$lpm1</a></li>";
				$pagination.= "<li class='page-item'><a class='page-link' href=\"$targetpage?page=$lastpage&radioOffice=$search_plant&start_date=$start_date&end_date=$end_date\">$lastpage</a></li>"; 
			}
			//close to end; only hide early pages
			else{
				$pagination.= "<li class='page-item'><a class='page-link' href=\"$targetpage?page=1&radioOffice=$search_plant&start_date=$start_date&end_date=$end_date\">1</a></li>";
				$pagination.= "<li class='page-item'><a class='page-link' href=\"$targetpage?page=2&radioOffice=$search_plant&start_date=$start_date&end_date=$end_date\">2</a></li>";
				$pagination.= "<li class='page-item'><a class='page-link' href='#'>...</a></li>";
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++){
					if ($counter == $page)
					$pagination.= "<li class='page-item active'><a class='page-link' href='#'>$counter</a></li>";
					else
					$pagination.= "<li class='page-item'><a class='page-link' href=\"$targetpage?page=$counter&radioOffice=$search_plant&start_date=$start_date&end_date=$end_date\">$counter</a></li>"; 
				}
			}
		}

		//next button
		if ($page < $counter - 1) 
			$pagination.= "<li class='page-item'><a class='page-link' href=\"$targetpage?page=$next&radioOffice=$search_plant&start_date=$start_date&end_date=$end_date\">>></a></li>";
		else
			$pagination.= "";
		$pagination.= "</ul></div>\n"; 
	}
 
 	$query = "SELECT *, FORMAT(quantity,0) as quantity, DATE_FORMAT(date_delivery,'%m/%d/%y') as date_delivery
				FROM delivery ".$string." ".$string_date."
				AND remarks = 'On Delivery' 
				ORDER BY delivery_id DESC LIMIT $start, $limit";
	echo $query;
	$result = mysqli_query($db, $query);
	if(mysqli_num_rows($result) > 0){
		$hash = 1;
		while($row = mysqli_fetch_assoc($result)){
?>
							<tr>
								<td class='col-md-1'><strong><?php echo $row['delivery_receipt_no']; ?></strong></td>
								<td class='col-md-1' style="cursor: pointer;" title="Click here to view transactions under P.O. No. <?php echo $row['po_no_delivery'] ?>" onclick="window.location='delivery_po_order_no_details.php?fk_no=<?php echo $row['fk_po_id']; ?>&po_no_delivery=<?php echo $row['po_no_delivery']; ?>&office=<?php echo $row['office']; ?>'"><strong><?php echo $row['po_no_delivery']; ?></strong></td>
								<td class='col-md-1'><strong><?php echo $row['item_no']; ?></strong></td>
								<td class='col-md-1'><strong><?php echo $row['quantity'].' pcs'; ?></strong></td>
								<td class='col-md-1'><strong><?php echo $row['client_name']; ?></strong></td>
								<td class='col-md-1'><strong><?php echo $row['address']; ?></strong></td>
								<td class='col-md-1'><strong><?php echo $row['contact']; ?></strong></td>
								<td class='col-md-1'><strong><?php echo $row['contact_no']; ?></strong></td>
								<td class='col-md-1'><strong><?php echo $row['gate_pass']; ?></strong></td>
								<td class='col-md-1'><strong><?php echo $row['date_delivery']; ?></strong></td>
<?php
			if($office == 'head'){
?>
								<td class='col-md-1' style="background: #ffcc00;"><strong><?php echo $row['remarks']; ?></strong></td>
<?php
			}else{
?>
								<td class="col-md-1">
									<form action="delivery_update.php" method="post">
										<input type="hidden" name="post_delivery_id" value="<?php echo $row['delivery_id']; ?>">
   										<input type="submit" value="Update Info" class="btn btn-warning btn-xs" style="margin-bottom: 4px;">
									</form>
						 				<button type="submit" class='btn btn-xs btn-success' style="margin-bottom: 3px; width: 85px;" data-toggle='modal' data-target='#deliveryModal<?php echo $hash; ?>'>Delivered</button>
						 				
						 				<form action="delivery.php" method="post">
						 				<div class="modal fade" id="deliveryModal<?php echo $hash;?>" role="dialog">
											<div class="modal-dialog modal-md">

												<div class="modal-content">
													<div class="modal-header">
														<h4 class="modal-title"><!-- DR No. <?php echo $row['delivery_receipt_no'] ?> -->Delivery Details</h4>
													</div>
													<div class="modal-body" style="text-align: left;">
														<input type="hidden" id="hidden_id" name="hidden_id" value="<?php echo $row['delivery_id']; ?>">
														<div class="row">
															<div class="col-md-6">
																<label>DR No.</label>
															</div>
															<div class="col-md-6">
																<strong><?php echo $row['delivery_receipt_no']; ?></strong>
															</div>
														</div>
														<hr>
														<div class="row">
															<div class="col-md-6">
																<label>P.O. No.</label>
															</div>
															<div class="col-md-6">
																<strong><?php echo $row['po_no_delivery']; ?></strong>
															</div>
														</div>
														<hr>
														<div class="row">
															<div class="col-md-6">
																<label>Item</label>
															</div>
															<div class="col-md-6">
																<strong><?php echo $row['item_no']; ?></strong>
															</div>
														</div>
														<hr>
														<div class="row">
															<div class="col-md-6">
																<label>Quantity</label>
															</div>
															<div class="col-md-6">
																<strong><?php echo $row['quantity']; ?> pcs</strong>
															</div>
														</div>
														<hr>
														<div class="row">
															<div class="col-md-6">
																<label>Client Name</label>
															</div>
															<div class="col-md-6">
																<strong><?php echo $row['client_name']; ?></strong>
															</div>
														</div>
														<hr>
														<div class="row">
															<div class="col-md-6">
																<label>Address</label>
															</div>
															<div class="col-md-6">
																<strong><?php echo $row['address']; ?></strong>
															</div>
														</div>
														<hr>
														<div class="row">
															<div class="col-md-6">
																<label>Contact</label>
															</div>
															<div class="col-md-6">
																<strong><?php echo $row['contact']; ?></strong>
															</div>
														</div>
														<hr>
														<div class="row">
															<div class="col-md-6">
																<label>Contact No.</label>
															</div>
															<div class="col-md-6">
																<strong><?php echo $row['contact_no']; ?></strong>
															</div>
														</div>
														<hr>
														<div class="row">
															<div class="col-md-6">
																<label>Gate Pass</label>
															</div>
															<div class="col-md-6">
																<strong><?php echo $row['gate_pass']; ?></strong>
															</div>
														</div>
														<hr>
														<div class="row">
															<div class="col-md-6">
																<label>Date Issued</label>
															</div>
															<div class="col-md-6">
																<strong><?php echo $row['date_delivery']; ?></strong>
															</div>
														</div>
														<hr>
														<div class="row">
															<div class="col-md-6">
																<label>Date Delivered</label>
															</div>
															<div class="col-md-6">
																<!-- <input type="datetime-local" name="option_delivered" required> -->
																<input type="date" name="option_delivered" class="form-control" required>
															</div>
														</div>
													</div>
													<div class="modal-footer">
														<input type="submit" id="delivered" name="delivered" value="Confirm" class="btn btn-primary">
														<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
													</div>
												</div>
											</div>
										</div>
										</form>
										<form action="delivery.php" method="post">
						 				<button type="submit" id="returned" name="returned" value="<?php echo $row['delivery_id']?>" class='btn btn-xs btn-danger' onclick="return confirm('Confirm DR No. <?php echo $row['delivery_receipt_no']; ?> as backload delivery?')" style=" width: 85px;" >Backload</button>
						 				</form>
						 		</td>
<?php
			}
?>					
							</tr>
<?php
		$hash++;
		}
	}else{
?>
							<tr>
								<td colspan="11" style='min-height: 100%; background: white; text-align:center; 
		    vertical-align:middle;'><h4><p class='text-muted'>No data found</p></h4>
		    					</td>
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

		if($_POST['end_date'] == ''){
			$end_date = '';
		}else{
			$end_date = $_POST['end_date'];
		}

		if($_POST['start_date'] == ''){
			$start_date = '';
		}else{
			$start_date = $_POST['start_date'];
		}

		if($_POST['start_date'] == '' && $_POST['end_date'] == ''){
			$string_date = "";
		}else if($_POST['start_date'] == '' && $_POST['end_date'] != ''){
			$string_date = "AND DATE_FORMAT(date_delivery,'%Y-%m-%d') <= '$end_date'";
		}else if($_POST['start_date'] != '' && $_POST['end_date'] == ''){
			$string_date = "AND DATE_FORMAT(date_delivery,'%Y-%m-%d') >= '$start_date'";		
		}else{
			$string_date = "AND DATE_FORMAT(date_delivery,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";
		}
?>
					<table class="table table-striped">
						<thead>
							<!-- <tr>
								<th colspan="11"><h3>On Delivery Orders</h3></th>
							</tr> -->
							<tr class="filterable">
								<th colspan="1">Plant: <?php echo ucfirst($search_plant); ?></th>
								<th colspan="10">
									<button class="btn btn-default btn-xs btn-filter" style="float: right;"><span class="glyphicon glyphicon-filter"></span> Filter</button>
								</th>
							</tr>
							<tr class="filters">
								<th class="col-md-1"><input type="text" class="form-control" placeholder="DR No." disabled></th>
		                        <th class="col-md-1"><input type="text" class="form-control" placeholder="P.O. No." disabled></th>
		                        <th class="col-md-1"><input type="text" class="form-control" placeholder="Item" disabled></th>
		                        <th class="col-md-1">Quantity</th>
		                        <th class="col-md-1"><input type="text" class="form-control" placeholder="Client Name" disabled></th>
		                        <th class="col-md-1"><input type="text" class="form-control" placeholder="Address" disabled></th>
		                        <th class="col-md-1">Contact</th>
		                        <th class="col-md-1">Contact No.</th>
		                        <th class="col-md-1">Gate Pass</th>
		                        <th class="col-md-1"><input type="text" class="form-control" placeholder="Date Issued" disabled></th>
<?php
		if($office == 'head'){
?>
								<th class="col-md-1">Status</th>
<?php
		}else{
?>
								<th class="col-md-1">Option</th>
<?php			
		}
?>
							</tr>
						</thead>
						<tbody>
<?php
	if($office == 'head'){
		$string = " WHERE office = '$search_plant'";
	}else{
		$string = " WHERE office = '$office'";
	}

	$sql = "select * from delivery".$string." ".$string_date." AND remarks = 'On Delivery'";
	// echo $sql;
	$sql_result = mysqli_query($db, $sql); 
	$total = mysqli_num_rows($sql_result);

	$adjacents = 3;
	$targetpage = "delivery.php"; //your file name
	$limit = 10; //how many items to show per page
	$page = $_GET['page'];

	if($page){ 
		$start = ($page - 1) * $limit; //first item to display on this page
	}else{
		$start = 0;
	}

	/* Setup page vars for display. */
	if ($page == 0) $page = 1; //if no page var is given, default to 1.
	$prev = $page - 1; //previous page is current page - 1
	$next = $page + 1; //next page is current page + 1
	$lastpage = ceil($total/$limit); //lastpage.
	$lpm1 = $lastpage - 1; //last page minus 1

	/* CREATE THE PAGINATION */
	$counter = 0;
	$pagination = "";
	if($lastpage > 1){ 
		$pagination .= "<div class='pagination1'> <ul class='pagination'>";
		if ($page > $counter+1) {
			$pagination.= "<li class='page-item'><a class='page-link' href=\"$targetpage?page=$prev&radioOffice=$search_plant&start_date=$start_date&end_date=$end_date\"><<</a></li>"; 
		}

		if ($lastpage < 7 + ($adjacents * 2)) { 
			for ($counter = 1; $counter <= $lastpage; $counter++){
				if ($counter == $page)
				$pagination.= "<li class='page-item active'><a class='page-link' href='#'>$counter</a></li>";
				else
				$pagination.= "<li class='page-item'><a class='page-link' href=\"$targetpage?page=$counter&radioOffice=$search_plant&start_date=$start_date&end_date=$end_date\">$counter</a></li>"; 
			}
		}
		elseif($lastpage > 5 + ($adjacents * 2)){ //enough pages to hide some
			//close to beginning; only hide later pages
			if($page < 1 + ($adjacents * 2)) {
				for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++){
					if ($counter == $page)
					$pagination.= "<li class='page-item active'><a class='page-link' href='#'>$counter</a></li>";
					else
					$pagination.= "<li class='page-item'><a class='page-link' href=\"$targetpage?page=$counter&radioOffice=$search_plant&start_date=$start_date&end_date=$end_date\">$counter</a></li>"; 
				}
				$pagination.= "<li class='page-item'><a class='page-link' href='#'>...</a></li>";
				$pagination.= "<li class='page-item'><a class='page-link' href=\"$targetpage?page=$lpm1&radioOffice=$search_plant&start_date=$start_date&end_date=$end_date\">$lpm1</a></li>";
				$pagination.= "<li class='page-item'><a class='page-link' href=\"$targetpage?page=$lastpage&radioOffice=$search_plant&start_date=$start_date&end_date=$end_date\">$lastpage</a></li>"; 
			}
			//in middle; hide some front and some back
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)){
				$pagination.= "<li class='page-item'><a class='page-link' href=\"$targetpage?page=1&radioOffice=$search_plant&start_date=$start_date&end_date=$end_date\">1</a></li>";
				$pagination.= "<li class='page-item'><a class='page-link' href=\"$targetpage?page=2&radioOffice=$search_plant&start_date=$start_date&end_date=$end_date\">2</a></li>";
				$pagination.= "<li class='page-item'><a class='page-link' href='#'>...</a></li>";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++){
					if ($counter == $page)
					$pagination.= "<li class='page-item active'><a class='page-link' href='#'>$counter</a></li>";
					else
					$pagination.= "<li class='page-item'><a class='page-link' href=\"$targetpage?page=$counter&radioOffice=$search_plant&start_date=$start_date&end_date=$end_date\">$counter</a></li>"; 
				}
				$pagination.= "<li class='page-item'><a class='page-link' href='#'>...</a></li>";
				$pagination.= "<li class='page-item'><a class='page-link' href=\"$targetpage?page=$lpm1&radioOffice=$search_plant&start_date=$start_date&end_date=$end_date\">$lpm1</a></li>";
				$pagination.= "<li class='page-item'><a class='page-link' href=\"$targetpage?page=$lastpage&radioOffice=$search_plant&start_date=$start_date&end_date=$end_date\">$lastpage</a></li>"; 
			}
			//close to end; only hide early pages
			else{
				$pagination.= "<li class='page-item'><a class='page-link' href=\"$targetpage?page=1&radioOffice=$search_plant&start_date=$start_date&end_date=$end_date\">1</a></li>";
				$pagination.= "<li class='page-item'><a class='page-link' href=\"$targetpage?page=2&radioOffice=$search_plant&start_date=$start_date&end_date=$end_date\">2</a></li>";
				$pagination.= "<li class='page-item'><a class='page-link' href='#'>...</a></li>";
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++){
					if ($counter == $page)
					$pagination.= "<li class='page-item active'><a class='page-link' href='#'>$counter</a></li>";
					else
					$pagination.= "<li class='page-item'><a class='page-link' href=\"$targetpage?page=$counter&radioOffice=$search_plant&start_date=$start_date&end_date=$end_date\">$counter</a></li>"; 
				}
			}
		}

		//next button
		if ($page < $counter - 1) 
			$pagination.= "<li class='page-item'><a class='page-link' href=\"$targetpage?page=$next&radioOffice=$search_plant&start_date=$start_date&end_date=$end_date\">>></a></li>";
		else
			$pagination.= "";
		$pagination.= "</ul></div>\n"; 
	}


	$query = "SELECT *, FORMAT(quantity,0) as quantity, DATE_FORMAT(date_delivery,'%m/%d/%y') as date_delivery 
				FROM delivery ".$string." ".$string_date."
				AND remarks = 'On Delivery' 
				ORDER BY delivery_id DESC
				LIMIT $start, $limit";
    echo $query;
	$result = mysqli_query($db, $query);
	if(mysqli_num_rows($result) > 0){
		$hash = 1;
		while($row = mysqli_fetch_assoc($result)){
			// $date = date_create($row['date_delivery']);
?>
							<tr>
								<td class='col-md-1'><strong><?php echo $row['delivery_receipt_no']; ?></strong></td>
								<td class='col-md-1' style="cursor: pointer;" title="Click here to view transactions under P.O. No. <?php echo $row['po_no_delivery'] ?>" onclick="window.location='delivery_po_order_no_details.php?fk_no=<?php echo $row['fk_po_id']; ?>&po_no_delivery=<?php echo $row['po_no_delivery']; ?>&office=<?php echo $row['office']; ?>'"><strong><?php echo $row['po_no_delivery']; ?></strong></td>
								<td class='col-md-1'><strong><?php echo $row['item_no']; ?></strong></td>
								<td class='col-md-1'><strong><?php echo $row['quantity'].' pcs'; ?></strong></td>
								<td class='col-md-1'><strong><?php echo $row['client_name']; ?></strong></td>
								<td class='col-md-1'><strong><?php echo $row['address']; ?></strong></td>
								<td class='col-md-1'><strong><?php echo $row['contact']; ?></strong></td>
								<td class='col-md-1'><strong><?php echo $row['contact_no']; ?></strong></td>
								<td class='col-md-1'><strong><?php echo $row['gate_pass']; ?></strong></td>
								<td class='col-md-1'><strong><?php echo $row['date_delivery']; ?></strong></td>
<?php
			if($office == 'head'){
?>
								<td class='col-md-1' style="background: #ffcc00;"><strong><?php echo $row['remarks']; ?></strong></td>
<?php
			}else{
?>
								<td class="col-md-1">
									<form action="delivery_update.php" method="post">
										<input type="hidden" name="post_delivery_id" value="<?php echo $row['delivery_id']; ?>">
   										<input type="submit" value="Update Info" class="btn btn-warning btn-xs" style="margin-bottom: 4px;">
									</form>
										<button type="submit" class='btn btn-xs btn-success' style="margin-bottom: 3px; width: 85px;" data-toggle='modal' data-target='#deliveryModal<?php echo $hash; ?>'>Delivered</button>
						 				
						 				<form action="delivery.php" method="post">
						 				<div class="modal fade" id="deliveryModal<?php echo $hash;?>" role="dialog">
											<div class="modal-dialog modal-md">

												<div class="modal-content">
													<div class="modal-header">
														<h4 class="modal-title"><!-- DR No. <?php echo $row['delivery_receipt_no'] ?> -->Delivery Details</h4>
													</div>
													<div class="modal-body" style="text-align: left;">
														<input type="hidden" id="hidden_id" name="hidden_id" value="<?php echo $row['delivery_id']; ?>">
														<div class="row">
															<div class="col-md-6">
																<label>DR No.</label>
															</div>
															<div class="col-md-6">
																<strong><?php echo $row['delivery_receipt_no']; ?></strong>
															</div>
														</div>
														<hr>
														<div class="row">
															<div class="col-md-6">
																<label>P.O. No.</label>
															</div>
															<div class="col-md-6">
																<strong><?php echo $row['po_no_delivery']; ?></strong>
															</div>
														</div>
														<hr>
														<div class="row">
															<div class="col-md-6">
																<label>Item</label>
															</div>
															<div class="col-md-6">
																<strong><?php echo $row['item_no']; ?></strong>
															</div>
														</div>
														<hr>
														<div class="row">
															<div class="col-md-6">
																<label>Quantity</label>
															</div>
															<div class="col-md-6">
																<strong><?php echo $row['quantity']; ?></strong>
															</div>
														</div>
														<hr>
														<div class="row">
															<div class="col-md-6">
																<label>Client Name</label>
															</div>
															<div class="col-md-6">
																<strong><?php echo $row['client_name']; ?></strong>
															</div>
														</div>
														<hr>
														<div class="row">
															<div class="col-md-6">
																<label>Address</label>
															</div>
															<div class="col-md-6">
																<strong><?php echo $row['address']; ?></strong>
															</div>
														</div>
														<hr>
														<div class="row">
															<div class="col-md-6">
																<label>Contact</label>
															</div>
															<div class="col-md-6">
																<strong><?php echo $row['contact']; ?></strong>
															</div>
														</div>
														<hr>
														<div class="row">
															<div class="col-md-6">
																<label>Contact No.</label>
															</div>
															<div class="col-md-6">
																<strong><?php echo $row['contact_no']; ?></strong>
															</div>
														</div>
														<hr>
														<div class="row">
															<div class="col-md-6">
																<label>Gate Pass</label>
															</div>
															<div class="col-md-6">
																<strong><?php echo $row['gate_pass']; ?></strong>
															</div>
														</div>
														<hr>
														<div class="row">
															<div class="col-md-6">
																<label>Date Issued</label>
															</div>
															<div class="col-md-6">
																<strong><?php echo $row['date_delivery']; ?></strong>
															</div>
														</div>
														<hr>
														<div class="row">
															<div class="col-md-6">
																<label>Date Delivered</label>
															</div>
															<div class="col-md-6">
																<!-- <input type="datetime-local" name="option_delivered" required> -->
																<input type="date" name="option_delivered" class="form-control" required>
															</div>
														</div>
													</div>
													<div class="modal-footer">
														<input type="submit" id="delivered" name="delivered" value="Confirm" class="btn btn-primary">
														<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
													</div>
												</div>
											</div>
										</div>
										</form>
										<form action="delivery.php" method="post">
						 				<button type="submit" id="returned" name="returned" value="<?php echo $row['delivery_id']?>" class='btn btn-xs btn-danger' onclick="return confirm('Confirm DR No. <?php echo $row['delivery_receipt_no']; ?> as backload delivery?')" style=" width: 85px;" >Backload</button>
						 				</form>
						 		</td>
<?php
			}
?>			
							</tr>
<?php
		$hash++;
		}
	}else{
?>
							<tr>
								<td colspan="11" style='min-height: 100%; background: white; text-align:center; vertical-align: middle;'><h4><p class='text-muted'>No data found</p></h4>
		    					</td>
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
			<div class="row">
				<div class="col-md-12">
					<div class="table_page">
<?php				
						echo $pagination; 
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
<?php
	if(isset($_POST['delivered'])){
		$dr_id = $_POST['hidden_id'];
		// $datetime = date("Y-m-d H:i:s");
		$datetime = $_POST['option_delivered']." ".date("H:i:s");
		$datetime = str_replace("T", " ", $datetime);
		// echo $datetime;
		$select = "SELECT * FROM delivery WHERE delivery_id = '$dr_id'";

		$result = mysqli_query($db, $select);
		$row = mysqli_fetch_assoc($result);

		$row_office = $row['office'];
		$row_po_no_delivery = $row['po_no_delivery'];
		$row_fk_po_id = $row['fk_po_id'];
		$row_delivery_receipt_no = $row['delivery_receipt_no'];
		$row_quantity = $row['quantity'];
		$row_item_no = $row['item_no'];
		$row_client_name = $row['client_name'];

		// $update_stock = "UPDATE item_stock SET stock = stock - '$row_quantity', last_update = '$datetime' 
		// 					WHERE item_no = '$row_item_no' AND office = '$row_office'";

		$purchase_order_count_update = "UPDATE purchase_order 
										SET delivered = delivered + '$row_quantity', date_delivered = '$datetime'
										WHERE office = '$row_office' 
										AND purchase_order_no = '$row_po_no_delivery'
										AND purchase_id = '$row_fk_po_id'";

		// mysqli_query($db, $purchase_order_count_update);
		
		$history_query = "INSERT INTO history(table_report,transaction_type,item_no,detail,history_date,office)
							VALUES('Delivery','Delivered Order','$row_item_no','".ucfirst($row_office)." delivered DR No. $row_delivery_receipt_no with P.O. No. $row_po_no_delivery and ".number_format($row_quantity)." pcs of $row_item_no to $row_client_name','$datetime','$row_office')";

		$batch_prod_stock = "INSERT INTO batch_prod_stock(item_no, delivered, office, date_production)
								VALUES('$row_item_no','$row_quantity','$row_office','$datetime')";
		
		// $sql = "SELECT * FROM purchase_order 
		// 		WHERE purchase_order_no = '".$row['po_no_delivery']."' 
		// 		AND office = '".$row['office']."'
		// 		AND purchase_id = '".$row['fk_po_id']."'";

		// $sql_result = mysqli_query($db, $sql);
		// $sql_row = mysqli_fetch_assoc($sql_result);

		if(getDeliveryBalance($db, $row_po_no_delivery, $row_fk_po_id, $row_office) == 0){
			$update_remarks = "UPDATE purchase_order SET remarks = 'Success'
								WHERE purchase_order_no = '$row_po_no_delivery'
								AND office = '$row_office'
								AND purchase_id = '$row_fk_po_id'";

			
			// mysqli_query($db, $update_remarks);
		}

		$update_delivery = "UPDATE delivery SET remarks = 'Delivered', date_delivery = '$datetime'
							WHERE delivery_id = '$dr_id' 
							AND delivery_receipt_no = '$row_delivery_receipt_no'
							AND po_no_delivery = '$row_po_no_delivery'
							AND office = '$row_office'
							AND fk_po_id = '$row_fk_po_id'";

		// echo $update_delivery;
		// echo $update_stock;
		// echo $purchase_order_count_update;
		// echo $history_query;
		// echo $batch_prod_stock;
		// if($row['quantity'] < getStock($db, $row['item_no'], $office)){
		if(mysqli_query($db, $update_delivery) && mysqli_query($db, $batch_prod_stock) && mysqli_query($db, $history_query)){
			phpAlert("Item has been delivered successfully!!!");
			echo "<meta http-equiv='refresh' content='0'>";
		}else{
			phpAlert("Something went wrong!!");
		}
		// }else{
		// 	phpAlert("Quantity exceeded over stock!!");
		// }
	}else if(isset($_POST['returned'])){
		$dr_id = $_POST['returned'];
		$datetime = date("Y-m-d H:i:s");

		$select = "SELECT * FROM delivery WHERE delivery_id = '$dr_id'";

		$result = mysqli_query($db, $select);
		$row = mysqli_fetch_assoc($result);

		$row_office = $row['office'];
		$row_po_no_delivery = $row['po_no_delivery'];
		$row_fk_po_id = $row['fk_po_id'];
		$row_delivery_receipt_no = $row['delivery_receipt_no'];
		$row_quantity = $row['quantity'];
		$row_item_no = $row['item_no'];
		$row_client_name = $row['client_name'];

		$update_delivery = "UPDATE delivery SET remarks = 'Backload', date_delivery = '$datetime'
							WHERE delivery_id = '$dr_id' 
							AND delivery_receipt_no = '$row_delivery_receipt_no' 
							AND office = '$row_office'";

		// $purchase_order_update = "UPDATE purchase_order SET backload = backload + '".$row['quantity']."'
		// 							WHERE purchase_order_no = '".$row['po_no_delivery']."' 
		// 							AND purchase_id = '".$row['fk_po_id']."'
		// 							AND office = '".$row['office']."'";

		$purchase_order_update = "UPDATE purchase_order SET balance = balance + '$row_quantity'
									WHERE purchase_order_no = '$row_po_no_delivery' 
									AND purchase_id = '$row_fk_po_id'
									AND office = '$row_office'";
		// echo $purchase_order_update;
		// mysqli_query($db, $purchase_order_update);
		// $update_stock = "UPDATE item_stock SET stock = stock + '$row_quantity', last_update = '$datetime' 
		// 				WHERE item_no = '$row_item_no' AND office = '$row_office'";

		$history_query = "INSERT INTO history(table_report, transaction_type, detail, history_date, office) 
							VALUES('Delivery','Backloaded Order','".ucfirst($row_office)." has backload delivery of DR No. $row_delivery_receipt_no with ".number_format($row_quantity)." pcs of $row_item_no under P.O. No. $row_po_no_delivery','$datetime','$row_office')";

		// $sql = "SELECT * FROM purchase_order 
		// 		WHERE purchase_order_no = '".$row['po_no_delivery']."' 
		// 		AND purchase_id = '".$row['fk_po_id']."' 
		// 		AND office = '".$row['office']."'";

		// $sql_result = mysqli_query($db, $sql);
		// $sql_row = mysqli_fetch_assoc($sql_result);

		// if(getDeliveryBalance($db, $row_po_no_delivery, $row_fk_po_id, $row_office) == 0){
		// 	$update_remarks = "UPDATE purchase_order SET remarks = 'Success'
		// 						WHERE purchase_order_no = '$row_po_no_delivery' 
		// 						AND purchase_id = '$row_fk_po_id'
		// 						AND office = '$row_office'";

		// 	// mysqli_query($db, $update_remarks);
		// }
		// echo $update_stock;
		// echo $purchase_order_update;
		// echo $update_delivery;
		// echo $history_query;
		if(mysqli_query($db, $update_delivery) && mysqli_query($db, $history_query) && mysqli_query($db, $purchase_order_update)){
			phpAlert("Item has been backload!!!");
			echo "<meta http-equiv='refresh' content='0'>";
		}else{
			phpAlert("Something went wrong!!");
		}
	}
	// else if(isset($_POST['update'])){


	// 	$delivery_id = $_POST['hidden_id'];
	// 	$new_delivery_receipt_no = $_POST['update_delivery_receipt_no'];
	// 	$new_quantity = $_POST['update_quantity'];
	// 	$new_gate_pass = $_POST['update_gate_pass'];
	// 	$delivery_receipt_no = $_POST['hidden_dr_no'];
	// 	$fk_po_id = $_POST['hidden_fk_id'];
	// 	$prev_item_no = $_POST['hidden_item_no'];
	// 	$client = $_POST['hidden_client'];
	// 	$update_item_no = mysqli_real_escape_string($db, $_POST['update_item']);
	// 	$datetime = date("Y/m/d H:i:s");
	// 	//$office = ucfirst($office);

	// 	$sql = "UPDATE delivery SET item_no = '$update_item_no', delivery_receipt_no = '$new_delivery_receipt_no', quantity = '$new_quantity', gate_pass = '$new_gate_pass' WHERE delivery_id = '$delivery_id' AND delivery_receipt_no = '$delivery_receipt_no' AND fk_po_id = '$fk_po_id' AND office = '$office'";

	// 	// $sql = "UPDATE delivery SET item_no = '$update_item_no' WHERE delivery_id = '$delivery_id' AND delivery_receipt_no = '$delivery_receipt_no' AND fk_po_id = '$fk_po_id' AND office = '$office'";

	// 	$history = "INSERT INTO history(table_report, transaction_type, item_no, detail, history_date, office) VALUES('Delivery','Update Delivery Order' ,'$update_item_no','".ucfirst($office)." changed DR No. $delivery_receipt_no information','$datetime','$office')";

	// 	// update item only
	// 	// $history = "INSERT INTO history(table_report, transaction_type, item_no, detail, history_date, office) VALUES('Delivery','Changed Item' ,'$update_item_no','".ucfirst($office)." changed DR No. $delivery_receipt_no item $prev_item_no to $update_item_no to be delivered to $client','$datetime','$office')";

	// 	// // update DR No. only
	// 	// $history = "INSERT INTO history(table_report, transaction_type, item_no, detail, history_date, office) VALUES('Delivery','Changed DR No.' ,'$update_item_no','".ucfirst($office)." changed DR No. $delivery_receipt_no to $delivery_id','$datetime','$office')";

	// 	// // update quantity only
	// 	// $history = "INSERT INTO history(table_report, transaction_type, item_no, detail, history_date, office) VALUES('Delivery','Changed Item Quantity' ,'$update_item_no','".ucfirst($office)." changed item quantity of $prev_item_no to $quantity under DR No. $delivery_receipt_no','$datetime','$office')";

	// 	// update gatepass only
	// 	// $history = "INSERT INTO history(table_report, transaction_type, item_no, detail, history_date, office) VALUES('Delivery','Changed DR No. and Item' ,'$update_item_no','".ucfirst($office)." changed DR No. $delivery_receipt_no to $delivery_id and item $prev_item_no to $update_item_no','$datetime','$office')";
		
	// 	// // update quantity and DR no only
	// 	// $history = "INSERT INTO history(table_report, transaction_type, item_no, detail, history_date, office) VALUES('Delivery','Changed Item Quantity and DR No.' ,'$update_item_no','".ucfirst($office)." changed DR No. $delivery_receipt_no to $delivery_id and quantity of item $prev_item_no to $quantity','$datetime','$office')";

	// 	// // update quantity and item only
	// 	// $history = "INSERT INTO history(table_report, transaction_type, item_no, detail, history_date, office) VALUES('Delivery','Changed Item and Item Quantity' ,'$update_item_no','".ucfirst($office)." changed DR No. $delivery_receipt_no to $delivery_id and item $prev_item_no to $update_item_no','$datetime','$office')";

	// 	// // update DR No and item only
	// 	// $history = "INSERT INTO history(table_report, transaction_type, item_no, detail, history_date, office) VALUES('Delivery','Changed DR No. and Item' ,'$update_item_no','".ucfirst($office)." changed DR No. $delivery_receipt_no to $delivery_id and item $prev_item_no to $update_item_no','$datetime','$office')";

	// 	// // update DR No., quantity and item only
	// 	// $history = "INSERT INTO history(table_report, transaction_type, item_no, detail, history_date, office) VALUES('Delivery','Changed DR No., Item and Item Quantity' ,'$update_item_no','".ucfirst($office)." changed DR No. $delivery_receipt_no to $delivery_id, item $prev_item_no to $update_item_no and quantity to $quantity','$datetime','$office')";





	// 	echo $sql;
	// 	echo $history;

		// if(mysqli_query($db, $sql) && mysqli_query($db, $history)){
		// 	phpAlert("Item has been updated!!!");
		// 	echo "<meta http-equiv='refresh' content='0'>";
		// }else{
		// 	phpAlert("Something went wrong!!!");
		// }
	// }
?>