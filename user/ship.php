<?php 
    // Title:       home.php
    // Desc:        Main display for logged in users.
    // Date:        March 22, 2014
    // Version:     1.0
    // Author:      John Meanor
    require_once("../inc/header.php");
    checkPermissions();

    // Only admins have access to this page.
    if (!isAdmin()) {

        header("Location: ../home.php"); 
        die("Not authorized administrator."); 
    }
    
    $null_date_string = '0000-00-00 00:00:00';
    
    if(isset($_POST['phase']))
		$phase = $_POST['phase'];
	else
		$phase = '';
      
   if($phase == 'received')
   {
      $item_id = $_POST['item'];
      $query = "UPDATE won_items SET item_received_date=NOW() WHERE item_id=:item_id";
      $query_params = array(':item_id' => $item_id);
      try 
      { 
         // Execute the query against the database 
         $stmt = $db->prepare($query); 
         $result = $stmt->execute($query_params); 
      } 
      catch(PDOException $ex) 
      { 
         die($ex);
      } 
      $phase = 'item';
   }
   elseif($phase == 'unreceived')
   {
      $item_id = $_POST['item'];
      $query = "UPDATE won_items SET item_received_date=:null_date WHERE item_id=:item_id";
      $query_params = array(':null_date' => $null_date_string, ':item_id' => $item_id);
      try 
      { 
         // Execute the query against the database 
         $stmt = $db->prepare($query); 
         $result = $stmt->execute($query_params); 
      } 
      catch(PDOException $ex) 
      { 
         die($ex);
      } 
      $phase = 'item';
   }
   elseif($phase == 'isent')
   {
      $item_id = $_POST['item'];
      $query = "UPDATE won_items SET item_sent_date=NOW() WHERE item_id=:item_id";
      $query_params = array(':item_id' => $item_id);
      try 
      { 
         // Execute the query against the database 
         $stmt = $db->prepare($query); 
         $result = $stmt->execute($query_params); 
      } 
      catch(PDOException $ex) 
      { 
         die($ex);
      } 
      $phase = 'item';
   }
   elseif($phase == 'iunsent')
   {
      $item_id = $_POST['item'];
      $query = "UPDATE won_items SET item_sent_date=:null_date WHERE item_id=:item_id";
      $query_params = array(':null_date' => $null_date_string, ':item_id' => $item_id);
      try 
      { 
         // Execute the query against the database 
         $stmt = $db->prepare($query); 
         $result = $stmt->execute($query_params); 
      } 
      catch(PDOException $ex) 
      { 
         die($ex);
      } 
      $phase = 'item';
   }
   elseif($phase == 'charged')
   {
      $item_id = $_POST['item'];
      $query = "UPDATE won_items SET card_charged_date=NOW() WHERE item_id=:item_id";
      $query_params = array(':item_id' => $item_id);
      try 
      { 
         // Execute the query against the database 
         $stmt = $db->prepare($query); 
         $result = $stmt->execute($query_params); 
      } 
      catch(PDOException $ex) 
      { 
         die($ex);
      } 
      $phase = 'item';
   }
   elseif($phase == 'uncharged')
   {
      $item_id = $_POST['item'];
      $query = "UPDATE won_items SET card_charged_date=:null_date WHERE item_id=:item_id";
      $query_params = array(':null_date' => $null_date_string, ':item_id' => $item_id);
      try 
      { 
         // Execute the query against the database 
         $stmt = $db->prepare($query); 
         $result = $stmt->execute($query_params); 
      } 
      catch(PDOException $ex) 
      { 
         die($ex);
      } 
      $phase = 'item';
   }
   elseif($phase == 'csent')
   {
      $item_id = $_POST['item'];
      $query = "UPDATE won_items SET check_mailed_date=NOW() WHERE item_id=:item_id";
      $query_params = array(':item_id' => $item_id);
      try 
      { 
         // Execute the query against the database 
         $stmt = $db->prepare($query); 
         $result = $stmt->execute($query_params); 
      } 
      catch(PDOException $ex) 
      { 
         die($ex);
      } 
      $phase = 'item';
   }
   elseif($phase == 'cunsent')
   {
      $item_id = $_POST['item'];
      $query = "UPDATE won_items SET check_mailed_date=:null_date WHERE item_id=:item_id";
      $query_params = array(':null_date' => $null_date_string, ':item_id' => $item_id);
      try 
      { 
         // Execute the query against the database 
         $stmt = $db->prepare($query); 
         $result = $stmt->execute($query_params); 
      } 
      catch(PDOException $ex) 
      { 
         die($ex);
      } 
      $phase = 'item';
   }
   elseif($phase == 'successful')
   {
      $item_id = $_POST['item'];
      $query = "UPDATE won_items SET successful_date=NOW() WHERE item_id=:item_id";
      $query_params = array(':item_id' => $item_id);
      try 
      { 
         // Execute the query against the database 
         $stmt = $db->prepare($query); 
         $result = $stmt->execute($query_params); 
      } 
      catch(PDOException $ex) 
      { 
         die($ex);
      } 
      $phase = 'item';
   }
   elseif($phase == 'failure')
   {
      $item_id = $_POST['item'];
      $query = "UPDATE won_items SET failure_notification_date=NOW() WHERE item_id=:item_id";
      $query_params = array(':item_id' => $item_id);
      try 
      { 
         // Execute the query against the database 
         $stmt = $db->prepare($query); 
         $result = $stmt->execute($query_params); 
      } 
      catch(PDOException $ex) 
      { 
         die($ex);
      } 
      $phase = 'item';
   }
   elseif($phase == 'unsuccessful')
   {
      $item_id = $_POST['item'];
      $query = "UPDATE won_items SET successful_date=:null_date WHERE item_id=:item_id";
      $query_params = array(':null_date' => $null_date_string, ':item_id' => $item_id);
      try 
      { 
         // Execute the query against the database 
         $stmt = $db->prepare($query); 
         $result = $stmt->execute($query_params); 
      } 
      catch(PDOException $ex) 
      { 
         die($ex);
      } 
      $phase = 'item';
   }


	if($phase == '')
	{
		$query = "SELECT i.item_id, i.name as item_name, u.name as user_name, w.date_won
				  FROM items i, won_items w, users u 
				  WHERE i.item_id=w.item_id 
				  AND u.user_id=i.seller_id 
				  ORDER BY w.date_won DESC";
		try 
        { 
            // Execute the query against the database 
            $stmt = $db->prepare($query); 
            $result = $stmt->execute(); 
        } 
        catch(PDOException $ex) 
        { 
            die($ex);
        } 
		$data = $stmt->fetchAll(); 
?>

<div class="container">
    <div class="row" align="center">
		<div class="col-lg-12">
			<form action="ship.php" method="POST">
				<input type='hidden' id='phase' name='phase' value='item' />
				<label for="item">Item:</label>
				<select name='item' id='item'>
<?php
		foreach($data as $row)
		{
			echo "<option value='" . $row['item_id'] . "'>" . $row['item_name'] . " (" . $row['user_name'] . ") " . $row['date_won'] . "</option>\n";
		}
?>
				</select>
				<br /><br />
				<button class="btn btn-lg btn-primary">Administrate Item</button>
			</form>

		</div>
	</div>
        <!-- /.row -->
<?php
	}
    elseif($phase == 'item')
	{
		$item_id = $_POST['item'];
		$query = "SELECT i.name as item_name, u.name as user_name, w.date_won, 
				  w.item_received_date, w.item_sent_date, w.card_charged_date, 
				  w.check_mailed_date, w.successful_date, w.failure_notification_date, 
				  (SELECT u.name FROM users u, credit_cards c, bids b WHERE u.user_id=c.user_id AND c.card_id=b.card_id AND b.bid_id=w.winning_bid) as buyer_name
				  FROM items i, won_items w, users u 
				  WHERE i.item_id=w.item_id 
				  AND u.user_id=i.seller_id 
				  AND i.item_id=:item_id";
		$query_params = array(
			':item_id' => $item_id
		);
		try 
        { 
            // Execute the query against the database 
            $stmt = $db->prepare($query); 
            $result = $stmt->execute($query_params); 
        } 
        catch(PDOException $ex) 
        { 
            die($ex);
        } 
		$row = $stmt->fetch(); 
		if($row['failure_notification_date'] == '')
		{
         $item_wdate = date('D, M. j, Y h:i:s A', strtotime($row['date_won']));
         
         $ready_success = true;
         
         if($row['item_received_date'] != $null_date_string)
         {
            $item_rdate = date('D, M. j, Y h:i:s A', strtotime($row['item_received_date']));
            $ready_success = $ready_success & true;
         }
         else 
         {
            $item_rdate = "N/A"; 
            $ready_success = $ready_success & false;
         }
            
         if($row['item_sent_date'] != $null_date_string)
         {
            $item_sdate = date('D, M. j, Y h:i:s A', strtotime($row['item_sent_date']));
            $ready_success = $ready_success & true;
         }
         else         
         {
            $item_sdate = "N/A";
            $ready_success = $ready_success & false;
         }
            
         if($row['card_charged_date'] != $null_date_string)
         {
            $card_cdate = date('D, M. j, Y h:i:s A', strtotime($row['card_charged_date']));
            $ready_success = $ready_success & true;
         }
         else  
         {
            $card_cdate = "N/A";
            $ready_success = $ready_success & false;
         }
         
         if($row['check_mailed_date'] != $null_date_string)
         {
            $card_mdate = date('D, M. j, Y h:i:s A', strtotime($row['check_mailed_date']));
            $ready_success = $ready_success & true;
         }
         else  
         {
            $card_mdate = "N/A";
            $ready_success = $ready_success & false;
         }   
         
         if($row['successful_date'] != $null_date_string)
            $success_date = date('D, M. j, Y h:i:s A', strtotime($row['successful_date']));
         else  
            $success_date = false;
?>
<div class="container">
   <form method='post' action='ship.php'>
    <input type='hidden' id='phase' name='phase' value='' />
    <input type='hidden' id='item' name='item' value='<?php echo $item_id ?>' />
  <div class="row">
    <div class="col-md-1">
        </div>
        <div class="col-md-10">
          <h4></h4>
		  <h1><?php echo $row['item_name']?></h1>
          <hr />
		  <p>Seller: <?php echo $row['user_name'] ?></p>
		  <p>Buyer: <?php echo $row['buyer_name'] ?></p>
		  <p>Date Auction Won: <?php echo $item_wdate ?></p>
        </div>
        <div class="col-md-1">
        </div>
    </div>
    
    <div class="row">
    <div class="col-md-1">
        </div>
        <div class="col-md-10">
        <h2>Shipping Details</h2>
        <hr />
        </div>
        <div class="col-md-1">
        </div>
    </div>
    
    <div class="row">
    <div class="col-md-1">
        </div>
        <div class="col-md-4 well">
        <p>Item Received From Seller: <?php echo $item_rdate ?></p>
        <?php if(!$success_date) { if($item_rdate == 'N/A') { ?>
        <button  class="btn btn-lg btn-success btn-block" type="submit" onclick="document.getElementById('phase').value = 'received';">Mark Item Received</button>
        <?php } else { ?>
        <button  class="btn btn-lg btn-danger btn-block" type="submit" onclick="document.getElementById('phase').value = 'unreceived';">Mark Item Not Received</button>
        <?php } } ?>
        </div>
        <div class="col-md-2">
        </div>
        <div class="col-md-4 well">
        <p>Item Sent To Buyer: <?php echo $item_sdate ?></p>
        <?php if(!$success_date) { if($item_sdate == 'N/A') { ?>
        <button  class="btn btn-lg btn-success btn-block" type="submit" onclick="document.getElementById('phase').value = 'isent';">Mark Item Sent</button>
        <?php } else { ?>
        <button  class="btn btn-lg btn-danger btn-block" type="submit" onclick="document.getElementById('phase').value = 'iunsent';">Mark Item Not Sent</button>
        <?php } } ?>
        </div>
        <div class="col-md-1">
        </div>
    </div>
    
    <div class="row">
    <div class="col-md-1">
        </div>
        <div class="col-md-10">
        <h2>Money Details</h2>
        <hr />
        </div>
        <div class="col-md-1">
        </div>
    </div>
    
    <div class="row">
    <div class="col-md-1">
        </div>
        <div class="col-md-4 well">
        <p>Buyer Card Charged: <?php echo $card_cdate ?></p>
        <?php if(!$success_date) { if($card_cdate == 'N/A') { ?>
        <button  class="btn btn-lg btn-success btn-block" type="submit" onclick="document.getElementById('phase').value = 'charged';">Mark Card Charged</button>
        <?php } else { ?>
        <button  class="btn btn-lg btn-danger btn-block" type="submit" onclick="document.getElementById('phase').value = 'uncharged';">Mark Card Not Charged</button>
        <?php } } ?>
        </div>
        <div class="col-md-2">
        </div>
        <div class="col-md-4 well">
        <p>Check Mailed To Seller: <?php echo $card_mdate ?></p>
        <?php if(!$success_date) { if($card_mdate == 'N/A') { ?>
        <button  class="btn btn-lg btn-success btn-block" type="submit" onclick="document.getElementById('phase').value = 'csent';">Mark Check Mailed</button>
        <?php } else { ?>
        <button  class="btn btn-lg btn-danger btn-block" type="submit" onclick="document.getElementById('phase').value = 'cunsent';">Mark Check Not Mailed</button>
        <?php } } ?>
        </div>
        <div class="col-md-1">
        </div>
    </div>
    
    <div class="row">
    <div class="col-md-1">
        </div>
        <div class="col-md-10">
        <h2>Auction Administration</h2>
        <hr />
        </div>
        <div class="col-md-1">
        </div>
    </div>
    
    <div class="row">
    <div class="col-md-1">
        </div>
        <div class="col-md-10 well">
        <p>Auction Marked Successful: <?php echo (!$success_date ? "N/A" : $success_date) ?></p>
        <?php if(!$success_date && $ready_success) { ?>
        <button  class="btn btn-lg btn-success btn-block" type="submit" onclick="document.getElementById('phase').value = 'successful';">Mark Auction Completed Successfully</button>
		<?php } ?>
        <button  class="btn btn-lg btn-danger btn-block" type="submit" onclick="document.getElementById('phase').value = 'failure';">Notified Participants of Failure</button>
        <?php if($success_date != false) { ?>
        <button  class="btn btn-lg btn-danger btn-block" type="submit" onclick="document.getElementById('phase').value = 'unsuccessful';">Mark Auction Not Complete</button>
        <?php } ?>
        </div>
        <div class="col-md-1">
        </div>
    </div>
    
    </form>
</div>
<?php
		}
		else
		{
?>
<div class="container">
  <div class="row">
    <div class="col-md-1">
        </div>
        <div class="col-md-10">
          <h4></h4>
		  <h1><?php echo $row['item_name']?></h1>
          <hr />
		  <p>Seller: <?php echo $row['user_name'] ?></p>
		  <p>Buyer: <?php echo $row['buyer_name'] ?></p>
        </div>
        <div class="col-md-1">
        </div>
    </div>
	<br />
	<br />
	<div class="row">
		<div class="col-lg-13">
			<h1>This item was marked for failure on <?php echo $row['failure_notification_date'] ?>.</h1>
		</div>
	</div>
</div>
<?php
		}	
	}
		
require_once("../inc/footer.php"); 
?>