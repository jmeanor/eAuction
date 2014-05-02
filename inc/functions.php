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

	function isAdmin() {
		if(empty($_SESSION['user'])) 
			return false;
		if ($_SESSION['user']['admin'])
			return true;
		else 
			return false;
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

	function markShipped($item_id, $db) {
		$data = array();
		$data['success'] = false;

		$query = " 
            SELECT item_id
            FROM won_items
			WHERE item_id = :item_id
        "; 
         
        // The parameter values 
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
            return false;
        } 

        $row = $stmt->fetch(); 
        if (!$row) {
        	$data['success'] = false;
        	$data['message'] = "No sold item with that ID #.";
			$data['data'] = null;
        	return $data;
        }


		$query = " 
            UPDATE won_items
			SET item_sent_date = NOW()
			WHERE item_id = :item_id
        "; 
         
        // The parameter values 
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
            return false;
        } 
        $data['success'] = true;

        return $data;

	}

	function getKeywords($db)
	{
        $query = "
				SELECT * FROM keywords";
        
        try 
        { 
            // Execute the query against the database 
            $stmt = $db->prepare($query); 
            $stmt->execute();
        } 
        catch(PDOException $ex) 
        { 
        	//die(var_dump
            die($ex);
        } 
        
        $result = $stmt->fetchAll();
        
        return $result;
	
	} 
	
	function getCategories($db)
	{
        $query = "
				SELECT * FROM categories WHERE category_id != 1 ORDER BY name";
        
        try 
        { 
            // Execute the query against the database 
            $stmt = $db->prepare($query); 
            $stmt->execute();
        } 
        catch(PDOException $ex) 
        { 
        	//die(var_dump
            die($ex);
        } 
        
        $result = $stmt->fetchAll();
        
        return $result;
	
	} 

	function getCategoryStats($db) {
		$data = array();
		$data['success'] = false;

		$query = " 
            SELECT COUNT(w.item_id) as count, c.name as name
			FROM won_items w
			JOIN items_in_categories ic ON w.item_id = ic.item_id
			JOIN categories c ON c.category_id = ic.category_id
			GROUP BY c.category_id
			ORDER BY c.category_id asc
        "; 
         
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

        $row = $stmt->fetchAll(); 
        if ($row) {
        	$data['success'] = true;
        	$data['data'] = $row;
        }
        else{
        	$data['success'] = false;
        	$data['message'] = "No won items!";
			$data['data'] = null;
        	return $data;
        }

        return $data;

	}

	function getAverageSale($db) {
		$data = array();
		$data['success'] = false;

		$query = " 
            SELECT AVG(b.price) as avg_p, c.name as name
			FROM bids b
			JOIN won_items w ON w.winning_bid = b.bid_id
			JOIN items_in_categories ic ON ic.item_id = w.item_id
			JOIN categories c ON ic.category_id = c.category_id
			GROUP BY ic.category_id 
        "; 
         
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

        $row = $stmt->fetchAll(); 
        if ($row) {
        	$data['success'] = true;
        	$data['data'] = $row;
        }
        else{
        	$data['success'] = false;
        	$data['message'] = "getAverageSale() failed to execute";
			$data['data'] = null;
        	return $data;
        }

        return $data;

	}

	function getReport($db) {
		$data = array();
		$data['success'] = false;

		$query = " 
            SELECT u.name, u.email, u.phone_number, u.public_location, p.age, p.gender, p.annual_income
			FROM users u
			JOIN people p ON u.user_id = p.user_id
        "; 
         
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

        $row = $stmt->fetchAll(); 
        if ($row) {
        	$data['success'] = true;
        	$data['data'] = $row;
        }
        else{
        	$data['success'] = false;
        	$data['message'] = "No report data found!";
			$data['data'] = null;
        	return $data;
        }

        return $data;

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

	function register($username, $enteredPassowrd, $email, $name, $phone, $type, $description, $public_location, $url,
					 $revenue, $category, $poc,
                              $age, $gender, $income, $db)
	{
	  $register_result = array('success' => false);

	  // We will use this SQL query to see whether the username entered by the 
	  // user is already in use.  A SELECT query is used to retrieve data from the database. 
	  // :username is a special token, we will substitute a real value in its place when 
	  // we execute the query. 
	  $query = " 
	      SELECT 
	          1 
	      FROM users 
	      WHERE 
	          username = :username 
	  "; 
	   
	  // This contains the definitions for any special tokens that we place in 
	  // our SQL query.  In this case, we are defining a value for the token 
	  // :username.  It is possible to insert $_POST['username'] directly into 
	  // your $query string; however doing so is very insecure and opens your 
	  // code up to SQL injection exploits.  Using tokens prevents this. 
	  // For more information on SQL injections, see Wikipedia: 
	  // http://en.wikipedia.org/wiki/SQL_Injection 
	  $query_params = array( 
	      ':username' => $username 
	  ); 
	   
	  try 
	  { 
	      // These two statements run the query against your database table. 
	      $stmt = $db->prepare($query); 
	      $result = $stmt->execute($query_params); 
	  } 
	  catch(PDOException $ex) 
	  { 
	      // Note: On a production website, you should not output $ex->getMessage(). 
	      // It may provide an attacker with helpful information about your code.  
	      die("Failed to run query: " . $ex->getMessage()); 
	  } 
	   
	  // The fetch() method returns an array representing the "next" row from 
	  // the selected results, or false if there are no more rows to fetch. 
	  $row = $stmt->fetch(); 
	   
	  // If a row was returned, then we know a matching username was found in 
	  // the database already and we should not allow the user to continue. 
	  if($row) 
	  { 
	    $register_result = array('success' => false, 'message' => 'This username is already in use.');
	    return $register_result;
	  } 
	   
	  // Now we perform the same type of check for the email address, in order 
	  // to ensure that it is unique. 
	  $query = " 
	      SELECT 
	          1 
	      FROM users 
	      WHERE 
	          email = :email 
	  "; 
	   
	  $query_params = array( 
	      ':email' => $email
	  ); 
	   
	  try 
	  { 
	      $stmt = $db->prepare($query); 
	      $result = $stmt->execute($query_params); 
	  } 
	  catch(PDOException $ex) 
	  { 
	      die("Failed to run query: " . $ex->getMessage()); 
	  } 
	   
	  $row = $stmt->fetch(); 
	   
	  if($row) 
	  { 
	    $register_result = array('success' => false, 'message' => 'This email address is already in registered.');
	    return $register_result;
	  } 
	   
	  // An INSERT query is used to add new rows to a database table. 
	  // Again, we are using special tokens (technically called parameters) to 
	  // protect against SQL injection attacks. 
	  $query = " 
	      INSERT INTO users ( 
	          username, 
	          password, 
	          salt, 
	          name,
	          email,
	          phone_number,
	          description,
	          public_location,
	          url,
	          user_type

	      ) VALUES ( 
	          :username, 
	          :password, 
	          :salt, 
	          :name,
	          :email,
	          :phone_number,
	          :description,
	          :public_location,
	          :url,
	          :type
	      );
	  "; 
	  if ($type == "person") {
	  	$query .= "
	  		 INSERT INTO people ( 
	  		 	user_id,
	  		 	age,
	  		 	gender,
	  		 	annual_income
	  		 ) VALUES (
				LAST_INSERT_ID(),
				:age,
				:gender,
				:annual_income
			);
	  	";
	  } else if ($type == "company") {
	  	$query .= "
	  		 INSERT INTO companies ( 
	  		 	user_id,
	  		 	revenue,
	  		 	category,
	  		 	point_of_contact
	  		 ) VALUES (
				LAST_INSERT_ID(),
				:revenue,
				:category,
				:point_of_contact
			);
	  	";
	  }
	   
	  // A salt is randomly generated here to protect again brute force attacks 
	  // and rainbow table attacks.  The following statement generates a hex 
	  // representation of an 8 byte salt.  Representing this in hex provides 
	  // no additional security, but makes it easier for humans to read. 
	  // For more information: 
	  // http://en.wikipedia.org/wiki/Salt_%28cryptography%29 
	  // http://en.wikipedia.org/wiki/Brute-force_attack 
	  // http://en.wikipedia.org/wiki/Rainbow_table 
	  $salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647)); 
	   
	  // This hashes the password with the salt so that it can be stored securely 
	  // in your database.  The output of this next statement is a 64 byte hex 
	  // string representing the 32 byte sha256 hash of the password.  The original 
	  // password cannot be recovered from the hash.  For more information: 
	  // http://en.wikipedia.org/wiki/Cryptographic_hash_function 
	  $password = hash('sha256', $enteredPassowrd . $salt); 
	   
	  // Next we hash the hash value 65536 more times.  The purpose of this is to 
	  // protect against brute force attacks.  Now an attacker must compute the hash 65537 
	  // times for each guess they make against a password, whereas if the password 
	  // were hashed only once the attacker would have been able to make 65537 different  
	  // guesses in the same amount of time instead of only one. 
	  for($round = 0; $round < 65536; $round++) 
	  { 
	      $password = hash('sha256', $password . $salt); 
	  } 
	   
	  // Here we prepare our tokens for insertion into the SQL query.  We do not 
	  // store the original password; only the hashed version of it.  We do store 
	  // the salt (in its plaintext form; this is not a security risk). 
	  $query_params = array( 
	      ':username' => $username, 
	      ':password' => $password, 
	      ':salt' => $salt, 
	      ':email' => $email,
	      ':name'=> $name,
	      ':phone_number'=> $phone,
	      ':description'=> $description,
	      ':public_location'=> $public_location,
	      ':url'=> $url,
	      ':type'=> $type
	  ); 

	if ($type == "person") {
		$query_params[':age'] = $age;
		$query_params[':gender'] = $gender;
		$query_params[':annual_income'] = $income;

	} else if ($type == "company") {
		$query_params[':revenue'] = $revenue;
		$query_params[':category'] = $category;
		$query_params[':point_of_contact'] = $poc;
	}
	   
	  try 
	  { 
	      // Execute the query to create the user 
	      $stmt = $db->prepare($query); 
	      $result = $stmt->execute($query_params); 
	  } 
	  catch(PDOException $ex) 
	  {   
	      // TODO:
	      // Note: On a production website, you should not output $ex->getMessage(). 
	      // It may provide an attacker with helpful information about your code.  
	      die("Failed to run query: " . $ex->getMessage()); 
	  }
	  

	  $register_result = array ('success' => true);
	  return $register_result;

}
	
	// Get data about ratings
	function getRatingsData($userid, $db) {
		$data = array();
		$data['success'] = false;

		// Query to gather ratings information
		$query = " 
            SELECT i.name, r.item_id, i.seller_id, r.score, r.description, r.seller_response  
            FROM users u, items i, ratings r
            WHERE 
                u.user_id = :userid
				AND u.user_id = i.seller_id
				AND i.item_id = r.item_id
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
        
        $row = $stmt->fetchAll(); 
        if ($row) {
        	$data['success'] = true;
        	$data['ratings_data'] = $row;
        }
		
        else{
        	$data['success'] = false;
        	$data['message'] = "No ratings found for that user.";
			$data['ratings_data'] = null;
        	return $data;
        }
        return $data;
	}
	
	function ItemRatingsData($itemid, $db) {
		$data = array();
		$data['success'] = false;

		// Query to gather ratings information
		$query = " 
            SELECT i.name, r.item_id, i.seller_id, r.score, r.description, r.seller_response  
            FROM items i, ratings r
            WHERE 
                i.item_id = :itemid
				AND i.item_id = r.item_id
        "; 
         
        // The parameter values 
        $query_params = array( 
            ':itemid' => $itemid
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
        
        $row = $stmt->fetchAll(); 
        if ($row) {
        	$data['success'] = true;
        	$data['ratings_data'] = $row;
        }
		
        else{
        	$data['success'] = false;
        	$data['message'] = "No ratings found for that user.";
			$data['ratings_data'] = null;
        	return $data;
        }
        return $data;
	}
	
	function getCards($userid, $db) {
		$data = array();
		$data['success'] = false;

		// Query to gather ratings information
		$query = " 
            SELECT card_id, card_type, card_number, expiration
			FROM credit_cards
			WHERE user_id = :userid
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
        
        $row = $stmt->fetchAll(); 
        if ($row) {
        	$data['success'] = true;
        	$data['card_data'] = $row;
        }
		
        else{
        	$data['success'] = false;
        	$data['message'] = "No ratings found for that user.";
			$data['card_data'] = null;
        	return $data;
        }
        return $data;
	}
	
	// Function to find the average rating score for a user
	function getRatingScore($userid, $db) {
		$data = array();
		$data['success'] = false;

		$query = " 
            SELECT AVG(r.score) AS avg_rating 
            FROM users u, items i, ratings r
            WHERE 
                u.user_id = :userid
				AND u.user_id = i.seller_id
				AND i.item_id = r.item_id
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
        	$data['ratings_data'] = $row;
        }
        else{
        	$data['success'] = false;
        	$data['message'] = "No ratings found for that user id number.";
			$data['ratings_data'] = null;
        	return $data;
        }
        return $data;
	}
	
	// Function to get the number of items a user has on sale/sold
	function getItemCount($userid, $db) {
		$data = array();
		$data['success'] = false;

		$query = " 
            SELECT COUNT(u.user_id)  
            FROM users u, won_items w, items i
            WHERE 
                u.user_id = :userid
				AND u.user_id = i.seller_id
				AND i.item_id = w.item_id
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
         
        $row = $stmt->fetch(); 
        if ($row) {
        	$data['success'] = true;
        	$data['user_data'] = $row;
        }
        else{
        	$data['success'] = false;
        	$data['message'] = "No items found with that user id number.";
			$data['user_data'] = null;
        	return $data;
        }
        return $data;
	}
	
	function getSocialMedia($userid, $db) {
		$data = array();
		$data['success'] = false;

		$query = " 
            SELECT sm.username, sm.sm_type
            FROM users u, social_media sm
            WHERE u.user_id = :userid
			AND sm.user_id = u.user_id
			ORDER BY sm.sm_type
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
         
        // Retrieve the Twitter data from the database.  If $row is false, then the user 
		// has no Twitter account, and none is shown.
        $row = $stmt->fetchAll(); 
		
        if ($row) {
        	$data['success'] = true;
        	$data['sm_data'] = $row;
        }
        else{
        	$data['success'] = false;
        	$data['message'] = "No social media accounts found for user with that id number.";
			$data['sm_data'] = null;
        	return $data;
        }
		
        return $data;
	}
	
	function getPics($item_id, $db) {
		$data = array();
		$data['success'] = false;

		$query = " 
            SELECT p.url  
            FROM item_pictures p, items i
            WHERE i.item_id = :item_id
			AND i.item_id = p.item_id
        "; 
         
        // The parameter values 
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
         
         
        $row = $stmt->fetchAll(); 
        if ($row) {
        	$data['success'] = true;
        	$data['picture_data'] = $row;
        }
        else{
        	$data['success'] = false;
        	$data['message'] = "No pictures found with that item id number.";
			$data['picture_data'] = null;
        	return $data;
        }
        return $data;
	}	
	
	function getItemData($userid, $db) {
		$data = array();
		$data['success'] = false;

		$query = " 
            SELECT i.item_id, i.name, i.description, i.starting_price, i.reserve_price
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
	
	function itemInfo($item_id, $db)
	{
		$data = array();
		$data['success'] = false;

		$query = " 
            SELECT i.item_id, i.seller_id, i.name, i.description, i.starting_price, i.buy_it_now_price, i.reserve_price, i.location, u.username, u.public_location, i.template 
            FROM items i, users u
            WHERE i.item_id = :item_id
			AND i.seller_id = u.user_id
        "; 
         
        // The parameter values 
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
         
         
        // Retrieve the user data from the database.  If $row is false, then the username 
        // they entered is not registered. 
        $row = $stmt->fetch();
        if ($row) {
        	$data['success'] = true;
        	$data['item_data'] = $row;
        }
        else{
        	$data['success'] = false;
        	$data['message'] = "No item found with that item id number.";
			$data['item_data'] = null;
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
	
	function getNumberOfItemsForCategory($category_id, $db)
	{
		$totalItems = 0; 
		
		$stmt = getSubCategories($category_id, $db);
		$counter = countNumberOfItems($category_id, $db);
	  	while ($row = $stmt->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_NEXT)) {
      		$current_category_id = $row[0];
      		$counter += getNumberOfItemsForCategory($current_category_id, $db);
       		
         
		}
		return $counter;
	}
	function countNumberOfItems($category_id, $db) 
	{  
	
		$query = " 
            SELECT i.item_id, i.name , i.start_time       
            FROM items_in_categories iic, items i
            WHERE iic.item_id = i.item_id AND iic.category_id = :category_id AND i.item_id NOT IN 
             		(SELECT wi.item_id
             		FROM won_items wi)";  
		
        $query_params = array(':category_id' => $category_id);
        try 
	    {  
	        // Execute the query to create the user 
	        $stmt = $db->prepare($query); 
            $stmt->execute($query_params);
        } 
        catch(PDOException $ex) 
        { 
        	//die(var_dump
            die($ex);
        } 
        $counter = 0;
        while ($row = $stmt->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_NEXT)) 
        {
        	$counter = $counter + 1;
	    }
	    return $counter;
    }  
	
	function getSearchResults($rootCategory, $startingCategory, $db)
	{
		$ids = array();
		if($rootCategory == $startingCategory)
			$ids[] = $rootCategory;
		$stmt = getSubCategories($rootCategory, $db);
	  	while ($row = $stmt->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_NEXT))
		{
      		$current_category_id = $row[0];
			$ids[] = $row[0];
			$arr = getSearchResults($current_category_id, $startingCategory, $db);
			foreach($arr as $ar)
				$ids[] = $ar;
		}
		return $ids;
	}
	
	function displayItemsForSearch($category_ids, $searchData, $minPrice, $maxPrice, $sortOn, $sortDir, $db) 
	{  
		$c_ids = '';
		for($i = 0; $i < count($category_ids); $i++)
		{
			$c_ids .= $category_ids[$i];
			if($i != (count($category_ids) - 1))
				$c_ids .= ", ";
		}
		
		$query = " 
            SELECT DISTINCT i.item_id, i.name, i.start_time, i.starting_price, i.buy_it_now_price            
            FROM items_in_categories iic, items i
            LEFT JOIN items_with_keywords iwk 
            	ON i.item_id = iwk.item_id
            	 
            WHERE i.item_id NOT IN 
             		(SELECT wi.item_id
             		FROM won_items wi)
            AND
            iic.item_id = i.item_id 
            AND ((i.name LIKE :searchData OR iwk.keyword LIKE :searchData)
            AND iic.category_id IN ($c_ids))
           "; 
		
        $query_params = array(':searchData' => $searchData);
        try 
	    {  
	        // Execute the query to create the user 
	        $stmt = $db->prepare($query); 
            $stmt->execute($query_params);
        } 
        catch(PDOException $ex) 
        { 
        	//die(var_dump
            die($ex);
        } 
		$data = '';
		$data = array();
        while ($row = $stmt->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_NEXT)) 
        {
			$add_days = 14;
			$endDate = strtotime($row[2]) + (24*3600*$add_days);
			
    		$buyNowPrice =  floatval($row[4]);

   			$bidPrice =  floatval($row[3]);
    			
    		$highestBidPrice = floatval(highestBid($row[0], $db));
    		if($highestBidPrice == 0)
    		{
    			$highestBidPrice = $bidPrice;
    		}
    		
    		if(($highestBidPrice >= $minPrice && $highestBidPrice <= $maxPrice) || ($buyNowPrice >= $minPrice && $buyNowPrice <= $maxPrice))
    		{
    			$data[] = array($row[0], $row[1], $endDate, $buyNowPrice, $highestBidPrice);
       		}
        }
		
		switch($sortOn)
		{
			case 'item':
				if($sortDir == 'asc')
					usort($data, function($a, $b) {
						return strcasecmp($a[1], $b[1]);
					});
				else
					usort($data, function($a, $b) {
						return strcasecmp($b[1], $a[1]);
					});
			break;
			case 'bid':
				if($sortDir == 'asc')
					usort($data, function($a, $b) {
						return $a[2] > $b[2];
					});
				else
					usort($data, function($a, $b) {
						return $b[2] > $a[2];
					});
			break;
			case 'buy-it-now':
				if($sortDir == 'asc')
					usort($data, function($a, $b) {
						return $a[3] > $b[3];
					});
				else
					usort($data, function($a, $b) {
						return $b[3] > $a[3];
					});
			break;
			case 'price':
				if($sortDir == 'asc')
					usort($data, function($a, $b) {
						return $a[4] > $b[4];
					});
				else
					usort($data, function($a, $b) {
						return $b[4] > $a[4];
					});
			break;
		}
		
		foreach($data as $row)
		{
			$pics = getPics($row[0], $db);
			
			if (!empty($pics['picture_data']))
				$img = "<img class=\"img-thubmnail\" name=\"mypic\" src=\"../" . $pics['picture_data'][0]['url'] . "\" style=\"height: auto; max-height: 100px; width: auto; max-width: 125px;\" >";
			else
				$img = "";
			
			echo "<tr>
					<td><center>$img</center></td>
					<td><a href='item.php?id=" . $row[0] . "'>" . $row[1] . "</a></td>
					<td>" . date('m-d-Y h:i a', $row[2]) . "</td>
					<td>" . ($row[3] == 0 ? "N/A" : "$" . number_format($row[3], 2)) . "</td>
					<td>$" . number_format($row[4], 2) . "</td>
				  </tr>";
		}
    }  

    function addNewKeyword($new_kw, $db) {
    	$query = " 
	      INSERT INTO keywords (`keyword`) VALUES (:keyword); ";

	     $query_params = array( 
		  ':keyword' => $new_kw
	  	); 
	   
		  try 
		  { 
		      // Execute the query to create the user 
		      $stmt = $db->prepare($query); 
		      $result = $stmt->execute($query_params); 
		  } 
		  
		  catch(PDOException $ex) 
		  {   
		      // TODO:
		      // Note: On a production website, you should not output $ex->getMessage(). 
		      // It may provide an attacker with helpful information about your code.  
		      die("Failed to run query: " . $ex->getMessage()); 
		  }
		  

    }
    
	
	function create_item($user_id, $item_name, $item_description, $keywords, $category, $starting_price, $buy_it_now_price, $reserve_price, $location, $url, $template, $db)
	{
	  $item_result = array('success' => false);
	  $start_time = date('Y-m-d H:i:s');
	  // An INSERT query is used to add new rows to a database table. 
	  // Again, we are using special tokens (technically called parameters) to 
	  // protect against SQL injection attacks. 
	  $query = " 
	      INSERT INTO items (
			  seller_id,
	          name, 
	          description, 
	          starting_price, 
	          buy_it_now_price,
			  reserve_price,
			  start_time,
	          location,
			  url,
			  template
			  
	      ) VALUES ( 
			  :user_id,
	          :item_name, 
	          :item_description, 
	          :starting_price, 
	          :buy_it_now_price,
			  :reserve_price,
			  :start_time,
	          :location,
			  :url,
			  :template
	      ) 
	  "; 
	  $query_params = array( 
		  ':user_id' => $user_id,
	      ':item_name' => $item_name, 
	      ':item_description' => $item_description, 
	      ':starting_price'=> floatval(str_replace('$', '', trim($starting_price))),
	      ':buy_it_now_price'=> floatval(str_replace('$', '', trim($buy_it_now_price))),
		  ':reserve_price'=> floatval(str_replace('$', '', trim($reserve_price))),
		  ':start_time'=>$start_time,
	      ':location'=> $location,
		  ':url'=> $url,
		  ':template'=> $template
	  ); 
	   
	  try 
	  { 
	      // Execute the query to create the user 
	      $stmt = $db->prepare($query); 
	      $result = $stmt->execute($query_params); 
	  } 
	  
	  catch(PDOException $ex) 
	  {   
	      // TODO:
	      // Note: On a production website, you should not output $ex->getMessage(). 
	      // It may provide an attacker with helpful information about your code.  
	      die("Failed to run query: " . $ex->getMessage()); 
	  }
	  
	  $itemid = $db->lastInsertId();
	  $query = "CREATE EVENT item_event_$itemid
                  ON SCHEDULE AT '$start_time' + INTERVAL 2 WEEK
                  DO
                     BEGIN
                        CALL proc_endAuction($itemid);
                     END";
	  
	  try 
	  { 
	      // Execute the query to create the user 
	      $stmt = $db->prepare($query); 
	      $result = $stmt->execute(); 
	  } 
	  
	  catch(PDOException $ex) 
	  {   
	      // TODO:
	      // Note: On a production website, you should not output $ex->getMessage(). 
	      // It may provide an attacker with helpful information about your code.  
	      die("Failed to run query: " . $ex->getMessage()); 
	  }	 

	$query = "INSERT INTO items_in_categories (item_id, category_id) 
			  VALUES (:itemid, :category)";
	$query_params = array(':itemid'=>$itemid, ':category'=>$category);

	 try 
		  { 
		      // Execute the query to create the user 
		      $stmt = $db->prepare($query); 
		      $result = $stmt->execute($query_params); 
		  } 
		  
		  catch(PDOException $ex) 
		  {   
		      // TODO:
		      // Note: On a production website, you should not output $ex->getMessage(). 
		      // It may provide an attacker with helpful information about your code.  
		      die("Failed to run query: " . $ex->getMessage()); 
		  }	 

	  foreach ( $keywords as $keyword ) {

		  $query = "
		  		INSERT INTO items_with_keywords (`item_id`, `keyword`) 
		  		VALUES (:itemid, :keyword); ";

		  $query_params = array( 
		  		':itemid' => $itemid,
		  		':keyword' => $keyword
		  	);
		  

		  try 
		  { 
		      // Execute the query to create the user 
		      $stmt = $db->prepare($query); 
		      $result = $stmt->execute($query_params); 
		  } 
		  
		  catch(PDOException $ex) 
		  {   
		      // TODO:
		      // Note: On a production website, you should not output $ex->getMessage(). 
		      // It may provide an attacker with helpful information about your code.  
		      die("Failed to run query: " . $ex->getMessage()); 
		  }	  
	}
	  
	  
	  $item_result = array ('success' => true, 'item_id' => $itemid);
	  return $item_result;
}

function submitRating($item_id, $buyer_id, $score, $description, $db)
	{
	  $item_result = array('success' => false);
	   
	  // An INSERT query is used to add new rows to a database table. 
	  // Again, we are using special tokens (technically called parameters) to 
	  // protect against SQL injection attacks. 
	  $query = " 
	      INSERT INTO ratings (
			  item_id,
			  buyer_id,
			  score,
			  description
			  
	      ) VALUES ( 
			  :item_id,
	          :buyer_id, 
	          :score, 
	          :description
	      ) 
	  "; 
	  $query_params = array( 
		  ':item_id' => $item_id,
	      ':buyer_id' => $buyer_id, 
		  ':score' => $score,
	      ':description' => $description
	  ); 
	   
	  try 
	  { 
	      // Execute the query to create the user 
	      $stmt = $db->prepare($query); 
	      $result = $stmt->execute($query_params); 
	  } 
	  
	  catch(PDOException $ex) 
	  {   
	      // TODO:
	      // Note: On a production website, you should not output $ex->getMessage(). 
	      // It may provide an attacker with helpful information about your code.  
	      die("Failed to run query: " . $ex->getMessage()); 
	  }
	  $item_result = array ('success' => true);
	  return $item_result;
}

	function highestBid($item_id, $db)
	{
		$data = array();
		$data['success'] = false;

		$query = " 
            SELECT MAX(price) as max_price
            FROM bids
            WHERE item_id = :item_id	
        "; 
         
        // The parameter values 
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
         
        // Retrieve the user data from the database.  If $row is false, then the username 
        // they entered is not registered. 
        $row = $stmt->fetch();
        if ($row) {
        	$max_price = $row['max_price'];
			if($max_price == '')
				$max_price = '0.00';
        }
        else{
			$max_price = '0.00';
        }
        return $max_price;
	}
	
	function recentBid($item_id, $user_id, $db)
	{
		$data = array();
		$data['success'] = false;

		$query = " 
            SELECT price
            FROM bids
            WHERE item_id = :item_id 
			AND price = (SELECT MAX(price) FROM bids WHERE item_id = :item_id AND card_id IN (SELECT card_id FROM credit_cards WHERE user_id = :user_id))
        "; 
         
        // The parameter values 
        $query_params = array( 
            ':item_id' => $item_id,
			':user_id' => $user_id
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
        	$max_price = $row['price'];
			if($max_price == '')
				$max_price = '0.00';
        }
        else{
			$max_price = '0.00';
        }
        return $max_price;
	}
	
	function itemsBought($user_id, $db)
	{
		$data = array();
		$data['success'] = false;

		$query = " 
            SELECT COUNT(w.winning_bid) as bids_won_count
            FROM won_items w, users u, bids b, credit_cards c
            WHERE u.user_id = :user_id
			AND w.winning_bid = b.bid_id
			AND c.card_id = b.card_id
			AND c.user_id = u.user_id
        "; 
         
        // The parameter values 
        $query_params = array( 
            ':user_id' => $user_id
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
        	$data['bid_data'] = $row;
        }
        else{
        	$data['success'] = false;
        	$data['message'] = "No item found with that item id number.";
			$data['bid_data'] = null;
        	return $data;
        }
        return $data;	
	}
	
	function numBids($item_id, $db)
	{
		$data = array();
		$data['success'] = false;
		$query = "SELECT COUNT(*) as count 
				  FROM bids 
				  WHERE item_id=:item_id";
		$query_params = array(':item_id'=>$item_id);
		
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
		if ($row)
		{
			$count = $row['count'];
		}
		else
		{
			$count = 0;
		}
		return $count;
	}
	
function addCard($user_id, $card_type, $card_number, $expiration, $db)
	{
	  $item_result = array('success' => false);
	   
	  // An INSERT query is used to add new rows to a database table. 
	  // Again, we are using special tokens (technically called parameters) to 
	  // protect against SQL injection attacks. 
	  $query = " 
	      INSERT INTO credit_cards (
			  user_id,
	          card_type, 
	          card_number,
			  expiration
			  
	      ) VALUES ( 
			  :user_id,
	          :card_type, 
	          :card_number,
			  :expiration
	      ) 
	  "; 
	  $query_params = array( 
		  ':user_id' => $user_id,
	      ':card_type' => $card_type, 
	      ':card_number' => $card_number,
		  ':expiration' => $expiration,
	  ); 
	   
	  try 
	  { 
	      // Execute the query to create the user 
	      $stmt = $db->prepare($query); 
	      $result = $stmt->execute($query_params); 
	  } 
	  
	  catch(PDOException $ex) 
	  {   
	      // TODO:
	      // Note: On a production website, you should not output $ex->getMessage(). 
	      // It may provide an attacker with helpful information about your code.  
	      die("Failed to run query: " . $ex->getMessage()); 
	  }
	  $item_result = array ('success' => true);
	  return $item_result;
}
	
function addSM($user_id, $username, $sm_type, $db)
	{
	  $item_result = array('success' => false);
	   
	  // An INSERT query is used to add new rows to a database table. 
	  // Again, we are using special tokens (technically called parameters) to 
	  // protect against SQL injection attacks. 
	  $query = " 
	      INSERT INTO social_media (
			  user_id,
	          sm_type, 
	          username
			  
	      ) VALUES ( 
			  :user_id,
	          :sm_type, 
	          :username
	      ) 
	  "; 
	  $query_params = array( 
		  ':user_id' => $user_id,
	      ':sm_type' => $sm_type, 
	      ':username' => $username
	  ); 
	   
	  try 
	  { 
	      // Execute the query to create the user 
	      $stmt = $db->prepare($query); 
	      $result = $stmt->execute($query_params); 
	  } 
	  
	  catch(PDOException $ex) 
	  {   
	      // TODO:
	      // Note: On a production website, you should not output $ex->getMessage(). 
	      // It may provide an attacker with helpful information about your code.  
	      die("Failed to run query: " . $ex->getMessage()); 
	  }
	  $item_result = array ('success' => true);
	  return $item_result;
}
	
function updateUser($user_id, $name, $email, $phone, $description, $public_location, $url, $db)
	{
	  $item_result = array('success' => false);
	   
	  // An INSERT query is used to add new rows to a database table. 
	  // Again, we are using special tokens (technically called parameters) to 
	  // protect against SQL injection attacks. 
	  $query = " 
	      UPDATE users 
		  SET name = :name,
			  email = :email,
			  phone_number = :phone_number,
			  description = :description,
			  public_location = :public_location,
			  url = :url
		  WHERE user_id = :user_id 
	  "; 
	  $query_params = array( 
		  ':user_id' => $user_id,
		  ':name' => $name,
	      ':email' => $email, 
	      ':phone_number'=> $phone,
	      ':description'=> $description,
		  ':public_location'=> $public_location,
		  ':url'=> $url
	  ); 
	   
	  try 
	  { 
	      // Execute the query to update the user 
	      $stmt = $db->prepare($query); 
	      $result = $stmt->execute($query_params); 
	  } 
	  
	  catch(PDOException $ex) 
	  {   
	      // TODO:
	      // Note: On a production website, you should not output $ex->getMessage(). 
	      // It may provide an attacker with helpful information about your code.  
	      die("Failed to run query: " . $ex->getMessage()); 
	  }
	  $item_result = array ('success' => true);
	  return $item_result;
}	

function submitResponse($seller_response, $item_id, $db)
	{
	  $item_result = array('success' => false);
	   
	  $query = " 
	      UPDATE ratings r 
		  SET r.seller_response = :seller_response
		  WHERE r.item_id = :item_id
	  "; 
	  $query_params = array( 
		  ':seller_response' => $seller_response,
		  ':item_id' => $item_id
	  ); 
	   
	  try 
	  { 
	      // Execute the query to update the user 
	      $stmt = $db->prepare($query); 
	      $result = $stmt->execute($query_params); 
	  } 
	  
	  catch(PDOException $ex) 
	  {   
	      // TODO:
	      // Note: On a production website, you should not output $ex->getMessage(). 
	      // It may provide an attacker with helpful information about your code.  
	      die("Failed to run query: " . $ex->getMessage()); 
	  }
	  $response_result = array ('success' => true);
	  return $response_result;
}	

	function checkIfOver($item_id, $db)
	{
		$data = array();
		$data['success'] = false;

		$query = " 
            SELECT w.item_id
            FROM won_items w
            WHERE w.item_id = :item_id
        "; 
         
        // The parameter values 
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
         
         
        // Retrieve the user data from the database.  If $row is false, then the username 
        // they entered is not registered. 
        $row = $stmt->fetchAll();
        if ($row) {
        	$data['success'] = true;
        	$data['item_data'] = $row;
        }
        else{
        	$data['success'] = false;
        	$data['message'] = "No item found with that item id number.";
			$data['item_data'] = null;
        	return $data;
        }
        return $data;	
	}	

	function checkSM($username, $sm_type, $db)
	{
		$data = array();
		$data['success'] = false;

		$query = " 
            SELECT sm.username
            FROM social_media sm
            WHERE sm.username = :username
			AND sm.sm_type = :sm_type
        "; 
         
        // The parameter values 
        $query_params = array( 
            ':username' => $username,
			':sm_type' => $sm_type
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
        	$data['message'] = "No item found with that item id number.";
			$data['item_data'] = null;
        	return $data;
        }
        return $data;	
	}
	
	function getBoughtItems($userid, $db) {
		$data = array();
		$data['success'] = false;
		$query = " 
            SELECT i.item_id, i.name, i.description
            FROM items i, won_items w, bids b
            WHERE b.card_id IN (SELECT card_id FROM credit_cards WHERE user_id=:userid)
			AND i.item_id = w.item_id
			AND w.winning_bid = b.bid_id
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
	
		
	   function uploadFile($fieldName, $item_id, $db)
	   {      
	      $_UPLOAD_URL = $_SERVER['DOCUMENT_ROOT'] . "/eAuction/shop/images";
	         
	      if(!isset($_FILES[$fieldName]))
	      {
	         return false;
	      }
	      // Ensure the file exists
	      if($_FILES[$fieldName]['error'] > 0)
	      {
	         return false;
	      }
	      else
	      {
	         // Split by Slash
	         $fullName = explode('/', $_FILES[$fieldName]['name']);
	         $n = count($fullName)-1; // File name will be at the end of the array
	         
	         // Up to this point, if the function is still going, no errors have been found with the file, so now it's time to upload it
	         $newName = uniqid() . "-" . $fullName[$n];
	         $destination = $_UPLOAD_URL . "/$newName"; // uniqid generates a random string to add to the name of the file to protect against duplicates
	         $deststring = "shop/images/$newName";
	         
	         // Ensure the destination directory exists
	         if(!is_dir($_UPLOAD_URL))
	         {
	            // Create the directory with the permissions already set so PHP can write to it
	            mkdir($_UPLOAD_URL, 0755);
	         }
	         
	         // Move the file
	         if(move_uploaded_file($_FILES[$fieldName]['tmp_name'], $destination))
	         {
	         	//die(var_dump("Made it"));

	         	$query = " 
		            INSERT INTO item_pictures
		            (`item_id`, `url`) VALUES 
		            (:item_id, :url);
		        "; 
		         
		        // The parameter values 
		        $query_params = array( 
		            ':item_id' => $item_id,
		            ':url' => $deststring
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
		         


	            // Return the location of the new file
	            return $deststring;
	         }
	         else
	         {
	         	//die(var_dump("Didn't make it"));

	            return false;
	         }
	      }
	   }	
?>