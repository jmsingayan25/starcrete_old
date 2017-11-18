<!DOCTYPE html>
<?php
	
	include("includes/config.php");
	include("includes/function.php");
	// include("delivery_function.php");

	session_start();
	if(!isset($_SESSION['login_user'])) {
		header("location: login.php");
	}

	if(isset($_GET['fk_no']) && isset($_GET['office']) && isset($_GET['po_no_delivery'])){
		$_SESSION['fk_no'] = $_GET['fk_no'];
		$_SESSION['office'] = $_GET['office'];
		$_SESSION['po_no_delivery'] = $_GET['po_no_delivery'];
	}
	$session_purchase_po = $_SESSION['po_no_delivery'];
	$session_po = $_SESSION['fk_no'];
	$session_office = $_SESSION['office'];

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
		echo "<title>P.O. No. Details - Delivery Report - Quality Star Concrete Products, Inc.</title>";
	}else{
		echo "<title>P.O. No. Details - Delivery Report - Starcrete Manufacturing Corporation</title>";
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
	<script src="js_ext/sidebar.js" type="text/javascript"></script>

<script>

	function goBack() {
	    window.history.back();
	}

	$(document).ready(function() {
		$('#myModal').on('hidden.bs.modal', function () {
		    $('.modal-body').find("input,textarea,select").val('').end();
		});
	});

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
			if(+order > +stock || +stock <= 0){
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
	min-height: 550px;
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
  height: 310px;
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
		<!-- <a href="diesel.php">Diesel Report</a> -->
<?php
	if($position != 'warehouseman')
		echo "<a href='purchase_order.php'>Issued Purchase Order</a>"
?>
<!-- 		<a href='purchase_order.php'>Issued Purchase Order</a> -->
		<a href="delivery.php">Issued Delivery Receipt</a>
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
			<span style="font-size:25px; color: white;"><a href="delivery.php" style="color: white;">Delivery Report</a> > Purchase Order No. <?php echo $session_purchase_po; ?> Details</span>
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
			<div class="row" style="margin: 0px; margin-bottom: 5px;">
				<div class="col-md-12">
					<!-- <a href='delivery.php' class='btn btn-default'><span class="glyphicon glyphicon-arrow-left"></span> Back to Delivery Report</a> -->
					<button class="btn btn-default" onclick="goBack()"><span class="glyphicon glyphicon-arrow-left"></span> Go Back to Previous Page</button>
				</div>
			</div>
			<div id="row">
				<div class="col-md-12">
					<div class="filterable">
					<table class="table table-striped">
						<thead>
							<tr class="filterable">
								<th colspan="2" style="text-align: left;">Plant: <?php echo ucfirst($session_office); ?> <br> Balance: <?php echo number_format(getDeliveryBalance($db, $session_purchase_po, $session_po, $session_office)); ?> pcs</th>
								<th colspan="2">Delivered: <?php echo number_format(getDeliveryDelivered($db, $session_purchase_po, $session_po, $session_office)); ?> pcs</th>
								<th colspan="2">On Delivery: <?php echo number_format(getDeliveryOnDelivery($db, $session_purchase_po, $session_po, $session_office)); ?> pcs</th>
								<th colspan="4">
									<button class="btn btn-default btn-xs btn-filter" style="float: right;"><span class="glyphicon glyphicon-filter"></span> Filter</button>
								</th>
							</tr>
							<tr class="filters">
								<th class="col-md-1">P.O. No.</th>
								<th class="col-md-1"><input type="text" class="form-control" placeholder="DR No." disabled></th>
								<th class="col-md-1"><input type="text" class="form-control" placeholder="Item" disabled></th>
								<th class="col-md-1">Quantity</th>
								<th class="col-md-2"><input type="text" class="form-control" placeholder="Client Name" disabled></th>
								<th class="col-md-2"><input type="text" class="form-control" placeholder="Address" disabled></th>
								<th class="col-md-1">Contact</th>
								<th class="col-md-1">Contact No.</th>
								<th class="col-md-1">Date Transaction</th>
								<th class="col-md-1">Status</th>
							</tr>
						</thead>
						<tbody>
<?php
	$sql = "SELECT *, DATE_FORMAT(date_delivery,'%m/%d/%y') as date_delivery1
			FROM delivery 
			WHERE fk_po_id = '$session_po'
			AND office = '$session_office'
			ORDER BY date_delivery DESC";

	$result = mysqli_query($db, $sql);
	while ($row = mysqli_fetch_assoc($result)) {
		// $date = date_create($row['date_delivery']);
?>
							<tr>
								<td class="col-md-1"><strong><?php echo $row['po_no_delivery']; ?></strong></td>
								<td class="col-md-1"><strong><?php echo $row['delivery_receipt_no']; ?></strong></td>
								<td class="col-md-1"><strong><?php echo $row['item_no']; ?></strong></td>
								<td class="col-md-1"><strong><?php echo number_format($row['quantity']); ?> pcs</strong></td>
								<td class="col-md-2"><strong><?php echo $row['client_name']; ?></strong></td>
								<td class="col-md-2"><strong><?php echo $row['address']; ?></strong></td>
								<td class="col-md-1"><strong><?php echo $row['contact']; ?></strong></td>
								<td class="col-md-1"><strong><?php echo $row['contact_no']; ?></strong></td>
								<td class='col-md-1'><strong><?php echo $row['date_delivery1']; ?></strong></td>
<?php
	if($row['remarks'] == 'Delivered'){
?>
								<td class="col-md-1" style="background-color: green; color: white;"><strong><?php echo $row['remarks']; ?></strong></td>
<?php
	}else if($row['remarks'] == 'Backload'){
?>
								<td class="col-md-1" style="background-color: #e60000; color: white;"><strong><?php echo $row['remarks']; ?></strong></td>
<?php
	}else if($row['remarks'] == 'On Delivery'){
?>
								<td class="col-md-1" style="background-color: #ffcc00;"><strong><?php echo $row['remarks']; ?></strong></td>
<?php
	}
?>
							</tr>
<?php
	}
?>
							
						</tbody>
					</table>
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