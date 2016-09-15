<?php 
	ob_start();
	session_start();
	$page_title="Staff's Works";
	include('includes/header_staff.inc.html');
	include('includes/sql_connection.inc.php');
	$position = $_SESSION['position'];
?>


<body>
	<div class="container">
		<div class="row text-center"> <!-- buttons -->
							<?php
								$result = pg_query_params($DBC,"select count(orderid) as data from orders where status=0 and delivertype=$1", array($position));
								$row = pg_fetch_array($result);
								$newOrders = $row['data'];

								$result = pg_query_params($DBC,"select count(orderid) as data from orders where status=1 and delivertype=$1", array($position));
								$row = pg_fetch_array($result);
								$pendingOrders = $row['data'];

								$result = pg_query_params($DBC,"select count(orderid) as data from orders where status=2 and delivertype=$1", array($position));
								$row = pg_fetch_array($result);
								$oldOrders = $row['data'];

								echo $newOrders." ".$pendingOrders." ".$oldOrders;
							?>


							<a href="#order-new"> 
								<button class="btn btn-default" type="button">
							  New Orders <span class="badge" id="new_noti"><?php echo $newOrders;?></span> 
								</button>
							</a>

							<a href="#order-pending"> 
								<button class="btn btn-default" type="button">
						  	Pending Orders <span class="badge" id="pending_noti"><?php echo $pendingOrders;?></span>
								</button>
							 </a>

							<a href="#order-old">  
								<button class="btn btn-default" type="button">
							  Old Orders <span class="badge" id="finish_noti"><?php echo $oldOrders;?></span>
								</button>
							</a>





		<div> <!-- end buttons -->

		<div class="box order_new" id="order-new"> <!-- box order_new -->
			<div class="table-responsive">          
        <table class="table">
          <thead>
            <tr>
              <th class="text-center">#</th>
              <th class="text-center">Orderid</th>
              <th class="text-center">Date</th>
              <th class="text-center">Total</th>
              <th class="text-center"></th>
            </tr>
          </thead>
          <tbody class="table-body new-body">
			      <?php
							$result = pg_query_params($DBC,"select * from orders where status = 0 and delivertype=$1 order by orderid DESC", array($position));
							if(!$result) {
								echo "query get order new error";
							} else {
								$count = 1;
								while( $row = pg_fetch_array($result)) {
									echo '
												<tr id="order-new-'.$row['orderid'].'">
	                    	<td class="text-center"><strong>'. $count.'</strong></td>
	                    	<td class="text-center"><strong>'. $row['orderid'].'</strong></td>
	                    	<td class="text-center"><strong>'. $row['orderdate'].'</strong></td>
	                    	<td class="text-center"><strong>'. $row['total'].'</strong></td>
	                    	<td class="text-center">

	                    		<button type="button" class="btn btn-default" data-toggle="modal" data-target="#infor-'.$row['orderid'].'">
														  Information
														</button>

														<!-- Modal -->
														<div class="modal fade" id="infor-'.$row['orderid'].'" tabindex="-1" role="dialog" aria-labelledby="InforLabel">
														  <div class="modal-dialog" role="document">
														    <div class="modal-content">
														      <div class="modal-header">
														        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
														        <h4 class="modal-title" id="myModalLabel">Information</h4>
														      </div>
														      <div class="modal-body">';
														        
														        	$result_orderlines = pg_query_params($DBC,"select orderid, name, quantity, price from orderlines ol natural join orders o, products p where orderid = $1 and p.prod_id = ol.prod_id", array($row['orderid']));
														        	if(!$result_orderlines) {
														        		echo "querry orderid error";
														        	} else {
														        		echo '<div class="table-responsive">          
															                  <table class="table">
															                    <thead>
															                      <tr>
															                        <th class="text-center">Orderid</th>
															                        <th class="text-center">Product Name</th>
															                        <th class="text-center">Quantity</th>
															                        <th class="text-center">Price (Each)</th>
															                      </tr>
															                    </thead>
															                    <tbody class="table-body">';
														        		while ($row_orderlines = pg_fetch_array($result_orderlines)) {
											                    echo '<tr>
											                    	<td class="text-center">'.$row_orderlines['orderid'].'</td>
											                    	<td class="text-center">'.$row_orderlines['name'].'</td>
											                    	<td class="text-center">'.$row_orderlines['quantity'].'</td>
											                    	<td class="text-center">'.$row_orderlines['price'].'</td>
											                    </tr>';
															          }
										                    echo   '</tbody>
											                  </table>
											                  </div>';
														        	}
														        
														      echo '</div>
														      <div class="modal-footer">
														        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
														      </div>
														    </div>
														  </div>
														</div>
													</div> <!--end button information--> 

	                    		<button type="button" class="btn btn-primary button-accept" id="accept-'.$row['orderid'].'">Accept</button>
	                    		<button type="button" class="btn btn-warning button-pending" id="pending-'.$row['orderid'].'" style="display: none">Pending..</button>
	                    		<button type="button" class="btn btn-success button-finish" id="finish-'.$row['orderid'].'" style="display: none">Finished</button>
	                    		<div id="order-alert-'.$row['orderid'].'" class="alert alert-success" style="display: none;"></div>
												</td>
	                    	</tr>
	                    ';
	                    $count ++;
								}								
							}
						?>
          </tbody>
        </table>
    	</div>
			
		</div><!--  end box order_new -->

		<div class="box order_pending" id="order-pending"> <!-- box order_pending -->
			<div class="table-responsive">          
        <table class="table">
          <thead>
            <tr>
              <th class="text-center">#</th>
              <th class="text-center">Orderid</th>
              <th class="text-center">Date</th>
              <th class="text-center">Total</th>
              <th class="text-center"></th>
            </tr>
          </thead>
          <tbody class="table-body pending-body">
			      <?php
							$result1 = pg_query_params($DBC,"select * from orders where status = 1 and delivertype=$1 and employeeid = $2 order by orderid DESC", array($position, $_SESSION['id']));
							if(!$result1) {
								echo "query get order pending error";
							} else {
								$count1 = 1;
								while( $row1 = pg_fetch_array($result1)) {
									echo '<tr id="order-pending-'.$row1['orderid'].'">
	                    	<td class="text-center"><strong>'. $count1.'</strong></td>
	                    	<td class="text-center"><strong>'. $row1['orderid'].'</strong></td>
	                    	<td class="text-center"><strong>'. $row1['orderdate'].'</strong></td>
	                    	<td class="text-center"><strong>'. $row1['total'].'</strong></td>
	                    	<td class="text-center">

	                    		<button type="button" class="btn btn-default" data-toggle="modal" data-target="#infor-'.$row1['orderid'].'">
														  Information
														</button>

														<!-- Modal -->
														<div class="modal fade" id="infor-'.$row1['orderid'].'" tabindex="-1" role="dialog" aria-labelledby="InforLabel">
														  <div class="modal-dialog" role="document">
														    <div class="modal-content">
														      <div class="modal-header">
														        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
														        <h4 class="modal-title" id="myModalLabel">Information</h4>
														      </div>
														      <div class="modal-body">';
														        
														        	$result_orderlines = pg_query_params($DBC,"select orderid, name, quantity, price from orderlines ol natural join orders o, products p where orderid = $1 and p.prod_id = ol.prod_id", array($row1['orderid']));
														        	if(!$result_orderlines) {
														        		echo "querry orderid error";
														        	} else {
														        		echo '<div class="table-responsive">          
															                  <table class="table">
															                    <thead>
															                      <tr>
															                        <th class="text-center">Orderid</th>
															                        <th class="text-center">Product Name</th>
															                        <th class="text-center">Quantity</th>
															                        <th class="text-center">Price (Each)</th>
															                      </tr>
															                    </thead>
															                    <tbody class="table-body">';
														        		while ($row_orderlines = pg_fetch_array($result_orderlines)) {
											                    echo '<tr>
											                    	<td class="text-center">'.$row_orderlines['orderid'].'</td>
											                    	<td class="text-center">'.$row_orderlines['name'].'</td>
											                    	<td class="text-center">'.$row_orderlines['quantity'].'</td>
											                    	<td class="text-center">'.$row_orderlines['price'].'</td>
											                    </tr>';
															          }
										                    echo   '</tbody>
											                  </table>
											                  </div>';
														        	}
														        
														      echo '</div>
														      <div class="modal-footer">
														        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
														      </div>
														    </div>
														  </div>
														</div>
													</div> <!--end button information-->
	                    		<button type="button" class="btn btn-primary button-accept" id="accept-'.$row1['orderid'].'" style="display: none">Accept</button>
	           	         		<button type="button" class="btn btn-warning button-pending" id="pending-'.$row1['orderid'].'">Pending..</button>
	                    		<button type="button" class="btn btn-success button-finish" id="finish-'.$row1['orderid'].'" style="display: none">Finished</button>
	                    		<div id="order-alert-'.$row1['orderid'].'" class="alert alert-success" style="display: none;"></div>
												</td>
	                    </tr>
	                    ';
	                    $count1 ++;
								}								
							}
						?>
          </tbody>
        </table>
    	</div>
		</div> <!-- end box order_pending -->


		<div class="box order_old" id="order-old"> <!-- box order_old -->
			<div class="table-responsive">          
        <table class="table">
          <thead>
            <tr>
              <th class="text-center">#</th>
              <th class="text-center">Orderid</th>
              <th class="text-center">Date</th>
              <th class="text-center">Total</th>
              <th class="text-center"></th>
            </tr>
          </thead>
          <tbody class="table-body finish-body">
			      <?php
							$result2 = pg_query_params($DBC,"select * from orders where status = 2 and delivertype=$1 order by orderid DESC", array($position));
							if(!$result2) {
								echo "query get order pending error";
							} else {
								$count2 = 1;
								while( $row2 = pg_fetch_array($result2)) {
									echo '<tr id="order-finish-'.$row2['orderid'].'">
	                    	<td class="text-center"><strong>'. $count2.'</strong></td>
	                    	<td class="text-center"><strong>'. $row2['orderid'].'</strong></td>
	                    	<td class="text-center"><strong>'. $row2['orderdate'].'</strong></td>
	                    	<td class="text-center"><strong>'. $row2['total'].'</strong></td>
	                    	<td class="text-center">

	                    		                    	
	                    		<button type="button" class="btn btn-default" data-toggle="modal" data-target="#infor-'.$row2['orderid'].'">
														  Information
														</button>

														<!-- Modal -->
														<div class="modal fade" id="infor-'.$row2['orderid'].'" tabindex="-1" role="dialog" aria-labelledby="InforLabel">
														  <div class="modal-dialog" role="document">
														    <div class="modal-content">
														      <div class="modal-header">
														        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
														        <h4 class="modal-title" id="myModalLabel">Information</h4>
														      </div>
														      <div class="modal-body">';
														        
														        	$result_orderlines = pg_query_params($DBC,"select orderid, name, quantity, price from orderlines ol natural join orders o, products p where orderid = $1 and p.prod_id = ol.prod_id", array($row2['orderid']));
														        	if(!$result_orderlines) {
														        		echo "querry orderid error";
														        	} else {
														        		echo '<div class="table-responsive">          
															                  <table class="table">
															                    <thead>
															                      <tr>
															                        <th class="text-center">Orderid</th>
															                        <th class="text-center">Product Name</th>
															                        <th class="text-center">Quantity</th>
															                        <th class="text-center">Price (Each)</th>
															                      </tr>
															                    </thead>
															                    <tbody class="table-body">';
														        		while ($row_orderlines = pg_fetch_array($result_orderlines)) {
											                    echo '<tr>
											                    	<td class="text-center">'.$row_orderlines['orderid'].'</td>
											                    	<td class="text-center">'.$row_orderlines['name'].'</td>
											                    	<td class="text-center">'.$row_orderlines['quantity'].'</td>
											                    	<td class="text-center">'.$row_orderlines['price'].'</td>
											                    </tr>';
															          }
										                    echo   '</tbody>
											                  </table>
											                  </div>';
														        	}
														        
														      echo '</div>
														      <div class="modal-footer">
														        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
														      </div>
														    </div>
														  </div>
														</div>
													</div> <!--end button information-->
	                    		<button type="button" class="btn btn-primary button-accept" id="accept-'.$row2['orderid'].'" style="display: none">Accept</button>
	                    		<button type="button" class="btn btn-warning button-pending" id="pending-'.$row2['orderid'].'" style="display: none">Pending..</button>
	                    		<button type="button" class="btn btn-success button-success" id="finish-'.$row2['orderid'].'">Finished</button>
	                    		<div id="order-alert-'.$row2['orderid'].'" class="alert alert-success" style="display: none;"></div>
												</td>
	                    </tr>
	                    ';
	                    $count2 ++;
								}								
							}
						?>
          </tbody>
        </table>
    	</div>
		</div> <!-- end box order_old -->

	</div> <!-- end container -->
</body>

<?php 
	pg_close($DBC);
	include('includes/footer.inc.html');
?>


<script>
$(document).ready(function() {
		
	$('.button-accept').click(function(){
		var x = $(this).attr('id').replace('accept-', '');
		//console.log(x);
		$('#order-new-'+x).clone().appendTo('.pending-body');
		$('#order-new-'+x).remove();
		$('#order-new-'+x).attr("id","order-pending-"+x);
		$.post("ajax.php",{'action': 'accept', 'orderid': x}, function (response){
			//console.log(response);
		});
		$('#finish-'+x).hide();
		$('#accept-'+x).hide();
		$('#pending-'+x).show()

		
		$('.button-pending').click(function(){
			var x = $(this).attr('id').replace('pending-', '');
			//console.log(x);
			$('#order-pending-'+x).clone().appendTo('.finish-body');
			$('#order-pending-'+x).remove();
			$('#order-pending-'+x).attr("id","order-finish-"+x);
			$.post("ajax.php",{'action': 'finish', 'orderid': x}, function (response){
				//console.log(response);
			});
			$('#finish-'+x).show();
			$('#accept-'+x).hide();
			$('#pending-'+x).hide();
			
		});
	});
});

</script>

<script>
$(document).ready(function() {
	$('.button-pending').click(function(){
		var x = $(this).attr('id').replace('pending-', '');
		//console.log(x);
		$('#order-pending-'+x).clone().appendTo('.finish-body');
		$('#order-pending-'+x).remove();
		$('#order-pending-'+x).attr("id","order-finish-"+x);
		$.post("ajax.php",{'action': 'finish', 'orderid': x}, function (response){
			console.log(response);
		});
		$('#finish-'+x).show();
		$('#accept-'+x).hide();
		$('#pending-'+x).hide()
	});
});
</script>