<!DOCTYPE html>
<?php

	include("includes/config.php");
	include("includes/function.php");
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
	// $user_query = "SELECT * FROM users WHERE username = '".$_SESSION['login_user']."'";
	// $result = mysqli_query($db, $user_query);
	// $user = mysqli_fetch_assoc($result);

	// $office = $user['office'];
	// $position = $user['position'];

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
		echo "<title>Home - Quality Star Concrete Products, Inc.</title>";
	}else{
		echo "<title>Home - Starcrete Manufacturing Corporation</title>";
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

	// var time = new Date().getTime();
	// $(document.body).bind("mousemove keypress", function(e) {
	// 	time = new Date().getTime();
	// });

	// function refresh() {
	// 	if(new Date().getTime() - time >= 120000) 
	// 		window.location.reload(true);
	// 	else 
	// 		setTimeout(refresh, 10000);
	// }

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
	             $table.find('tbody').prepend($('<tr class="no-result text-center"><td  style="height: 100%;background: white; text-align:center; vertical-align:middle;"><h4><p class="text-muted">No data found</p></h4></td></tr>'));
	        }
	    });
	});

	function filterTable() {
		// Declare variables 
		var input, filter, table, tr, td, i;
		input = document.getElementById("select_status");
		filter = input.value.toUpperCase();
		table = document.getElementById("myTable");
		tr = table.getElementsByTagName("tr");

		// Loop through all table rows, and hide those who don't match the search query
		for (i = 0; i < tr.length; i++) {
			td = tr[i].getElementsByTagName("td")[0];
			if (td) {
				if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
					tr[i].style.display = "";
				} else {
					tr[i].style.display = "none";
				}
			} 
		}
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
	/*min-height: 510px;*/
	padding-bottom:50px; /* Height of the footer element */
	padding-right: 15px;
	padding-left: 15px;
	padding-top: 15px;
}

#footer {
	width:100%;
	position:absolute;
	bottom:0;
	/*left:0;*/
	background-color: #0884e4;
    color: white;
    text-align: center; 
    padding: 10px;
}
/*.table tbody{
  overflow-y: scroll;
  height: 370px;
  width: 99%;
  position: absolute;
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
.table_page{
	margin: auto;
    width: 50%;
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
		<a href="stock_report.php">Stock Report</a>
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
		<a href="batch_plant_report.php">Batch Report</a>
<?php
	}
?>
		<a href="diesel.php">Diesel Report</a>
<?php
	if($position != 'warehouseman')
		echo "<a href='purchase_order.php'>Issued Purchase Order</a>";
?>
		<!-- <a href='purchase_order.php'>Issued Purchase Order</a> -->
		<a href="delivery.php">Issued Delivery Receipt</a>
		<a href='purchase_order_aggregates.php'>Issued Purchase Order Aggregates</a>
		<a href="received.php">Received Order</a>
		<a href="transmittal.php">Transmittal</a>
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
			<span style="font-size:25px; cursor:pointer; color: white;">Home</span>
			<ul class="nav navbar-nav navbar-right">
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" style="color: white; background-color: #0884e4;">Welcome! <strong><?php echo ucfirst($user['firstname']); ?></strong><span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="logout.php">Logout</a></li>
					</ul>
				</li>
			</ul>
			<!-- <div class="pull-right">
				Welcome! <strong><?php echo ucfirst($user['firstname']); ?></strong>
			</div> -->
		</div>
	</nav>

	<div id="wrapper" onclick="closeNav();">
		<div id="content">
			<form action="index.php" method="post" class="form-inline">
			<div class="row" style="margin: 0px; margin-bottom: 5px;">
				<div class="col-md-12">
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
			</div>
			</form>
			<div class="row" style="margin: 0px;">
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
			$string_date = "AND DATE_FORMAT(history_date,'%Y-%m-%d') <= '$end_date'";
		}else if($_POST['start_date'] != '' && $_POST['end_date'] == ''){
			$string_date = "AND DATE_FORMAT(history_date,'%Y-%m-%d') >= '$start_date'";		
		}else{
			$string_date = "AND DATE_FORMAT(history_date,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";
		}
?>
				<table class="table table-striped" id="myTable">
					<thead>
						<!-- <tr class="filterable"> -->
						<tr>
							<!-- <th colspan="3" style="text-align: left;">
								Date: <?php echo date_format($date_view,"F d, Y"); ?>
							</th> -->
							<!-- <th colspan="1">
								<button class="btn btn-default btn-xs btn-filter" style="float: right;"><span class="glyphicon glyphicon-filter"></span> Filter</button>
							</th> -->
						</tr>
						<!-- <tr class="filters"> -->
						<tr>
			                <th class="col-md-1">
			                	<!-- <input type="text" class="form-control" placeholder="Status" disabled> -->
			                	<select id="select_status" name="select_status" class="form-control" onchange="filterTable();">
			                		<option value="">All Status</option>
<?php
	$sql_type = "SELECT DISTINCT transaction_type
					FROM history
					WHERE office = '$search_plant' 
					AND transaction_type != ''
					ORDER BY transaction_type ASC";
	// echo $sql_type;
	$sql_type_result = mysqli_query($db, $sql_type);
	while($row = mysqli_fetch_assoc($sql_type_result)){
								echo "<option value='".$row['transaction_type']."'>".$row['transaction_type']."</option>";
	}
?>
			                	</select>
			                </th>
			                <th class="col-md-5">Transaction</th>
			                <th class="col-md-1">Date</th>
			              </tr>
					</thead>
					<tbody>
<?php

	if($office == 'head'){
		$string = " WHERE office = '$search_plant'";
	}else{
		$string = " WHERE office = '$office'";
	}
	$sql = "SELECT * FROM history".$string." ".$string_date;

	$result = mysqli_query($db, $sql); 
	$total = mysqli_num_rows($result);

	$adjacents = 3;
	$targetpage = "index.php"; //your file name
	$limit = 20; //how many items to show per page
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

	$sql1 = "SELECT table_report, transaction_type, detail, office, DATE_FORMAT(history_date,'%m/%d/%y')  as history_date1 
			FROM history ".$string." ".$string_date." 
			ORDER BY history_id DESC limit $start, $limit";

	// echo $sql1;
    $result = mysqli_query($db,$sql1);
    if(mysqli_num_rows($result) > 0){
      while($row = mysqli_fetch_array($result)){
?>
						<tr>
							<td class="col-md-1"><strong><?php echo $row['transaction_type']; ?></strong></td>
							<td class="col-md-5" style='text-align: justify;'><strong><?php echo $row['detail']; ?></strong></td>
							<td class="col-md-1"><strong><?php echo $row['history_date1']; ?></strong></td>
						</tr>
<?php
      }
    }else{
?>
						<tr>
							<td colspan="3" style='height: 100%; background: white; text-align:center; 
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
			$string_date = "AND DATE_FORMAT(history_date,'%Y-%m-%d') <= '$end_date'";
		}else if($_POST['start_date'] != '' && $_POST['end_date'] == ''){
			$string_date = "AND DATE_FORMAT(history_date,'%Y-%m-%d') >= '$start_date'";		
		}else{
			$string_date = "AND DATE_FORMAT(history_date,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";
		}		
?>
				<table class="table table-striped" id="myTable">
					<thead>
						<tr class="filterable">
							<!-- <th colspan="3" style="text-align: left;">
								Date: <?php echo date_format($date_view,"F d, Y"); ?>
							</th> -->
							<!-- <th colspan="1">
								<button class="btn btn-default btn-xs btn-filter" style="float: right;"><span class="glyphicon glyphicon-filter"></span> Filter</button>
							</th> -->
						</tr>
						<tr class="filters">
			                <th class="col-md-1">
			                	<!-- <input type="text" class="form-control" placeholder="Status" disabled></th> -->
								<select id="select_status" name="select_status" class="form-control" onchange="filterTable();">
			                		<option value="">All Status</option>
<?php
	$sql_type = "SELECT DISTINCT transaction_type
					FROM history
					WHERE office = '$search_plant' 
					AND transaction_type != ''
					ORDER BY transaction_type ASC";
	// echo $sql_type;
	$sql_type_result = mysqli_query($db, $sql_type);
	while($row = mysqli_fetch_assoc($sql_type_result)){
								echo "<option value='".$row['transaction_type']."'>".$row['transaction_type']."</option>";
	}
?>
			                	</select>
			                <th class="col-md-5">Transaction</th>
			                <th class="col-md-1">Date</th>
			              </tr>
					</thead>
					<tbody>
<?php

	if($office == 'head'){
		$string = " WHERE office = '$search_plant'";
	}else{
		$string = " WHERE office = '$office'";
	}

	$sql = "SELECT * FROM history".$string." ".$string_date;
	$result = mysqli_query($db, $sql); 
	$total = mysqli_num_rows($result);

	$adjacents = 3;
	$targetpage = "index.php"; //your file name
	$limit = 20; //how many items to show per page
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

	$sql1 = "SELECT table_report, transaction_type, detail, office, DATE_FORMAT(history_date,'%m/%d/%y')  as history_date1 
			FROM history ".$string." ".$string_date."
			ORDER BY history_id DESC limit $start, $limit";
	// echo $sql1;
    $result = mysqli_query($db,$sql1);
    if(mysqli_num_rows($result) > 0){
      while($row = mysqli_fetch_array($result)){

?>
						<tr>
							<td class="col-md-1"><strong><?php echo $row['transaction_type']; ?></strong></td>
							<td class="col-md-5" style='text-align: justify;'><strong><?php echo $row['detail']; ?></strong></td>
							<td class="col-md-1"><strong><?php echo $row['history_date1']; ?></strong></td>
						</tr>
<?php
      }
    }else{
?>
						<tr>
							<td colspan="3" style='height: 100%; background: white; text-align:center; 
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


