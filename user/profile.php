<?php 
    // Title:       shop/item.php
    // Desc:        Displays individual item auction page.  
    // Date:        March 22, 2014
    // Version:     1.0
    // Author:      John Meanor
    require_once("../inc/functions.php");
    checkPermissions();

    require_once("../inc/header.php");

    if (!empty($_GET['userid'])) {
      $data = getProfileData($_GET['userid'], $db);
    }
    else {
      $data = getProfileData($_SESSION['user']['user_id'], $db);
    }
	
	if (!empty($_GET['userid'])) {
	  $ratings_data = getRatingsData($_GET['userid'], $db);
	}
	else {
	  $ratings_data = getRatingsData($_SESSION['user']['user_id'], $db);
	}
	
	if (!empty($_GET['userid'])) {
	  $tw_data = getTwitter($_GET['userid'], $db);
	}
	else {
	  $tw_data = getTwitter($_SESSION['user']['user_id'], $db);
	}
	
	if (!empty($_GET['userid'])) {
	  $fb_data = getFacebook($_GET['userid'], $db);
	}
	else {
	  $fb_data = getFacebook($_SESSION['user']['user_id'], $db);
	}
	if (!empty($_GET['userid'])) {
	  $yt_data = getYoutube($_GET['userid'], $db);
	}
	else {
	  $yt_data = getYoutube($_SESSION['user']['user_id'], $db);
	}
	
	if (!empty($_GET['userid'])) {
	  $item_data = getItemData($_GET['userid'], $db);
	}
	else {
	  $item_data = getItemData($_SESSION['user']['user_id'], $db);
	}
?>

    <div class="container">


      <?php // Check for error to display, else, display the profile data 
            if (!$data['success'] ) { ?>
        <div class="alert alert-danger"><?php echo $data['message']; ?></div>
      <?php } else { ?>

      <div class="row">
        <div class="col-lg-12">
          <h1><?php echo $data['user_data']['name'] ?></h1>
          <h3><span class="glyphicon glyphicon-user"></span> <?php echo $data['user_data']['username'] ?></h3>
          <hr />
       </div>
     </div><!-- /row -->

      <div class="row">
        
        <div class="col-lg-4">
          <h3><span class="glyphicon glyphicon-comment"></span> <?php echo $data['user_data']['username'] ?>'s Social Media </h3>
         <hr>
          <p>Twitter: <?php echo $tw_data['user_data']['username']?></p>
          <p>Facebook: <?php echo $fb_data['user_data']['username']?></p>
          <p>YouTube: <?php echo $yt_data['user_data']['username']?></p>
        </div>

        <div class="col-lg-4">
          <h3><span class="glyphicon glyphicon-thumbs-up"></span> <?php echo $data['user_data']['username'] ?>'s Feedback</h3>
		  <hr>
          <p>Score: </p>
          <p>Recently sold: </p>
        </div>
        
        <div class="col-lg-4">
          <h3><span class="glyphicon glyphicon-info-sign"></span> <?php echo $data['user_data']['username'] ?>'s Contact Information</h3>
		  <hr>
          <div class="well">
            <p><b>Phone:</b> <?php echo $data['user_data']['phone_number']; ?> </p>
            <p><b>Email:</b> <?php echo $data['user_data']['email']; ?></p>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-1"></div>
        <div class="col-lg-9">
          <h3><span class="glyphicon glyphicon-shopping-cart"></span> <?php echo $data['user_data']['username'] ?>'s Items for Sale</h3>
          <hr>
          </div>
        <div class="col-lg-1"></div>
      </div>

      <div class="row">
        <div class="col-lg-1"></div>
          <div class="col-lg-3">
            <center><img class="img-circle" height="140" width="140" src="../inc/img/download.png" alt="Generic placeholder image"></center>
            <h5><?php echo $item_data['user_data']['name'] ?></h5>
            <p><?php echo $item_data['user_data']['description']?></p>
            <p><a class="btn btn-default btn-xs" href="#" role="button">View details &raquo;</a></p>
          </div><!-- /.col-lg-3 -->
          <div class="col-lg-3">
            <center><img class="img-circle" height="140" width="140" src="../inc/img/download.png" alt="Generic placeholder image"></center>
            <h5><?php echo $item_data['user_data']['name'] ?></h5>
            <p><?php echo $item_data['user_data']['description']?></p>
            <p><a class="btn btn-default btn-xs" href="#" role="button">View details &raquo;</a></p>
          </div><!-- /.col-lg-3 -->
          <div class="col-lg-3">
            <center><img class="img-circle" height="140" width="140" src="../inc/img/download.png" alt="Generic placeholder image"></center>
             <h5><?php echo $item_data['user_data']['name'] ?></h5>
            <p><?php echo $item_data['user_data']['description']?></p>
            <p><a class="btn btn-default btn-xs" href="#" role="button">View details &raquo;</a></p>
          </div><!-- /.col-lg-3 -->
        <div class="col-lg-2"></div>
      </div><!-- /.row -->
	  
	  
	        <div class="row">
        <div class="col-lg-1"></div>
        <div class="col-lg-9">
          <h3><span class="glyphicon glyphicon-shopping-cart"></span> <?php echo $data['user_data']['username'] ?>'s User Ratings</h3>
          <hr>
          </div>
        <div class="col-lg-1"></div>
      </div>

      <div class="row">
        <div class="col-lg-1"></div>
          <div class="col-lg-3">
            <center><img class="img-circle" height="140" width="140" src="../inc/img/download.png" alt="Generic placeholder image"></center>
            <h5><?php echo $ratings_data['user_data']['name']?> : <?php echo $ratings_data['user_data']['score']?></h5>
            <p><b>Customer Message: </b><?php echo $ratings_data['user_data']['description']?></p>
            <p><b>Seller Response: </b><?php echo $ratings_data['user_data']['seller_response']?></p>
			<p><a class="btn btn-default btn-xs" href="#" role="button">View details &raquo;</a></p>
          </div><!-- /.col-lg-3 -->
          <div class="col-lg-3">
            <center><img class="img-circle" height="140" width="140" src="../inc/img/download.png" alt="Generic placeholder image"></center>
			<h5><?php echo $ratings_data['user_data']['name']?> : <?php echo $ratings_data['user_data']['score']?></h5>
            <p><b>Customer Message: </b><?php echo $ratings_data['user_data']['description']?></p>
            <p><b>Seller Response: </b><?php echo $ratings_data['user_data']['seller_response']?></p>
            <p><a class="btn btn-default btn-xs" href="#" role="button">View details &raquo;</a></p>
          </div><!-- /.col-lg-3 -->
          <div class="col-lg-3">
            <center><img class="img-circle" height="140" width="140" src="../inc/img/download.png" alt="Generic placeholder image"></center>
            <h5><?php echo $ratings_data['user_data']['name']?> : <?php echo $ratings_data['user_data']['score']?></h5>
            <p><b>Customer Message: </b><?php echo $ratings_data['user_data']['description']?></p>
            <p><b>Seller Response: </b><?php echo $ratings_data['user_data']['seller_response']?></p>
            <p><a class="btn btn-default btn-xs" href="#" role="button">View details &raquo;</a></p>
          </div><!-- /.col-lg-3 -->
        <div class="col-lg-2"></div>
      </div><!-- /.row -->
	  
      <?php } ?>

      <?php require_once("../inc/footer.php"); ?>
