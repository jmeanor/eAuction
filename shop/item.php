<?php 
    // Title:       shop/item.php
    // Desc:        Displays individual item auction page.  
    // Date:        March 22, 2014
    // Version:     1.0
    // Author:      John Meanor

    require_once("../inc/header.php");
	$item_id = $_GET['id'];
	
	$test = itemInfo($item_id, $db);
	$pics = getPics($item_id, $db);
	$total_bought = itemsBought($test['item_data']['seller_id'], $db);
    $item_count = getItemCount($test['item_data']['seller_id'], $db);
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
		  <h1><?php echo $test['item_data']['name']?></h1>
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
			  <?php if (!empty($pics['picture_data']))
			  {
					$counter = 1;
			  ?>  	  
					<ul class="pagination">	
					<?php foreach ($pics['picture_data'] as $pic_info)
					{
					?>
						<img class="img-thubmnail" src="../<?php echo $pic_info['url']?>" height="200">
						<li class="active"><a><?php echo $counter ?> <span class="sr-only">(current)</span></a></li>	
					<?php
						$counter++;
					}
					?>
					</ul>
				<?php
				}
			  ?>

          </center>
        </div><!-- /.col-lg-4 -->
        <div class="col-lg-5">
          <h2><b>Current Bid</b>: $<?php echo $test['item_data']['buy_it_now_price']?> <!--<span class="badge">12</span><br />--></h2>
          <h2><b>Buy it Now Price:</b> $<?php echo $test['item_data']['buy_it_now_price']?></h2>
          <h1></h1>
          <center><a class="btn btn-default btn-xs" class="btn btn-primary" href="../shop/bidding.php?id=<?php echo $item_id ?>" role="button">Place a Bid or Buy It Now! &raquo;</a></center>
          <br/>
          <p><?php echo $test['item_data']['description']?></p>
        </div>
        <div class="col-lg-2">
          <h4>Seller Info</h4>
          <p><b><?php echo $test['item_data']['username']?></b></p>
		  <?php if (!empty($total_bought))
		  {
		  ?>
			<p>Items Bought: <?php echo $total_bought['bid_data']['bids_won_count']?></p>
		  <?php
		  }
		  ?>
		  <?php if (!empty($total_bought))
		  {
		  ?>
			<p>Items Sold: <?php echo $item_count['user_data']['COUNT(u.user_id)'] ?></p>
		  <?php
		  }
		  ?>          
		  <p><?php echo $test['item_data']['public_location']?></p>
          <p><a class="btn btn-default btn-xs" class="btn btn-primary" href="../user/profile.php?id=<?php echo $test['item_data']['seller_id']?>" role="button">View Profile &raquo;</a></p>


        </div>
       
        <div class="col-lg-1"></div>
      </div><!-- /.row -->



      <?php require_once("../inc/footer.php"); ?>
