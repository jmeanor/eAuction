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


?>