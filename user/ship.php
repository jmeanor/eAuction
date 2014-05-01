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
    
    if(isset($_POST['phase']))
		$phase = $_POST['phase'];
	else
		$phase = '';


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