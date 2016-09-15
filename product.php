<?php 
	$page_title = "Product";
	session_start();
	include('includes/header.inc.html');
	$prod_id = $_GET['id'];
?>
<body>
	<div class="container">
		<div class="row">
			<div class="box">
				<div class="col-lg-6">
					<!-- intro-text -->
					<hr>
					<h2 class="intro-text text-center">
						<strong>
							<?php 
								include('includes/sql_connection.inc.php');
								$result = pg_query("SELECT * FROM products WHERE prod_id=$prod_id");
								if ($result){
									$row = pg_fetch_array($result);

									//product's information
									$category = $row['category'];
									$name = $row['name'];
									$price = $row['price'];
									$price = round($price);
									$describe = $row['description'];
									$image_name = $row['image'];
									$avg_rating = $row['avg_rating'];

									//fetch category's 
									$result = pg_query("SELECT * FROM categories WHERE ctg_id=$category");
									if ($result){
										$row = pg_fetch_array($result);
										echo $row['category'];
									} //if
								} //if
							?> <!-- end of php -->
						</strong>
					</h2>
					<hr>

						<div class="row text-center">
							<h4>
								<?php echo $name ?>
							</h4>
						</div> <!-- end of name -->

						<br>
					
						<div class="row text-center">
							<img src="<?php echo 'uploads/'.$image_name ?>" alt="#" width="400px">
						</div> <!-- end of image row -->

						<div class="row text-center" >
							<p>
								<?php echo $describe ?>
							</p>
						</div> <!-- end of describe row -->

						<?php
							$i = 0;
							echo '
								<div class="row text-center">';
									if($avg_rating == 0) {
											echo '<i class="glyphicon glyphicon-star-empty gi-2x"></i><i class="glyphicon glyphicon-star-empty gi-2x"></i><i class="glyphicon glyphicon-star-empty gi-2x"></i><i class="glyphicon glyphicon-star-empty gi-2x"></i><i class="glyphicon glyphicon-star-empty gi-2x"></i>';
									} else {
										for($i = 0; $i < $avg_rating; $i++) {
											echo '<i class="glyphicon glyphicon-star gi-2x"></i>';
										}
										while($i<5) {
											echo '<i class="glyphicon glyphicon-star-empty gi-2x"></i>';
											$i++;
										}
									}
									echo '</div>';
						?>

					<div class="row text-center">
						<h5 class="price">
							<strong><?php echo $price ?> <sup>đ</sup></strong>
						</h5>
					</div>
					

					<!-- functions -->
          <?php
					echo '<div class="row text-center">
									<div class="form-group item-wrapper" id="form-'.$prod_id.'">
						    		<label class="title">Số Lượng:</label>
								    <input type="hidden" class="item-id" value="' . $prod_id . '" />
								    <input type="hidden" class="item-name" value="' . $name . '" />
								    <input type="hidden" class="item-price" value="' . $price . '" />
							    	<input type="number" class="form-control item-quantity text-center" placeholder="0">
								    <button class="btn-default item-send-button-'.$prod_id.'"> Send </button>
               		</div>
									<button class="select-button btn-success btn-lg" value="'. $prod_id .'"> Chọn </button>
									<div id="test_alert-'.$prod_id.'" class="alert alert-success" style="display: none;"></div>
              	</div>'
          ?>
			</div> <!-- end of col -->
					
				
				<!--comment +rating-->
				<?php
				echo '<div class="col-lg-6">';
					if(isset($_SESSION['user_name'])) {
						echo '
						
					<div class="box comment-rating">
						
						<div class="row text-center">
							Rating:  	
		        	<input type="number" data-max="5" data-min="1" name="your_awesome_parameter" id="star_id" 
		        	class="rating" data-empty-value="0"
		        	data-active-icon="glyphicon glyphicon-star" data-inactive-icon="glyphicon glyphicon-star-empty"
		        	/>
	        	</div>

	          <strong class="row text-center"><h4> Comment </h4></strong>
	          <div class="col-lg-12">
	          	<textarea class="form-control" id="comment_text" rows="3" placeholder="Your Comment Goes Here"></textarea>
	          </div>

	          <div class="row col-lg-12">
        		<div class="text-right"><button type="submit" class="btn btn-success" id="comment-submit" value="'.$prod_id.'">Submit</button></div>
          </div>
          </div>
        	

					
          
          <div id="alert_comment" class="alert alert-success" style="display: none;">
         	
				</div> <!-- end comment +rating-->';
					} 

						//box comment
						echo '<div class="box"> <!--box comment-->';					
								$result = pg_query_params($DBC,"select * from comments where prod_id=$1", array($prod_id));
								if(!$result) {
									echo "query error";
								} else {
									while($row = pg_fetch_array($result)) {
										$star = $row['rating'];
										if(!isset($row['rating'])) {
											$star = 0;
										}
										$comment = $row['comment'];
										$customerid = $row['customerid'];
										$result_tmp = pg_query_params($DBC,"select firstname ||' '|| lastname as name_tmp from customers where customerid = $1", array($customerid));
										$row_tmp = pg_fetch_array($result_tmp);
										$name = $row_tmp['name_tmp'];
										
										$i = 0;
										echo '
											<div class="well box-shadow--3dp roundborders">
												<div class="row">';
													echo '<h6><strong>'.$name.': </strong><h6>';
													if($star == 0) {
															echo '<div class="row text-center"><i class="glyphicon glyphicon-star-empty gi-2x"></i><i class="glyphicon glyphicon-star-empty gi-2x"></i><i class="glyphicon glyphicon-star-empty gi-2x"></i><i class="glyphicon glyphicon-star-empty gi-2x"></i><i class="glyphicon glyphicon-star-empty gi-2x"></i></div>';
													} else {
														echo '<div class="row text-center">';
														for($i = 0; $i < $star; $i++) {
															echo '<i class="glyphicon glyphicon-star gi-2x"></i>';
														}
														while($i<5) {
															echo '<i class="glyphicon glyphicon-star-empty gi-2x"></i>';
															$i++;
														}
														echo '</div>';
													}  // end show-star
													echo '
												</div> 
												<div class="row"><p>'.$comment.'</p></div>
											</div>
										';
									}
						echo '</div> <!--end box comment-->
						</div>';
						//end box comment
					}
				?>
				<!--end box right-->
			</div><!-- end of box -->
		</div><!--  end of row -->
	</div><!--  end of container -->
</body>

<?php 
	include('includes/footer.inc.html');
?>


<script>
$(document).ready(function(){
	$('#comment-submit').click(function(){
		var x = $('#star_id').val();
		var	 y = $('#comment_text').val();
		var z = $(this).val();
		console.log(x+y+z);
		$.post("ajax.php",{'rating': x, 'comment': y, 'product_id': z}, function (response){
			$('.comment-rating').hide();
			$('#comment-submit').hide();
			$('#alert_comment').html(response).show();
		});
	});
});
	
</script>
