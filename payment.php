<?php
	session_start();
	include("includes/header.inc.html");
?>


<?php
	//print_r($_SESSION['orderlines']);
	//echo "<br>".$_POST['destination']." ".$_POST['phone-number'];
	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		$destination = $_POST['destination'];
		$phone = $_POST['phone-number'];
		$orderLines = $_SESSION["orderlines"];
	require("includes/sql_connection.inc.php");
    $conn = $DBC;
    if(!$conn) echo "db err";

		if(isset($_SESSION["user_name"])) {
			$username = $_SESSION["user_name"];
			$password = $_SESSION["password"];
			
			$result = pg_query_params($conn, "select customerid from customers where username=$1 and pass=$2", array($username, $password));
			
			if(!$result) {
				echo "error query get customerid";
			} else {
				$row = pg_fetch_array($result);
				$customerId = $row['customerid'];
				echo $customerid;
			}
		} else {
			$customerId = -1;
		}
		//echo $customerId. ' '. $destination. ' '. $phone;}
		$result = pg_query_params($conn, "insert into orders (orderdate, customerid, employeeid, delivertype, destination, status, phone_number) 
																				values (current_date, $1, -1, 1, $2, 0, $3);", array($customerId, $destination, $phone));
		if(!$result) {
			echo "error query create order";
		} else {
      $result_orderid = pg_query_params($conn, "select orderid from orders where orderdate=current_date and destination=$1 and phone_number=$2", array($destination,$phone));

			if(!$result_orderid) {
				echo "error query orderid";
			} else {
				$row = pg_fetch_array($result_orderid);
				$orderid = $row['orderid'];
		          
		    foreach($orderLines as $value) {
		    	//echo "<br>".$value["prd_id"]." ".$value['quantity']." ".$orderid;
		    	$result_orderlines = pg_query_params($conn, "insert into orderlines (prod_id, quantity, orderid)
		                                        values ($1, $2, $3)", array($value['prd_id'], $value['quantity'], $orderid));
		    	if (!$result_orderlines) {
		    		echo "error query orderlines";
		    	}
	    	} 
	    	echo "success";
			}
		}
		pg_close($conn);		
	}	
?>



<body>
<div class="container"> 
	<div class="box text-center">

			<div class="row text-center">
		<h5 class="intro-text">
			Please select a delivery type
		</h5>
	</div>
		<button style="font-size: 1.5em; margin-right: 30px" type="button" class="btn-success btn-lg" id="pay">Pay <i class="glyphicon glyphicon-check"></i></button> 
		<div id="pay_alert" class="row col-lg-12" style="display: none;">
		</div>

	<button style="font-size: 1.5em"type="button" class="btn-primary btn-lg" id="ship">Ship <i class="glyphicon glyphicon-send"></i></button>
		
		<div class="ship-method-form">
			<form class="form-horizontal" role="form" method="POST" action="<?php echo $_SERVER["PHP_SELF"];?>">
			  <div class="form-group">
			    <label class="control-label col-sm-2">Destination:</label>
			    <div class="col-sm-10">
			      <input type="text" name="destination" class="form-control" id="form-des" placeholder="Enter Destination">
			    </div>
			  </div>
			  <div class="form-group">
			    <label class="control-label col-sm-2">Phone Number:</label>
			    <div class="col-sm-10"> 
			      <input type="text" name="phone-number" class="form-control" id="form-phone" placeholder="Enter Phone Number">
			    </div>
			  </div>
			  <div class="form-group"> 
			    <div class="col-sm-offset-2 col-sm-10">
			      <button type="submit" class="btn btn-default">Submit</button>
			    </div>
			  </div>
			</form>
		</div>

	</div>
</div>
</body>	
	
<?php
	include("includes/footer.inc.html");
?>

<script>
	$(document).ready(function() {
		$('.ship-method-form').hide();
		
		$('#ship').click(function(){
			$('#pay').hide();
			$('.ship-method-form').toggle();
		});

		$('#pay').click(function(){
			$('#ship').hide();
			$.post("payment_ajax.php", {'payment-menthod': 'pay'}, function (respond){
				$('#pay_alert').html(respond).show();
			});			
		});

	});
</script>