<?php 
	ob_start();
	session_start();
	include('includes/header_simple.inc.html');

	if (isset($_SESSION['user_name'])){
		echo '<h1>OK</h1>';
		$success_login = TRUE;

		require('includes/sql_connection.inc.php');
		$username = $_SESSION['user_name'];
		$password = $_SESSION['password'];
		$result = pg_query_params($DBC, "SELECT user_group_id, firstname, lastname, position, customerid FROM customers WHERE username=$1 AND pass=$2", array($username, $password));
		if ($result){
			$row=pg_fetch_array($result);
			$login_role = $row['user_group_id'];

			switch ($login_role) {
				case 0:
					$_SESSION['login_role'] = "admin";
					break;
				case 1:
					$_SESSION['login_role'] = "staff";
					$_SESSION['position'] = $row['position'];
					break;
				case 2:
					$_SESSION['login_role'] = "user";
		            $_SESSION["orderlines"] = array();
		            $_SESSION["count_orderlines"] = 0;
					break;
			} //end switch
			$_SESSION['id'] = $row['customerid'];
			$name = $row['firstname'].' '.$row['lastname'];

		} else {
			echo '<h1>Cannot fetch result</h1>';
		}
		pg_close($DBC);

	} else {
		$success_login = FALSE;
	}

?>

<body>
	<div class="container">
		<div class="box">
			<div class="row welcome">
				<div class="col-md-offset-3 col-md-6">

					<h1 class="intro-text text-center">
						<hr>
						<?php 
							if ($success_login){
								echo 'Welcome, <strong id="username">'.$name.'</strong>';
							} else{
								echo 'Login failed';
								header("refresh:5; url=index.php");
								exit();
							}
						?>
						<hr>
					</h1>

				</div> <!-- end col -->

			</div> <!-- end row -->

			<div class="row name">
				<p class="text-center">
					<?php  
						if ($success_login){
							echo 'You are now logged in as ';
						}
				
						switch ($login_role) {
							case 0:
								echo 'an <strong>Administrator</strong> ';
								echo 'with Username: <em id="username">'.$username.'</em>';
								echo '<br>';
								echo '<a href="admin.php"><button class="btn btn-success">Go to Administrator\' page</button></a>';
								break;
							case 1:
								echo 'an <strong>Employee</strong> ';
								echo 'with Username: <em id="username">'.$username.'</em>.';
								echo '<br>';
								echo '<a href="staff.php"><button class="btn btn-success">Go to Employee\' page</button></a>';
								break;
							case 2:
								echo 'a <strong>Customer</strong> ';
								echo 'with Username: <em id="username">'.$username.'</em>.';
								echo '<br>';
								echo '<a href="index.php"><button class="btn btn-success">Go to Homepage</button></a>';
								break;
							default:
								break;
						} //end switch
					?>
				
				</p> <!-- end p -->

			</div><!--  end row -->

		</div> <!-- end box -->

	</div> <!-- end container -->
</body>

<?php include('includes/footer.inc.html'); ?>