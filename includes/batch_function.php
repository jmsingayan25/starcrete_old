<?php

	function getBatchType($db, $office, $machine_no, $date){

		$sql = "SELECT item_no
				FROM batch
				WHERE machine_no = '$machine_no'
				AND office = '$office'
				AND DATE_FORMAT(batch_date,'%Y-%m-%d') = DATE_FORMAT('".$date."','%Y-%m-%d')";

		$result = mysqli_query($db, $sql);
		$row = mysqli_fetch_assoc($result);

		return $row['item_no'];
	}

	function getBatchCement($db, $office, $machine_no, $date){

		$sql = "SELECT MAX(cement) as cement
				FROM batch
				WHERE machine_no = '$machine_no'
				AND office = '$office'
				AND DATE_FORMAT(batch_date,'%Y-%m-%d') = DATE_FORMAT('".$date."','%Y-%m-%d')";

		$result = mysqli_query($db, $sql);
		$row = mysqli_fetch_assoc($result);

		return $row['cement'];
	}

	function getBatch($db, $office, $machine_no, $date, $time){

		$sql = "SELECT SUM(batch_count) as batch_count
				FROM batch
				WHERE machine_no = '$machine_no'
				AND office = '$office'
				AND HOUR(batch_date) = '$time'
				AND DATE_FORMAT(batch_date,'%Y-%m-%d') = DATE_FORMAT('".$date."','%Y-%m-%d')";
		// echo $sql;
		$result = mysqli_query($db, $sql);
		$row = mysqli_fetch_assoc($result);

		return $row['batch_count'];
	}

	function getComment($db, $office, $machine_no, $date, $time){

		$sql = "SELECT GROUP_CONCAT(CASE WHEN comment IS NOT NULL AND comment <> '' THEN CONCAT('',comment) 
				END SEPARATOR ', ')  as comment
				FROM batch
				WHERE machine_no = '$machine_no'
				AND office = '$office'
				AND HOUR(batch_date) = '$time'
				AND DATE_FORMAT(batch_date,'%m/%d/%Y') = DATE_FORMAT('".$date."','%m/%d/%Y')";

		$result = mysqli_query($db, $sql);
		$row = mysqli_fetch_assoc($result);

		return $row['comment'];
	}

?>