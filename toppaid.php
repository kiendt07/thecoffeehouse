<?php 
	require('includes/sql_connection.inc.php');
	$result = pg_query_params($DBC, "with customer_static_tb as(
										select customerid, sum(total) as customer_total_paid, count(distinct orderid) as customer_num_order, sum(quantity) as customer_total_prod, count(distinct prod_id) as customer_num_prod from orders natural join orderlines group by customerid)
										select customerid, username, firstname, lastname, customer_total_paid, customer_num_order, customer_total_prod, customer_num_prod from customer_static_tb natural join customers
										ORDER BY customer_total_paid DESC", array());
	while ($row=pg_fetch_array($result)){
		echo '<tr>
				<td id="username">@'.$row['username'].'</td>
				<td>'.$row['firstname'].' '.$row['lastname'].'</td>
				<td class="price">'.round($row['customer_total_paid']).'</td>
				<td>'.$row['customer_num_order'].'</td>
				<td>'.$row['customer_total_prod'].'</td>
				<td>'.$row['customer_num_prod'].'</td>
		</tr>';
	}