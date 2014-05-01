<?php 
    // Title:       shop/bidding.php
    // Desc:        Displays bidding page.
    // Date:        April 14, 2014
    // Version:     1.0
    // Author:      Tom Byrne
	//
	// TO DO
    require_once("../inc/header.php");
	$item_id = $_GET['id'];
  	if(isset($_POST['phase']))
		$phase = $_POST['phase'];
	else
		$phase = '';
	
	$item = itemInfo($item_id, $db);
	$pics = getPics($item_id, $db);
	$cards = getCards($_SESSION['user']['user_id'], $db);
	$highest_bid = highestBid($item_id, $db);
	$total_bought = itemsBought($item['item_data']['seller_id'], $db);
    $item_count = getItemCount($item['item_data']['seller_id'], $db);
	if($highest_bid != '0.00')
	{
		$min_bid = floatval($highest_bid) * 1.05;
	}
	elseif(floatval($item['item_data']['starting_price']) >= floatval('1.00'))
	{
		$min_bid = floatval($item['item_data']['starting_price']) * 1.05;
	}
	else
	{
		$min_bid = floatval('1.00');
	}
	$count = numBids($item_id, $db);
	$message = '';
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
<?php
		
	if($phase == 'bid')
	{
		$user_bid = floatval(str_replace('$', '', trim($_POST['bid'])));
		
	}
	elseif($phase == 'buy')
	{
	
	}
	
	if($phase == '')
	{
	
		
?>

    <div class="container">
      <div class="row">
        <div class="col-md-1">
        </div>
        <div class="col-md-10">
          <h4></h4>
		  <h1>Bid on <?php echo $item['item_data']['name'] ?></h1>
          <a class="btn btn-default" href="../shop/item.php?id=<?php echo $item_id ?>" type="button">Back to Item Description &raquo;</a>
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
          <h2><b>Current Bid</b>: $<?php echo $highest_bid ?> <span class="badge"><?php echo $count ?></span><br />
		  <?php if($item['item_data']['buy_it_now_price'] != '0.00')
		  {
		  ?>
          <b>Buy it Now Price:</b> $<?php echo $item['item_data']['buy_it_now_price'] ?></h2>
		  <?php
		  }
		  ?>
          <h1></h1>
		  
		  <form id="bidform" class="form-signin" action="bidding.php?id=<?php echo $item_id ?>" method="POST"> 
		  <input type='hidden' id='phase' name='phase' value='' />
          <h2 class="form-signin-heading">Place Your Bid</h2>
		<?php if($cards['success'] == true)
		{
		?>
            <p>Use Card: <select name='card' id='card'>
			<?php
				foreach($cards['card_data'] as $card_info)
				{
					echo "<option value='" . $card_info['card_id'] . "'>" . $card_info['card_type'] . " " . substr($card_info['card_number'], -4) . " " . $card_info['expiration'] . "</option>";
				}
			?></select></p>
			<input class="form-control" type="text" name="bid" placeholder="Minimum bid: $<?php echo number_format($min_bid, 2) ?>" autofocus /> 
            <button  class="btn btn-lg btn-primary btn-block" type="submit" onclick="document.getElementById('phase').value = 'bid';">Place Bid!</button>
			<?php if($item['item_data']['buy_it_now_price'] != '0.00')
			  {
			  ?>
			<button  class="btn btn-lg btn-primary btn-block" type="submit" onclick="document.getElementById('phase').value = 'buy';">Buy it Now!</button> 			
			<?php
			  }
		}
		else
		{
		?>
		<p>You must have a card on file in order to bid.</p>
		<?php
		}
		?>
        </form>
		  
        <br/>
        </div>
        <div class="col-lg-2">
          <h4>Seller Info</h4>
          <p><b><?php echo $item['item_data']['username']?></b></p>
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
		  <p><?php echo $item['item_data']['public_location']?></p>
          <p><a class="btn btn-default btn-xs" class="btn btn-primary" href="../user/profile.php?id=<?php echo $item['item_data']['seller_id']?>" role="button">View Profile &raquo;</a></p>


        </div>
       
        <div class="col-lg-1"></div>
      </div><!-- /.row -->



      <?php 
	  }
	  require_once("../inc/footer.php"); ?>
