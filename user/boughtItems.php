<?php 
    // Title:       home.php
    // Desc:        Main display for logged in users.
    // Date:        March 22, 2014
    // Version:     1.0
    // Author:      John Meanor
    require_once("../inc/functions.php");    
    require_once("../inc/header.php");
	
	 if (!empty($_GET['id'])) {
	  $data = getProfileData($_GET['id'], $db);
	  $item_data = getItemData($_GET['id'], $db);
	}
    else {
      $data = getProfileData($_SESSION['user']['user_id'], $db);
	  $item_data = getItemData($_SESSION['user']['user_id'], $db);
    }
	
	getBoug
	
	function getBoughtItems($userid, $item_id $db) {
		$data = array();
		$data['success'] = false;
// Need to go from 
		$query = " 
            SELECT i.item_id, i.seller_id 
            FROM users u, items i
            WHERE u.user_id = :userid
			AND u.user_id = i.seller_id
			AND i.item_id NOT IN (SELECT w.item_id
								  FROM won_items w)
        "; 
         
        // The parameter values 
        $query_params = array( 
            ':userid' => $userid
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
         
         
        // Retrieve the user data from the database.  If $row is false, then the username 
        // they entered is not registered. 
        $row = $stmt->fetchAll();
        if ($row) {
        	$data['success'] = true;
        	$data['item_data'] = $row;
        }
        else{
        	$data['success'] = false;
        	$data['message'] = "No items found.";
			$data['item_data'] = null;
        	return $data;
        }
        return $data;
	}
	
	
	
?>

<div class="container">
    <h1><?php echo $data['user_data']['username']?>'s Items on Sale</h1>
    <hr>
		
    <!-- /.row -->

    <div class="row text-center">
	  <?php if (!empty($item_data))
	  {
		foreach($item_data['item_data'] as $item_info)
		{
			$pics = getPics($item_info['item_id'], $db);
	  ?>
		<div class="col-lg-3 col-md-6 hero-feature">
			<div class="thumbnail">
				<?php if ($pics['picture_data'][0]['url'] != null)
				{
				?>
					<center><img height="200" src="../<?php echo $pics['picture_data'][0]['url']?>" alt="Generic placeholder image"></center>
				<?php
				}
				else
				{
				?>
					<center><img height="200" src="../shop/images/placeholder1.jpg" alt="Generic placeholder image"></center>
				<?php
				}
				?>
				<div class="caption">
					<h3><?php echo $item_info['name']?></h3>
					<p><?php echo $item_info['description']?></p>
					<p><a href="../shop/item.php?id=<?php echo $item_info['item_id']?>" class="btn btn-default">More Info</a></p>
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