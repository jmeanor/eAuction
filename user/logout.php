<?php 
    // Title:       user/logout.php
    // Desc:        Lets the user logout of their account
    // Date:        March 22, 2014
    // Version:     1.0
    // Author:      John Meanor
	
    // First we execute our common code to connection to the database and start the session 
    require("../inc/db.php");
     
    // We remove the user's data from the session 
    unset($_SESSION['user']); 
     
    // We redirect them to the login page 
    header("Location: login.php"); 
    die("Redirecting to: login.php");

    require("../inc/header.php"); 