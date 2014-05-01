<?php 
    // Title:       shop/item.php
    // Desc:        The action page that lists all the search results from the query bar.  
    // Date:        March 22, 2014
    // Version:     1.0
    // Author:      Luke Keniston
    require_once("../inc/functions.php");
    checkPermissions();
    require_once("../inc/header.php");

    if (!empty($_POST['search'])) {
		$searchData = "%" . trim($_POST["search"]) . "%";
	}
	else {
	  	$searchData = "%";
	}
	
	if (!empty($_POST['category'])) 
	{
		$category_id = $_POST['category'];
	}
	else {
	  	$category_id = 1;
	}
	
	if (!empty($_POST['itemType'])) 
	{
		$itemType = $_POST['itemType'];	
	}
	else {
	  	$itemType = 'Both';
	}
	
	if (!empty($_POST['lowerPrice']) && $_POST['lowerPrice'] > 0) 
	{
		$lowerPriceBound = $_POST['lowerPrice'];
		$lowerPriceDisplayed = $_POST['lowerPrice'];
	}
	else 
	{
	  	$lowerPriceBound = 0;
	  	$lowerPriceDisplayed = "";
	}
	
	if (!empty($_POST['upperPrice']) && $_POST['upperPrice'] > 0) 
	{
		$upperPriceBound = $_POST['upperPrice'];
		$upperPriceDisplayed = $_POST['upperPrice'];
	}
	else {
	  	$upperPriceBound = 9999999999999999999999999999;
	  	$upperPriceDisplayed = "";
	}
	
	if($lowerPriceBound > $upperPriceBound)
	{
		$lowerPriceBound = 0; 
		$upperPriceBound = 9999999999999999999999999999;
	
	}
	
	
    if (!empty($_GET['userid'])) {
      $data = getProfileData($_GET['userid'], $db);
    }
    else {
      $data = getProfileData($_SESSION['user']['user_id'], $db);
    }
    
    if(!empty(getParentId($category_id, $db)))
    {
    	$parent_id = getParentId($category_id, $db);
    }
    else
    	$parent_id = 1;
  
?>
    <div class="container">


    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
          <ul class="nav nav-sidebar">
   			<p> Categories </p>
   			<?php 
		
	
		$stmt = getSubCategories($category_id, $db);
        $parentName = getCategoryName($parent_id, $db);
        $parentNameShown = $parentName;
    	if($parentNameShown == 'root')
        {
        	$parentNameShown = "All";
        }

      	$itemCountParent = getNumberOfItemsForCategory($parent_id, $db);
      	$itemCountCurrent = getNumberOfItemsForCategory($category_id, $db);
      	$categoryName = getCategoryName($category_id, $db);
      	if($categoryName == 'root')
      	{
      		$categoryName = "All";
      	}
      
      	
 	
      if($category_id != 1)
      {
          
  ?>      
   <form name="<?php print $parentName ?>" method="post" action="results.php">
            <input type="hidden" name="category" value="<?php print $parent_id ?>">
            <li><a href="javascript:document.forms['<?php print $parentName ?>'].submit()">Go Back to: <?php print $parentNameShown ?> (<?php print $itemCountParent ?>)</a></li>
            </form> 
                
         <?php
       } 
       ?> <p><li> <?php print $categoryName ?> (<?php print $itemCountCurrent ?>)</li> <?php
        // prints out list of current categories
     
    
    while ($row = $stmt->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_NEXT)) {
      $current_category_id = $row[0];
      $current_category_name = $row[1];
      $itemCount = getNumberOfItemsForCategory($current_category_id, $db);
      $category_Label = $current_category_name . " (" . $itemCount . ")";
        ?>
      <p> 
 			
            <form name="<?php print $current_category_name ?>" method="post" action="results.php">
            <input type="hidden" name="category" value="<?php print $current_category_id ?>">
            <li><a href="javascript:document.forms['<?php print $current_category_name ?>'].submit()"><?php print $category_Label ?></a></li>
            </form>
   <?php 
	}
    ?>
    
    	<p/> 
    	<p> Price filter </p>
		<form method="post" action="results.php">
        	<input type="text" name="lowerPrice" placeholder="min price $" value=<?php echo $lowerPriceDisplayed ?> >
        	<p/>
        	<br/>
        	<input type ="text" name="upperPrice" placeHolder="max price $" value=<?php echo $upperPriceDisplayed ?> >  
    	
    	<p> Item Filter </p>
    		
    		<input type="radio" name="itemType" value ="Both" <?php echo ($itemType =='Both')?'checked':'' ?> > Both</input><br/>
			<input type="radio" name="itemType" value="BuyItNow" <?php echo ($itemType =='BuyItNow')?'checked':'' ?> > Buy It Now Only</input><br/>
			<input type="radio" name="itemType" value="BidOnly" <?php echo ($itemType =='BidOnly')?'checked':'' ?> > Bid Only</input><br/><br/>
			<button type="submit" class="">Filter</button>  
		</form> 
		
		
		
          </ul>
		
        </div>
        <div class="col-sm-9 col-md-10 main">
<?php 


         
       
   ?>     
        <h2 class="sub-header">Search Results</h2>
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Item name</th>
                  <th>Bid ends on:</th>
                  <th>Buy It Now Price</th>
                  <th>Latest Bid</th>
                </tr>
              </thead>
              <tbody> 
                <?php  getSearchResults($category_id, $searchData, $itemType, $lowerPriceBound , $upperPriceBound, $db); ?> 
			</tbody>

	</thread>
    </table>   
      </div>
    </div>
</div> 


      <?php require_once("../inc/footer.php"); ?>
      
<form id="categoriesForm" method="post" action="results.php">
<input type="hidden" name="category" id="category">
</form>