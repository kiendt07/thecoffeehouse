<?php
ob_start();
session_start();
$page_title = "Customer Static";
require('includes/sql_connection.inc.php');
if (!isset($_SESSION['user_name'])){
    header("Location: index.php");
    exit();
}
if(($_SESSION["login_role"] == "user") || ($_SESSION["login_role"] == "staff")) {
    header("Location: index.php");
    exit();
} 
include('includes/header_admin.inc.html');
require_once('includes/statics.php');
?>

<body>
	<div class="container">
		<div class="row">
			<div class="box">

				<div class="row">
                    <hr>
                    <h2 class="intro-text text-center">
                        <strong>Customer statics</strong>
                    </h2>
                    <hr>
                </div> 			
				
				<div class="row">
                  <!-- Nav tabs -->
                    <ul class="nav navbar-nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#list" aria-controls="list" role="tab" data-toggle="tab">List</a></li>
                        <li role="presentation"><a href="#static" aria-controls="static" role="tab" data-toggle="tab">Static</a></li>
                    </ul>

                  <!-- Tab panes -->
	                <div class="tab-content">
	                    <div role="tabpanel" class="tab-pane active" id="list">
	                        <div class="container">
	                            <table id="customer_new" class="table table-hover table-striped text-center">  
	                                <thead>
					                    <tr class="table-title">
					                        <td>#</td>
					                        <td>Name</td>
					                        <td>Sex</td>
					                        <td>Address</td>
					                        <td>Phone</td>
					                        <td>@username</td>
					                        <td>Edit</td>
					                        <td>Delete</td>
					                    </tr>
					                </thead>
	                            
	                                <tbody>  
	                                    <?php 	                                    	
	                                        for ($i=0; $i<count($customer_list); $i++){
	                                            echo '<tr>
	                                                    <td>'.$customer_list[$i]['customerid'].'</td>
	                                                    <td>'.$customer_list[$i]['firstname'].' '.$customer_list[$i]['lastname'].'</td>
	                                                    <td>'.$customer_list[$i]['sex'].'</td>
	                                                    <td>'.$customer_list[$i]['address1'].'</td>
	                                                    <td>'.$customer_list[$i]['phone'].'</td>
	                                                    <td id="username">@'.$customer_list[$i]['username'].'</td>
	                                                    <td><a class="btn btn-default" href="edit_user.php?id='.$customer_list[$i]['customerid'].'">Edit</a></td>	                                                    
	                                                    <td><button class="btn btn-default" id="delete_user" value="'.$customer_list[$i]['customerid'].'"><span style="color: #e74c3c">Delete </span><strong id="username">@'.$customer_list[$i]['username'].'</strong></button></td></td>
	                                                </tr>';
	                                        }
	                                    ?>
	                                </tbody>	                     
	                            </table>
	                        </div> <!-- container -->
	                    </div> <!--  tab-pane -->   

						<!-- Customers listed by total -->
	                    <div role="tabpanel" class="tab-pane" id="static">
	                        <div class="container">
	                            <table id="customer_total" class="table table-hover table-striped text-center">  
	                                <thead>
					                    <tr class="table-title">
					                        <td>#</td>
					                        <td>Name</td>
					                        <td>@username</td>
					                        <td>Times</td>
					                        <td>Product quantity</td>
					                        <!-- <td>Phone</td> -->					                        
					                        <td>Total</td>
					                        <td>Edit</td>
					                    </tr>
					                </thead>
	                            
	                                <tbody>  
	                                    <?php 
	                                        for ($i=0; $i<count($customer_list_by_total); $i++){
	                                            echo '<tr>
	                                                    <td>'.$customer_list_by_total[$i]['customerid'].'</td>
	                                                    <td id="name">'.$customer_list_by_total[$i]['firstname'].' '.$customer_list_by_total[$i]['lastname'].'</td>
	                                                    <td id="username">@'.$customer_list_by_total[$i]['username'].'</td>
	                                                    <td>'.$customer_list_by_total[$i]['times'].'</td>
	                                                    <td>'.$customer_list_by_total[$i]['total_quantity'].'</td>	                                                    	                                                    
														<td><button id="button_customer" type="button" value="'.$customer_list_by_total[$i]['customerid'].'" class="btn btn-primary" data-toggle="modal" data-target=".bs-example-modal-lg">'.round($customer_list_by_total[$i]['customer_total']).'</button></td>	                                                   
	                                                    <td><a class="btn btn-default" href="edit_user.php?id='.$customer_list_by_total[$i]['customerid'].'">Edit</a></td>	                                                    
	                                                </tr>';
	                                        }
	                                    ?>

	                                </tbody>	                     

	                            </table>

	                            <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
	                            	<div class="modal-dialog modal-lg">
	                            		<div class="modal-content">
	                            			<h4 id="name_model"></h4>
	                            			<table class="table table-hover table-striped text-center">  
	                            				<thead>  
	                            					<tr>  
	                            						<th>#</th>  
	                            						<th>Customer ID</th>  
	                            						<th>Employee ID</th>  
	                            						<th>Date</th>  
	                            						<th>Deliver Type</th>
	                            						<th>Destination</th>
	                            						<th>Status</th>
	                            						<th>Total</th>
	                            					</tr>  
	                            				</thead>  

	                            				<tbody id="customer_order">  
	
		                            			</tbody>
		                            		</table>
	                            		</div>
	                            	</div>
	                            </div>
	                        </div> <!-- container -->
	                    </div> <!--  tab-pane -->   

	                </div> <!-- tab-content -->
	            </div> <!-- nav -->


			</div> <!-- box -->
		</div>
	</div>
</body>

<?php 
	include('includes/footer.inc.html');
?>

<script>
	$(document).ready(function(){
	    $('#customer_new').dataTable();
	    //$('#customer_total').dataTable();

	    $("button#delete_user").click(function(){
            //alert($("button#delete_user").val());
            $.post("delete_user_ajax.php", 
            {
                id: $("button#delete_user").val(),
            },
            function(data, status){            	
            	if (data=="success"){
            		alert("Delete successfully!");
            		$("button#delete_user").attr("disabled", "disabled");
            	}
                //alert("Ajax: " + status + "\nDelete: " + data);
            },
            "text"
            );
        });        

        $("button#button_customer").click(function(){
        	console.log($("#button_customer").val());	        	
        	$.post(
        		"customer_order.php",
        		{
        			id: $("#button_customer").val(),
        		},
        		function(data, status){        			
        			$("#customer_order").html(data);
        		},
        		"html"
        	);
        });
	});
</script>