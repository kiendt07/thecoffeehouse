<?php
	ob_start();
	session_start();
	if(isset($_POST['item-delete-index']) && isset($_SESSION['orderlines'])) {
		$orderlines = $_SESSION['orderlines'];
		$itemIndex = $_POST['item-delete-index'];
		unset($orderlines[$itemIndex]);
		$orderlines = array_values($orderlines);
		$_SESSION['orderlines'] = $orderlines;
		//echo count($_POST['item-delete-index']);
	}
?>