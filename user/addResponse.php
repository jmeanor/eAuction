<?php 
    // Title:       shop/item.php
    // Desc:        Displays individual item auction page.  
    // Date:        March 22, 2014
    // Version:     1.0
    // Author:      John Meanor

    require_once("../inc/header.php");
	if (!empty($_GET['userid'])) {
	  $data = getProfileData($_GET['userid'], $db);
	}
    else {
      $data = getProfileData($_SESSION['user']['user_id'], $db);
    }
	
	$ratings_data = getRatingsData($data['user_data']['user_id']);
	$rating_id = $_GET['id'];
?>

<style type="text/css">
  body {
    padding-top: 40px;
    padding-bottom: 40px;
    background-color: #eee;
  }
</style>

    <div class="container">
      <div class="row">
        <div class="col-md-1">
        </div>
        <div class="col-md-10">
          <h4></h4>
		  <h1>Respond to Rating for <?php echo $info['ratings_data']['name']?></h1>
          <hr />
        </div>
        <div class="col-md-1">
        </div>
      </div>
    </div>

    <div class="container marketing">

      <!-- Three columns of text below the carousel -->
      <div class="row">
        <div class="col-lg-1"></div>
        <div class="col-lg-3">
          <center>
			  Score: <?php echo $info['ratings_data']['score'];?>

          </center>
        </div><!-- /.col-lg-4 -->
        <div class="col-lg-5">
          <center><a class="btn btn-default btn-xs" class="btn btn-primary" href="../shop/bidding.php?id=<?php echo $item_id ?>" role="button">Place a Bid or Buy It Now! &raquo;</a></center>
          <br/>
          <p><?php echo $test['item_data']['description']?></p>
        </div>       
        <div class="col-lg-1"></div>
      </div><!-- /.row -->



      <?php require_once("../inc/footer.php"); ?>
