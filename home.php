<?php 
    // Title:       home.php
    // Desc:        Main display for logged in users.
    // Date:        March 22, 2014
    // Version:     1.0
    // Author:      John Meanor
    require_once("inc/header.php");
	checkPermissions();
  
?>

<div class="container">
        <div class="jumbotron hero-spacer">
            <h1>Welcome to eAuction!</h1>
            <p>We're the most user-friendly auction website there is since 2014. Search for what you want to buy above, <a href="shop/results.php">shop by category</a>, or check out the featured items below. </p>
        </div>

        <hr>

        <div class="row">
            <div class="col-lg-12">
                <h3>Featured auctions</h3>
            </div>
        </div>
        <!-- /.row -->

        <div class="row text-center">
<?php
	$query = "SELECT item_id, name, description 
			  FROM items
			  ORDER BY start_time DESC 
			  LIMIT 4";
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
	foreach($data as $row)
	{
		$pics = getPics($row['item_id'], $db);
?>
            <div class="col-lg-3 col-md-6 hero-feature">
                <div class="thumbnail">
<?php	if($pics['success'] == true)
		{
            echo "<img src='" . $pics['picture_data'][0]['url'] . "' alt=''>\n";
		}
?>
                    <div class="caption">
                        <h3><?php echo $row['name'] ?></h3>
                        <p><?php echo $row['description'] ?></p>
                        <p><a class="btn btn-default btn-xs" href="shop/item.php?id=<?php echo $row['item_id']?>" name="option1" role="button">View details &raquo;</a>
                        </p>
                    </div>
                </div>
            </div>
<?php
	}
?>


        </div>
        <!-- /.row -->


        <?php require_once("inc/footer.php"); ?>