<?php
ob_start();
session_start();
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
//require_once('includes/statics.php');
?>

<body>
    <div class="container">
        <div class="row">
            <div class="box">
                <!-- TITLE -->
                <div class="row">
                    <hr>
                    <h2 class="intro-text text-center">
                        <strong>Add</strong>
                        <br>
                        <small>a staff, product,...</small>
                    </h2>
                    <hr>
                </div>

                <!-- CONTENT -->
                <br>
                <div class="row">
                    <div class="col-md-3 col-md-offset-3 text-center">
                        <a href="add_product.php" class="btn btn-default" style="background-color: #3498db;">
                            <h5 style="text-transform: none; color: #ecf0f1">
                                Add a product
                            </h5>
                        </a>
                    </div>

                    <div class="col-md-3 text-center">
                        <a href="signup.php?user_group_id=1" class="btn btn-default" style="background-color: #f1c40f;">
                            <h5 style="text-transform: none; color: #ecf0f1">
                                Add a staff
                            </h5>
                        </a>
                    </div>
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