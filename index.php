<!DOCTYPE html>
<html lang="en">


    <body>

        <div id="clock" style="hidden"></div>

        <?php
            session_start();
            $page_title = "Homepage";
            include("includes/header.inc.html");
            require('includes/sql_connection.inc.php');
        ?>  

        <?php
            $query = "select p.prod_id, count(ol.orderlineid), image from products p, orderlines ol 
                        where p.prod_id = ol.prod_id
                        group by p.prod_id
                        order by count desc limit 3";
            $i = 0;
            $result = pg_query($DBC,$query);
            $picture = array();
            for($i = 1; $i<=3; $i++) {
                $row = pg_fetch_array($result);
                $tmp = array();
                $tmp['id'] = $row['prod_id'];
                $tmp['image'] = $row['image'];
                $picture[$i] = $tmp;
            }
            //print_r($picture);
        ?>
        
        <div class="container">

            <div class="row" id="myCarousel">
                <div class="box">
                    <div class="col-lg-12 text-center">
                        <div id="carousel-example-generic" class="carousel slide">
                            <!-- Indicators -->
                            <ol class="carousel-indicators hidden-xs">
                                <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
                                <li data-target="#carousel-example-generic" data-slide-to="1"></li>
                                <li data-target="#carousel-example-generic" data-slide-to="2"></li>
                            </ol>

                            <!-- Wrapper for slides -->
                            <div class="carousel-inner">
                                <div class="item active">
                                    <a href="product.php?id=<?php echo $picture[1]['id']?>"><img class="img-responsive img-full" src="uploads/<?php echo $picture[1]['image']?>" alt=""></a>
                                </div>
                                <div class="item">
                                    <a href="product.php?id=<?php echo $picture[2]['id']?>"><img class="img-responsive img-full" src="uploads/<?php echo $picture[2]['image']?>" alt=""></a>
                                </div>
                                <div class="item">
                                    <a href="product.php?id=<?php echo $picture[3]['id']?>"><img class="img-responsive img-full" src="uploads/<?php echo $picture[3]['image']?>" alt=""></a>
                                </div>
                            </div>

                            <!-- Controls -->
                            <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
                                <span class="icon-prev"></span>
                            </a>
                            <a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
                                <span class="icon-next"></span>
                            </a>
                        </div>
                        <h2 class="brand-before">
                            <small></small>
                        </h2>
                        <h1 class="brand-name">Coffee House</h1>
                        <hr class="tagline-divider">
                        <h2>
                            <small>By
                                <strong>Nhóm 10</strong>
                            </small>
                        </h2>
                    </div>
                </div>
            </div>

        <div class="row">
            <div class="box">

                <div class="row">
                    <hr>
                    <h2 class="intro-text text-center">
                        <strong>
                            Categories
                        </strong>
                    </h2>
                </div> <!-- end of row -->

<!--                <div class="row">
                    <h3 class="text-center">
                        Select a category
                    </h3>
                </div> end of row -->

                <div class="row">
                    <?php 
                        require('includes/sql_connection.inc.php');
                        $result = pg_query($DBC, "SELECT * FROM categories ORDER BY ctg_id ASC");
                        while ($row = pg_fetch_array($result)){
                            $id = $row['ctg_id'];
                            $category = $row['category'];
                            echo 
                                '<div class="col-lg-3 text-center">
                                    <a href="display_category.php?id='.$id.'">
                                        <h5>
                                        '.$category.'
                                        </h5>
                                    </a>
                                </div>';
                        }
                        pg_close($DBC);
                    ?>
                </div>

                <div class="row">
                    <hr>
                    <h3 class="intro-text text-center">
                        <?php echo $active_category ?>
                    </h3>
                </div> <!-- end of row -->

                <div class="row">
                    <?php
                        require('includes/sql_connection.inc.php');

                        if (isset($_GET['id'])){
                            $result=pg_query($DBC, "SELECT * from products WHERE category=$active_id ORDER BY prod_id ASC");
                            while ($row=pg_fetch_array($result)){
                                $id=$row['prod_id'];
                                $name = $row['name'];
                                $price = $row['price'];
                                $price = round($price);
                                $description = $row['description'];
                                $image = $row['image'];
                                $avg_rating=$row['avg_rating'];
                                echo 
                        '<div class="col-lg-4 feature text-center">

                                    <div class="row">
                                        <div class="col-lg-12">
                                            <h4>
                                                '.$name.'                           
                                            </h4>   
                                            <hr>
                                        </div>
                                    </div>
                                    
                                    <div class="row feature">
                                        <div class="col-lg-12">
                                            <img src="uploads/'.$image.'" alt="#" width="250px">
                                            <p>Price: '.$price.'<sup>đ</sup></p>
                                        </div>
                                    </div>';


                                $i = 0; // stars
                                echo '
                                    <div class="row text-center">';
                                        if($avg_rating == 0) {
                                                echo '<div class="row text-center"><i class="glyphicon glyphicon-star-empty gi-2x"></i><i class="glyphicon glyphicon-star-empty gi-2x"></i><i class="glyphicon glyphicon-star-empty gi-2x"></i><i class="glyphicon glyphicon-star-empty gi-2x"></i><i class="glyphicon glyphicon-star-empty gi-2x"></i></div>';
                                        } else {
                                            for($i = 0; $i < $avg_rating; $i++) {
                                                echo '<i class="glyphicon glyphicon-star gi-2x"></i>';
                                            }
                                            while($i<5) {
                                                echo '<i class="glyphicon glyphicon-star-empty gi-2x"></i>';
                                                $i++;
                                            }
                                        }
                                echo '</div>'; // end stars

                                echo    '<div class="row">
                        
                        <a href="product.php?id='.$id.'" class="btn btn-default">Chi tiết</a>
                                                <div class="form-group item-wrapper" id="form-'.$id.'">
                                                <label class="title">Số Lượng:</label>
                                                <input type="hidden" class="item-id" value="' . $id . '" />
                                                <input type="hidden" class="item-name" value="' . $name . '" />
                                                <input type="number" class="form-control item-quantity text-center" placeholder="0">
                                                <button class="item-send-button-'.$id.'"> Send </button>
                                          </div>
                                                <button class="btn-success btn-lg select-button" value="'. $id .'"> Select </button>
                                                <div id="test_alert-'.$id.'" class="alert alert-success" style="display: none;"></div>
                                            </div>
                        </div>';

                            } //end of while
                        

                            pg_close($DBC);

                        } else {
                            $result=pg_query($DBC, "SELECT * from products ORDER BY prod_id ASC");

                            while ($row=pg_fetch_array($result)){
                                $id=$row['prod_id'];
                                $name = $row['name'];
                                $price = $row['price'];
                                $price = round($price);
                                $description = $row['description'];
                                $image = $row['image'];
                                $avg_rating = $row['avg_rating'];
                                echo 
                    '<div class="col-lg-4 feature text-center">

                                    <div class="row">
                                        <div class="col-lg-12">
                                            <h4>
                                                '.$name.'                           
                                            </h4>   
                                            <hr>
                                        </div>
                                    </div>

                                    <div class="row feature">
                                        <div class="col-lg-12">
                                            <img src="uploads/'.$image.'" alt="#" width="250px">
                                            <p>Price: '.$price.'<sup>đ</sup></p>
                                        </div>
                                    </div>';

                                    $i = 0; //stars
                                    echo '
                                    <div class="row text-center">';
                                        if($avg_rating == 0) {
                                                echo '<div class="row text-center"><i class="glyphicon glyphicon-star-empty gi-2x"></i><i class="glyphicon glyphicon-star-empty gi-2x"></i><i class="glyphicon glyphicon-star-empty gi-2x"></i><i class="glyphicon glyphicon-star-empty gi-2x"></i><i class="glyphicon glyphicon-star-empty gi-2x"></i></div>';
                                        } else {
                                            for($i = 0; $i < $avg_rating; $i++) {
                                                echo '<i class="glyphicon glyphicon-star gi-2x"></i>';
                                            }
                                            while($i<5) {
                                                echo '<i class="glyphicon glyphicon-star-empty gi-2x"></i>';
                                                $i++;
                                            }
                                        }
                                    echo '</div>'; //end stars

                                    echo '<div class="row">
                            <a href="product.php?id='.$id.'" class="btn btn-default">Chi tiết</a>
                                                    <div class="form-group item-wrapper" id="form-'.$id.'">
                                                    <label class="title">Số Lượng:</label>
                                                    <input type="hidden" class="item-id" value="' . $id . '" />
                                                    <input type="hidden" class="item-name" value="' . $name . '" />
                                                    <input type="hidden" class="item-price" value="' . $price . '" />
                                                    <input type="number" class="text-center form-control item-quantity" placeholder="0">
                                                    <button class="btn-default item-send-button-'.$id.'"> Send </button>    
                                                  </div>
                                                    <button class="select-button btn-success btn-lg" value="'. $id .'"> Chọn </button>
                                                    <div id="test_alert-'.$id.'" class="alert alert-success" style="display: none;"></div>
                                                </div>
                    </div>';
                            } //end of while
                        } //end of else

                    ?> <!-- end of php code -->
                </div> <!-- end of row -->

            </div> <!-- end of box -->

        </div> <!-- end of row -->

                
            </div>

            

        </div>
        <!-- /.container -->
        <?php include('includes/footer.inc.html');?>
    </body>

    </html>


