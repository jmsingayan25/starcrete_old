<?php

	include("includes/config.php");
	include("includes/function.php");

	session_start();
	$user_query = $db->prepare("SELECT * FROM users WHERE username = ?");
	$user_query->bind_param('s', $_SESSION['login_user']);
	$user_query->execute();
	$result = $user_query->get_result();
	$user = $result->fetch_assoc();

	$office = $user['office'];

	if(isset($_GET['pending_delivery'])){
		$pending_delivery = $_GET['pending_delivery'];
?>
<script>
	$(function() {
        
        var $form = $( "#form" );
        var $input = $form.find( "#quantity" );

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
</script>
			<table class="table table-striped">
				<thead>
					<tr>
						<th class="col-md-1">P.O. #</th>
		     			<th class="col-md-1">Item</th>
		     			<th class="col-md-1">Order</th>
		     			<th class="col-md-1">Balance</th>
		     			<th class="col-md-1">Stock</th>
		     			<th class="col-md-2">Client Name</th>
						<th class="col-md-1">Address</th>
						<th class="col-md-1">Contact</th>
						<th class="col-md-1">Contact #</th>
						<th class="col-md-1">Date Ordered</th>
						<th class="col-md-1"></th>
					</tr>
				</thead>
				<tbody>
<?php

	if($office != 'head'){
		$string = " AND p.office = '$office' ";
	}else{
		if($pending_delivery == ''){
			$string = " AND p.office = 'bravo'";
		}else{
			$string = " AND p.office = '$pending_delivery'";
		}
	}
	$query = "SELECT p.purchase_id, p.purchase_order_no, p.client_name, p.item_no, CONCAT(FORMAT(p.quantity,0), ' ', l.unit) as quantity_order, p.quantity, delivered, backload, balance, address, contact_person, contact_no, date_purchase, office, remarks
	 			FROM purchase_order p, batch_list l
	 			WHERE p.item_no = l.item_no".$string."
	 			AND p.balance != 0
				ORDER BY date_purchase ASC, purchase_order_no DESC";

	$result = mysqli_query($db, $query);

	$count = mysqli_num_rows($result);
	if($count > 0){
		$hash = 1;
		while($row = mysqli_fetch_assoc($result)){
			$date = date_create($row['date_purchase']);
?>
					<tr>
						<td class='col-md-1'><strong><?php echo $row['purchase_order_no']; ?></strong></td>
						<td class='col-md-1'><strong><?php echo $row['item_no']; ?></strong></td>
						<td class='col-md-1'><strong><?php echo $row['quantity_order']; ?></strong></td>
						<td class='col-md-1'><strong><?php echo number_format((float)$row['balance'])." pcs"; ?></strong></td>
						<td class='col-md-1'>
							<strong>
							<?php if(getStock($db, $row['item_no'], $row['office']) == NULL || getStock($db, $row['item_no'], $row['office']) == ''){
									echo "0 pcs";
								}else{
									echo number_format((float)getStock($db, $row['item_no'], $row['office']))." pcs";
								}  
							?>
							</strong>
						</td>
						<td class='col-md-2'><strong><?php echo $row['client_name']; ?></strong></td>
						<td class='col-md-1'><strong><?php echo $row['address']; ?></strong></td>
						<td class='col-md-1'><strong><?php echo $row['contact_person']; ?></strong></td>
						<td class='col-md-1'><strong><?php echo $row['contact_no']; ?></strong></td>
						<td class='col-md-1'><strong><?php echo date_format($date,'m/d/y g:i A'); ?></strong></td>
			<?php if($office == 'head'){ ?>
						<td class='col-md-1'><strong>Pending</strong></td>
			<?php }else{ ?>
						<td class='col-md-1'><button type='button' class='btn btn-success btn-sm' data-toggle='modal' data-target='#myModal<?php echo $hash;?>' style='float: center'>Issue DR No.</button>
			<?php } ?>
						<!-- Modal -->
							<div class="modal fade" id="myModal<?php echo $hash;?>" role="dialog">
								<div class="modal-dialog modal-sm">

								<!-- Modal content-->
								<form action="delivery_transaction.php" method="post" id="form">
									<div class="modal-content">
										<div class="modal-header">
											<h4 class="modal-title">Purchase Order # <?php echo $row['purchase_order_no'] ?></h4>
										</div>
										<div class="modal-body" style="text-align: left;">
											<input type="hidden" id="po_no" name="po_no" class="form-control" value="<?php echo $row['purchase_order_no'] ?>">
											<input type="hidden" id="quantity_order" name="quantity_order" class="form-control" value="<?php echo $row['quantity'] ?>">
											<div class="form-group">
												<label for="dr_no">Delivery No.</label>
												<input type="text" id="dr_no" name="dr_no" class="form-control"  required>
											</div>
											<div class="form-group">
												<label for="item_no">Item</label>
												<input type="text" id="item_no" name="item_no" class="form-control" value="<?php echo $row['item_no'] ?>" readonly>
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
												<input type="text" id="quantity" name="quantity" class="form-control" required>
											</div>					
										</div>
										<div class="modal-footer">
											<input type="submit" id="submit" name="submit" value="Submit" class="btn btn-primary">
											<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
										</div>
									</div>
								</form>
								</div>
							</div>
						</td>
					</tr>
<?php
		$hash++;
		}
	}else{
?>
		<tr><td style='width: 1500px; height: 395px; background: white; border: none; text-align:center; 
	    vertical-align:middle;'><h4><p class='text-muted'>No data found</p></h4></td></tr>
<?php
	}
?>
				</tbody>
			</table>
<?php
	}
?>