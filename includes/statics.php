<?php 
if (!isset($_POST)){
	require('includes/sql_connection.inc.php');
} else {
	require('sql_connection.inc.php');
}

//LIFE TIME SALE
$result=pg_query_params($DBC, "SELECT sum(total) FROM orders", array());
if ($result){
	$row = pg_fetch_array($result);
	$life_time_sale = round($row[0]);
}

//LIFE TIME ORDERS
$result=pg_query_params($DBC, "SELECT count(orderid) FROM orders", array());
if ($result){
	$row = pg_fetch_array($result);
	$life_time_order = $row[0];
}

//AVERAGE ORDER
$result = pg_query_params($DBC, "SELECT avg(total) FROM orders", array());
if ($result){
	$row = pg_fetch_array($result);
	$average_order = round($row[0]);
}

//Last 5 order
$result = pg_query_params($DBC, "SELECT orderid, firstname, lastname, total FROM orders NATURAL JOIN customers ORDER BY orderid DESC LIMIT 5", array());
if ($result){
	$last5order=array();
	$i=0;
	while ($row = pg_fetch_array($result)){
		$last5order[$i]=$row;
		$i++;
	}
}

//Alltheorders

	$result = pg_query_params($DBC, "SELECT * FROM orders ORDER BY orderid DESC", array());
	if ($result){
		$order_list = pg_fetch_all($result);
	}


//List of products

$result = pg_query_params($DBC, "with total_quantity_tb as(
select products.prod_id, sum(quantity) as total_sold from products left join orderlines on products.prod_id = orderlines.prod_id group by products.prod_id)
select products.prod_id, name, categories.category, price, total_sold, rating from categories, total_quantity_tb right join 
products on products.prod_id = total_quantity_tb.prod_id left join comments on products.prod_id = total_quantity_tb.prod_id 
where products.category = categories.ctg_id", array());
if ($result){
	$product_list = pg_fetch_all($result);
}

//New Account Customers

	$result = pg_query_params($DBC, "SELECT * FROM customers WHERE user_group_id=2 ORDER BY customerid DESC", array());
	if ($result){
		$customer_list = pg_fetch_all($result);
	}

//Customers sort by total

	$query = "
		WITH customer_total_t AS(
			SELECT 
				customerid, sum(total) as customer_total, 
				count(orderid) as times, 
				sum(quantity) as total_quantity
			FROM 
				orders NATURAL JOIN orderlines
			GROUP BY 
				customerid
			)
		SELECT customerid, firstname, lastname, times, total_quantity, customer_total, username
		FROM 
			customers NATURAL JOIN customer_total_t;
	";
	$result = pg_query_params($DBC, $query, array());
	if ($result){
		$customer_list_by_total = pg_fetch_all($result);
	}

//order_list_by_time	
	$result = pg_query_params($DBC,"SELECT 
										date_trunc($1, orderdate) as date_filter, 
										count(distinct orderid) as numberof_order,
										count(distinct customerid) as numberof_customer,
										count(distinct prod_id) as numberof_prod,
										sum(quantity) as quantity_by_time,
										sum(total) as total_by_time 
									FROM orders NATURAL JOIN orderlines
									GROUP BY date_filter", array("day"));

	if ($result){
		$order_list_by_time = pg_fetch_all($result);
	}

//FINISH
pg_close($DBC);
