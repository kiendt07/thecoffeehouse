<?php
	require_once('includes/sql_connection.inc.php');

	function num_of_customer_ft(){
		global $DBC;
		$result = pg_query_params($DBC, "SELECT count(customerid) FROM customers WHERE user_group_id = $1", array(2));
		$row = pg_fetch_array($result);
		echo $row[0];
	}

	if ($_POST['action'] = 'num_of_customer'){
		num_of_customer_ft();
	}

	// if ($_POST['action'] = 'sex_ratio'){
	// 	sex_ratio_ft();
	// }