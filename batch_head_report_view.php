<!DOCTYPE html>
<?php

	include("includes/config.php");
	include("includes/function.php");

	session_start();
	if(!isset($_SESSION['login_user'])) {
		header("location: login.php");
	}

	if(isset($_GET['option']) && isset($_GET['office'])){
		$_SESSION['option'] = $_GET['option'];
		$_SESSION['office'] = $_GET['office'];
	}

	$option = $_SESSION['option'];
	$office = $_SESSION['office'];

	$user_query = $db->prepare("SELECT * FROM users WHERE username = ?");
	$user_query->bind_param('s', $_SESSION['login_user']);
	$user_query->execute();
	$result = $user_query->get_result();
	$user = $result->fetch_assoc();

	$user_office = $user['office'];
	$position = $user['position'];

	if($option == 'total_cement_day'){
		$header = ucfirst($office)." Cement per Day Report";
	}else if($option == 'total_batch'){
		$header = ucfirst($office)." Total Batch and Cement Report";
	}else if($option == 'output_batch'){
		$header = ucfirst($office)." Output Batch Report";
	}else if($option == 'monthly_output'){
		$header = ucfirst($office)." Monthly Output Report";
	}else if($option == 'monthly_delivery'){
		$header = ucfirst($office)." Monthly Delivery Report";
	}
?>
<html>
<head>
<?php
	if($office == 'delta'){
		echo "<title>Production Report - Quality Star Concrete Products, Inc.</title>";
	}else{
		echo "<title>Production Report - Starcrete Manufacturing Corporation</title>";
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
	<script type="text/javascript" src="js_ext/batch.js"></script>

<script>

</script>
<style>

html, body {
	margin:0;
	padding:0;
	height:100%;
}
.table tbody{
  overflow-y: scroll;
  height: 373px;
  position: absolute;
  border:1px solid #cecece;
}
.table td {
   border-bottom: 1px solid #bababa;
   border-right: 1px solid #d1d1d1;
   border-left: 1px solid #d1d1d1;
}
#wrapper {
	min-height:83%;
	position:relative;
}
#content {
	margin: 0 auto;
	min-height: 600px;
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

th{
    background-color: #0884e4;
    color: white;
}

th, td{
	 text-align: center;
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
		<!-- <a href="stock.php">Stock Report</a> -->
<?php
	if($user_office == 'head'){
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
		<a href="batch_plant_report.php">Batch Report</a>
<?php
	}
?>
		<!-- <a href="diesel.php">Diesel Report</a> -->
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
			<span style="font-size:25px; color: white;"><?php echo $header; ?></span>
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
			<!-- <div class="row" style="margin: 0px;">
				<div class="col-md-2">
					<button type="button" onclick="location.href='batch_head_report.php';" class="btn btn-default"><span class="glyphicon glyphicon-arrow-left"></span> Back to List of <?php echo ucfirst($office); ?> Report</button>
				</div>
			</div> -->
		</div>
		<div id="content">
			<div class="row" style="margin-bottom: 5px;">
				<div class="col-md-1 col-md-offset-9">
					<div class="pull-right">
						<div class="dropdown">
							<button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">View Reports
							<span class="caret"></span></button>
							<ul class="dropdown-menu">
								<li class="dropdown-header">View Reports</li>
								<li><a href="batch_head_report.php">Daily Production</a></li>
								<li><a href="batch_head_report_view.php?option=total_cement_day&office=<?php echo $office; ?>">Total cement used per day</a></li>
								<li><a href="batch_head_report_view.php?option=total_batch&office=<?php echo $office; ?>">Total batch and cement used</a></li>
								<li><a href="batch_head_report_view.php?option=output_batch&office=<?php echo $office; ?>">Output per batch</a></li>
								<li><a href="batch_head_report_view.php?option=monthly_output&office=<?php echo $office; ?>">Monthly production output</a></li>
								<li><a href="batch_head_report_view.php?option=monthly_delivery&office=<?php echo $office; ?>">Monthly delivered CHB</a></li>
							</ul>
						</div>
					</div>
				</div>
				
			</div>
<?php
		if($option == 'total_cement_day'){
?>
			<div class="row">
				<div class="col-md-8 col-md-offset-2">
					<table class='table table-striped' style='width: 100%; '>
						<thead>
							<tr>
								<th colspan="2"><h4>Total cement per day</h4></th>
							</tr>
							<tr>
								<th class="col-md-1">Cement(kg)</th>
								<th class="col-md-1">Date</th>
							</tr>
						</thead>
						<tbody> 
<?php
			$rows = array();
			$final = array();

			$limit = 10;  
			if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };  
			$start_from = ($page-1) * $limit; 
			$query = "SELECT cement * SUM(batch_count) as total, DATE_FORMAT(batch_date,'%m/%d/%y') as prod_date
						FROM batch WHERE office = '$office'
						GROUP BY prod_date, machine_no, cement
		                ORDER BY prod_date DESC";
		                
			$result = mysqli_query($db, $query);
			while($row = mysqli_fetch_assoc($result)){
		    	$rows[] = $row;
			}
			foreach($rows as $value) {
			    $id = $value['prod_date'];
			    $filter = array_filter($rows, function($ar) {
			        GLOBAL $id;
			        $valueArr = ($ar['prod_date'] == $id);
			        return $valueArr;
			    });
			    $sum = array_sum(array_column($filter, 'total'));
			    $final[$id] = array('prod_date' => $id, 'total' => $sum);
			}
			foreach ($final as $total) {
?>
							<tr>
								<td class="col-md-1"><strong><?php echo $total['total']; ?></strong></td>
								<td class="col-md-1"><strong><?php echo $total['prod_date']; ?></strong></td>
							</tr>
<?php
			}
?>
						</tbody>
					</table>
<!-- <?php
	$sql = "SELECT count(*) FROM (SELECT cement * SUM(batch_count) as total, DATE_FORMAT(batch_date,'%m/%d/%y') as prod_date
			FROM batch WHERE office = '$office'
			GROUP BY prod_date, machine_no, cement
            ORDER BY prod_date DESC) as counting";

    $rs_result = mysqli_query($db, $sql);  
    $row = mysqli_fetch_row($rs_result);  
    $total_records = $row[0];  
    $total_pages = ceil($total_records / $limit);  
    $pagLink = "<nav style='margin-top:355px;'><ul class='pagination'>";  
    for ($i=1; $i<=$total_pages; $i++) {  
                 $pagLink .= "<li><a href='batch_head_report_view.php?page=".$i."'>".$i."</a></li>";  
    };  
    echo $pagLink . "</ul></nav>";  
?> -->
				</div>
			</div>
<?php
		}else if($option == 'total_batch'){
?>
			<div class="row">
				<div class="col-md-8 col-md-offset-2">
					<table class='table table-striped' style='width: 100%;' id='myTable'>
						<thead>
							<tr>
								<th colspan="5"><h4>Total batch and cement used</h4></th>
							</tr>
							<tr>
								<th colspan='4'></th>
								<th colspan='1'>
									<input type="" id='select_date' name='select_date' class='form-control' onkeyup='myFunction();' placeholder="Search for date">
<!-- <?php
	$limit = 10;  
	if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };  
	$start_from = ($page-1) * $limit; 
	$query = "SELECT DISTINCT DATE_FORMAT(batch_date,'%m/%d/%y') as prod_date
				FROM batch WHERE office = '$office'
				GROUP BY machine_no, item_no, prod_date, cement
			  	ORDER BY prod_date DESC, machine_no LIMIT $start_from, $limit";

	$result = mysqli_query($db, $query);
	foreach ($result as $row) {
								echo "<option value='".$row['prod_date']."'>".$row['prod_date']."</option>";
	}
?> -->
								</th>
							</tr>
							<tr>
				     			<th class="col-md-1">Machine no.</th>
				     			<th class="col-md-1">Item</th>
								<th class="col-md-1">Batch</th>
								<th class="col-md-1">Total Cement(kg)</th>
								<th class="col-md-1">Date</th>
							</tr>
						</thead>
						<tbody>
<?php

	$limit = 10;  
	if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };  
	$start_from = ($page-1) * $limit;  
	$query = "SELECT machine_no, item_no, cement * SUM(batch_count) as total, SUM(batch_count) as count, DATE_FORMAT(batch_date,'%m/%d/%y') as prod_date
				FROM batch WHERE office = '$office'
				GROUP BY machine_no, item_no, prod_date, cement
			  	ORDER BY prod_date DESC, machine_no";
	$result = mysqli_query($db, $query);
	while($row = mysqli_fetch_assoc($result)){	
?>		
							<tr>
								<td class="col-md-1"><strong><?php echo $row['machine_no']; ?></strong></td>
								<td class="col-md-1"><strong><?php echo $row['item_no']; ?></strong></td>
								<td class="col-md-1"><strong><?php echo $row['count']; ?></strong></td>
								<td class="col-md-1"><strong><?php echo $row['total']; ?></strong></td>
								<td class="col-md-1"><strong><?php echo $row['prod_date']; ?></strong></td>
							</tr>
<?php
	}
?>
						</tbody>
					</table>
<!-- <?php
	$sql = "SELECT count(*) FROM (SELECT machine_no, item_no, cement * SUM(batch_count) as total, SUM(batch_count) as count, DATE_FORMAT(batch_date,'%m/%d/%y') as prod_date
				FROM batch WHERE office = '$office'
				GROUP BY machine_no, item_no, prod_date, cement
			  	ORDER BY prod_date DESC, machine_no) as counting";

    $rs_result = mysqli_query($db, $sql);  
    $row = mysqli_fetch_row($rs_result);  
    $total_records = $row[0];  
    $total_pages = ceil($total_records / $limit);  
    $pagLink = "<nav style='margin-top:355px;'><ul class='pagination'>";  
    for ($i=1; $i<=$total_pages; $i++) {  
                 $pagLink .= "<li><a href='batch_head_report_view.php?page=".$i."'>".$i."</a></li>";  
    };  
    echo $pagLink . "</ul></nav>";  
?> -->
				</div>
			</div>
<?php
	}else if($option == 'output_batch'){
?>
		<div class="col-md-8 col-md-offset-2">
			<table class='table table-striped' style='width: 100%;' id='myTable2'>
				<thead>
					<tr>
						<th colspan="6"><h4>Output per batch</h4></th>
					</tr>
					<tr>
						<th colspan='5'></th>
						<th colspan='1'>
							<input type="text" id='select_date2' name='select_date2' class='form-control' onkeyup='myFunction2();' placeholder="Search for date">
<!-- 								<option value=''>Select Date</option>
<?php
	$query = "SELECT DISTINCT DATE_FORMAT(date_production,'%m/%d/%y') as prod_date
					FROM batch_prod WHERE office = '$office'
					GROUP BY prod_date, item_no, reject, batch_prod_id
					ORDER BY prod_date DESC, item_no ASC";

	$result = mysqli_query($db, $query);
	foreach ($result as $row) {
								echo "<option value='".$row['prod_date']."'>".$row['prod_date']."</option>";
	}
?>
							</select> -->
						</th>
					</tr>
					<tr>
		     			<th class='col-md-1'>Item</th>
						<th class='col-md-1'>Production</th>
						<th class='col-md-1'>Batch</th>
						<th class='col-md-1'>Output</th>
						<th class='col-md-1'>Reject</th>
						<th class='col-md-1'>Date</th>
					</tr>
				</thead>
				<tbody>
<?php

	$limit = 10;  
	if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };  
	$start_from = ($page-1) * $limit;  
	$query = "SELECT batch_prod_id, item_no, CONCAT(SUM(actual_prod), ' pcs') as actual_prod, CONCAT(SUM(batch_prod), ' batches') as batch_prod, CONCAT(FORMAT(SUM(output),2), ' pcs') as output, DATE_FORMAT(date_production,'%m/%d/%y') as prod_date, CONCAT(reject, ' pcs') as reject
		FROM batch_prod WHERE office = '$office'
		GROUP BY prod_date, item_no, reject, batch_prod_id
		ORDER BY prod_date DESC, item_no ASC";

	$result = mysqli_query($db, $query);
	while($row = mysqli_fetch_assoc($result)){	
?>	
					<tr>
						<td class='col-md-1'><strong><?php echo $row['item_no']; ?></strong></td>
						<td class='col-md-1'><strong><?php echo $row['actual_prod']; ?></strong></td>
						<td class='col-md-1'><strong><?php echo $row['batch_prod']; ?></strong></td>
						<td class='col-md-1'><strong><?php echo $row['output']; ?></strong></td>
						<td class='col-md-1'><strong><?php echo $row['reject']; ?></strong></td>
						<td class='col-md-1'><strong><?php echo $row['prod_date']; ?></strong></td>
					</tr>
<?php
	}
?>
				</tbody>
			</table>
<!-- <?php
	$sql = "SELECT count(*) FROM (SELECT batch_prod_id, item_no, CONCAT(SUM(actual_prod), ' pcs') as actual_prod, CONCAT(SUM(batch_prod), ' batches') as batch_prod, CONCAT(SUM(output), ' pcs') as output, DATE_FORMAT(date_production,'%m/%d/%y') as prod_date, CONCAT(reject, ' pcs') as reject
		FROM batch_prod WHERE office = '$office'
		GROUP BY prod_date, item_no, reject, batch_prod_id
		ORDER BY prod_date DESC, item_no ASC) as counting";

    $rs_result = mysqli_query($db, $sql);  
    $row = mysqli_fetch_row($rs_result);  
    $total_records = $row[0];  
    $total_pages = ceil($total_records / $limit);  
    $pagLink = "<nav style='margin-top:355px;'><ul class='pagination'>";  
    for ($i=1; $i<=$total_pages; $i++) {  
                 $pagLink .= "<li><a href='batch_head_report_view.php?page=".$i."'>".$i."</a></li>";  
    };  
    echo $pagLink . "</ul></nav>";  
?> -->
		</div>
<?php
	}else if($option == 'monthly_output'){
?>
		<div class='col-md-4 col-md-offset-2'>
			<table class='table table-striped' style='width: 100%;' id='myTable3'>
				<thead>
					<tr>
						<th colspan='3'> Monthly Production Per Type</th>
					</tr>
					<tr>
						<th colspan='3'>
							<input type="text" id='select_date3' name='select_date3' class='form-control' onkeyup='myFunction3();' placeholder="Search for date">
								<!-- <option value=''>Select Date</option>
<?php
	$query = "SELECT DISTINCT DATE_FORMAT(date_production,'%m/%Y') as prod_date
			FROM batch_prod
			WHERE office = '$office' 
			GROUP BY MONTH(date_production), item_no 
			ORDER BY MONTH(date_production) DESC, item_no ASC";

	$result = mysqli_query($db, $query);
	foreach ($result as $row) {
							echo "<option value='".$row['prod_date']."'>".$row['prod_date']."</option>";
	}
?>
							</select> -->
						</th>
					</tr>
					<tr>
						<th class='col-md-1'>Date</th>
						<th class='col-md-1'>Type</th>
						<th class='col-md-1'>Total</th>
					</tr>
				</thead>
				<tbody>
<?php
	$limit = 10;  
	if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };  
	$start_from = ($page-1) * $limit;  
	$query = "SELECT DATE_FORMAT(date_production,'%m/%Y') as prod_date, item_no, SUM(actual_prod) as total
				FROM batch_prod
				WHERE office = '$office' 
				GROUP BY MONTH(date_production), item_no 
				ORDER BY MONTH(date_production) DESC, item_no ASC";

	$result = mysqli_query($db, $query);

	while($row = mysqli_fetch_assoc($result)){
?>		
					<tr>
						<td class='col-md-1' text-align: center'><strong><?php echo $row['prod_date']; ?></strong></td>
						<td class='col-md-1'><strong><?php echo $row['item_no']; ?></strong></td>
						<td class='col-md-1'><strong><?php echo $row['total']; ?></strong></td>
					</tr>
<?php
	}
?>
				</tbody>
			</table>
<!-- <?php
	$sql = "SELECT count(*) FROM ( SELECT COUNT(DISTINCT MONTH(date_production)) AS counting
			FROM batch_prod WHERE office = 'delta'
            GROUP BY item_no) as temp";

    $rs_result = mysqli_query($db, $sql);  
    $row = mysqli_fetch_row($rs_result);  
    $total_records = $row[0];  
    $total_pages = ceil($total_records / $limit);  
    $pagLink = "<nav style='margin-top:355px;'><ul class='pagination'>";  
    for ($i=1; $i<=$total_pages; $i++) {  
                 $pagLink .= "<li><a href='batch_head_report_view.php?page=".$i."'>".$i."</a></li>";  
    } 
    echo $pagLink . "</ul></nav>";  
?> -->
		</div>
		<div class='col-md-4'>
			<table class='table table-striped' style='width: 100%;' id='myTable4'>
				<thead>
					<tr>
						<th colspan='2'>Monthly Production for All Type</th>
					</tr>
					<tr>
						<th colspan='3'>
							<input type="text" id='select_date4' name='select_date4' class='form-control' onkeyup='myFunction4();' placeholder="Search for date">
								<!-- <option value=''>Select Date</option>
<?php
	$query = "SELECT DISTINCT DATE_FORMAT(date_production,'%m/%Y') as prod_date
				FROM batch_prod
				WHERE office = '$office' 
				GROUP BY MONTH(date_production) 
				ORDER BY MONTH(date_production) DESC, item_no ASC";

	$result = mysqli_query($db, $query);
	foreach ($result as $row) {
		echo "<option value='".$row['prod_date']."'>".$row['prod_date']."</option>";
	}
?>
							</select> -->
						</th>
					</tr>
					<tr>
						<th class='col-md-1'>Date</th>
						<th class='col-md-1'>Total</th>
					</tr>
				</thead>
				<tbody>
<?php
	$limit = 10;  
	if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };  
	$start_from = ($page-1) * $limit;  
	$query = "SELECT DATE_FORMAT(date_production,'%m/%Y') as prod_date, item_no, SUM(actual_prod) as total
				FROM batch_prod
				WHERE office = '$office' 
				GROUP BY MONTH(date_production) 
				ORDER BY MONTH(date_production) DESC, item_no ASC";

	$result = mysqli_query($db, $query);
	while($row = mysqli_fetch_assoc($result)){		
?>
					<tr>
						<td class='col-md-1' text-align: center'>
							<strong><?php echo $row['prod_date']; ?></strong>
						</td>
						<td class='col-md-1'><strong><?php echo $row['total']; ?></strong></td>
					</tr>
<?php
	}
?>
				</tbody>
			</table>
<!-- <?php
	$sql = "SELECT count(*) FROM (SELECT DATE_FORMAT(date_production,'%m/%Y') as prod_date, item_no, SUM(actual_prod) as total
				FROM batch_prod
				WHERE office = '$office' 
				GROUP BY MONTH(date_production) 
				ORDER BY MONTH(date_production) DESC, item_no ASC) as counting";

    $rs_result = mysqli_query($db, $sql);  
    $row = mysqli_fetch_row($rs_result);  
    $total_records = $row[0];  
    $total_pages = ceil($total_records / $limit);  
    $pagLink = "<nav style='margin-top:355px;'><ul class='pagination'>";  
    for ($i=1; $i<=$total_pages; $i++) {  
                 $pagLink .= "<li><a href='batch_head_report_view.php?page=".$i."'>".$i."</a></li>";  
    }
    echo $pagLink . "</ul></nav>";  
?> -->
		</div>
<?php
	}else if($option == 'monthly_delivery'){
?>
		<div class='col-md-4 col-md-offset-2'>
			<table class='table table-striped' style='width: 100%;' id='myTable5'>
				<thead>
					<tr>
						<th colspan='3'>Monthly Delivered CHB Per Type</th>
					</tr>
					<tr>
						<th colspan='3'>
							<input type="text" id='select_date5' name='select_date5' class='form-control' onkeyup='myFunction5();' placeholder="Search for date">
								<!-- <option value=''>Select Date</option>
<?php
	$query = "SELECT DISTINCT DATE_FORMAT(date_production,'%m/%Y') as delivery_date 
				FROM batch_prod_stock 
				WHERE office = '$office'
				GROUP BY MONTH(date_production), item_no 
				ORDER BY MONTH(date_production) DESC, item_no ASC";

	$result = mysqli_query($db, $query);
	foreach ($result as $row) {
								echo "<option value='".$row['delivery_date']."'>".$row['delivery_date']."</option>";
	}
?>
							</select> -->
						</th>
					</tr>
					<tr>
						<th class='col-md-1'>Date</th>
						<th class='col-md-1'>Type</th>
						<th class='col-md-1'>Total</th>
					</tr>
				</thead>
				<tbody>
<?php
	$limit = 10;  
	if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };  
	$start_from = ($page-1) * $limit;  
	$query = "SELECT item_no, SUM(delivered) as total, DATE_FORMAT(date_production,'%m/%Y') as delivery_date 
				FROM batch_prod_stock 
				WHERE office = '$office'
				GROUP BY MONTH(date_production), item_no 
				ORDER BY MONTH(date_production) DESC, item_no ASC";

	$result = mysqli_query($db, $query);

	while($row = mysqli_fetch_assoc($result)){		
?>
					<tr>
						<td class='col-md-1' style='text-align: center'><strong><?php echo $row['delivery_date']; ?></strong></td>
						<td class='col-md-1''><strong><?php echo $row['item_no']; ?></strong></td>
						<td class='col-md-1''><strong><?php echo $row['total']; ?></strong></td>
					</tr>
<?php
	}
?>
				</tbody>
			</table>
<!-- <?php
	$sql = "SELECT count(*) FROM (SELECT item_no, SUM(delivered) as total, DATE_FORMAT(date_production,'%m/%Y') as delivery_date 
				FROM batch_prod_stock 
				WHERE office = '$office'
				GROUP BY MONTH(date_production), item_no 
				ORDER BY MONTH(date_production) DESC, item_no ASC) as counting";

    $rs_result = mysqli_query($db, $sql);  
    $row = mysqli_fetch_row($rs_result);  
    $total_records = $row[0];  
    $total_pages = ceil($total_records / $limit);  
    $pagLink = "<nav style='margin-top:355px;'><ul class='pagination'>";  
    for ($i=1; $i<=$total_pages; $i++) {  
                 $pagLink .= "<li><a href='batch_head_report_view.php?page=".$i."'>".$i."</a></li>";  
    }
    echo $pagLink . "</ul></nav>";  
?> -->
		</div>
		<div class='col-md-4'>
			<table class='table table-striped' style='width: 100%;' id='myTable6'>
				<thead>
					<tr>
						<th colspan='2'>Monthly Delivered CHB for All Type</th>
					</tr>
					<tr>
						<th colspan='3'>
							<input type="text" id='select_date6' name='select_date6' class='form-control' onkeyup='myFunction6();' placeholder="Search for date">
								<!-- <option value=''>Select Date</option>
<?php
	$query = "SELECT DISTINCT DATE_FORMAT(date_production,'%m/%Y') as delivery_date 
				FROM batch_prod_stock 
				WHERE office = '$office'
				GROUP BY MONTH(date_production) 
				ORDER BY MONTH(date_production) DESC, item_no ASC";

	$result = mysqli_query($db, $query);
	foreach ($result as $row) {
							echo "<option value='".$row['delivery_date']."'>".$row['delivery_date']."</option>";
	}
?>
							</select> -->
						</th>
					</tr>
					<tr>
						<th class='col-md-1'>Date</th>
						<th class='col-md-1'>Total</th>
					</tr>
				</thead>
				<tbody>
<?php
	$limit = 10;  
	if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };  
	$start_from = ($page-1) * $limit;  
	$query = "SELECT item_no, SUM(delivered) as total, DATE_FORMAT(date_production,'%m/%Y') as delivery_date 
				FROM batch_prod_stock 
				WHERE office = '$office'
				GROUP BY MONTH(date_production) 
				ORDER BY MONTH(date_production) DESC, item_no ASC";

	$result = mysqli_query($db, $query);

	while($row = mysqli_fetch_assoc($result)){	
?>	
					<tr>
						<td class='col-md-1' style='text-align: center'><strong><?php echo $row['delivery_date']; ?></strong></td>
						<td class='col-md-1'><strong><?php echo $row['total']; ?></strong></td>
					</tr>
<?php
	}
?>
				</tbody>
			</table>
<!-- <?php
	$sql = "SELECT count(*) FROM (SELECT item_no, SUM(delivered) as total, DATE_FORMAT(date_production,'%m/%Y') as delivery_date 
				FROM batch_prod_stock 
				WHERE office = '$office'
				GROUP BY MONTH(date_production) 
				ORDER BY MONTH(date_production) DESC, item_no ASC) as counting";

    $rs_result = mysqli_query($db, $sql);  
    $row = mysqli_fetch_row($rs_result);  
    $total_records = $row[0];  
    $total_pages = ceil($total_records / $limit);  
    $pagLink = "<nav style='margin-top:355px;'><ul class='pagination'>";  
    for ($i=1; $i<=$total_pages; $i++) {  
                 $pagLink .= "<li><a href='batch_head_report_view.php?page=".$i."'>".$i."</a></li>";  
    }
    echo $pagLink . "</ul></nav>";  
?> -->
		</div>

<?php
	}
?>
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