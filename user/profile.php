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
          <h3><span class="glyphicon glyphicon-comment"></span> <?php // echo username 's here ?> Social Media </h3>
         <hr>
          <p>Twitter</p>
          <p>Facebook</p>
          <p>YouTube</p>
        </div>

        <div class="col-lg-4">
          <h3>Feedback</h3>
          <p>Score: </p>
          <p>Recently sold: </p>
        </div>
        
        <div class="col-lg-4">
          <h3>Contact Information</h3>
          <div class="well">
            <p><b>Phone:</b> <?php echo $data['user_data']['phone_number']; ?> </p>
            <p><b>Email:</b> <?php echo $data['user_data']['email']; ?></p>
          </div>
        </div>

      </div>
      

      <div class="row">
        <div class="col-lg-1"></div>
        <div class="col-lg-9">
          <h3><span class="glyphicon glyphicon-shopping-cart"></span> <?php // echo username 's here ?> Items for Sale</h3>
          <hr>
          </div>
        <div class="col-lg-1"></div>
      </div>

      <div class="row">
        <div class="col-lg-1"></div>
          <div class="col-lg-3">
            <center><img class="img-circle" height="140" width="140" src="../inc/img/download.png" alt="Generic placeholder image"></center>
            <h5>Item 1 </h5>
            <p>Donec sed odio dui. Etiam porta sem malesuada magna mollis euismod. Nullam id dolor id nibh ultricies vehicula ut id elit. Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Praesent commodo cursus magna.</p>
            <p><a class="btn btn-default btn-xs" href="#" role="button">View details &raquo;</a></p>
          </div><!-- /.col-lg-3 -->
          <div class="col-lg-3">
            <center><img class="img-circle" height="140" width="140" src="../inc/img/download.png" alt="Generic placeholder image"></center>
            <h5>Item 2</h5>
            <p>Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh.</p>
            <p><a class="btn btn-default btn-xs" href="#" role="button">View details &raquo;</a></p>
          </div><!-- /.col-lg-3 -->
          <div class="col-lg-3">
            <center><img class="img-circle" height="140" width="140" src="../inc/img/download.png" alt="Generic placeholder image"></center>
            <h5>Item 3</h5>
            <p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
            <p><a class="btn btn-default btn-xs" href="#" role="button">View details &raquo;</a></p>
          </div><!-- /.col-lg-3 -->
        <div class="col-lg-2"></div>
      </div><!-- /.row -->
      <?php } ?>

      <?php require_once("../inc/footer.php"); ?>
