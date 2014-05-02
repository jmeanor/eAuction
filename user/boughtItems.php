<?php 
    // Title:       boughtItems.php
    // Desc:        Displays the items that the user has bought.
    // Date:        March 22, 2014
    // Version:     1.0
    // Author:      John Meanor
    require_once("../inc/functions.php");    
    require_once("../inc/header.php");
	
	if(!empty($_POST))
	{
		$item_id = $_GET['iid'];
	}
	
	if (!empty($_GET['id'])) {
	  $data = getProfileData($_GET['id'], $db);
	}
    else {
      $data = getProfileData($_SESSION['user']['user_id'], $db);
    }
	$bought_items = getBoughtItems($data['user_data']['user_id'], $db);
	
?>

<div class="container">
    <h1><?php echo $data['user_data']['username']?>'s Bought Items</h1>
    <hr>
		
    <!-- /.row -->

    <div class="row text-center">
	  <?php if ($bought_items['success'] == true)
	  {
		foreach($bought_items['item_data'] as $bought_info)
		{
			$pics = getPics($bought_info['item_id'], $db);
			$rating_info = itemRatingsData($bought_info['item_id'], $db);
	  ?>
		<div class="col-lg-3 col-md-6 hero-feature">
			<div class="thumbnail">
				<?php if ($pics['picture_data'][0]['url'] != null)
				{
				?>
					<img src="../<?php echo $pics['picture_data'][0]['url']?>" alt="" height="200">
				<?php
				}
				else
				{
				?>
					<img src="../shop/images/placeholder1.jpg" alt ="" height="200">
				<?php
				}
				?>
				<div class="caption">
					<h3><?php echo $bought_info['name']?></h3>
					<p><b>Info: </b><?php echo $bought_info['description']?></p>
					<?php if ($data['user_data']['user_id'] == $_SESSION['user']['user_id'])
					{
						if($rating_info['ratings_data'][0]['score'] != null)
						{ ?>
							<p><b>Score: </b><?php echo $rating_info['ratings_data'][0]['score']?></p>
						<?php
						}
						if($rating_info['ratings_data'][0]['description'] == null)
						{ ?>
							<a class="btn btn-default btn-xs" href="../shop/rating.php?id=<?php echo $data['user_data']['user_id']?>&iid=<?php echo $bought_info['item_id']?>" role="button"> Rate this Item &raquo;</a>
						<?php
						}
						else
						{ ?>
							<p><b>Rating: </b><?php echo $rating_info['ratings_data'][0]['description']?></p>
						<?php
						}
						if($rating_info['ratings_data'][0]['seller_response'] != null)
						{ ?>
							<p><b>Score: </b><?php echo $rating_info['ratings_data'][0]['seller_response']?></p>
						<?php
						}
						?>
				</div>
			</div>
		</div>
		
	  <?php
		}
	  }
	  }
	  ?>
	</div>
	<!-- /.row -->


    <?php require_once("../inc/footer.php"); ?>