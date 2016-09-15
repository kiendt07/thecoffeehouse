<!DOCTYPE html>
<html lang="en">


<body>


    <?php
        session_start();
        include("includes/header.inc.html");
        require('includes/sql_connection.inc.php');
    ?>

<div class="container">
	<div class="row">
	    <div class="box text-center">
		 	<div class="clock" style="margin:2em;"></div>
		    <div class="message"></div>

		    <div class="row">
		    	<button id="start-<?php echo $_SESSION['time']; ?>" class="btn btn-primary start-button">In</button>
			</div>
			<div class="row">
				<button id="stop--<?php echo $_SESSION['time']; ?>" class="btn btn-danger stop-button">Out</button>
			</div>
		</div>
	</div>
</div>



    <?php include("includes/footer.inc.html"); ?>
</body>
