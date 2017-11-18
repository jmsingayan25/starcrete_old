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
		echo "<title>Production Form - Quality Star Concrete Products, Inc.</title>";
	}else{
		echo "<title>Production Form - Starcrete Manufacturing Corporation</title>";
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
        var $input = $form.find( "#blocks");

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

	function getMachine(str){
        var date_prev = $('#date_prev').val();
        var office = $('#office').val();
	    var dataString = "date_prev="+date_prev+"&office="+office;
	    $.ajax({
	        type: "POST",
	        url: "batch_result.php", // Name of the php files
	        data: dataString,
	        success: function(html)
	        {
	            $("#type_result").html(html);
	        }
	    });
	}
</script>
<style>

th, footer {
    background-color: #0884e4;
    color: white;
}

.row.content {
	min-height: 457px
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
	margin: 20px 0 0 -300px;
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
			<span style="font-size:25px; color: white;">Batch Page > CHB Production Form </span>
			<ul class="nav navbar-nav navbar-right">
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" style="color: white; background-color: #0884e4;">Welcome! <strong><?php echo $user['firstname']; ?></strong><span class="caret"></span></a>
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
			<button type="button" onclick="location.href='batch_plant_report.php';" class="btn btn-default" style="float: left;"><span class="glyphicon glyphicon-arrow-left"></span> Back to Batch Page</button>
		</div>
	</div>
	<div class="row content" style="margin: 0px;">
		<div class="col-md-12">
			<form action="batch_form_production.php" method="post" id="form">
				<div id="box">
					<div class="row">
						<div class="col-md-12" style="text-align: center; background-color: #0884e4; color: white; padding: 10px; margin: -16px 0 15px 0;"><h3><strong>Production Form</strong></h3></div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="date_prev">Date</label>
<?php
	$sql = "SELECT DATE_FORMAT(batch_date,'%m/%d/%y') as prod_date 
			FROM batch 
			WHERE office = '$office' 
			AND (item_no,DATE_FORMAT(batch_date,'%m/%d/%y')) NOT IN (SELECT item_no, DATE_FORMAT(date_production,'%m/%d/%y') 
																	FROM batch_prod WHERE office = '$office') 
			GROUP BY prod_date 
			ORDER BY prod_date DESC";
			
	$result = mysqli_query($db,$sql);
?>
								<select id="date_prev" name="date_prev" onchange="getMachine(this.value);" class="form-control" required>
								 	<option value="">Select</option>
							          <?php foreach($result as $row){
							              echo "<option value='" . $row['prod_date'] . "'>" . $row['prod_date'] . "</option>";
							            }
							          ?>
							    </select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<div id="type_result" name="type_result"></div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="blocks">Actual Production</label>
								<input id="blocks" type="text" name="blocks" class="form-control" autocomplete="off" required>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="reject">Rejected</label>
								<input id="reject" type="text" name="reject" autocomplete="off" class="form-control">
							</div>
						</div>
					</div>
					<hr>
					<div class="row">
						<div class="col-md-12">
							<button type="submit" id="add_production" name="add_production" value="<?php echo $user['office']; ?>" class="btn btn-primary btn-block">Add</button>
							<input type="reset" name="Reset" class="btn btn-warning btn-block">
						</div>
					</div>
				</div>
			</form>
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
	if(isset($_POST['add_production'])){ //input for actual production

		$item_prod = mysqli_real_escape_string($db, $_POST['item_no_prod']);
		$actual = str_replace(",", "", mysqli_real_escape_string($db, $_POST['blocks']));
		$date=date_create(mysqli_real_escape_string($db, $_POST['date_prev']));
		$datetime = date_format($date,"Y-m-d");
		$now_datetime = date("Y-m-d H:i:s");
		$plant = ucfirst(mysqli_real_escape_string($db, $_POST['add_production']));
		if($_POST['reject'] != ''){
			$reject = mysqli_real_escape_string($db, $_POST['reject']);
		}else{
			$reject = 0;
		}
		//query for batches produce by $type within the day
		$prod_sql = "SELECT SUM(batch_count) as count FROM batch 
						WHERE DATE_FORMAT(batch_date,'%Y-%m-%d') = '$datetime'
						AND item_no = '$item_prod' AND office = '$plant'";
		// echo $prod_sql;
		$result = mysqli_query($db, $prod_sql);
		$row5 = mysqli_fetch_assoc($result);

		$output = number_format($actual / $row5['count'],2);
		// echo $output;
		$prod_insert = "INSERT INTO batch_prod(item_no, actual_prod, batch_prod, output, reject, date_production, office) 
						VALUES ('$item_prod','$actual','".$row5['count']."','$output','$reject','$datetime','$plant')";

		// echo $prod_insert;
		$sql = "SELECT item_no FROM item_stock WHERE item_no = '$item_prod' AND office = '$plant'";
		$result = mysqli_query($db, $sql);

		if(mysqli_num_rows($result) > 0){
			//update data in item_stock if item_no is equal to $type
			$stock_query = "UPDATE item_stock SET stock = stock + '$actual', last_update = '$now_datetime' 
							WHERE item_no = '$item_prod' AND office = '$plant'";
		}else{
			//insert to item_stock if not exist
			$stock_query = "INSERT INTO item_stock(item_no, stock, office, last_update) 
							VALUES('$item_prod', '$actual', '$plant', '$now_datetime')";
		}

		$batch_stock = "INSERT INTO batch_prod_stock(item_no, production, office, date_production) 
						VALUES('$item_prod','$actual','$plant','$datetime')";

		$history_query = "INSERT INTO history(table_report, transaction_type, item_no, detail, history_date, office) 
							VALUES('Batch','Production','$item_prod','$plant produced ".number_format($actual)." pcs of $item_prod',NOW(),'$plant')";


		if(mysqli_query($db, $prod_insert) && mysqli_query($db, $stock_query) && mysqli_query($db, $batch_stock) && mysqli_query($db, $history_query)){
			phpAlert("Production successfully added!!");
		}else{
			phpAlert("Something went wrong!!");
		}		
	}

?>