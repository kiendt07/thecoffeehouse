<?php
ob_start();
session_start();
$page_title = "Sale Static";
require_once('includes/sql_connection.inc.php');
require_once('includes/data_init.php');
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
                <!-- TITLE -->
                <div class="row">
                    <hr>
                    <h2 class="intro-text text-center">
                        <strong>Order Detail</strong>
                    </h2>
                    <hr>
                </div>

                <!-- CONTENT -->

                <div class="row">
                  <!-- Nav tabs -->
                    <ul class="nav navbar-nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#orderlist" aria-controls="orderlist" role="tab" data-toggle="tab">List</a></li>
                        <li role="presentation"><a href="#static" aria-controls="static" role="tab" data-toggle="tab">Daily Static</a></li>
                    </ul>

                  <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="orderlist">
                            <div class="container">
                                <table id="ordertable" class="table table-hover table-striped text-center">  
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
                                
                                    <tbody>  
                                        <?php                                         
                                            for ($i=0; $i<count($order_list); $i++){
                                                $order_list[$i]['employeeid'] = ($order_list[$i]['employeeid'] == -1 ? NULL : $order_list[$i]['employeeid']);
                                                echo '<tr>
                                                        <td>'.$order_list[$i]['orderid'].'</td>
                                                        <td>'.$order_list[$i]['customerid'].'</td>
                                                        <td>'.$order_list[$i]['employeeid'].'</td>
                                                        <td>'.$order_list[$i]['orderdate'].'</td>
                                                        <td>'.DELIVER_TYPE[$order_list[$i]['delivertype']].'</td>
                                                        <td>'.$order_list[$i]['destination'].'</td>
                                                        <td>'.STATUS[$order_list[$i]['status']].'</td>
                                                        <td>'.round($order_list[$i]['total']).'</td>
                                                    </tr>';
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- </div> tab pane 1 -->   

                        <div role="tabpanel" class="tab-pane" id="static">
                            <div class="container">
                                <table class="table table-hover table-striped text-center" id="producttable">                                      
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
                                        <?php 
                                            for ($i=0; $i<count($order_list_by_time); $i++){
                                                echo '<tr>
                                                        <td>'.date('D d/M/Y', strtotime($order_list_by_time[$i]['date_filter'])).'</td>
                                                        <td>'.$order_list_by_time[$i]['numberof_order'].'</td>
                                                        <td>'.$order_list_by_time[$i]['numberof_customer'].'</td>
                                                        <td>'.$order_list_by_time[$i]['numberof_prod'].'</td>                                                    
                                                        <td>'.$order_list_by_time[$i]['quantity_by_time'].'</td>
                                                        <td style="font-weight: bold;" class="price">'.round($order_list_by_time[$i]['total_by_time']).'</td>
                                                    </tr>';
                                            } 
                                        ?>
                                    </tbody>   
                                </table>
                            </div> <!-- end container -->
                        </div>  <!-- tab pane 2          -->                        
                    </div>

</div> <!-- end box -->
</div> <!-- end row -->
</div> <!-- end container -->
</body>

<?php 
include('includes/footer.inc.html');
?>

<script>
    $(document).ready(function(){
        $('#ordertable').dataTable();
        $('#producttable').dataTable();
    });
</script>