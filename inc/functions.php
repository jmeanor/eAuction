<?php 
	// Title:		functions.php
	// Desc:		Included via header.php, provides common functions globally.
	// Date:		March 22, 2014
	// Version:		1.0
	// Author:		John Meanor

	require_once("db.php");

	function isLoggedIn(){
		if(empty($_SESSION['user']))
			return false;
		else 
			return true;
	}

	function checkPermissions() 
	{
		if (strpos($_SERVER['PHP_SELF'], '/shop/') !== FALSE || 
			strpos($_SERVER['PHP_SELF'], '/user/') !== FALSE  ) {
			$path = "../";
		} else {
			$path = "";
		}
		// At the top of the page we check to see whether the user is logged in or not 
		if(empty($_SESSION['user'])) 
		{ 
			// If they are not, we redirect them to the login page. 
			header("Location: ".$path."user/login.php"); 


			// Remember that this die statement is absolutely critical.  Without it, 
			// people can view your members-only content without logging in. 
			die("Redirecting to user/login.php"); 
		} 
	}

	function getProfileData($userid, $db) {
		$data = array();
		$data['success'] = false;

		$query = " 
            SELECT user_id, username, name, email, phone_number, description, public_location, url, user_type            
            FROM users u
            WHERE 
                u.user_id = :userid
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
        $row = $stmt->fetch(); 
        if ($row) {
        	$data['success'] = true;
        	$data['user_data'] = $row;
        }
        else{
        	$data['success'] = false;
        	$data['message'] = "No user found with that user id number.";
        	return $data;
        }

        return $data;

	}
	
	function getCategoryName($categoryId, $db)
	{
	    $query = " 
			SELECT name 
			FROM categories c
			WHERE category_id = :category_id";
			$query_params = array(':category_id' => $categoryId);
			try 
        	{ 
            	// Execute the query against the database 
            	$stmt = $db->prepare($query); 
            	$stmt->execute($query_params);
        	} 
        	catch(PDOException $ex) 
        	{ 
        		//die(var_dump
            	die($ex);
        	} 
        	$categoryName = $stmt->fetch(PDO::FETCH_NUM);
        	
        	return $categoryName[0];
        	
	}
	 
	
	
	function getParentId($currentCategory_id , $db)
	{
		$query = " 
			SELECT parent 
			FROM categories c
			WHERE category_id = :category_id";
			$query_params = array(':category_id' => $currentCategory_id);
			try 
        	{ 
            	// Execute the query against the database 
            	$stmt = $db->prepare($query); 
            	$stmt->execute($query_params);
        	} 
        	catch(PDOException $ex) 
        	{ 
        		//die(var_dump
            	die($ex);
        	} 
        
        	$parent = $stmt->fetch(PDO::FETCH_NUM);
        	
        	return $parent[0];

	}
	
	function getSubCategories($category_id, $db)
	{
        $query = " 
				SELECT category_id, name
				FROM categories c
				WHERE parent = :categoryId AND category_id != '1'";
		$query_params = array(':categoryId' => $category_id);
        
        try 
        { 
            // Execute the query against the database 
            $stmt = $db->prepare($query); 
            $stmt->execute($query_params);
        } 
        catch(PDOException $ex) 
        { 
        	//die(var_dump
            die($ex);
        } 
        
        	
        return $stmt;
	
	} 
	
	
	function getSearchResults($rootCategory, $searchData, $itemCriteria, $minPrice, $maxPrice, $db)
	{
		if($rootCategory != 1)
		{
			displayItemsForSearch($rootCategory, $searchData, $itemCriteria, $minPrice, $maxPrice, $db);
		}
		$stmt = getSubCategories($rootCategory, $db);
	
	  while ($row = $stmt->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_NEXT)) {
      	$current_category_id = $row[0];
        getSearchResults($current_category_id, $searchData, $itemCriteria, $minPrice, $maxPrice, $db);
         
		}

	
	}
	
	function displayItemsForSearch($category_id, $searchData, $itemCriteria, $minPrice, $maxPrice, $db) 
	{
		
		if($itemCriteria == 'Both')
		{	
			$option = 0;
		}
		else if($itemCriteria == 'BuyItNow')
		{
			$option = 1;
		}	
		else 
		{
			$option = 2;
		}	  
	
		$query = " 
            SELECT DISTINCT i.item_id, i.name, i.start_time, i.starting_price, i.buy_it_now_price            
            FROM items_in_categories iic, items i
            LEFT JOIN items_with_keywords iwk 
            	ON i.item_id = iwk.item_id
            	 
            WHERE iic.item_id = i.item_id 
            AND (
            	(i.name LIKE :searchData OR iwk.keyword LIKE :searchData)
            	AND :category_id = iic.category_id
           		AND (
            		   ($option = 0 AND (i.starting_price > 0 OR i.buy_it_now_price > 0)) 
            		OR ($option = 1 AND i.starting_price = 0 AND i.buy_it_now_price > 0)
            		OR ($option = 2 AND i.starting_price > 0 AND i.buy_it_now_price = 0)
            		)
            	AND ((i.starting_price >= :minPrice || i.buy_it_now_price >= :minPrice) 
            		 AND ((i.starting_price <= :maxPrice AND i.starting_price != 0) || (i.buy_it_now_price <= :maxPrice AND i.buy_it_now_price != 0))
            	    )
            	 )
           ";  

        $query_params = array(':searchData' => $searchData, ':category_id' => $category_id, ':option' => $option, ':minPrice' => $minPrice, ':maxPrice' => $maxPrice); 
         
        try 
        { 
            // Execute the query against the database 
            $stmt = $db->prepare($query); 
            $stmt->execute($query_params);
        } 
        catch(PDOException $ex) 
        { 
        	//die(var_dump
            die($ex);
        } 
        ?> <tr> <?php
        while ($row = $stmt->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_NEXT)) 
        {
        	$today = date('Y-m-d h:i:s');
        	$add_days = 14;
			$endDate = date('Y-m-d h:i:s',strtotime($row[2]) + (24*3600*$add_days));

			$today_time = strtotime($today);
			$end_time = strtotime($endDate);

		  if ($end_time > $today_time)
          {
    		if($row[4] == 0)
    			$buyNowPrice = "N/A";
    		else 
    			$buyNowPrice =  $row[4];
    		if($row[3] == 0)
    			$bidPrice = "N/A";
    		else 
    			$bidPrice =  $row[3];
    			
        	$data = "<td><a href=item.php?id=" . $row[0] . ">" . $row[1] .
        		    "</a></td> <td> " . $endDate . "</td> <td> " . $buyNowPrice .
        		    "</td> <td> " . $bidPrice . "</td>" ;  
       		 print $data; 
	    	?> </tr> <?php
	      }
        }
    }  
    
    function checkSelectedOption($name, $selectedOption)
    {
    	if($name = $selectedOption)
    		return "checked";
    	else 
    		return "";
    }    
	
?>