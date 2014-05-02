<?php 
    // Title:       shop/item.php
    // Desc:        Displays individual item auction page.  
    // Date:        March 22, 2014
    // Version:     1.0
    // Author:      John Meanor
    require_once("../inc/functions.php");
	require_once("../inc/header.php");
    checkPermissions();

    require_once("../inc/header.php");

    if (!empty($_GET['id'])) {
	  $data = getProfileData($_GET['id'], $db);
	  $item_data = getItemData($_GET['id'], $db);
	  $ratings_data = getRatingsData($_GET['id'], $db);
	  $ratings_score = getRatingScore($_GET['id'], $db);
	  $item_count = getItemCount($_GET['id'], $db);
	  $sm_data = getSocialMedia($_GET['id'], $db);
	}
    else {
      $data = getProfileData($_SESSION['user']['user_id'], $db);
	  $item_data = getItemData($_SESSION['user']['user_id'], $db);
	  $ratings_score = getRatingScore($_SESSION['user']['user_id'], $db);
	  $ratings_data = getRatingsData($_SESSION['user']['user_id'], $db);
	  $item_count = getItemCount($_SESSION['user']['user_id'], $db);
	  $sm_data = getSocialMedia($_SESSION['user']['user_id'], $db);
    }
?>
    <div class="container">

      <?php // Check for error to display, else, display the profile data 
            if (!$data['success']) 
			{ ?>
				<div class="alert alert-danger"><?php echo $data['message']; ?></div>
      <?php } else { ?>

      <div class="row">
        <div class="col-lg-12">
          <h1><?php echo $data['user_data']['name'] ?></h1>
          <h3><span class="glyphicon glyphicon-user"></span> <?php echo $data['user_data']['username'] ?>
		  <?php if (empty($_GET['id'])) 
		  { ?>
			<a class="btn btn-default btn-xs" href="../user/updateInfo.php" role="button"> Update Your Contact Information &raquo;</a> <a class="btn btn-default btn-xs" href="../user/addSocialMedia.php" role="button"> Add Social Media &raquo;</a> <a class="btn btn-default btn-xs" href="../shop/newAuction.php" role="button"> Create an Auction &raquo;</a> 
			<?php if ($data['user_data']['user_type'] == "person") 
			{?>
			  <a class="btn btn-default btn-xs" href="../shop/editCard.php" role="button">Add/Remove Credit Card &raquo;</a> <a class="btn btn-default btn-xs" href="../user/boughtItems.php" role="button"> View Bought Items&raquo;</a>
			<?php } ?>
		  <?php 
		  } 
		  ?>
		  </h3>
          <hr />
        </div>
      </div><!-- /row -->

      <div class="row">

	  <?php if ($sm_data['success'] == true)
	  { 
	  ?>        
		<div class="col-lg-4">
	    <h3><span class="glyphicon glyphicon-comment"></span> <?php echo $data['user_data']['username'] ?>'s Social Media</h3>
		<hr>
			
		<?php foreach ($sm_data['sm_data'] as $sm_info)
		{
			if ($sm_info['sm_type'] == "tw")
			{
			?>
				<p><b>Twitter: </b> <?php echo $sm_info['username']?></p>
			<?php
			}
			
			else if ($sm_info['sm_type'] == "fb")
			{
			?>
				<p><b>Facebook: </b> <?php echo $sm_info['username']?></p>
		<?php
			}
		}
	  }
	  ?>
      </div>

      <?php if ($ratings_score['success'] == true && $ratings_data['success'] == true)
		{
		?>
			<div class="col-lg-4">
			  <h3><span class="glyphicon glyphicon-thumbs-up"></span> <?php echo $data['user_data']['username'] ?>'s Feedback</h3>
			  <hr>
			  <p><b>Score:</b> <?php echo number_format(($ratings_score['ratings_data']['avg_rating']), 2)?></p>
			  <p><b>Number of Items Sold: </b> <?php echo $item_count['user_data']['COUNT(u.user_id)'] ?></p>
			</div>
	  <?php
		}
	    ?>
        
        <div class="col-lg-4">
          <h3><span class="glyphicon glyphicon-info-sign"></span> <?php echo $data['user_data']['username'] ?>'s Contact Information</h3>
		  <hr>
          <div class="well">
            <p><b>Phone:</b> <?php echo $data['user_data']['phone_number']; ?> </p>
            <p><b>Email:</b> <?php echo $data['user_data']['email']; ?></p>
          </div>
        </div>
      </div>

	  <?php if ($item_data['success'] == true)
	  {
	  ?>
	    <div class="row">
        <div class="col-lg-1"></div>
        <div class="col-lg-9">
          <h3><span class="glyphicon glyphicon-shopping-cart"></span> <?php echo $data['user_data']['username'] ?>'s Items for Sale <a class="btn btn-default btn-xs" href="../user/userItems.php?id=<?php echo $data['user_data']['user_id']?>" name="option1" role="button">See all &raquo;</a></h3>

          <hr>
          </div>
        <div class="col-lg-1"></div>
		</div>
		
	  <?php
	  }
	  ?>
				<div class="row">
					<div class="col-lg-1"></div>
	  <?php 
		for ($i = 0; $i<3; $i++) {
			if (!empty($item_data['item_data'][$i]['name'])) { 
				$pics = getPics($item_data['item_data'][$i]['item_id'], $db);?>
					  <div class="col-lg-3">
					<?php if ($pics['success'] == true)
					{
					?>
					<center><img height="200" src="../<?php echo $pics['picture_data'][0]['url']?>" alt="Generic placeholder image" style="height: auto; max-height: 200px; width: auto; max-width: 250px;"></center>
					<?php
					}
					else
					{
					?>
						<center><img height="200" src="../shop/images/placeholder1.jpg" alt="Generic placeholder image" style="height: auto; max-height: 200px; width: auto; max-width: 250px;"></center>
					<?php
					}
					?>					  
						<h3><?php echo $item_data['item_data'][$i]['name'] ?></h3>
						<p><?php echo $item_data['item_data'][$i]['description']?></p>
						<p><a class="btn btn-default btn-xs" href="../shop/item.php?id=<?php echo $item_data['item_data'][$i]['item_id']?>" name="option1" role="button">View details &raquo;</a></p>
					  </div>
		<?php
			}
		}
		?>
			<div class='col-lg-2'></div>
		</div>

	  <?php if ($ratings_data['success'] == true)
	  {
	  ?>
	    <div class="row">
        <div class="col-lg-1"></div>
        <div class="col-lg-9">
          <h3><span class="glyphicon glyphicon-ok"></span> <?php echo $data['user_data']['username'] ?>'s Ratings <a class="btn btn-default btn-xs" href="../user/userRatings.php?id=<?php echo $data['user_data']['user_id']?>" name="option1" role="button">See more details &raquo;</a></h3>
          <hr>
          </div>
        <div class="col-lg-1"></div>
		</div>
	  <?php
	  }
	  ?>

	  				<div class="row">
					<div class="col-lg-1"></div>
	  <?php 
		for ($i = 0; $i<3; $i++) {
			if (!empty($ratings_data['ratings_data'][$i]['name'])) { 
				$pics = getPics($ratings_data['ratings_data'][$i]['item_id'], $db);?>
					<div class="col-lg-3">
					<?php if ($pics['success'] == true)
					{
					?>
					<center><img height="200" src="../<?php echo $pics['picture_data'][0]['url']?>" alt="Generic placeholder image" style="height: auto; max-height: 200px; width: auto; max-width: 250px;"></center>
					<?php
					}
					else
					{
					?>
						<center><img height="200" src="../shop/images/placeholder1.jpg" alt="Generic placeholder image" style="height: auto; max-height: 200px; width: auto; max-width: 250px;"></center>
					<?php
					}
					?>
					<h3><?php echo $ratings_data['ratings_data'][$i]['name']?> : <?php echo $ratings_data['ratings_data'][$i]['score']?></h3>
					<p><b>Customer Message: </b><?php echo $ratings_data['ratings_data'][$i]['description']?></p>
					</div>
		<?php
			}
		}
		?>
					<div class='col-lg-2'></div>
				</div>	  
      <?php } ?>

      <?php require_once("../inc/footer.php"); ?>
