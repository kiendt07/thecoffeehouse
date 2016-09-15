<body>
	<!-- HEADER -->
	<?php 
	$page_title = 'Add a product';
	include('includes/header.inc.html');
	?>
	
	<!-- BODY -->	

	<div class="container">

		<div class="row"> 
			<div class="box">
				<hr>
				<h2 class="intro-text text-center">
					<strong>Add a product</strong>
				</h2>
				<h5 class="text-center">
					with name, price and describe...
				</h5>
				<hr>

				<!-- if submitted, insert data to database -->
				<?php  
					if ($_SERVER['REQUEST_METHOD'] == 'POST'){
						$uploaddir = "D:/Apache/apache/www/template_new/uploads/";
						$uploadfile = $uploaddir.$_FILES["image"]["name"];
						if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadfile)){
							$name = $_POST['name'];
							$category = $_POST['category'];
							$price = $_POST['price'];
							$describe = $_POST['describe'];
							$image_name = $_FILES['image']["name"];

							require('includes/sql_connection.inc.php');
							$query = array($category, $name, $price, $describe, $image_name);
							//print_r($query);
							$result = pg_query_params(
							$DBC,
							"INSERT INTO products(category, name, price, description, image) 
							 VALUES ($1, $2, $3, $4, $5)", $query);

							if ($result){
								echo "<br>Insert successfully!";
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
				<form action="add_product.php" method="POST" enctype="multipart/form-data">

					<div class="row">
						<div class="form-group text-center col-md-4 col-md-offset-4">
							<label for="name">
								<h5><strong>Name <span class="glyphicon glyphicon-glass"></span></strong></h5>

							</label>
							<input type="text" class="form-control" name="name">
						</div> <!-- name -->
					</div> <!-- end of row -->

					<div class="row">
						<div class="form-group text-center col-md-4 col-md-offset-4">
							<label for="category"><h5><strong>Category <span class="glyphicon glyphicon-list-alt"></span></strong></h5></label>
							<select name="category" class="form-control">
								<?php 
								require('includes/sql_connection.inc.php');
								$result = pg_query($DBC, "SELECT * FROM categories");
								while ($row = pg_fetch_array($result)){
									echo '<option value='.$row[0].'>'.$row[1].'</option>';
								}
								?>
							</select>
						</div>
					</div>

					<div class="row">
						<div class="form-group text-center col-md-4 col-md-offset-4">
							<label for="price"><h5><strong>Price <span class="glyphicon glyphicon-euro"></span></strong></h5></label>
							<input type="text" class="form-control" name="price">
						</div> <!-- name -->
					</div>

					<div class="row">
						<div class="form-group text-center col-md-4 col-md-offset-4">
							<label for="describe"><h5><strong>Describe <span class="glyphicon glyphicon-edit"></span></strong></h5></label>
							<textarea class="form-control" rows="5" name="describe"></textarea>
						</div> <!-- name -->
					</div>

					<div class="row">
						<div class="form-group text-center col-md-4 col-md-offset-4">
							<label for="describe"><h5><strong>Image <span class="glyphicon glyphicon-picture"></span></strong></h5></label>
							<input type="file" name="image">
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

