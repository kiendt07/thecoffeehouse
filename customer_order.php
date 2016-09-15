<?php 
	$page_title = "Customer's Orders";
	require_once('includes/sql_connection.inc.php');
	require_once('includes/data_init.php');

	function customer_order_ft($id){
		global $DBC;
		$result = pg_query_params($DBC, "SELECT * FROM orders WHERE customerid=$1 ORDER BY orderid DESC", array($id));		
		$order_list = pg_fetch_all($result);
		if ($result){
			for ($i=0; $i<count($order_list); $i++){
                $order_list[$i]['employeeid'] = ($order_list[$i]['employeeid'] == -1 ? NULL : $order_list[$i]['employeeid']);
                echo '<tr>
                        <td>'.$order_list[$i]['orderid'].'</td>
                        <td>'.$order_list[$i]['customerid'].'</td>
                        <td>'.$order_list[$i]['employeeid'].'</td>
                        <td>'.$order_list[$i]['orderdate'].'</td>
                        <td>'.DELIVER_TYPE[$order_list[$i]['delivertype']].'</td>
                        <td>'.$order_list[$i]['destination'].'</td>
                        <td>'.STATUS[$order_list[$i]['status']].'</td>
                        <td>'.$order_list[$i]['total'].'</td>
                    </tr>';
            }
		}
	}

	if (isset($_POST['id'])){
		customer_order_ft($_POST['id']);
	}

	pg_close($DBC);