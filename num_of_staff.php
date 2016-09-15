<?php 
	require_once('includes/sql_connection.inc.php');

	$result = pg_query_params($DBC, "SELECT count(customerid) FROM customers WHERE user_group_id=$1", array(1));
	$row = pg_fetch_array($result);
	echo $row[0];
	