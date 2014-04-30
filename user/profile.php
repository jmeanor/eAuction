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

    if (!empty($_GET['userid'])) {
	  $data = getProfileData($_GET['userid'], $db);
	  $item_data = getItemData($_GET['userid'], $db);
	  $ratings_data = getRatingsData($_GET['userid'], $db);
	  $ratings_score = getRatingScore($_GET['userid'], $db);
	  $item_count = getItemCount($_GET['userid'], $db);
	  $sm_data = getSocialMedia($_GET['userid'], $db);
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

	  <?php if (!empty($sm_data['sm_data'][0]['username']))
	  { 
	  ?>        
			<div class="col-lg-4">
	        <h3><span class="glyphicon glyphicon-comment"></span> <?php echo $data['user_data']['username'] ?>'s Social Media </h3>
			<hr>
			
			<?php foreach ($sm_data['sm_data'] as $sm_info)
			{
				if ($row['sm_data']['sm_type'] = "tw")
				{
				?>
					<p><b>Twitter: </b> <?php echo $sm_info['username']?></p>
				<?php
				}
				
				else if ($row['sm_data']['sm_type'] = "fb")
				{
				?>
					<p><b>Facebook: </b> <?php echo $sm_info['username']?></p>
			<?php
				}
			}
	  }
	  ?>
        </div>

      <?php if (!empty($ratings_score['ratings_data']['avg_rating']))
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

	  <?php if (!empty($item_data['item_data'][0]['name']))
	  {
	  ?>
	    <div class="row">
        <div class="col-lg-1"></div>
        <div class="col-lg-9">
          <h3><span class="glyphicon glyphicon-shopping-cart"></span> <?php echo $data['user_data']['username'] ?>'s Items for Sale</h3>
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
			if (!empty($item_data['item_data'][$i]['name'])) { ?>
					  <div class="col-lg-3">
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

	  <?php if (!empty($ratings_data['ratings_data'][0]['name']))
	  {
	  ?>
	    <div class="row">
        <div class="col-lg-1"></div>
        <div class="col-lg-9">
          <h3><span class="glyphicon glyphicon-shopping-cart"></span> <?php echo $data['user_data']['username'] ?>'s Ratings</h3>
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
			if (!empty($ratings_data['ratings_data'][$i]['name'])) { ?>
					<div class="col-lg-3">
					<h3><?php echo $ratings_data['ratings_data'][$i]['name']?> : <?php echo $ratings_data['ratings_data'][$i]['score']?></h3>
					<p><b>Customer Message: </b><?php echo $ratings_data['ratings_data'][$i]['description']?></p>
					<p><b>Seller Response: </b><?php echo $ratings_data['ratings_data'][$i]['seller_response']?></p>
					</div>
		<?php
			}
		}
		?>
					<div class='col-lg-2'></div>
				</div>	  
      <?php } ?>

      <?php require_once("../inc/footer.php"); ?>
