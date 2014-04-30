<?php 
    // Title:       shop/item.php
    // Desc:        Displays individual item auction page.  
    // Date:        March 22, 2014
    // Version:     1.0
    // Author:      John Meanor

    require_once("../inc/header.php");
	$test = itemInfo($_SESSION['item_id'], $db);
	$pics = getPics($_SESSION['item_id'], $db);
?>

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
            <img class="img-thumbnail" height="200" width="200" src="<?php $pics['picture_data']['url']?>" alt="Generic placeholder image">
            <ul class="pagination">
              <li class="active"><a href="<?php echo $pics['picture_data']['url']?>">1 <span class="sr-only">(current)</span></a></li>
              <li class="disabled"><a href="#">2</a></li>
              <li class="disabled"><a href="#">3</a></li>
              <li class="disabled"><a href="#">4</a></li>
            </ul>

          </center>
        </div><!-- /.col-lg-4 -->
        <div class="col-lg-5">
          <h2><b>Current Bid</b>: $<?php echo $test['item_data']['buy_it_now_price']?> <!--<span class="badge">12</span><br />--></h2>
          <h2><b>Buy it Now Price:</b> $<?php echo $test['item_data']['buy_it_now_price']?></h2>
		  <h2><b>Reserve Price:</b> $<?php echo $test['item_data']['reserve_price']?></h2>
          <h1></h1>
          <center><a class="btn btn-default btn-xs" class="btn btn-primary" href="../shop/bidding.php" role="button">Place a Bid or Buy It Now! &raquo;</a></center>
          <br/>
          <p><?php echo $test['item_data']['description']?></p>
        </div>
        <div class="col-lg-2">
          <h4>Seller Info</h4>
          <p><a><?php echo $test['item_data']['username']?></a></p>
          <p>Items Sold: 254</p>
          <p>Items Bought: 14</p>
          <p><?php echo $test['item_data']['public_location']?></p>
          <p><button type="button" class="btn btn-sm btn-default">Contact Seller</button></p>


        </div>
       
        <div class="col-lg-1"></div>
      </div><!-- /.row -->



      <?php require_once("../inc/footer.php"); ?>
