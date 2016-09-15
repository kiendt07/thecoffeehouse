<body>

     <?php
     ob_start();
     session_start();
     $page_title = "Admin";
     require_once('includes/sql_connection.inc.php');
     if (!isset($_SESSION['user_name'])){
        header("Location: index.php");
        exit();
     }
     if(($_SESSION["login_role"] == "user") || ($_SESSION["login_role"] == "staff")) {
        header("Location: index.php");
        exit();
     } 
     include('includes/header_admin.inc.html');
    ?>
    
    <div class="container">
        <!-- STATICS -->
        <?php require_once('includes/statics.php') ?>
        <div class="row">
            <div class="box">
                <div class="row">
                    <div class="text-center">
                        <hr>
                        <h2 class="intro-text">
                            <strong>Order</strong>
                        </h2>
                        <hr>
                    </div> 
                </div>
            
            
                <div class="row">
                    <!-- content -->
                    <div class="col-md-3">

                        <div class="panel panel-default text-center">
                            <div class="panel-heading">
                                <h3 class="panel-title"><strong>Life-time Orders</strong></h3>
                            </div>
                            <div class="panel-body">
                                <h5>
                                    <small class="price"><?php echo $life_time_order ?></small>
                                </h5>
                            </div>
                        </div>

<!-- LIFE TIME SALE -->
                        <div class="panel panel-default text-center">
                            <div class="panel-heading">
                                <h3 class="panel-title"><strong>Life-time Sale</strong></h3>
                            </div>
                            <div class="panel-body">
                                <h5>
                                    <small class="price"><?php echo $life_time_sale ?><sup style="text-transform: none">đ</sup></small>
                                </h5>
                            </div>
                        </div>
<!-- AVERAGE SALE -->            
                        <div class="panel panel-default text-center">
                            <div class="panel-heading">
                                <h3 class="panel-title"><strong>Average orders cost</strong></h3>
                            </div>
                            <div class="panel-body">
                                <h5>
                                    <small class="price"><?php echo $average_order ?><sup style="text-transform: none">đ</sup></small>
                                </h5>
                            </div>
                        </div>
            
                        <div class="panel panel-default">
                            <div class="panel-heading text-center">
                                <h3 class="panel-title"><strong>Last 5 orders</strong></h3>
                            </div>
                            <div class="panel-body">
                                <table class="table table-hover text-center">
                                    <colgroup>
                                        <col class="col-sm-1">
                                        <col class="col-sm-3">
                                        <col class="col-sm-2">
                                    </colgroup>
            
                                    <thead>
                                        <tr class="table-title">
                                            <td>#</td>
                                            <td>Name</td>
                                            <td>Total</td>        
                                        </tr>
                                    </thead>
            
                                    <tbody>
                                        <?php 
                                            for ($i=0; $i<count($last5order); $i++){
                                                echo '<tr>
                                                        <td>'.$last5order[$i]['orderid'].'</td>
                                                        <td>'.$last5order[$i]['firstname'].' '.$last5order[$i]['lastname'].'</td>
                                                        <td>'.round($last5order[$i]['total']).'</td>
                                                    </tr>';
                                            }
                                        ?>
                                    </tbody>
                                </table>      
                            </div>
                        </div>
            
                    </div> <!-- end col -->
                    
                    <!-- ACTUAL CONTENT -->
                    <div class="col-md-9">
                        <!-- Numbers of orders in day/week/month/year -->
                            <div class="jumbotron text-center">
                                <div class="row">
                                    <h4>
                                        <strong>Orders Statics</strong> 
                                        <hr>
                                    </h4>
                                </div>

<!-- FILTER -->
                                <div class="row">                                    
                                    <h5 class="text-center" style="text-transform: none">                                                
                                        filter by
                                            <form action="admin.php" method="post" style="display: inline">
                                                <select name="time_filter" id="filter" > <!-- onchange='this.form.submit()' -->
                                                    <option selected="selected" value="day">Day</option>
                                                    <option value="week">Week</option>
                                                    <option value="month">Month</option>
                                                    <option value="year">Year</option>
                                                </select>
                                                <!-- <noscript><input type="submit" value="Submit"></noscript> -->
                                            </form>
                                    </h5>                                    
                                </div> <!-- row -->

                                <table class="table table-hover table-striped text-center tablesorter" id="ordertable">                                      
                                <thead>  
                                  <tr>  
                                    <th>Date</th>  
                                    <th>Num of orders</th>  
                                    <th>Num of customers</th>  
                                    <th>Num of products</th>                                      
                                    <th>Product sold</th>
                                    <th>Total</th>
                                </tr>  
                                </thead>  
                            
                                <tbody id="ordertablebytime">  
                                    
                                </tbody>                                 
                            </table>
                            </div>
                         <!-- end numbers of order row -->
            
                    </div> <!-- end actual content -->
                </div> <!-- end content row -->
                
            </div> <!-- end box -->

<!-- CUSTOMER=============================================================================================== -->
            <div class="box">
                <div class="row">
                    <div class="row">
                    <div class="text-center">
                        <hr>
                        <h2 class="intro-text">
                            <strong>Customer</strong>
                        </h2>
                        <hr>
                    </div> 
                </div>
            
            
                <div class="row">
                    <!-- content -->
                    <div class="col-md-3">

                        <div class="panel panel-default text-center">
                            <div class="panel-heading">
                                <h3 class="panel-title"><strong>Numbers of customers</strong></h3>
                            </div>
                            <div class="panel-body">
                                <h5>
                                    <small class="price" id="num_of_customer"></small>
                                </h5>
                            </div>
                        </div>

                        <div class="panel panel-default text-center">
                            <div class="panel-heading">
                                <h3 class="panel-title"><strong>Men-Women ratio</strong></h3>
                            </div>
                            <div class="panel-body">
                                <h5>
                                    <small class="price" id="sex_ratio"></small>
                                </h5>
                            </div>
                        </div>

                        <div class="panel panel-default text-center">
                            <div class="panel-heading">
                                <h3 class="panel-title"><strong>Last 5 customers</strong></h3>
                            </div>
                            <div class="panel-body">
                                <table class="table table-hover text-center">
                                    <colgroup>
                                        <col class="col-sm-1">
                                        <col class="col-sm-3">
                                        <col class="col-sm-2">
                                    </colgroup>
            
                                    <thead>
                                        <tr class="table-title">
                                            <td>#</td>
                                            <td>Username</td>
                                            <td>Total</td>        
                                        </tr>
                                    </thead>
            
                                    <tbody id="last5customers">
                                        
                                    </tbody>
                                </table> 
                            </div>
                        </div>

<!-- LIFE TIME SALE -->                                    
                    </div> <!-- end col -->
                    
                    <!-- ACTUAL CONTENT -->
                    <div class="col-md-9">
                        <!-- Numbers of orders in day/week/month/year -->
                            <div class="jumbotron text-center">
                                <div class="row">
                                    <h4>
                                        <strong>Top customers</strong> 
                                        <hr>
                                    </h4>
                                </div>

                                <table class="table table-hover text-center" id="customer_static">                                              
                                    <thead>
                                        <tr class="table-title">
                                            <td>Username</td>
                                            <td>Name</td>
                                            <td>Total paid</td>
                                            <td>Num of orders</td>        
                                            <td>Total products paid</td>
                                            <td>Num of products</td>
                                        </tr>
                                    </thead>
            
                                    <tbody id="toppaid">
                                        
                                    </tbody>
                                </table> 
                            </div> <!-- end jumbotron -->
                         <!-- end numbers of order row -->
            
                    </div> <!-- end actual content -->
                </div>
            </div>
            
        </div> <!--   row   -->

        <!-- =========================================================================================== -->
        
            
        </div> <!--   row   -->

    </div> <!-- end of container -->

</body>

<?php 
    include('includes/footer.inc.html'); 
    pg_close($DBC);
?>

<!-- <script type="text/javascript" src="js/jquery-latest.js"></script> 
<script type="text/javascript" src="js/jquery.tablesorter.js"></script>  -->


<script>

    $(document).ready(function(){
        // $("#filter").change(function(){
        //     alert($("#filter").val());
        //     $.post("admin.php",
        //         {
        //             time_filter: $("#filter").val(),
        //         },
        //         function(data, status){
        //             alert("posted");
        //             $("#ordertablebytime").load("admin.php #ordertablebytime", function(){
        //                 alert("loaded")
        //             });
        //         }
        //     );
        // });
        $("button#delete_user").click(function(){
            alert($("button#delete_user").val());
            $.post("delete_user_ajax.php", 
            {
                id: $("button#delete_user").val(),
            },
            function(data, status){
                alert("Ajax: " + status + "\nDelete: " + data);
            });
        });

        $.post("revenue.php",
                {
                    time_filter: "day",
                    action: "order_list_by_time"
                },
                function(data, status){    
                    $("#ordertablebytime").html(data);
                    // $.each(json_data[0], function(key, value){
                    //     alert(key + ' ' + value);                        
                    // });
                    // alert(JSON.stringify(json_data));                                   
                },
                "html"
            );

        $("#filter").change(function(){
            $.post("revenue.php",
                {
                    time_filter: $("#filter").val(),
                    action: "order_list_by_time"
                },
                function(data, status){    
                    $("#ordertablebytime").html(data);
                    // $.each(json_data[0], function(key, value){
                    //     alert(key + ' ' + value);                        
                    // });
                    // alert(JSON.stringify(json_data));                                   
                },
                "html"
            );
        });

        $.post(
            "customer.php",
            {
                action: "num_of_customer"
            },
            function(data, status){
                //alert(JSON.stringify(data[0]));
                $("#num_of_customer").text(data);
            },
            "text"
        );

        $.post(
            "sex_ratio.php",
            {
                action: "sex_ratio"
            },
            function(data, status){
                $("#sex_ratio").text(data);
            },
            "text"
        );

        $.post(
            "last5customers.php",
            function(data, status){
                //alert(status);
                $("#last5customers").html(data);
            },
            "html"
        );

        $.post(
            "toppaid.php",
            function(data, status){
                //alert(status);
                $("#toppaid").html(data);
            },
            "html"
        );

        $.post(
            "num_of_staff.php",
            function(data, status){
                $("#num_of_staff").text(data);
            },
            "text"
        );
    });

$(document).ready(function(){
        // $("ordertable").tablesorter();
        $('#ordertable').dataTable();
        $('#customer_static').dataTable();

    });

</script>
