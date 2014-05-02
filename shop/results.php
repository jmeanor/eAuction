<?php 
    // Title:       shop/item.php
    // Desc:        The action page that lists all the search results from the query bar.  
    // Date:        March 22, 2014
    // Version:     1.0
    // Author:      Luke Keniston
    require_once("../inc/header.php");
    checkPermissions();

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
	
	if (!empty($_POST['lowerPrice']) && floatval(str_replace('$', '', trim($_POST['lowerPrice']))) > 0) 
	{
		$lowerPriceBound = floatval(str_replace('$', '', trim($_POST['lowerPrice'])));
		$lowerPriceDisplayed = $_POST['lowerPrice'];
	}
	else 
	{
	  	$lowerPriceBound = 0;
	  	$lowerPriceDisplayed = "";
	}
	
	if (!empty($_POST['upperPrice']) && floatval(str_replace('$', '', trim($_POST['upperPrice']))) > 0) 
	{
		$upperPriceBound = floatval(str_replace('$', '', trim($_POST['upperPrice'])));
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
	
	if(!empty($_POST['sort']))
	{
		$sortOn = $_POST['sort'];
		$sortDir = $_POST['sort_dir'];
	}
	else
	{
		$sortOn = 'item';
		$sortDir = 'asc';
	}
    
    $parent_id = getParentId($category_id, $db);

  
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
		<form method="post" action="results.php">
    	<p>Filter By:
        	<input type="text" name="lowerPrice" placeholder="min price $" value="<?php echo $lowerPriceDisplayed ?>" />
        	<br />
        	<input type ="text" name="upperPrice" placeHolder="max price $" value="<?php echo $upperPriceDisplayed ?>" /></p>
			<p>Sort By:<br />
			<select name='sort'>
				<option value='item' <?php echo ($sortOn == 'item' ? "selected='selected'" : '') ?> >Item Name</option>
				<option value='bid' <?php echo ($sortOn == 'bid' ? "selected='selected'" : '') ?> >Bid Ends On</option>
				<option value='buy-it-now' <?php echo ($sortOn == 'buy-it-now' ? "selected='selected'" : '') ?> >Buy It Now Price</option>
				<option value='price' <?php echo ($sortOn == 'price' ? "selected='selected'" : '') ?> >Price</option>
			</select><br />
			<input type='radio' name='sort_dir' value='asc' <?php echo ($sortDir == 'asc' ? "checked" : '') ?> /> Ascending<br />
			<input type='radio' name='sort_dir' value='desc' <?php echo ($sortDir == 'desc' ? "checked" : '') ?> /> Descending</p>
			<button type="submit" class="">Go</button>  
		</form> 
		
		
		
          </ul>
		
        </div>
        <div class="col-sm-9 col-md-10 main">  
        <h2 class="sub-header">Search Results</h2>
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Image</th>
				  <th>Item Name</th>
                  <th>Bid Ends On</th>
                  <th>Buy It Now Price</th>
                  <th>Price</th>
                </tr>
              </thead>
              <tbody> 
                <?php $ids = getSearchResults($category_id, $category_id, $db);
					  displayItemsForSearch($ids, $searchData, $lowerPriceBound, $upperPriceBound, $sortOn, $sortDir, $db); ?> 
			</tbody>
    </table>   
      </div>
    </div>
</div> 


      <?php require_once("../inc/footer.php"); ?>
      
<form id="categoriesForm" method="post" action="results.php">
<input type="hidden" name="category" id="category">
</form>