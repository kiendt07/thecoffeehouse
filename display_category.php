<?php
	ob_start();
	session_start();
	$page_title='Category';
	include('includes/header.inc.html');
	require('includes/sql_connection.inc.php');
	$active_id = $_GET['id'];
	$result = pg_query($DBC, "SELECT * FROM categories WHERE ctg_id=$active_id");
	$row = pg_fetch_array($result);
	$active_category = $row['category'];
	pg_close($DBC);
	if(!isset($_SESSION["orderlines"])) {
		$_SESSION["orderlines"] = array();
	}
?>






<body>
	<div class="container">
		<div class="row">
			<div class="box">

				<div class="row">
					<hr>
					<h2 class="intro-text text-center">
						<strong>
							Categories
						</strong>
					</h2>
				</div> <!-- end of row -->

<!-- 				<div class="row">
					<h3 class="text-center">
						Select a category
					</h3>
				</div> end of row -->

				<div class="row">
					<?php 
						require('includes/sql_connection.inc.php');
						$result = pg_query($DBC, "SELECT * FROM categories ORDER BY ctg_id ASC");
						while ($row = pg_fetch_array($result)){
							$id = $row['ctg_id'];
							$category = $row['category'];
							echo 
								'<div class="col-lg-3 text-center">
									<a href="display_category.php?id='.$id.'">
										<h5>
										'.$category.'
										</h5>
									</a>
								</div>';
						}
						pg_close($DBC);
					?>
				</div>

				<div class="row">
					<hr>
					<h3 class="intro-text text-center">
						<?php echo $active_category ?>
					</h3>
				</div> <!-- end of row -->

				<div class="row">
					<?php
						require('includes/sql_connection.inc.php');

						if (isset($_GET['id'])){
							$result=pg_query($DBC, "SELECT * from products WHERE category=$active_id ORDER BY prod_id ASC");
							while ($row=pg_fetch_array($result)){
								$id=$row['prod_id'];
								$name = $row['name'];
								$price = $row['price'];
								$price = round($price);
								$description = $row['description'];
								$image = $row['image'];
								$avg_rating=$row['avg_rating'];
								echo 
						'<div class="col-lg-4 feature text-center">

									<div class="row">
										<div class="col-lg-12">
											<h4>
												'.$name.'							
											</h4>	
											<hr>
										</div>
									</div>
									
									<div class="row feature">
										<div class="col-lg-12">
											<img src="uploads/'.$image.'" alt="#" width="250px">
											<p>Price: '.$price.'<sup>đ</sup></p>
										</div>
									</div>';


								$i = 0; // stars
								echo '
									<div class="row text-center">';
										if($avg_rating == 0) {
												echo '<div class="row text-center"><i class="glyphicon glyphicon-star-empty gi-2x"></i><i class="glyphicon glyphicon-star-empty gi-2x"></i><i class="glyphicon glyphicon-star-empty gi-2x"></i><i class="glyphicon glyphicon-star-empty gi-2x"></i><i class="glyphicon glyphicon-star-empty gi-2x"></i></div>';
										} else {
											for($i = 0; $i < $avg_rating; $i++) {
												echo '<i class="glyphicon glyphicon-star gi-2x"></i>';
											}
											while($i<5) {
												echo '<i class="glyphicon glyphicon-star-empty gi-2x"></i>';
												$i++;
											}
										}
								echo '</div>'; // end stars

								echo	'<div class="row">
                        
                        <a href="product.php?id='.$id.'" class="btn btn-default">Chi tiết</a>
												<div class="form-group item-wrapper" id="form-'.$id.'">
											    <label class="title">Số Lượng:</label>
											    <input type="hidden" class="item-id" value="' . $id . '" />
											    <input type="hidden" class="item-name" value="' . $name . '" />
											    <input type="number" class="form-control item-quantity text-center" placeholder="0">
											    <button class="item-send-button-'.$id.'"> Send </button>
									   	  </div>
												<button class="btn-success btn-lg select-button" value="'. $id .'"> Select </button>
												<div id="test_alert-'.$id.'" class="alert alert-success" style="display: none;"></div>
											</div>
						</div>';

							} //end of while
						

							pg_close($DBC);

						} else {
							$result=pg_query($DBC, "SELECT * from products ORDER BY prod_id ASC");

							while ($row=pg_fetch_array($result)){
								$id=$row['prod_id'];
								$name = $row['name'];
								$price = $row['price'];
								$price = round($price);
								$description = $row['description'];
								$image = $row['image'];
								$avg_rating = $row['avg_rating'];
								echo 
					'<div class="col-lg-4 feature text-center">

									<div class="row">
										<div class="col-lg-12">
											<h4>
												'.$name.'							
											</h4>	
											<hr>
										</div>
									</div>

									<div class="row feature">
										<div class="col-lg-12">
											<img src="uploads/'.$image.'" alt="#" width="250px">
											<p>Price: '.$price.'<sup>đ</sup></p>
										</div>
									</div>';

									$i = 0; //stars
									echo '
									<div class="row text-center">';
										if($avg_rating == 0) {
												echo '<div class="row text-center"><i class="glyphicon glyphicon-star-empty gi-2x"></i><i class="glyphicon glyphicon-star-empty gi-2x"></i><i class="glyphicon glyphicon-star-empty gi-2x"></i><i class="glyphicon glyphicon-star-empty gi-2x"></i><i class="glyphicon glyphicon-star-empty gi-2x"></i></div>';
										} else {
											for($i = 0; $i < $avg_rating; $i++) {
												echo '<i class="glyphicon glyphicon-star gi-2x"></i>';
											}
											while($i<5) {
												echo '<i class="glyphicon glyphicon-star-empty gi-2x"></i>';
												$i++;
											}
										}
									echo '</div>'; //end stars

									echo '<div class="row">
	                        <a href="product.php?id='.$id.'" class="btn btn-default">Chi tiết</a>
													<div class="form-group item-wrapper" id="form-'.$id.'">
												    <label class="title">Số Lượng:</label>
												    <input type="hidden" class="item-id" value="' . $id . '" />
												    <input type="hidden" class="item-name" value="' . $name . '" />
												    <input type="hidden" class="item-price" value="' . $price . '" />
												    <input type="number" class="text-center form-control item-quantity" placeholder="0">
												    <button class="btn-default item-send-button-'.$id.'"> Send </button>	
												  </div>
													<button class="select-button btn-success btn-lg" value="'. $id .'"> Chọn </button>
													<div id="test_alert-'.$id.'" class="alert alert-success" style="display: none;"></div>
												</div>
					</div>';
							} //end of while
						} //end of else

					?> <!-- end of php code -->
				</div> <!-- end of row -->

			</div> <!-- end of box -->

		</div> <!-- end of row -->
			
	</div> <!-- end of container -->

</body>

<?php 
	include('includes/footer.inc.html');
?>

