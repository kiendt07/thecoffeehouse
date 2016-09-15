<?php 
	ob_start();
	session_start();
	$page_title = "Profile";
	if ((isset($_SESSION['login_role'])) && ($_SESSION['login_role'] == 'admin')){
		include('includes/header_admin.inc.html');
	} else {
		if($_SESSION['login_role'] == 'staff') {
			include('includes/header_staff.inc.html');
		} else {
			include('includes/header.inc.html');
		}	
	}
		
	if (isset($_GET['id'])){
		$id = $_GET['id'];
	}
?>

<body>
	<div class="container">
		<div class="row">
		<div class="box">
			<div class="row">
				<hr>
				<h2 class="intro-text text-center">
					Profile
					<br>

					<?php 
						if ((!isset($id)) && (!isset($_SESSION['user_name']))){
							echo '<small class="warning">Please select an user!!<br>Returning to Homepage...</small>';
							header("refresh:3; url=index.php");
							exit();
						}
					?>	

					<?php 
						require('includes/sql_connection.inc.php');
						if (isset($id)){
							$result = pg_query($DBC, "SELECT * FROM customers WHERE customerid = $id");
						} else {
							if (isset($_SESSION['user_name'])){
								$username = $_SESSION['user_name'];
								$result = pg_query($DBC, "SELECT * FROM customers WHERE username = '$username'");
							}
						}


						if ($result){
							$row = pg_fetch_array($result);
							$username = $row['username'];
							$id = $row['customerid'];
							echo '<small>
									<a href="profile.php?id='.$id.'">@<span id="username">'.$username.'</span></a>
								</small>';
							$firstname = $row['firstname'];
							$lastname = $row['lastname'];
							$sex = $row['sex'];
							$address1 = $row['address1'];
							$address2 = $row['address2'];
							$email = $row['email'];
							$phone = $row['phone'];
							$username = $row['username'];
							$user_group_id = $row['user_group_id'];
							$salary = $row['salary'];
							$position = $row['position'];
							$i = array('First name', 'Last name', 'Gender', 'Address', 'Company', 'Email', 'Phone', 'Username');

						}
					?>

				</h2> <!-- end h2 -->
				<hr>


				<?php
				if($_SESSION['login_role'] == "user") {
				echo '<div class="col-md-6 col-md-offset-3 text-center">
					<!-- Button trigger modal -->
					<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#history">
					  Shopping history
					</button>

					<!-- Modal -->
					<div class="modal fade" id="history" tabindex="-1" role="dialog" aria-labelledby="historyLabel">
					  <div class="modal-dialog" role="document">
					    <div class="modal-content">
					      <div class="modal-header">
					        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					        <h4 class="modal-title" id="myModalLabel">History</h4>
					      </div>
					      <div class="modal-body">';
					        
					        	$result = pg_query_params($DBC,"select orderdate, quantity, name, price from orderlines ol natural join products p, orders o
																										where o.customerid = $1 and ol.orderid = o.orderid
																										order by o.orderdate", array($id));
					        	if(!$result) {
					        		echo "querry history error";
					        	} else {
					        		echo '<div class="table-responsive">          
						                  <table class="table">
						                    <thead>
						                      <tr>
						                        <th class="text-center">Date</th>
						                        <th class="text-center">Product Name</th>
						                        <th class="text-center">Quantity</th>
						                        <th class="text-center">Price (Each)</th>
						                      </tr>
						                    </thead>
						                    <tbody class="table-body">';
					        		while ($row_history = pg_fetch_array($result)) {
		                    echo '<tr>
		                    	<td class="text-center">'.$row_history['orderdate'].'</td>
		                    	<td class="text-center">'.$row_history['name'].'</td>
		                    	<td class="text-center">'.$row_history['quantity'].'</td>
		                    	<td class="text-center">'.$row_history['price'].'</td>
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
				</div>

			</div> <!-- end row -->';
		}
			?>
			<div class="">
			<table class="table table-hover table-striped text-center">
	                <colgroup>
	                    <col class="col-md-3 col-md-offset-6">
	                    <col class="col-md-3">
	                </colgroup>
	                <thead>
	                 
	                </thead>
	                <tbody>
	                    <tr>
	                    	<td><strong>First name</strong></td>
	                    	<td><?php echo $firstname; ?></td>
	                    </tr>

	                    <tr>
	                    	<td><strong>Last name</strong></td>
	                    	<td><?php echo $lastname; ?></td>
	                    </tr>

	                    <tr>
	                    	<td><strong>Gender</strong></td>
	                    	<td><?php echo $sex; ?></td>
	                    </tr>

	                    <tr>
	                    	<td><strong>Address</strong></td>
	                    	<td><?php echo $address1; ?></td>
	                    </tr>

	                    <tr>
	                    	<td><strong>Company</strong></td>
	                    	<td><?php echo $address2; ?></td>
	                    </tr>

	                    <tr>
	                    	<td><strong>Email</strong></td>
	                    	<td><?php echo $email; ?></td>
	                    </tr>

	                    <tr>
	                    	<td><strong>Phone</strong></td>
	                    	<td><?php echo $phone; ?></td>
	                    </tr>

	                    <tr>
	                    	<td><strong>Username</strong></td>
	                    	<td id="username"><?php echo $username; ?></td>
	                    </tr>

	                    <?php
	                    	if($user_group_id == 1) {
	                    		echo '<tr>
	                    	<td><strong>Salary</strong></td>
	                    	<td id="salary">'. $salary .'</td>
	                    </tr>';
	                    		if($position == 0) $tmp = "0";
	                    		if($position == 1) $tmp = "1"	;
	                    		echo '<tr>
	                    	<td><strong>Position</strong></td>
	                    	<td id="position">'. $tmp .'</td>
	                    </tr>
	                    		';
	                    		$result = pg_query_params($DBC, "select count(orderid) as record from orders where employeeid = $1", array($id));
	                    		if(!$result) {
	                    			echo "query error";
	                    		} else {
	                    			$row1 = pg_fetch_array($result);
	                    			$order_handled = $row1['record'];
	                    			echo '<tr>
	                    	<td><strong>Orders Handled</strong></td>
	                    	<td id="order_handled">'. $order_handled .'</td>
	                    </tr>';
	                    		}
	                    	}
	                    ?>

	                </tbody>
	            </table> <!-- end of table -->
	          </div>

	            <div class="row text-center">
	            	<?php 
	            		if (($_SESSION['user_name'] == $username) || ($_SESSION['login_role'] == 'admin')){
	            			echo '<a href="edit_user.php?id='.$id.'"><button class="btn btn-default">Update</button></a>';
	            		}
	            	?>
	            </div> <!-- end row -->
		</div><!--  end box -->
	</div>
	</div> <!-- end container -->
</body>

<?php 
	pg_close($DBC);
	include('includes/footer.inc.html');
?>