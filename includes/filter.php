<?php 
	require('sql_connection.inc.php');
	if (isset($_POST['time_filter'])){
		$filter = $_POST['time_filter'];
	} else {
		$filter = 'day';
	}
	$result = pg_query_params($DBC,"SELECT 
										date_trunc($1, orderdate) as date_filter, 
										count(distinct orderid) as numberof_order,
										count(distinct customerid) as numberof_customer,
										count(distinct prod_id) as numberof_prod,
										sum(quantity) as quantity_by_time,
										sum(total) as total_by_time 
									FROM orders NATURAL JOIN orderlines
									GROUP BY date_filter", array($filter));

	if ($result){
		$order_list_by_time = pg_fetch_all($result);
	}

//FINISH
pg_close($DBC);
