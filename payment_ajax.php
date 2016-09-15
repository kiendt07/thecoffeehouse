<?php
	ob_start();
	session_start();
    require_once('includes/sql_connection.inc.php');
    $conn = $DBC;
	//$conn = pg_connect("dbname=coffee_house user=postgres password=123");
	if(isset($_SESSION["orderlines"])) {
		$orderlines = $_SESSION["orderlines"];
		$count_orderlines = $_SESSION["count_orderlines"];
        if(count($orderlines) == 0) {
            echo "You must choose something first";
        } else {
            if(isset($_SESSION["user_name"])) {
    			$username = $_SESSION["user_name"];
    			$password = $_SESSION["password"];

    			$result = pg_query_params($conn, "select customerid from customers where username=$1 and pass=$2", array($username, $password));
    			
    			if(!$result) {
    				echo "error query get customerid";
    			} else {
    				$row = pg_fetch_array($result);
    				$customerId = $row['customerid'];
    			}
    		} else {
    			$customerId = -1;
    		}
    		//echo $customerId. ' '. $destination. ' '. $phone;}
    		$result = pg_query_params($conn, "insert into orders (orderdate, customerid, employeeid, delivertype, status) 
    																				values (current_date, $1, -1, 0, 0);", array($customerId));
    		if(!$result) {
    			echo "error query create order";
    		} else {
            $result_orderid = pg_query_params($conn, "select orderid from orders where orderdate=current_date and status=0 and customerid=$1", array($customerId));
    
    			if(!$result_orderid) {
    				echo "error query orderid";
    			} else {
    				$row = pg_fetch_array($result_orderid);
    				$orderid = $row['orderid'];
    		          
    		    foreach($orderlines as $value) {
    		    	//echo "<br>".$value["prd_id"]." ".$value['quantity']." ".$orderid;
    		    	$query = array($value['prd_id'], $value['quantity'], $orderid);
    		    	//print_r($query);
    		    	$result_orderlines = pg_query_params($conn, "insert into orderlines (prod_id, quantity, orderid)
    		                                        values ($1, $2, $3)", $query);
    		    	if (!$result_orderlines) {
    		    		echo "error query orderlines";
    		    	}
    	    	}


    	    	echo '<div class="text-center">
                        <h4 class="alert alert-success" style="text-transform: none">
                            <strong>'."Please wait for your order <br> with the number of ".$orderid.'</strong>
                        </h4>                    
                        <div style="font-size: 1.25em">Meanwhile, let\'s pick some another drinks!</div>
                    </div>
                    <hr>
                    <div class="text-center">
                        <h3><strong>Order details</strong></h3>
                    </div>';
                echo "<br>";
                echo '<div class="table-responsive">          
                  <table class="table">
                    <thead>
                      <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Product Name</th>
                        <th class="text-center">Quantity</th>
                        <th class="text-center">Price (Each)</th>
                      </tr>
                    </thead>
                    <tbody class="table-body">';
                    $count = 1;
                    foreach($orderlines as $value){
                        $name_tmp = $value['productName'];
                        $quantity_tmp = $value['quantity'];
                        $price_tmp = $value['productPrice'];
                        echo '<tr>
                            <td class="text-center">'.$count.'</td>
                            <td class="text-center">'.$name_tmp.'</td>
                            <td class="text-center">'.$quantity_tmp.'</td>
                            <td class="text-center">'.$price_tmp.'</td></tr>';
                        $count++;
                    }     
                    echo   '</tbody>
                  </table>
                  </div>';
    			}
            }    
        }	
	} else {
        echo "You must choose something first";
        header("refresh:3; url=display_category.php");
	}	
	pg_close($conn);  
?>

