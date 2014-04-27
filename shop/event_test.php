<?php 
    // Title:       shop/item.php
    // Desc:        Displays individual item auction page.  
    // Date:        March 22, 2014
    // Version:     1.0
    // Author:      John Meanor

   require_once("../inc/header.php");
   
   if(!empty($_POST))
   {
      $query = "CREATE EVENT item_event_25
                  ON SCHEDULE AT '2014-05-04 12:35:33'
                  DO
                     BEGIN
                        CALL proc_endAuction(25);
                     END";
					 
		try
		{
			$stmt = $db->prepare($query);
			$result = $stmt->execute();
		}
		catch(PDOException $ex)
		{
			die("Failed to run query: " . $ex->getMessage()); 
		}
   }
?>

   <div class="container">
      <form method='post' action='event_test.php'>
		 <input type='hidden' name='phase' id='phase' value='' />
         <input type='submit' value='Submit' />
      </form>
   </div>

<?php require_once("../inc/footer.php"); ?>
