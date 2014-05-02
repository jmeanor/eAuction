<?php 
    // Title:       user/addResponse.php
    // Desc:        Allows seller to respond to a user's rating of his item
    // Date:        March 22, 2014
    // Version:     1.0
    // Author:      John Meanor

    require_once("../inc/header.php");
	$item_info = itemInfo($_GET['id'], $db);
	$ratings_data = ItemRatingsData($_GET['id'], $db);
?>

<style type="text/css">
  body {
    padding-top: 40px;
    padding-bottom: 40px;
    background-color: #eee;
  }
</style>

    <div class="container">
	  <h1>Feedback on <?php echo $ratings_data['ratings_data'][0]['name'] ?></h1>
    </div>

    <div class="container marketing">
	  <body>
	  <div class="container">
	  <div class="row">
	    <div class="col-lg-6">
		<h2>Score: <?php echo $ratings_data['ratings_data'][0]['score'] ?></h2>
		<h2>Feedback</h2>
		<p><?php echo $ratings_data['ratings_data'][0]['description'] ?></p>
		</div>
		
		<div class="col-lg-6">
		  <form id="responseform" class="form-create" action="userRatings.php?id=<?php echo $item_info['item_data']['seller_id'] ?>&iid=<?php echo $_GET['id']?>" method="POST"> 
			<h2>Your Response</h2>
			<p><textarea class="form-control" name="response"/></textarea></p>
			<button  class="btn btn-lg btn-primary btn-block" type="submit">Submit Feedback</button> 
		  </form>
      </div>    
      </div><!-- /.row -->
	  </div>
	  
      <?php require_once("../inc/footer.php"); ?>
