<body>
	<!-- HEADER -->
	<?php 
	ob_start();
	session_start();
	$page_title = 'Edit a product';
	include('includes/header.inc.html');
	?>
	
	<!-- BODY -->	

	<div class="container">

		<div class="row"> 
			<div class="box">
				<hr>
				<h2 class="intro-text text-center">
					<strong>Edit a product</strong>
				</h2>
			
				<hr>

				<!-- if submitted, insert data to database -->
				<?php
					if((isset($_SESSION['user_name'])) && ($_SESSION['login_role'] == 'admin')) {
						if(isset($_GET['pid'])) {
							$prod_id = $_GET['pid'];
						} else {
							header("Location:index.php");
						}
					} else {
						header("Location:index.php");
					}

					require('includes/sql_connection.inc.php');
					$result = pg_query_params($DBC,"select * from products where prod_id=$1",array($prod_id));
					if(!$result) {
						echo "error get products";
					}	 else {
						$row = pg_fetch_array($result);
						//echo $_SERVER['PHP_SELF'].'?pid='.$prod_id ;
						//var_dump($row);
						$name = $row['name'];
						$price = $row['price'];
						$category = $row['category'];
						$describe = $row['description'];
						$image_name = $row['image'];
					}
					

					if ($_SERVER['REQUEST_METHOD'] == 'POST'){
						$uploaddir = "D:/Apache/apache/www/template_new/uploads/";
						$uploadfile = $uploaddir.$_FILES["image"]["name"];
						if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadfile)){
							if(!empty($_POST['name'])){
								$name = $_POST['name'];
							}
							if(!empty($_POST['category'])){
								$category = $_POST['category'];
							}
							if(!empty($_POST['price'])){
								$price = $_POST['price'];
							}
							if(!empty($_POST['describe'])){
								$describe = $_POST['describe'];
							}
							if(!empty($_FILES['image']["name"])){
								$image_name = $_FILES['image']["name"];
							}
							
							$query = array($category, $name, $price, $describe, $image_name,$prod_id);
							print_r($query);
							$result = pg_query_params(
							$DBC,
							"UPDATE products
							set (category, name, price, description, image) 
							 = ($1, $2, $3, $4, $5) where prod_id= $6", $query);

							if ($result){
								echo "<br>Update successfully!";
							} else {
								echo "query error";
							}
						} else{
							echo 'file is too big';
						}
						pg_close($DBC);
					}
				?>

				<!-- form -->
				<form action="<?php echo $_SERVER['PHP_SELF'].'?pid='.$prod_id ;?>" method="POST" enctype="multipart/form-data">

					<div class="row">
						<div class="form-group text-center col-md-4 col-md-offset-4">
							<label for="name">
								<h5><strong>Name <span class="glyphicon glyphicon-glass"></span></strong></h5>
							</label>
							<input type="text" class="form-control" name="name" placeholder="<?php echo $name; ?>">
						</div> <!-- name -->
					</div> <!-- end of row -->

					<div class="row">
						<div class="form-group text-center col-md-4 col-md-offset-4">
							<label for="category"><h5><strong>Category <span class="glyphicon glyphicon-list-alt"></span></strong></h5></label>
							<select name="category" class="form-control">
								<?php 
								$result = pg_query($DBC, "SELECT * FROM categories");
								while ($row = pg_fetch_array($result)){
									if($row[0] == $category) {
										echo '<option value='.$row[0].' default selected>'.$row[1].'</option>';
									} else {
										echo '<option value='.$row[0].'>'.$row[1].'</option>';
									}
								}
								?>
							</select>
						</div>
					</div>

					<div class="row">
						<div class="form-group text-center col-md-4 col-md-offset-4">
							<label for="price"><h5><strong>Price <span class="glyphicon glyphicon-euro"></span></strong></h5></label>
							<input type="text" class="form-control" name="price" placeholder="<?php echo $price;?>">
						</div> <!-- name -->
					</div>

					<div class="row">
						<div class="form-group text-center col-md-4 col-md-offset-4">
							<label for="describe"><h5><strong>Describe <span class="glyphicon glyphicon-edit"></span></strong></h5></label>
							<textarea class="form-control" rows="5" name="describe" placeholder="<?php echo $describe; ?>"></textarea>
						</div> <!-- name -->
					</div>

					<div class="row">
						<div class="form-group text-center col-md-4 col-md-offset-4">
							<label for="describe"><h5><strong>Image <span class="glyphicon glyphicon-picture"></span></strong></h5></label>
							<input type="file" name="image" placeholder="<?php echo $image_name;?>">
						</div> <!-- name -->
					</div>

					<div class="row">
						<div class="col-md-4 col-md-offset-4">
							<button type="submit" class="btn btn-default" id="submit-btn">Submit</button>
						</div>
					</div>
				</form>
			</div> <!-- end of box -->

		</div> <!-- end of row -->

	</div><!--  end of container -->
	

	<!-- FOOTER -->
	<?php 
	include('includes/footer.inc.html');
	?>	
</body>

