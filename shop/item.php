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
	
	if (!empty($pics['picture_data']))
	{
?>
<script language="JavaScript">
var num=1;
<?php
	$counter = 1;
	foreach($pics['picture_data'] as $pic_data)
	{
		echo "img$counter = new Image();\n";
		echo "img$counter.src = \"../" . $pic_data['url'] . "\";\n";
		$counter++;
	}
?>

function slideshowUp()
{
	var prev=num;
	num=num+1;
	if (num==<?php echo $counter ?>)
	{
		num=1;
	}
	document.mypic.src=eval("img"+num+".src");
	document.getElementById("sel"+num).className="glyphicon glyphicon-expand";
	document.getElementById("sel"+prev).className="glyphicon glyphicon-unchecked";
}

function slideshowBack()
{
	var prev=num;
	num=num-1;
	if (num==0)
	{
		num=<?php echo ($counter-1) ?>;
	}
	document.mypic.src=eval("img"+num+".src");
	document.getElementById("sel"+num).className="glyphicon glyphicon-expand";
	document.getElementById("sel"+prev).className="glyphicon glyphicon-unchecked";
}

</script>
<?php
	}
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
				?>	  
						<img class="img-thubmnail" name="mypic" src="../<?php echo $pics['picture_data'][0]['url']?>" style="height: auto; max-height: 200px; width: auto; max-width: 250px;" >
				<?php
					if(count($pics['picture_data']) > 1)
					{
				?>
						<br /><a href="JavaScript:slideshowBack()"><span class="glyphicon glyphicon-chevron-left"></span></a> <span class="glyphicon glyphicon-expand" id='sel1'></span>
				<?php
					for($i = 2; $i <= count($pics['picture_data']); $i++)
					{
						echo " <span class='glyphicon glyphicon-unchecked' id='sel$i'></span>";
					}
				?>
						<a href="JavaScript:slideshowUp()"><span class="glyphicon glyphicon-chevron-right"></span></a> 
				<?php
					}
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
