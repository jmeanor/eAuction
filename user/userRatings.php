<?php 
    // Title:       userRatings.php
    // Desc:        Displays Ratings for users
    // Date:        March 22, 2014
    // Version:     1.0
    // Author:      John Meanor
	
    require_once("../inc/functions.php");    
    require_once("../inc/header.php");
	
	if(!empty($_POST))
	{
		$item_id = $_GET['iid'];
		$response = $_POST['response'];
		
		submitResponse($response, $item_id, $db);
	}
	
	 if (!empty($_GET['id'])) {
	  $data = getProfileData($_GET['id'], $db);
	  $item_data = getItemData($_GET['id'], $db);
	  $ratings_data = getRatingsData($_GET['id'], $db);
	}
    else {
      $data = getProfileData($_SESSION['user']['user_id'], $db);
	  $item_data = getItemData($_SESSION['user']['user_id'], $db);
	  $ratings_data = getRatingsData($_SESSION['user']['user_id'], $db);
    }
?>

<div class="container">
    <h1><?php echo $data['user_data']['username']?>'s Bought Items</h1>
    <hr>
		
    <!-- /.row -->

    <div class="row text-center">
	  <?php if ($ratings_data['success'] == true)
	  {
		foreach($ratings_data['ratings_data'] as $item_info)
		{
			$pics = getPics($item_info['item_id'], $db);
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
					<h3><?php echo $item_info['name']?></h3>
					<p><b>User's Rating: </b> <?php echo $item_info['description']?></p>
					<?php if ($data['user_data']['user_id'] == $_SESSION['user']['user_id'] && $item_info['seller_response'] == null)
					{
					?>
						<a class="btn btn-default btn-xs" href="../user/addResponse.php?id=<?php echo $item_info['item_id']?>" role="button"> Respond to Rating &raquo;</a>
					<?php
					}
					if ($item_info['seller_response'] != null)
					{
					?>
					<p><b><?php echo $data['user_data']['name']?>'s Response: </b> <?php echo $item_info['seller_response']?></p>
					<?php
					}
					?>
					</p>
				</div>
			</div>
		</div>
		
	  <?php
		}
	  }
	  ?>
	</div>
	<!-- /.row -->


    <?php require_once("../inc/footer.php"); ?>