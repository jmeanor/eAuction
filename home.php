<?php 
    // Title:       home.php
    // Desc:        Main display for logged in users.
    // Date:        March 22, 2014
    // Version:     1.0
    // Author:      John Meanor
    require_once("inc/header.php");
	checkPermissions();
  
?>

<div class="container">
        <div class="jumbotron hero-spacer">
            <h1>Welcome to eAuction!</h1>
            <p>We're the most user-friendly auction website there is since 2014. Search for what you want to buy above, <a href="#">shop by category</a>, or check out the featured items below. </p>
            <p><a class="btn btn-primary btn-large">Sign up!</a> 
            </p>
        </div>

        <hr>

        <div class="row">
            <div class="col-lg-12">
                <h3>Featured auctions</h3>
            </div>
        </div>
        <!-- /.row -->

        <div class="row text-center">

            <div class="col-lg-3 col-md-6 hero-feature">
                <div class="thumbnail">
                    <img src="http://placehold.it/800x500" alt="">
                    <div class="caption">
                        <h3>Feature Label</h3>
                        <p>This would be a great spot to feature some brand new products!</p>
                        <p><a href="#" class="btn btn-primary">Buy Now!</a>  <a href="#" class="btn btn-default">More Info</a>
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 hero-feature">
                <div class="thumbnail">
                    <img src="http://placehold.it/800x500" alt="">
                    <div class="caption">
                        <h3>Feature Label</h3>
                        <p>This would be a great spot to feature some brand new products!</p>
                        <p><a href="#" class="btn btn-primary">Buy Now!</a>  <a href="#" class="btn btn-default">More Info</a>
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 hero-feature">
                <div class="thumbnail">
                    <img src="http://placehold.it/800x500" alt="">
                    <div class="caption">
                        <h3>Feature Label</h3>
                        <p>This would be a great spot to feature some brand new products!</p>
                        <p><a href="#" class="btn btn-primary">Buy Now!</a>  <a href="#" class="btn btn-default">More Info</a>
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 hero-feature">
                <div class="thumbnail">
                    <img src="http://placehold.it/800x500" alt="">
                    <div class="caption">
                        <h3>Feature Label</h3>
                        <p>This would be a great spot to feature some brand new products!</p>
                        <p><a href="#" class="btn btn-primary">Buy Now!</a>  <a href="#" class="btn btn-default">More Info</a>
                        </p>
                    </div>
                </div>
            </div>


        </div>
        <!-- /.row -->


        <?php require_once("inc/footer.php"); ?>