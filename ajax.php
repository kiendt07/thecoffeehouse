<?php
session_start();
require('includes/sql_connection.inc.php');

if(isset($_SESSION['user_name'])) {
	$username = $_SESSION['user_name'];
	$password = $_SESSION['password'];	
	if(!$DBC) {
		echo "Database error";
	}	else {
		$result = pg_query_params($DBC,"select * from customers where username=$1 and pass=$2", array($username, $password));
		$row = pg_fetch_array($result);
		$customerid = $row['customerid'];
	}
} else {
	$customerid = -1;
}

	// process customer's order
	if (isset($_POST['id'])) {
		$quantity = $_POST['quantity'];
		$itemId = $_POST['id'];
		$name = $_POST["productName"];
		$price = $_POST['productPrice'];
		$orderlines = $_SESSION["orderlines"];
		$countOrderlines = $_SESSION["count_orderlines"];
		$orderlines[$countOrderlines] = array('prd_id' => $itemId,'quantity' => $quantity, 'productName' => $name, 'productPrice' => $price);
		$_SESSION["orderlines"] = $orderlines;
		$_SESSION["count_orderlines"] = $countOrderlines + 1;
		echo  "Đã thêm ".$quantity." ".$name." vào hóa đơn!";
	}

	// process comments
	if(isset($_POST['comment'])) {
		if($customerid == -1) {
			echo "You must Login First";
		} else {
			$comment = $_POST['comment'];
			$prod_id = $_POST['product_id'];
			$rating = $_POST['rating'];
			if($rating == NULL) {
				$result = pg_query_params($DBC,"insert into comments (customerid, prod_id, comment) 
																				values ($1, $2, $3)", array($customerid, $prod_id, $comment));
				if(!$result) {
					echo "query error1";
				} else {
					echo "Thank you, we appriciate your feedback.";
				}	
			}	else {
			//echo "<br>".$customerid." ".$rating." ".$comment." ".$prod_id;
				$result = pg_query_params($DBC,"insert into comments (customerid, prod_id, comment, rating) 
																					values ($1, $2, $3, $4)", array($customerid, $prod_id, $comment, $rating));
				if(!$result) {
					echo "query error2";
				} else {
					echo "Thank you, we appriciate your feedback.";
				}
			}
		}		
	}

	// staff processed orders
	if(isset($_POST['action'])) {
		$orderid = $_POST['orderid'];
		$employeeid = $customerid;
		if($_POST['action'] == 'accept') {
			$result = pg_query_params($DBC,"update orders set employeeid=$1, status=$2 where orderid=$3",array($employeeid,1,$orderid));
			if(!$result) {
				echo "update query 1 error";
			} else echo "1";
		} else {
			if($_POST['action'] == 'finish') {
				$result = pg_query_params($DBC,"update orders set employeeid=$1, status=$2 where orderid=$3",array($employeeid,2,$orderid));
				if(!$result) {
					echo "update query 2 error";
				} else echo "2";
			}
		}
	}


	// search bar
	if(isset($_POST['mission']) && ($_POST['mission'] == 'search')) {
		$data = $_POST['data'];
		//echo $data;
		$data = strtolower($data);
		$query = "select name,prod_id from products where lower(name) like '%".$data."%' order by name limit 3";
		$result_name = pg_query($DBC, $query);

		echo '<div class="list_group">';
		while($row = pg_fetch_array($result_name)) {
			$name = $row['name'];
			$prod_id = $row['prod_id'];
			echo '<a style="font-size: 1.2em; color: #000;" href="product.php?id='.$prod_id.'">'.$name.'</a><br>';
		}		
		$query = "select ctg_id, category from categories where lower(category) like '%".$data."%' order by category limit 3";
		$result_category = pg_query($DBC, $query);
		while($row = pg_fetch_array($result_category)) {
			$category = $row['category'];
			$ctg_id = $row['ctg_id'];
			echo '<a style="font-size: 1.2em; color: #000;" href="display_category.php?id='.$ctg_id.'">'.$category.'</a><br>';
		}
		echo '</div>';
	}


	//delete products
	if(isset($_POST['delete-product']) && ($_POST['delete-product'] == 'true')) {
		$prod_id =  $_POST['prod_id'];

		$result = pg_query_params($DBC, "delete from products where prod_id=$1",array($prod_id));
		if(!$result) {
			echo "error query delete";
		} else {
			echo "success";
		}
	}

	pg_close($DBC);

?>