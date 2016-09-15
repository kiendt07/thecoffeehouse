<body>
<?php 
    $page_title = "Login";
?>
<!-- kiem tra dang nhap sau khi submit form-->
    <?php
        ob_start();
        session_start();
        $page_title = "Login";
        include('includes/header.inc.html');
        $error ='';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            if (empty($_POST["username"]) || empty($_POST["password"])) {
                $error = "Username or Password is empty";
            } else {

                $username = $_POST["username"];
                $password = $_POST["password"];
                $username = stripslashes($username);
                $password = stripslashes($password);

                
                require('includes/sql_connection.inc.php');
                $conn = $DBC;
                if (!$conn) {
                    $error = "Database error";
                } else { //if (!conn)

                    $tmp = pg_query_params($conn,"select * from customers where username = $1 and pass = $2", array($username, $password));
                    echo $username.' '.$password;
                    $row = pg_num_rows($tmp);
                    if($row == 0) {
                        $error = "Username or Password wrong";
                    } else {
                        $_SESSION["user_name"] = $username;
                        $_SESSION["password"] = $password;
                        header("Location: test.php");
                        exit();
                    } //end if-else
                } //end if-else

                pg_close($conn);

            } //end if (empty)

        } else { //if (isset)

            if (isset($_SESSION["user_name"])) {
                echo $_SESSION["user_name"];
                header("refresh:2; url=index.php");
                exit();
            }       

        }
    ?> <!-- end php -->
<!-- giao dien form -->    
<section id="main">
    <div class="container">
            <div class="row">

                <div class="col-md-6 col-md-offset-3">
                    <div class="panel panel-default">
                
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                LOGIN
                            </h3>        
                        </div> <!-- end of panel heading -->
                
                        <div class="panel-body">

                            <form class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF']; ?>" role="form" method="post">

                                <span><?php echo $error; ?></span>
                
                                <div class="form-group">
                                    <label for="username" class="col-sm-2 control-label"><strong>Username</strong></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="username" id="username" placeholder="Username">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="password" class="col-sm-2 control-label"><strong>Password</strong></label>
                                    <div class="col-sm-10">
                                        <input type="password" id="password" name="password" class="form-control" placeholder="Password">
                                    </div>
                                </div>
                            
                                <div class="form-group">
                                    <div class="col-sm-10 col-sm-offset-2">
                                      <!-- <input type="submit" name="submit" value="Login" id="login"> -->
                                      <button type="submit" class="btn btn-default">Sign in</button>
                                    </div>
                                </div>
  
                            </form> <!-- end of form -->

                        </div> <!-- end of panel body -->
                
                    </div> <!-- end of panel -->
                
                </div> <!-- end of col -->

            </div> <!-- end of row -->

    </div>  <!-- end of container -->

</section> <!-- end section -->

<?php include('includes/footer.inc.html'); ?>

</body>