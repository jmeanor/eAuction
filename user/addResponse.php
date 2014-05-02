<?php 
    // Title:       shop/item.php
    // Desc:        Displays individual item auction page.  
    // Date:        March 22, 2014
    // Version:     1.0
    // Author:      John Meanor

    require_once("../inc/header.php");
	$item_info = itemInfo($_GET['id'], $db);
	$ratings_data = getRatingsData($_GET['id'], $db);
	
	$submitted_response = "";
		
	// If the user has entered form information to log in with.
    if (!empty($_POST))
    {
        // variable used to determine if form is okay to submit to API
        $fields_ok = true;

	    if($fields_ok) {
	      $result = submitResponse($_POST['response'], $ratings_data['ratings_data'][0]['item_id'], $db);

		  if ($result['success'])
		  {
			header("Location: ../user/profile.php"); 
			die("Redirecting to: ../user/profile.php"); 
		  }
		  else 
		  {
			// Set error message 
			$_POST['message']['content'] = $result['message'];
			$_POST['message']['type'] = "danger";

			$submitted_response= $_POST['response'];
		  }		  
	  }
	}
?>

<style type="text/css">
  body {
    padding-top: 40px;
    padding-bottom: 40px;
    background-color: #eee;
  }
</style>

    <div class="container">
	  <h1>Feedback on <?php echo $ratings_data['ratings_data'][0]['name'] ?></h1>
	  <h2>Score: <?php echo $ratings_data['ratings_data'][0]['score'] ?></h2>
	  <h2>Feedback</h2>
	  <p><?php echo $ratings_data['ratings_data'][0]['description'] ?></p>
    </div>

    <div class="container marketing">
	  <body>
	  <div class="container">
	  <div class="row">
	    <h1></h1>
	  </div>

	  <form id="responseform" class="form-create" action="addResponse.php" method="POST"> 
        <h2>Your Response</h2>
		<p><input class="form-control" type="text" name="response" placeholder="Place Response Here"  value="<?php echo $submitted_response ?>"/> </p>
	    <button  class="btn btn-lg btn-primary btn-block" type="submit">Submit Feedback</button> 
      </form>
      </div>     
      <div class="col-lg-1"></div>
      </div><!-- /.row -->
	  
      <?php require_once("../inc/footer.php"); ?>
