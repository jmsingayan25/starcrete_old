<?php

	date_default_timezone_set('Asia/Manila');

	// $con = new mysqli('localhost','root','','veeds');
	$con = new mysqli('localhost','root','','starcrete');

	if (mysqli_connect_error()) {
   		echo "Database connection failed: " . mysqli_connect_error();
	}else
		echo '1';

	// $con = new jp_controller('localhost','root','','veeds');
	$con = new jp_controller('localhost','root','','starcrete');
	echo "yooooo";
?>
