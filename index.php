<?php 
    // Title:       index.php
    // Desc:        Starts the user session
    // Date:        March 22, 2014
    // Version:     1.0
    // Author:      John Meanor
	
    // First we execute our common code to connection to the database and start the session 
    require("inc/functions.php");

    if (isLoggedIn()){
        header("Location: home.php"); 
        die("Redirecting to: home.php");
    } else {
        header("Location: user/login.php"); 
        die("Redirecting to: user/login.php"); 
    }
     

?> 

