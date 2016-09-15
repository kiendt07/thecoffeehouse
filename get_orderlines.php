<?php
ob_start();
session_start();
if(isset($_SESSION['orderlines'])) {
	$orderlines = $_SESSION['orderlines'];
	$count = 1;
	foreach($orderlines as $value){
		$name_tmp = $value['productName'];
		$quantity_tmp = $value['quantity'];
		$price_tmp = $value['productPrice'];
		$price_tmp = round($price_tmp);
		echo '<tr>
            <td class="text-center">'.$count.'</td>
            <td class="text-center">'.$name_tmp.'</td>
            <td class="text-center">'.$quantity_tmp.'</td>
            <td class="text-center">'.$price_tmp.'<sup>đ</sup></td>
            <td><div class="checkbox text-center">
							<input id="check-'. $count .'" class="check-item" type="checkbox" value="">
							</div></td>
      		</tr>';
     $count++;
  }
}
?>