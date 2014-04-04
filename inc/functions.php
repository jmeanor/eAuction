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

	function register($username, $enteredPassowrd, $email, $name, $phone, $type, $description, $public_location, $url, $db)
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
	          :email,
	          :name,
	          :phone_number,
	          :description,
	          :public_location,
	          :url,
	          :type
	      ) 
	  "; 
	   
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
	      ':type'=> $type,
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
	  
	  //$result = login($username, $enteredPassword, $db);

	  // if (isset($result['user']))
	  // {
	  //   $register_result = array('success' => true, 'user' => $result['user']);
	  //   return $register_result; 
	  // }
	  // else 
	  // {
	  //   $register_result = array('success' => false, 'message' => 'Register + Login Failure', 'submitted_username' => $submitted_username);
	  //   return $register_result;
	  // }

	  $register_result = array ('success' => true);
	  return $register_result;

	  // // This redirects the user back to the login page after they register 
	  // header("Location: login.php"); 
	   
	  // // Calling die or exit after performing a redirect using the header function 
	  // // is critical.  The rest of your PHP script will continue to execute and 
	  // // will be sent to the user if you do not die or exit. 
	  // die("Redirecting to login.php"); 


}





?>