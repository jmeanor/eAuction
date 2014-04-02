<?php 

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

