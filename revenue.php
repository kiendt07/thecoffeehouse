<?php 
	require_once('includes/sql_connection.inc.php');



	function order_list_by_time($filter){	
		//require_once('includes/sql_connection.inc.php');
		global $DBC;
		$result = pg_query_params($DBC,"SELECT 
											date_trunc($1, orderdate) as date_filter, 
											count(distinct orderid) as numberof_order,
											count(distinct customerid) as numberof_customer,
											count(distinct prod_id) as numberof_prod,
											sum(quantity) as quantity_by_time,
											sum(total) as total_by_time 
										FROM orders NATURAL JOIN orderlines
										GROUP BY date_filter
										ORDER BY total_by_time DESC", array($filter));

		if ($result){
			$order_list_by_time = pg_fetch_all($result);
			switch ($filter) {
				case 'day':
					$format = 'D d/M/Y';
					break;
				case 'week':
					$format = 'W M/Y';
					break;
				case 'month':
					$format = 'M/Y';
					break;
				case 'year':
					$format = 'Y';
					break;
				default:				
					break;
			}

			for ($i=0; $i<count($order_list_by_time); $i++){
	                                            echo '<tr>
	                                                    <td>'.date($format, strtotime($order_list_by_time[$i]['date_filter'])).'</td>
	                                                    <td>'.$order_list_by_time[$i]['numberof_order'].'</td>
	                                                    <td>'.$order_list_by_time[$i]['numberof_customer'].'</td>
	                                                    <td>'.$order_list_by_time[$i]['numberof_prod'].'</td>                                                    
	                                                    <td>'.$order_list_by_time[$i]['quantity_by_time'].'</td>
	                                                    <td style="font-weight: bold;" class="price">'.round($order_list_by_time[$i]['total_by_time']).'</td>
	                                                </tr>';
	                                        }                                      
		}
	}
	//END FUNCTION

	//MAIN	
	if ($_POST['action']="order_list_by_time"){
		$filter = $_POST['time_filter'];
		order_list_by_time($filter);
	}
	//echo json_encode($order_list_by_time);
	pg_close($DBC);