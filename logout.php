<?php 
	$page_title = 'Log out';
	ob_start();
	session_start();
	$page_title = "LogOut";
	include('includes/header.inc.html');

	if(isset($_COOKIES[session_name()])) {
		setcookie(session_name(), time()-3600,'/',0,0);
	}
?>

<body>
	<section>
		<div class="container">
			<div class="box">
				<div class="row">
					<div class="col-md-6 col-md-offset-3">
						<h3 class="text-center">
							<?php 
								if(isset($_SESSION["user_name"])) {
									echo '<strong id="username">'.$_SESSION["user_name"].'</strong>';
									session_destroy();
									echo "<br>You're now logged out in a moment";
									header("refresh:3; url=index.php");
								} else {
									header("Location: index.php");
								}
							?>
						</h3>
					</div>
				</div>
			</div>
		</div>
	</section>
</body>

<?php include('includes/footer.inc.html'); ?>