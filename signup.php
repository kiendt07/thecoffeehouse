<?php 
    $page_title='Sign up';
    ob_start();
    session_start();
    include('includes/header.inc.html');
    // if ((isset($_SESSION['user_name'])) && (!isset($_GET['user_group_id']))){
    //     header("Location: index.php");
    //     exit();
    // }
?>
<body>
    <?php
        $firstname = $lastname = $gender = $address1 = $phone = $username = $pass = "";
        $firstname_err = $lastname_err = $gender_err = $address1_err = $password_err = $phone_err = $username_err = "";
        $address2 = $_POST['address2'];
        $email = $_POST['email'];
        $success=FALSE;
        if (isset($_GET['user_group_id'])){
            $user_group_id=$_GET['user_group_id'];
        } else{
            $user_group_id = 2;
        }
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            function test_input($data)
            {
                $data = trim($data);
                $data = stripslashes($data);
                $data = htmlspecialchars($data);
                return $data;
            }

            if (!empty($_POST['firstname']))
                $firstname = test_input($_POST['firstname']);
            else {
                $firstname_err = "Required Firstname";
            }

            if(!empty($_POST['lastname']))
                $lastname = test_input($_POST['lastname']);
            else {
                $lastname_err = "Required Lastname";
            }

            if(!empty($_POST['address1']))
                $address1 = test_input($_POST['address1']);
            else {
                $address1_err = "Required Address";
            }

            if(!empty($_POST['phone']))
                $phone= test_input($_POST['phone']);
            else {
                $phone_err = "Required Phone";
            }

            if (empty($_POST['username']))
                $username_err = "Required UserName";
            else {
                $username = test_input($_POST['username']);
            }

            if (empty($_POST['password']))
                $password_err = "Required Password";
            else {
                $password = test_input($_POST['password']);
            }

            if (empty($_POST['gender'])) {
                $gender_err = "Required Gender";
            } else {
                $gender = test_input($_POST['gender']);
            }



            require('includes/sql_connection.inc.php');
            $conn = $DBC;
            
            if (!$conn) {
                $error = "Database error";
            } else {
                $query = array($firstname, $lastname, $gender, $address1, $address2, $email, $phone, $username, $password);
                if($address2 == NULL) {
                    if($email == NULL) {
                        $query = array($firstname, $lastname, $gender, $address1, $phone, $username, $password, $user_group_id);
                        $result = pg_query_params($conn, "Insert into customers (firstname,lastname,sex,address1,phone,username,pass,user_group_id) 
                            values ($1, $2, $3, $4, $5, $6, $7, $8)", $query);
                        if(!$result) {
                            echo "error1";
                        } else {
                            $success=TRUE;
                            //header("refresh:3; url=")
                        }
                    }
                    else {
                        $query = array($firstname, $lastname, $gender, $address1, $email, $phone, $username, $password, $user_group_id);
                        $result = pg_query_params($conn, "Insert into customers (firstname,lastname,sex,address1,email,phone,username,pass, user_group_id) 
                            values ($1, $2, $3, $4, $5, $6, $7, $8, $9)", $query);
                        if(!$result) {
                            echo "error2";
                        } else {
                            $success=TRUE;
                        }
                    }
                } else {
                    if($email == NULL) {
                        $query = array($firstname, $lastname, $gender, $address1, $address2, $phone, $username, $password, $user_group_id);
                        $result = pg_query_params($conn, "Insert into customers (firstname,lastname,sex,address1,address2,phone,username,pass,user_group_id) 
                            values ($1, $2, $3, $4, $5, $6, $7, $8, $9)", $query);
                        if(!$result) {
                            echo "error3";
                        } else {
                            $success=TRUE;
                        }
                    }
                    else {
                        $query = array($firstname, $lastname, $gender, $address1, $address2, $email, $phone, $username, $password, $user_group_id);
                        $result = pg_query_params($conn, "Insert into customers (firstname,lastname,sex,address1,address2,email,phone,username,pass,user_group_id) 
                            values ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10)", $query);
                        if(!$result) {
                            echo "error4";
                        } else {
                            $success=TRUE;
                        }
                    }
                }
            }
            pg_close($conn);
        }
    ?>
 
    <div class="container">
        <div class="box">
            <div class="row">
                <hr>
                <h2 class="intro-text text-center">
                    <strong>Sign up</strong>
                </h2>
                <h5 class="text-center">
                    <?php 
                        if (isset($_GET['user_group_id'])){
                            echo 'Add an new employee';
                        } else {
                            echo 'to become our customer';
                        }
                    ?>
                </h5>
                <hr>
                <?php 
                    if ($success){
                        echo '<div class="alert alert-success text-center" style="font-size: 1.3em" role="alert">Successfully create an account with username: '.$username.'</div>';
                    }
                ?>
            </div>



            <div class="row ">
                <div class="col-md-8 col-md-offset-3">
                    <form class="form-horizontal" role="form" method="POST" action="
                            <?php 
                                if (isset($_GET['user_group_id'])){
                                    echo 'signup.php?user_group_id='.$_GET['user_group_id'];
                                } else{
                                    echo 'signup.php';
                                }
                            ?>
                            ">
                        <span> <b> * Required FIELD</b></span>
                    
                        <div class="form-group">
                            <label for="firstname" class="title col-sm-2 control-label">First name</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="firstname" name="firstname" placeholder="First name">
                            </div>
                            <span> *<?php echo $firstname_err; ?> </span>
                        </div>
                    
                        <div class="form-group">
                            <label for="lastname" class="title col-sm-2 control-label">Last name</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Last name">
                            </div>
                            <span> *<?php echo $lastname_err; ?> </span>
                        </div>
                    
                        <div class="form-group">
                            <label for="gender" class="title col-sm-2 control-label">Gender</label>
                            <div class="col-sm-10">
                                <input type="radio" name="gender" id="male" value="M">
                                <label for="gender">Male</label>
                                <input type="radio" name="gender" id="female" value="F">
                                <label for="gender">Female</label>
                                <span> *<?php echo $gender_err; ?> </span>
                            </div>
                        </div>
                    
                        <div class="form-group">
                            <label for="address1" class="title col-sm-2 control-label">Address</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="address1" name="address1">
                            </div>
                            <span> *<?php echo $address1_err; ?> </span>
                        </div>
                    
                        <div class="form-group">
                            <label for="address2" class="title col-sm-2 control-label">Company</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="address2" name="address2">
                            </div>
                        </div>
                    
                        <div class="form-group">
                            <label for="email" class="title col-sm-2 control-label">Email</label>
                            <div class="col-sm-6">
                                <input type="email" class="form-control" id="email" name="email">
                            </div>
                        </div>
                    
                        <div class="form-group">
                            <label for="phone" class="title col-sm-2 control-label">Phone</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="phone" name="phone">
                            </div>
                            <span> *<?php echo $phone_err; ?> </span>
                        </div>
                    
                        <div class="form-group">
                            <label for="username" class="title col-sm-2 control-label">UserName</label>
                            <div class="col-sm-6">
                                <input type="text" id="username" class="form-control" name="username">
                            </div>
                            <span> *<?php echo $username_err; ?> </span>
                        </div>
                    
                        <div class="form-group">
                            <label for="password" class="title col-sm-2 control-label">Password</label>
                            <div class="col-sm-6">
                                <input type="password" class="form-control" id="password" name="password">
                            </div>
                            <span> *<?php echo $password_err; ?> </span>
                        </div>                        
                            
                        <div class="form-group">
                            <div class="col-sm-10 col-sm-offset-2">
                              <button type="submit" class="btn btn-default">Sign up</button>
                            </div>
                        </div>
                    </form>
                </div> <!-- .col -->
            </div> <!-- .row -->
        </div> <!-- .box -->
    </div>    <!-- .container -->
 
</body>

<?php include('includes/footer.inc.html'); ?>