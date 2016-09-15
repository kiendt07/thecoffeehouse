<?php
ob_start();
session_start();
$page_title = "Product Detail";
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
                        <strong>Product details</strong>
                    </h2>
                    <hr>
                </div>

                <!-- CONTENT -->

                <div class="row">
                    <div class="container">
                            <table id="producttable" class="table table-hover table-striped text-center">  
                                <thead class="text-center">  
                                  <tr>  
                                    <th>#</th>  
                                    <th>Name</th>  
                                    <th>Category</th>  
                                    <th>Price</th>
                                    <th>Sold</th>
                                    <th>Rating</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                </tr>  
                                </thead>  
                            
                                <tbody>  
                                    <?php                                         
                                        for ($i=0; $i<count($product_list); $i++){
                                            $price = round($product_list[$i]['price']);
                                            echo '<tr id="product-'.$product_list[$i]['prod_id'].'">
                                                    <td>'.$product_list[$i]['prod_id'].'</td>
                                                    <td>'.$product_list[$i]['name'].'</td>
                                                    <td>'.$product_list[$i]['category'].'</td>
                                                    <td>'.$price.'</td>
                                                    <td>'.$product_list[$i]['total_sold'].'</td>
                                                    <td>'.$product_list[$i]['rating'].'</td>
                                                    <td><a href="edit_product.php?pid='.$product_list[$i]['prod_id'].'" class="btn btn-default">Edit</a></td>
                                                    <td><button class="btn btn-default delete-prod" id="delete_prod" value="'.$product_list[$i]['prod_id'].'"><span style="color: #e74c3c">Delete</span></button></td></td>
                                                </tr>';
                                        }
                                    ?>
                                </tbody>  
                            </table>

                            

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


<script>
$(document).ready(function(){
    $('.delete-prod').click(function (){
        var x = $(this).val();
        $.post("ajax.php", {'delete-product': 'true', 'prod_id': x}, function (response){
            console.log(response);
            $('#product-'+x).remove();
        });
    });
});
</script>