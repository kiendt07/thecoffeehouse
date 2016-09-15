<?php 
	$page_title = 'Edit an user';
	ob_start();
	session_start();

	//This must be update
	if ((isset($_SESSION['login_role'])) && ($_SESSION['login_role'] == 'admin')){
		include('includes/header_admin.inc.html');
	} else {
		include('includes/header.inc.html');
	}
	//

	
	if (!isset($_SESSION['user_name'])){
		header("Location: index.php");
		exit();
	}

	if (isset($_GET['id'])){
		$id=$_GET['id'];
	} else {
		header("Location: index.php");
		exit();	
	}

	require('includes/sql_connection.inc.php');
	$result = pg_query($DBC, "SELECT * FROM customers WHERE customerid=$id");
	$row = pg_fetch_array($result);
	$username = $row['username'];
	if (($_SESSION['login_role'] != 'admin') && ($_SESSION['user_name'] != $username)){
		header("Location: index.php");
		exit();
	}
?>

<body>
	<div class="container">
		<div class="row">
			<div class="box">
				<div class="row text-center">

					<!-- get user info -->
					<?php 
						if (!isset($_GET['id'])){
							echo '<div class="alert alert-warning" role="alert">
										<strong>Warning</strong>
										Please choose an user to edit
								</div>
';
							header("refresh: 3;url=admin.php");
							exit();
						}

						$id=$_GET['id']; 
						require('includes/sql_connection.inc.php');
						$result = pg_query("SELECT * FROM customers WHERE customerid=$id");
						if ($result){
							$row = pg_fetch_array($result);
							$firstname = $row['firstname'];
							$lastname = $row['lastname'];
							$gender = $row['sex'];
							$address1 = $row['address1'];
							$address2 = $row['address2'];
							$email = $row['email'];
							$phone = $row['phone'];
							$username = $row['username'];
							$user_group_id = $row['user_group_id'];
						}
					?>
					<!-- php -->
					<hr>
					<h1 class="intro-text">
						Edit @<?php echo '<span id="username">'.$username.'</span>'; ?>
						<br>
						<small>
							<?php 
								if ($user_group_id == 2) {
									echo 'Customer\'s account';
								}
								if ($user_group_id == 1) {
									echo 'Employee\'s account';
								}
							?>
						</small>
					</h1>
					<hr>
					
					<!-- update info -->
					<?php
						if ($_SERVER["REQUEST_METHOD"] == "POST") {
							$success=FALSE;
							function test_input($data)
							{
								$data = trim($data);
								$data = stripslashes($data);
								$data = htmlspecialchars($data);
								return $data;
							}

							if (!empty($_POST['firstname']))
								$firstname = test_input($_POST['firstname']);
							else {
								$firstname_err = "Required Firstname";
							}

							if(!empty($_POST['lastname']))
								$lastname = test_input($_POST['lastname']);
							else {
								$lastname_err = "Required Lastname";
							}

							if(!empty($_POST['address1']))
								$address1 = test_input($_POST['address1']);
							else {
								$address1_err = "Required Address";
							}

							if(!empty($_POST['phone']))
								$phone= test_input($_POST['phone']);
							else {
								$phone_err = "Required Phone";
							}

							if (!empty($_POST['gender'])) {
								$gender = test_input($_POST['gender']);
							} else {
								$gender_err = "Required Gender";
							}

							if (!empty($_POST['address2']))
								$address2=$_POST['address2'];

							if (!empty($_POST['email']))
								$email=$_POST['email'];

							$conn = pg_connect("dbname=coffee_house user=postgres password=j");
							if (!$conn) {
								$error = "Database error";
							} else {
								$query = array($firstname, $lastname, $gender, $address1, $address2, $email, $phone, $username, $password);
								if($address2 == NULL) {
									if($email == NULL) {
										$query = array($firstname, $lastname, $gender, $address1, $phone);
										$result = pg_query_params($conn, "update customers set (firstname,lastname,sex,address1,phone) 
											= ($1, $2, $3, $4, $5) where customerid = $id", $query);
										if(!$result) {
											echo "error1";
										} else {
											$success = TRUE;

	                            //header("refresh:3; url=")
										}
									}
									else {
										$query = array($firstname, $lastname, $gender, $address1, $email, $phone);
										$result = pg_query_params($conn, "update customers set (firstname,lastname,sex,address1,email,phone) 
											= ($1, $2, $3, $4, $5, $6) where customerid = $id", $query);
										if(!$result) {
											echo "error2";
										} else {
											$success = TRUE;
										}
									}
								} else {
									if($email == NULL) {
										$query = array($firstname, $lastname, $gender, $address1, $address2, $phone);
										$result = pg_query_params($conn, "update customers set (firstname,lastname,sex,address1,address2,phone) 
											= ($1, $2, $3, $4, $5, $6) where customerid = $id", $query);
										if(!$result) {
											echo "error3";
										} else {
											$success = TRUE;
										}
									}
									else {
										$query = array($firstname, $lastname, $gender, $address1, $address2, $email, $phone);
										$result = pg_query_params($conn, "update customers set (firstname,lastname,sex,address1,address2,email,phone) 
											= ($1, $2, $3, $4, $5, $6, $7) where customerid = $id", $query);
										if(!$result) {
											echo "error4";
										} else {
											$success = TRUE;
										}
									}
								}
							}
							pg_close($conn);
						} //end if
					?>
					<!-- end php -->
				</div> <!-- .row -->

				<?php 
					if ($success){
						echo '<div class="row text-center">
								<h3>
									<span class="label label-success">Update successfully</span>
								</h3>
							</div>';
					}
				?>

				<div class="row text-center">
					<div class="page-header">
						<h3 style="text-transform: none">Input user information to the form bellow</h3>
						<br>
						<small style="color: #e74c3c; font-size: 1.25em">* Required field</small>
					</div>
				</div> <!-- .row -->

				<div class="row">
					<div class="col-md-8 col-md-offset-3">
						<form class="form-horizontal" role="form" method="POST" action="edit_user.php?id=<?php echo $id;?>">

							<div class="form-group">
								<label for="firstname" class="title col-sm-2 control-label">First name</label>
								<div class="col-sm-6">
									<input type="text" placeholder="<?php echo $firstname ?>" class="form-control" id="firstname" name="firstname" placeholder="First name">
								</div>
								
							</div>

							<div class="form-group">
								<label for="lastname" class="title col-sm-2 control-label">Last name</label>
								<div class="col-sm-6">
									<input type="text" placeholder="<?php echo $lastname ?>" class="form-control" id="lastname" name="lastname" placeholder="Last name">
								</div>
								
							</div>

							<div class="form-group">
								<label for="gender" class="title col-sm-2 control-label">Gender</label>
								<div class="col-sm-10">
									<input <?php if ($gender=='M') echo 'checked'; ?> type="radio" name="gender" id="male" value="M">
									<label for="male">Male</label>
									<input <?php if ($gender=='F') echo 'checked'; ?> type="radio" name="gender" id="female" value="F">
									<label for="female">Female</label>
								</div>
							</div>

							<div class="form-group">
								<label for="address1" class="title col-sm-2 control-label">Address</label>
								<div class="col-sm-6">
									<input type="text" placeholder="<?php echo $address1 ?>" class="form-control" id="address1" name="address1">
								</div>
								
							</div>

							<div class="form-group">
								<label for="address2" class="title col-sm-2 control-label">Company</label>
								<div class="col-sm-6">
									<input type="text" placeholder="<?php echo $address2 ?>" class="form-control" id="address2" name="address2">
								</div>
							</div>

							<div class="form-group">
								<label for="email" class="title col-sm-2 control-label">Email</label>
								<div class="col-sm-6">
									<input type="email" placeholder="<?php echo $email ?>" class="form-control" id="email" name="email">
								</div>
							</div>

							<div class="form-group">
								<label for="phone" class="title col-sm-2 control-label">Phone</label>
								<div class="col-sm-6">
									<input type="text" placeholder="<?php echo $phone ?>" class="form-control" id="phone" name="phone">
								</div>
								
							</div>

							<div class="form-group">
								<div class="col-sm-10 col-sm-offset-2">
									<button type="submit" class="btn btn-default">Update</button>
								</div>
							</div>
						</form>
					</div> <!-- .col -->
				</div> <!-- .row -->


			</div> <!-- .box -->
		</div> <!-- .row -->
	</div> <!-- end of container -->
</body>

<?php include('includes/footer.inc.html'); ?>