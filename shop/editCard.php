<?php 
    // Title:       shop/editCard.php
    // Desc:        Displays user credit cards for either adding, changing, or deleting 
    // Date:        March 22, 2014
    // Version:     1.0
    // Author:      John Meanor
    require_once("../inc/functions.php");
	require_once("../inc/header.php");

    if (!empty($_GET['userid'])) {
	  $data = getProfileData($_GET['userid'], $db);
	  $item_data = getItemData($_GET['userid'], $db);
	  $ratings_data = getRatingsData($_GET['userid'], $db);
	  $ratings_score = getRatingScore($_GET['userid'], $db);
	  $item_count = getItemCount($_GET['userid'], $db);
	  $sm_data = getSocialMedia($_GET['userid'], $db);
	}
    else {
      $data = getProfileData($_SESSION['user']['user_id'], $db);
	  $item_data = getItemData($_SESSION['user']['user_id'], $db);
	  $ratings_score = getRatingScore($_SESSION['user']['user_id'], $db);
	  $ratings_data = getRatingsData($_SESSION['user']['user_id'], $db);
	  $item_count = getItemCount($_SESSION['user']['user_id'], $db);
	  $sm_data = getSocialMedia($_SESSION['user']['user_id'], $db);
    }
	
	
	
?>

