<?php
	function phpAlert($msg) {
	    echo '<script type="text/javascript">alert("' . $msg . '")</script>';
	}
/* List of miscellaneous functions */
	function getAddress($db, $client){

		$sql = "SELECT address
				FROM client 
				WHERE client_name = '$client'";

      	$result = mysqli_query($db, $sql);
      	$row = mysqli_fetch_assoc($result);

      	return $row['address'];
	}

	function getUnit($db, $item_no){

		$sql = "SELECT unit FROM item_list 
		  		WHERE item_no = '".$item_no."'";
		
		$result = mysqli_query($db, $sql);
		$row = mysqli_fetch_assoc($result);
		return $row['unit'];
	}

	function getTruck($db, $item_no){

		$sql = "SELECT truck FROM item_list 
		  		WHERE item_no = '".$item_no."'";
		
		$result = mysqli_query($db, $sql);
		$row = mysqli_fetch_assoc($result);
		return $row['truck'];
	}

	function getStock($db, $item_no, $office){

		$sql = "SELECT stock FROM item_stock 
				WHERE item_no = '$item_no' 
				AND office = '$office'";
		
		$result = mysqli_query($db, $sql);
		$row = mysqli_fetch_assoc($result);
		return $row['stock'];
	}
/* End of list of miscellaneous functions */

/* List of functions for Received Page */

	function getPurchaseAggId($db, $po_no, $item_no, $office){

		$sql = "SELECT purchase_order_aggregates_id 
				FROM purchase_order_aggregates
		  		WHERE item_no = '$item_no' 
		  		AND purchase_order_aggregates_no = '$po_no'
		  		AND office = '$office'";
		
		$result = mysqli_query($db, $sql);
		$row = mysqli_fetch_assoc($result);
		return $row['purchase_order_aggregates_id'];
	}

	function getPurchaseAggQuantity($db, $po_no, $item_no, $office, $fkid){

		$sql = "SELECT quantity 
				FROM purchase_order_aggregates
		  		WHERE item_no = '$item_no' 
		  		AND purchase_order_aggregates_no = '$po_no'
		  		AND office = '$office'
		  		AND purchase_order_aggregates_id = '$fkid'";
		
		$result = mysqli_query($db, $sql);
		$row = mysqli_fetch_assoc($result);
		return $row['quantity'];
	}

	function getPurchaseAggSupplier($db, $po_no, $item_no, $office, $fkid){

		$sql = "SELECT supplier_name 
				FROM purchase_order_aggregates
		  		WHERE item_no = '$item_no' 
		  		AND purchase_order_aggregates_no = '$po_no'
		  		AND office = '$office'
		  		AND purchase_order_aggregates_id = '$fkid'";
	
		$result = mysqli_query($db, $sql);
		$row = mysqli_fetch_assoc($result);
		return $row['supplier_name'];
	}

	function getPurchaseAggPlant($db, $po_no, $item_no, $fkid){

		$sql = "SELECT office 
				FROM purchase_order_aggregates
		  		WHERE item_no = '$item_no' 
		  		AND purchase_order_aggregates_no = '$po_no'
		  		AND purchase_order_aggregates_id = '$fkid'";
		
		$result = mysqli_query($db, $sql);
		$row = mysqli_fetch_assoc($result);
		return $row['office'];
	}

/* End of list of functions for Recevied Page */

/* List of functions for Delivery Page */

	function getPurchasePlant($db, $po_no, $item_no, $office){

		$sql = "SELECT office
				FROM purchase_order 
				WHERE item_no = '$item_no' 
				AND purchase_order_no = '$po_no'
				AND office = '$office'";

      	$result = mysqli_query($db, $sql);
      	$row = mysqli_fetch_assoc($result);

      	return $row['office'];
	}

	function getPurchaseClient($db, $po_no, $item_no, $office){

		$sql = "SELECT client_name
				FROM purchase_order 
				WHERE item_no = '$item_no' 
				AND purchase_order_no = '$po_no'
				AND office = '$office'
      			ORDER BY date_purchase DESC";

      	$result = mysqli_query($db, $sql);
      	$row = mysqli_fetch_assoc($result);

      	return $row['client_name'];
	}

/* End of list of functions for Delivery Page */

/* Start of list of functions for Batch Page */

	function getItemNo($db, $machine, $office, $date){
		
		$sql = "SELECT item_no 
				FROM batch 
				WHERE machine_no = '$machine' 
				AND office = '$office' 
				AND DATE_FORMAT(batch_date,'%Y-%m-%d') = '$date'";
	
		$result = mysqli_query($db, $sql);
      	$row = mysqli_fetch_assoc($result);

      	return $row['item_no'];
	}

	function getCement($db, $machine, $type, $office, $date){

		$sql = "SELECT cement 
				FROM batch 
				WHERE machine_no = '$machine'
				AND item_no = '$type' 
				AND office = '$office' 
				AND DATE_FORMAT(batch_date,'%Y-%m-%d') = '$date'";

		$result = mysqli_query($db, $sql);
      	$row = mysqli_fetch_assoc($result);

      	return $row['cement'];
		
	}

/* End of list of functions for Batch Page */

/* Start of list of functions for Purchase Order Page */

	function getCountOnDelivery($db){

		$sql = "SELECT count(*) as total
				FROM purchase_order
				WHERE remarks = 'On Delivery'";

		$result = mysqli_query($db, $sql);
      	$row = mysqli_fetch_assoc($result);

      	return $row['total'];
	}

	function getCountOnDeliveryOffice($db, $office){

		$sql = "SELECT count(*) as total
				FROM purchase_order
				WHERE remarks = 'On Delivery'
				AND office = '$office'";

		$result = mysqli_query($db, $sql);
      	$row = mysqli_fetch_assoc($result);

      	return $row['total'];
	}

	function getCountPending($db, $office){

		$sql = "SELECT count(*) as total
				FROM purchase_order
				WHERE balance != 0
				AND office = '$office'";

		$result = mysqli_query($db, $sql);
      	$row = mysqli_fetch_assoc($result);

      	return $row['total'];
	}

	function getCountPendingOffice($db, $office){

		$sql = "SELECT count(*) as total
				FROM purchase_order
				WHERE balance != 0
				AND office = '$office'";

		$result = mysqli_query($db, $sql);
      	$row = mysqli_fetch_assoc($result);

      	return $row['total'];
	}

/* End of list of functions for Purchase Order Page */

/* Start of list of functions for Delivery Order Page */

	function getDeliveryCountOnDeliveryOffice($db, $office){

		$sql = "SELECT count(*) as total
				FROM delivery
				WHERE remarks = 'On Delivery'
				AND office = '$office'";

		$result = mysqli_query($db, $sql);
      	$row = mysqli_fetch_assoc($result);

      	return $row['total'];
	}

	function getDeliveryBalance($db, $po_no_delivery, $po_id, $office){

		$sql = "SELECT balance 
				FROM purchase_order 
				WHERE purchase_order_no = '$po_no_delivery' 
				AND office = '$office'
				AND purchase_id = '$po_id'";
			
		$result = mysqli_query($db, $sql);
      	$row = mysqli_fetch_assoc($result);

      	return $row['balance'];
	}

	function getDeliveryDelivered($db, $po_no_delivery, $po_id, $office){

		$sql = "SELECT SUM(quantity) as quantity
				FROM delivery
				WHERE remarks = 'Delivered'
				AND office = '$office'
				AND fk_po_id = '$po_id'
				AND po_no_delivery = '$po_no_delivery'";
			// echo $sql;
		$result = mysqli_query($db, $sql);
      	$row = mysqli_fetch_assoc($result);

      	return $row['quantity'];

	}

	function getDeliveryOnDelivery($db, $po_no_delivery, $po_id, $office){

		$sql = "SELECT SUM(quantity) as quantity
				FROM delivery
				WHERE remarks = 'On Delivery'
				AND office = '$office'
				AND fk_po_id = '$po_id'
				AND po_no_delivery = '$po_no_delivery'";
			// echo $sql;
		$result = mysqli_query($db, $sql);
      	$row = mysqli_fetch_assoc($result);

      	return $row['quantity'];

	}
/* End of list of functions for Delivery Order Page */

/* Start of list of functions for Purchase Order Aggegrates Page */



/* End of list of functions for Purchase Order Aggegrates Page */

/* Start of list of functions for Received Page */

	function getAggCountPending($db){

		$sql = "SELECT count(*) as total
				FROM purchase_order_aggregates
				WHERE remarks = 'Pending'";

		$result = mysqli_query($db, $sql);
      	$row = mysqli_fetch_assoc($result);

      	return $row['total'];
	}

	function getAggCountPendingOffice($db, $office){

		$sql = "SELECT count(*) as total
				FROM purchase_order_aggregates
				WHERE remarks = 'Pending'
				AND office = '$office'";
	
		$result = mysqli_query($db, $sql);
      	$row = mysqli_fetch_assoc($result);

      	return $row['total'];
	}

/* End of list of functions for Purchase Order Page */
?>
