<!DOCTYPE html>
<?php

	include("includes/config.php");
	include("includes/function.php");
	
	session_start();
	if(isset($_SESSION['login_user'])) {
		header("location: index.php");
	}

?>
<html>
<head>

  <title>Starcrete Manufacturing Corp. / Quality Star Concrete Products, Inc.</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width ,height=device-height , initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/bootstrap.theme.css" rel="stylesheet">
  <link href="css/bootstrap.theme.min.css" rel="stylesheet">
  <link href="css/bootstrap.css" rel="stylesheet">
  <link href="css_ext/login.css" rel="stylesheet">
  <script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
  <script type="text/javascript" src="js/bootstrap.min.js"></script>
  <script type="text/javascript" src="js/bootstrap.js"></script>
  <script type="text/javascript" src="js/npm.js"></script>
  <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> -->

<script language="Javascript">
	function capLock(e){
		kc = e.keyCode?e.keyCode:e.which;
		sk = e.shiftKey?e.shiftKey:((kc == 16)?true:false);
		if(((kc >= 65 && kc <= 90) && !sk)||((kc >= 97 && kc <= 122) && sk))
			document.getElementById('divMayus').style.visibility = 'visible';
		else
			document.getElementById('divMayus').style.visibility = 'hidden';
	}
</script>
<style>
.footer{
	background-color: #0884e4;
	color: white;
    position: fixed;
    text-align: center; 
    padding:10px;
    bottom: 0;
    width: 100%;
}
/*body{
	background-image:url("images/block.jpg");
	background-repeat:no-repeat;
	background-size:cover;
}*/
</style>
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-md-8" style="margin-top: -90px;">
				<img src="images/starcrete.png" width="700" height="300" class="img-responsive">
				<img src="images/starcrete.png" width="700" height="50" class="img-responsive">
			</div>
			<div class="col-md-4">
				<div class="panel panel-login">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-12">
								<img src="images/starcrete.png" width="150" height="50">
							</div>
						</div>
						<hr>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-lg-12">
								<form id="login-form" action="login.php" method="post" role="form" style="display: block;">
									<div class="form-group">
										<input type="text" name="username" id="username" tabindex="1" class="form-control" placeholder="Username" value="" required>
									</div>
									<div class="form-group">
										<input type="password" name="password" id="password" tabindex="2" class="form-control" placeholder="Password" onkeypress="capLock(event);" required>
										<div id="divMayus" style="visibility:hidden">Caps Lock is on.</div> 
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-sm-6 col-sm-offset-3">
												<input type="submit" name="login-submit" id="login-submit" tabindex="4" class="form-control btn btn-login" value="Log In">
											</div>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<footer class="footer">
		<div class="container">
			<h4>Starcrete Manufacturing Corporation 2017</h4>
		</div>
	</footer>
</body>
</html>

<?php

if(isset($_POST['login-submit'])){

    $username = $_POST['username'];
    $password = $_POST['password'];
    // $sql = "SELECT user_id FROM users WHERE username = '$username' AND `password` = '$password'";

    // $result = mysqli_query($db,$sql);
    // $count = mysqli_num_rows($result);

    $stmt = $db->prepare('SELECT user_id FROM users WHERE username = ? AND `password` = ?');
	$stmt->bind_param('ss', $username, $password);

	$stmt->execute();
	$count = $stmt->get_result();
    if(mysqli_num_rows($count) == 1) {
      session_start();
      $_SESSION['login_user'] = $username;
      header("location: index.php");
    }else{
      phpAlert("Your Username or Password is invalid");
    }
  }

?>
