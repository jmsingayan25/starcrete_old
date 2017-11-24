<!DOCTYPE html>
<?php

	include("includes/config.php");
	include("includes/function.php");
	//include("delivery_function.php");
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

	$dr_query = "SELECT delivery_receipt_no
					FROM delivery
					WHERE office = '$office'";
	$array = array();
	$dr_result = mysqli_query($db, $dr_query);
	while ($row = mysqli_fetch_assoc($dr_result)) {
		$array[] = '"'.$row['delivery_receipt_no'].'"';
	}
	//$dr_array = implode(",", $array);
?>
<html>
<head>
<?php
	if($office == 'delta'){
		echo "<title>Pending Orders - Delivery Report - Quality Star Concrete Products, Inc.</title>";
	}else{
		echo "<title>Pending Orders - Delivery Report - Starcrete Manufacturing Corporation</title>";
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

	 // $('.class_quantity').keyup(function(){
 //    $(function() {
    
 //    // var $form = $( "#form" );
 //    // var $input = $form.find( "#quantity" );

 //    // $input.on( "keyup", function( event ) {

	// 	// var $form = $( ".class_form" );
	//  //    //var $input = $form.find( ".class_quantity" );

 //  //       $form.on( "keyup", ".class_quantity", function( event ) {
	//  //    	var balance = parseFloat($('input[type=hidden]#balance').val());
	//  //    	var $this = $( this );
 //  //           alert(balance);
 //  //           // Get the value.
 //  //           var input = $this.val();
 //  //           var input = input.replace(",","");
	// 	// 	//var thetext = parseFloat($(this).val().replace(",",""));
		
	// 	// 	if (+input > +balance) {
	// 	// 		$('#submit').attr('disabled', 'disabled');
	// 	// 	} else {
	// 	// 		$('#submit').removeAttr('disabled');
	// 	// 	}
	// 	// });
	// 	var $form = $( ".class_form" );



	// });

	$(function() {
        
        // var $form = $( "#form" );
        // var $input = $form.find( "#quantity" );

        // $input.on( "keyup", function( event ) {

    	var $form = $( ".class_form" );
        //var $input = $form.find( ".class_quantity" );

        $form.on( "keyup", ".class_quantity", function( event ) {
            
            
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
        });    

  //       $(".class_quantity").keyup(function(){
		//     $(this).closest('tr').find("input[type=hidden]#balance").each(function() {
		// 		var $this = $( this );
            
	 //            // Get the value.
	 //            var balance = $this.val();
		//         // alert(balance)
		//     });

		//     var thetext = parseFloat($(this).val().replace(",",""));

		// });  
    });	

	// function compareValue(value){
	// 	var balance = document.getElementById('balance').value;
	// 	var e = document.getElementById('submit');
	// 	var a = document.getElementById('warning');
	// 	var number = parseFloat(value.replace(",",""));
	
	// 	setTimeout(function () {
	// 		if(+number > +balance || isNaN(number) || balance == '0'){
	// 			e.disabled = true;
	// 			a.style.display = "block";
	// 		}else{
	// 			e.disabled = false;
	// 			a.style.display = "none";
	// 		}
	// 	}, 0);
	// }

	// function test(el) {
 //        var id = $(el).closest("tr").find('td:eq(2)').text();
 //        var val = $(el).closest("tr").find('#quantity').value();
 //        alert(val);
 //    }


	// function drCompare(value){
	// 	var e = document.getElementById('submit');
	// 	var js_array = <?php echo json_encode($array);?>;

	// 	// setTimeout(function () {
	// 	// 	if ($.inArray(+value,js_array) > -1) {
	// 	// 		// e.disabled = true;
	// 	// 		alert("True");
	// 	// 	}else{
	// 	// 		// e.disabled = false;
	// 	// 		alert("False");
	// 	// 	}
	// 	// }, 0);
	// 	if (value !== "") {
	// 		for (var i = 0; i < js_array.length; i++) {
	// 			if (js_array[i].indexOf(value) > -1) {
	// 				console.log("Object with index number " + i + " contains " + value);
	// 			}
	// 		}
	// 	}
	// }
	// alert(<?php echo json_encode($array);?>);
	// $(document).ready(function(){
		$("#dr_no").keyup(function() {

		    // var a = ["http://", "www.", ".com", ".co.uk"]; //Add the substrings
			var a = [<?php echo json_encode($array);?>];
			a.forEach(function(k){
				if($("#dr_no").attr("value").indexOf(k) > -1){
					alert('Found!'); //Do something
					return true;
				}else{
					return false;
				}
			});
		});
	// });

</script>
<style>
#wrapper {
	min-height:83%;
	position:relative;
}

#content {
	margin: 0 auto;
	min-height: 790px;
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
  height: 480px;
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
	<nav class="navbar navbar-default" id="secondary-nav" style="background-color: #0884e4; margin-bottom: 10px; vertical-align: middle;">
		<div class="container-fluid">
			<span style="font-size:30px; cursor:pointer; color: white;" onclick="openNav();">&#9776;</span>
			<span style="font-size:25px; color: white;"><a href="delivery.php" style="color: white;">Delivery Report</a> > Pending Orders</span>
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
	<!-- <input type="text" name="dr_array" id="dr_array" value="<?php echo $dr_array; ?>"> -->

	<div id="wrapper" onclick="closeNav();">
		<div id="content">
			<form action="delivery_transaction.php" method="post" class="form-inline">
			<div class="row" style="margin: 0px; margin-bottom: 5px;">
				<div class="col-md-6">
					
<?php
	if($office == 'head'){
?>
						<label for="search">Plant: </label>
						<label class="radio-inline">
							<input type="radio" name="radioOffice" value="bravo" <?php if(isset($_POST['radioOffice']) && $_POST['radioOffice'] == 'bravo') { echo "checked"; }?> checked>
<?php
	$sql = "SELECT purchase_order_no FROM purchase_order WHERE balance != 0 AND office = 'bravo'";
	$result = mysqli_query($db, $sql);
	$count = mysqli_num_rows($result);

	echo "Bravo <span class='badge'>".$count." Order(s)</span>";
?>
						</label>
						<label class="radio-inline">
							<input type="radio" name="radioOffice" value="delta" <?php if(isset($_POST['radioOffice']) && $_POST['radioOffice'] == 'delta') { echo "checked"; }?>>
<?php
	$sql = "SELECT purchase_order_no FROM purchase_order WHERE balance != 0 AND office = 'delta'";
	$result = mysqli_query($db, $sql);
	$count = mysqli_num_rows($result);

	echo "Delta <span class='badge'>".$count." Order(s)</span>";
?>
						</label>
<?php
	}
?>	
				</div>
			</div>
			<div class="row" style="margin: 0px; margin-bottom: 5px;">
				<div class="col-md-6">
					<input type="date" name="date_view" class="form-control" value="<?php if(isset($_POST['date_view'])) { echo htmlentities ($_POST['date_view']); }?>">
					<input type="submit" name="search_received" id="search_received" value="Search" class="btn btn-primary">
				</div>
				<div class="col-md-3 col-md-offset-3">
					<div class="pull-right">
					<!-- <a href='delivery.php' class='btn btn-default' style='float: left'><span class="glyphicon glyphicon-arrow-left"></span> Back to Delivery Report</a> -->
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
<!-- <?php
	if($count > 0){
?>
							<a href='delivery_transaction.php' class="btn btn-danger">Issue DR No. <span class='badge'><?php echo $count; ?></span></a>
<?php
	}else{
?>
							<a href='delivery_transaction.php' class="btn btn-success" readonly>Issue DR No. <span class='badge'><?php echo $count; ?></span></a>
<?php
	}
?> -->
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

		if($_POST['date_view'] == ''){
			$string_date = "";
		}else{
			$date = $_POST['date_view'];
			$string_date = "AND DATE_FORMAT(date_purchase,'%Y-%m-%d') = '$date'";
		}
?>
					<table class="table table-striped">
						<thead>
							<!-- <tr>
								<th colspan="11"><h3>Pending Orders</h3></th>
							</tr> -->
							<tr class="filterable">
								<th colspan="1">Plant: <?php echo ucfirst($search_plant); ?></th>
								<th colspan="9">
									<button class="btn btn-default btn-xs btn-filter" style="float: right;"><span class="glyphicon glyphicon-filter"></span> Filter</button>
								</th>
							</tr>
							<tr class="filters">
								<th class="col-md-1"><input type="text" class="form-control" placeholder="P.O. No." disabled></th>
				     			<th class="col-md-1"><input type="text" class="form-control" placeholder="Item" disabled></th>
				     			<th class="col-md-1">Order</th>
				     			<th class="col-md-1">Balance</th>
				     			<!-- <th class="col-md-1">Stock</th> -->
				     			<th class="col-md-2"><input type="text" class="form-control" placeholder="Client Name" disabled></th>
								<th class="col-md-1"><input type="text" class="form-control" placeholder="Address" disabled></th>
								<th class="col-md-1">Contact</th>
								<th class="col-md-1">Contact #</th>
								<th class="col-md-1">Date Ordered</th>
<?php
		if($office == 'head'){
?>
								<th class="col-md-1">Status</th>
<?php
		}else{
?>
								<th class="col-md-1"></th>
<?php			
		}
?>
							</tr>
						</thead>
						<tbody>
<?php
	if($office == 'head'){
		$string = " AND office = '$search_plant'";
	}else{
		$string = " AND office = '$office'";
	}
	$query = "SELECT p.purchase_id, p.purchase_order_no, p.client_name, p.item_no, CONCAT(FORMAT(p.quantity,0), ' ', l.unit) as quantity_order, p.quantity, delivered, backload, balance, address, contact_person, contact_no, DATE_FORMAT(date_purchase,'%m/%d/%y') as date_purchase , office, remarks
	 			FROM purchase_order p, batch_list l
	 			WHERE p.item_no = l.item_no 
	 			AND p.balance != 0 ".$string." ".$string_date."
				ORDER BY purchase_id DESC";

	$result = mysqli_query($db, $query);
	$count = mysqli_num_rows($result);
	if($count > 0){
		$hash = 1;
		while($row = mysqli_fetch_assoc($result)){
			// $date = date_create($row['date_purchase']);
?>
							<tr>
								<!-- <td class='col-md-1'><strong><?php echo $row['purchase_order_no']; ?></strong></td> -->
								<td class='col-md-1' style="cursor: pointer;" title="Click here to view transactions under P.O. No. <?php echo $row['purchase_order_no'] ?>" onclick="window.location='delivery_po_order_no_details.php?fk_no=<?php echo $row['purchase_id']; ?>&po_no_delivery=<?php echo $row['purchase_order_no']; ?>&office=<?php echo $row['office']; ?>'"><strong><?php echo $row['purchase_order_no']; ?></strong></td>
								<td class='col-md-1'><strong><?php echo $row['item_no']; ?></strong></td>
								<td class='col-md-1'><strong><?php echo $row['quantity_order']; ?></strong></td>
								<td class='col-md-1'><strong><?php echo number_format((float)$row['balance'])." pcs"; ?></strong></td>
								<!-- <td class='col-md-1'>
									<strong>
									<?php if(getStock($db, $row['item_no'], $row['office']) == NULL || getStock($db, $row['item_no'], $row['office']) == ''){
											echo "0 pcs";
										}else{
											echo number_format((float)getStock($db, $row['item_no'], $row['office']))." pcs";
										}  
									?>
									</strong>
								</td> -->
								<td class='col-md-2'><strong><?php echo $row['client_name']; ?></strong></td>
								<td class='col-md-1'><strong><?php echo $row['address']; ?></strong></td>
								<td class='col-md-1'><strong><?php echo $row['contact_person']; ?></strong></td>
								<td class='col-md-1'><strong><?php echo $row['contact_no']; ?></strong></td>
								<td class='col-md-1'><strong><?php echo $row['date_purchase']; ?></strong></td>
<?php if($office == 'head'){ ?>
								<td class='col-md-1'><strong>Pending</strong></td>
<?php }else{ ?>
								<!-- <td class='col-md-1'><button type='button' class='btn btn-success btn-sm' data-toggle='modal' data-target='#myModal<?php echo $hash;?>' style='float: center'>Issue DR No.</button> -->
								<td class='col-md-1'>
									<form action="delivery_transaction_issue.php" method="post">
										<input type="hidden" name="post_delivery_purchase_id" value="<?php echo $row['purchase_id']; ?>">
   										<input type="submit" value="Issue DR No." class="btn btn-success btn-xs" style="margin-bottom: 5px;">
									</form>
								</td>
<?php } ?>
							<!-- Modal -->
								<!-- <div class="modal fade" id="myModal<?php echo $hash;?>" role="dialog">
									<div class="modal-dialog modal-sm">

									
									<form action="delivery_transaction.php" method="post" class="class_form">
										<div class="modal-content">
											<div class="modal-header">
												<h4 class="modal-title">Purchase Order # <?php echo $row['purchase_order_no'] ?></h4>
											</div>
											<div class="modal-body" style="text-align: left;">
												<input type="hidden" id="po_id" name="po_id" class="form-control" value="<?php echo $row['purchase_id'] ?>">
												<input type="hidden" id="quantity_order" name="quantity_order" class="form-control" value="<?php echo $row['quantity'] ?>">
												<input type="hidden" id="item_no" name="item_no" class="form-control" value="<?php echo $row['item_no'] ?>">
												<input type="hidden" id="balance" name="balance" class="form-control" value="<?php echo $row['balance'] ?>">
												Item: <strong><?php echo $row['item_no'] ?></strong>	
												<div class="form-group">
													<label for="dr_no">DR No.</label>
													<input type="text" id="dr_no" name="dr_no" class="form-control" autocomplete="off" required>
												</div>
												<div class="form-group">
													<label for="dr_no">Gate Pass No.</label>
													<input type="text" id="gate_pass_no" name="gate_pass_no" class="form-control" autocomplete="off" required>
												</div>
												<div class="form-group">
													<label for="quantity">
														Balance: <?php echo number_format((float)$row['balance'])." pcs"; ?>
														Stock: <?php if(getStock($db, $row['item_no'], $row['office']) == NULL || getStock($db, $row['item_no'], $row['office']) == ''){
																echo "0 pcs";
															}else{
																echo number_format((float)getStock($db, $row['item_no'], $row['office']))." pcs";
															}  
														?>
													</label>
													<input type="hidden" id="stock" name="stock" 
													value="<?php if(getStock($db, $row['item_no'], $row['office']) == NULL || getStock($db, $row['item_no'], $row['office']) == ''){
																echo "0 pcs";
															}else{
																echo number_format((float)getStock($db, $row['item_no'], $row['office']))." pcs";
															}  
														?>">
													<input type="text" id="quantity" name="quantity" class="class_quantity form-control" autocomplete="off" required>
												</div>

											</div>
											<div class="modal-footer">
												<input type="submit" id="submit" name="submit" value="Submit" class="btn btn-primary">
												<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
											</div>
										</div>
									</form>
									</div>
								</div> -->
							</tr>
<?php
		$hash++;
		}
	}else{
?>
							<tr>
								<td style='width: 1500px; height: 345px; background: white; border: none; text-align:center; 
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
								<th colspan="11"><h3>Pending Orders</h3></th>
							</tr> -->
							<tr class="filterable">
								<th colspan="1">Plant: <?php echo ucfirst($search_plant); ?></th>
								<th colspan="9">
									<button class="btn btn-default btn-xs btn-filter" style="float: right;"><span class="glyphicon glyphicon-filter"></span> Filter</button>
								</th>
							</tr>
							<tr class="filters">
								<th class="col-md-1"><input type="text" class="form-control" placeholder="P.O. No." disabled></th>
				     			<th class="col-md-1"><input type="text" class="form-control" placeholder="Item" disabled></th>
				     			<th class="col-md-1">Order</th>
				     			<th class="col-md-1">Balance</th>
				     			<!-- <th class="col-md-1">Stock</th> -->
				     			<th class="col-md-2"><input type="text" class="form-control" placeholder="Client Name" disabled></th>
								<th class="col-md-1"><input type="text" class="form-control" placeholder="Address" disabled></th>
								<th class="col-md-1">Contact</th>
								<th class="col-md-1">Contact #</th>
								<th class="col-md-1">Date Ordered</th>
<?php
		if($office == 'head'){
?>
								<th class="col-md-1">Status</th>
<?php
		}else{
?>
								<th class="col-md-1"></th>
<?php			
		}
?>
							</tr>
						</thead>
						<tbody>
<?php
	if($office == 'head'){
		$string = " AND office = '$search_plant'";
	}else{
		$string = " AND office = '$office'";
	}
	$query = "SELECT p.purchase_id, p.purchase_order_no, p.client_name, p.item_no, CONCAT(FORMAT(p.quantity,0), ' ', l.unit) as quantity_order, p.quantity, delivered, backload, balance, address, contact_person, contact_no, DATE_FORMAT(date_purchase,'%m/%d/%y') as date_purchase , office, remarks
	 			FROM purchase_order p, batch_list l
	 			WHERE p.item_no = l.item_no".$string."
	 			AND p.balance != 0
				ORDER BY purchase_id DESC";

	$result = mysqli_query($db, $query);

	$count = mysqli_num_rows($result);
	if($count > 0){
		$hash = 1;
		while($row = mysqli_fetch_assoc($result)){
			// $date = date_create($row['date_purchase']);
?>
							<tr>
								<!-- <td class='col-md-1'><strong><?php echo $row['purchase_order_no']; ?></strong></td> -->
								<td class='col-md-1' style="cursor: pointer;" title="Click here to view transactions under P.O. No. <?php echo $row['purchase_order_no'] ?>" onclick="window.location='delivery_po_order_no_details.php?fk_no=<?php echo $row['purchase_id']; ?>&po_no_delivery=<?php echo $row['purchase_order_no']; ?>&office=<?php echo $row['office']; ?>'"><strong><?php echo $row['purchase_order_no']; ?></strong></td>
								<td class='col-md-1'><strong><?php echo $row['item_no']; ?></strong></td>
								<td class='col-md-1'><strong><?php echo $row['quantity_order']; ?></strong></td>
								<td class='col-md-1'><strong><?php echo number_format((float)$row['balance'])." pcs"; ?></strong></td>
								<!-- <td class='col-md-1'>
									<strong>
									<?php if(getStock($db, $row['item_no'], $row['office']) == NULL || getStock($db, $row['item_no'], $row['office']) == ''){
											echo "0 pcs";
										}else{
											echo number_format((float)getStock($db, $row['item_no'], $row['office']))." pcs";
										}  
									?>
									</strong>
								</td> -->
								<td class='col-md-2'><strong><?php echo $row['client_name']; ?></strong></td>
								<td class='col-md-1'><strong><?php echo $row['address']; ?></strong></td>
								<td class='col-md-1'><strong><?php echo $row['contact_person']; ?></strong></td>
								<td class='col-md-1'><strong><?php echo $row['contact_no']; ?></strong></td>
								<td class='col-md-1'><strong><?php echo $row['date_purchase']; ?></strong></td>
<?php if($office == 'head'){ ?>
								<td class='col-md-1'><strong>Pending</strong></td>
<?php }else{ ?>
								<td class='col-md-1'>
									<!-- <button type='button' class='btn btn-success btn-sm' data-toggle='modal' data-target='#myModal<?php echo $hash;?>' style='float: center'>Issue DR No.</button> -->
									<form action="delivery_transaction_issue.php" method="post">
										<input type="hidden" name="post_delivery_purchase_id" value="<?php echo $row['purchase_id']; ?>">
   										<input type="submit" value="Issue DR No." class="btn btn-success btn-xs" style="margin-bottom: 5px;">
									</form>
<?php } ?>
							<!-- Modal -->
								<!-- <div class="modal fade" id="myModal<?php echo $hash;?>" role="dialog">
									<div class="modal-dialog modal-sm">

									
									<form action="delivery_transaction.php" method="post" class="class_form">
										<div class="modal-content">
											<div class="modal-header">
												<h4 class="modal-title">Purchase Order # <?php echo $row['purchase_order_no'] ?></h4>
											</div>
											<div class="modal-body" style="text-align: left;">
												<input type="hidden" id="po_id" name="po_id" class="form-control" value="<?php echo $row['purchase_id'] ?>">
												<input type="hidden" id="quantity_order" name="quantity_order" class="form-control" value="<?php echo $row['quantity'] ?>">
												<input type="hidden" id="item_no" name="item_no" class="form-control" value="<?php echo $row['item_no'] ?>">
												<input type="hidden" id="balance" name="balance" class="form-control" value="<?php echo $row['balance'] ?>">
												Item: <strong><?php echo $row['item_no'] ?></strong>	
												<div class="form-group">
													<label for="dr_no">DR No.</label>
													<input type="text" id="dr_no" name="dr_no" class="form-control" autocomplete="off"  required>
												</div>
												<div class="form-group">
													<label for="dr_no">Gate Pass No.</label>
													<input type="text" id="gate_pass_no" name="gate_pass_no" class="form-control" autocomplete="off" required>
												</div>
												<div class="form-group">
													<label for="quantity">
														Balance: <?php echo number_format((float)$row['balance'])." pcs"; ?>
														Stock: <?php if(getStock($db, $row['item_no'], $row['office']) == NULL || getStock($db, $row['item_no'], $row['office']) == ''){
																echo "0 pcs";
															}else{
																echo number_format((float)getStock($db, $row['item_no'], $row['office']))." pcs";
															}  
														?>
													</label>
													<input type="hidden" id="stock" name="stock" 
													value="<?php if(getStock($db, $row['item_no'], $row['office']) == NULL || getStock($db, $row['item_no'], $row['office']) == ''){
																echo "0 pcs";
															}else{
																echo number_format((float)getStock($db, $row['item_no'], $row['office']))." pcs";
															}  
														?>">
													<input type="text" id="quantity" name="quantity" class="class_quantity form-control" autocomplete="off" required>
												</div>					
											</div>
											<div class="modal-footer">
												<input type="submit" id="submit" name="submit" value="Submit" class="btn btn-primary">
												<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
											</div>
										</div>
									</form>
									</div>
								</div> -->
								</td>
							</tr>
<?php
		$hash++;
		}
	}else{
?>
							<tr>
								<td style='width: 1500px; height: 345px; background: white; border: none; text-align:center; 
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
<?php

	if(isset($_POST['submit'])){

		$delivery = mysqli_real_escape_string($db, $_POST['dr_no']);
		$item = mysqli_real_escape_string($db, $_POST['item_no']);
		$gate_pass_no = mysqli_real_escape_string($db, $_POST['gate_pass_no']);
		$balance = str_replace(",", "", $_POST['balance']);
		$po_id = $_POST['po_id'];
		
		$sql = "SELECT * FROM purchase_order WHERE purchase_id = '$po_id'";
		$result = mysqli_query($db, $sql);
		$sql_row = mysqli_fetch_assoc($result);

		// $client = getClient($db, $po_no, $item, $office);
		$po_no = $sql_row['purchase_order_no'];
		$client = $sql_row['client_name'];
		$contact = $sql_row['contact_person'];
		$contact_no = $sql_row['contact_no'];
		$plant = ucfirst($sql_row['office']);
		$address = getAddress($db, $client);
		$datetime = date("Y/m/d H:i:s");
		$quantity = str_replace(",", "", mysqli_real_escape_string($db, $_POST['quantity']));
		

		$delivery_insert = "INSERT INTO delivery(delivery_receipt_no, item_no, quantity, client_name, address, contact, contact_no, gate_pass, po_no_delivery, date_delivery, office, remarks, fk_po_id) 
							VALUES('$delivery','$item','$quantity','$client','$address','$contact','$contact_no','$gate_pass_no','$po_no','$datetime','$office','On Delivery','$po_id')";

		$history_query = "INSERT INTO history(table_report, transaction_type, detail, history_date, office) 
		 					VALUES('Delivery','Issued DR No.','$plant issued DR No. $delivery with P.O. No. $po_no and ".$_POST['quantity']." pcs of $item and ready to deliver to $client','$datetime','$office')";

		$purchase_order_update = "UPDATE purchase_order SET balance = balance - '$quantity'
									WHERE purchase_order_no = '$po_no'
									AND item_no = '$item' 
									AND office = '$office'
									AND purchase_id = '$po_id'";



		// echo $delivery_insert;
		// echo $history_query;
		// echo $purchase_order_update;

		if($quantity <= $balance){
			if(!in_array($delivery, $array)){
			// echo "EXISTS";
				if(mysqli_query($db, $delivery_insert) && mysqli_query($db, $history_query) && mysqli_query($db, $purchase_order_update)){
					phpAlert("Delivery No. $delivery issued successfully!! Transaction can be viewed on Delivery Report Page");
					echo "<meta http-equiv='refresh' content='0'>";
				}else{
					phpAlert("Something went wrong!!");
				}
			}else{
				// echo "NOT EXISTS";
				phpAlert("DR No. already exists!!");
				echo "<meta http-equiv='refresh' content='0'>";
			}
		}else{
			phpAlert("Quantity exceeds over balance!!");
			echo "<meta http-equiv='refresh' content='0'>";
		}	
	}
?>