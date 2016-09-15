<?php
	require_once('includes/sql_connection.inc.php');

	function sex_ratio_ft(){
		global $DBC;
		$result = pg_query_params($DBC, "
										WITH men_number_tb AS(
SELECT count(customerid) AS men_number FROM customers WHERE sex='M' AND user_group_id=2
)
SELECT round(avg(men_number) / count(customerid), 2) FROM customers, men_number_tb WHERE user_group_id=2

										",
										array());
		$row = pg_fetch_array($result);
		echo $row[0];
	}

	if ($_POST['action'] = 'sex_ratio'){
		sex_ratio_ft();
	}