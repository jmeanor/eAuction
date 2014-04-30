<?php 
    // Title:       shop/bidding.php
    // Desc:        Displays bidding page.
    // Date:        April 14, 2014
    // Version:     1.0
    // Author:      Tom Byrne
	//
	// TO DO
    require_once("../inc/header.php");
  	$test = itemInfo($_SESSION['item_id'], $db);
	$highest_bid = highestBid($_SESSION['item_id'], $db);
	
	$submitted_bid = "";
	
	var_dump($test);
?>
<style type="text/css">
  body {
    padding-top: 40px;
    padding-bottom: 40px;
    background-color: #eee;
  }

  .form-signin {
    max-width: 330px;
    padding: 15px;
    margin: 0 auto;
  }
  .form-signin .form-signin-heading,
  .form-signin .checkbox {
    margin-bottom: 10px;
  }
  .form-signin .checkbox {
    font-weight: normal;
  }
  .form-signin .form-control {
    position: relative;
    font-size: 16px;
    height: auto;
    padding: 10px;
    -webkit-box-sizing: border-box;
       -moz-box-sizing: border-box;
            box-sizing: border-box;
  }
  .form-signin .form-control:focus {
    z-index: 2;
  }
  .form-signin input[type="text"] {
    margin-bottom: -1px;
    border-bottom-left-radius: 0;
    border-bottom-right-radius: 0;
  }
  .form-signin input[type="password"] {
    margin-bottom: 10px;
    border-top-left-radius: 0;
    border-top-right-radius: 0;
  }
</style>


    <div class="container">
      <div class="row">
        <div class="col-md-1">
        </div>
        <div class="col-md-10">
          <h4></h4>
		  <h1>Bid on/Buy <?php echo $test['item_data']['name'] ?></h1>
          <a class="btn btn-default" href="../shop/item.php" type="button">Back to Item Description &raquo;</a>
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
            <img class="img-thumbnail" height="200" width="200" src="../inc/img/2.jpg" alt="Generic placeholder image"> <?php //echo photo here ?>
          </center>
        </div><!-- /.col-lg-4 -->
        <div class="col-lg-5">
          <h2><b>Current Bid</b>: $<?php echo $highest_bid['item_data']['price']?> <span class="badge">12</span><br />
          <b>Buy it Now Price:</b> $<?php echo $test['item_data']['buy_it_now_price'] ?>
          <h1></h1>
		  
		  <form id="bidform" class="form-signin" action="bidding.php" method="POST"> 
          <h2 class="form-signin-heading">Place Your Bid</h2>
            <input class="form-control" type="text" name="bid" placeholder="Place your bid!"  value="<?php echo $submitted_bid; ?>" autofocus /> 
            <button  class="btn btn-lg btn-primary btn-block" type="submit">Place Bid!</button>
			<button  class="btn btn-lg btn-primary btn-block" type="submit">Buy it Now!</button> 			
        </form>
		  
        <br/>
        </div>
        <div class="col-lg-2">
          <h4>Seller Info</h4>
          <p>Sold by <a>temp<?php // echo username?></a></p>
          <p>Items Sold: <?php // echo items sold count? ?>254</p>
          <p>Items Bought: <?php // echo items bought count? ?>14</p>
          <p>Location: <?php // echo user location ?>United Kingdom</p>
          <p><button type="button" class="btn btn-sm btn-default">Contact Seller</button></p>


        </div>
       
        <div class="col-lg-1"></div>
      </div><!-- /.row -->



      <?php require_once("../inc/footer.php"); ?>
