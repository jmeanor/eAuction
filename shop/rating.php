<?php 
    // Title:       rating.php
    // Desc:        Displays rating page. 
    // Date:        April 14, 2014
    // Version:     1.0
    // Author:      Tom Byrne

    require_once("../inc/header.php");
	if (!empty($_GET['id'])) {
	  $data = getProfileData($_GET['id'], $db);
	}
    else {
      $data = getProfileData($_SESSION['user']['user_id'], $db);
    }
  // If the user has entered form information to log in with.
    if (!empty($_POST))
    {
        // variable used to determine if form is okay to submit to API
        $fields_ok = true;

        // Ensure that the user has entered a non-empty username 
        if(($_POST['rating'] > 10) || ($_POST['rating'] < 0)) 
        {   
            $_POST['message']['content'] = "Please enter a rating between 0 and 10."; 
            $_POST['message']['type'] = "danger";
            $fields_ok = false;
        }  
    }


	if($fields_ok) {
		$result = submit_rating($_POST['item_id'], $_POST['buyer_id'], $_POST['score'], $_POST['description'], $db)
	  if ($result['success'])
	  {
		
	  }
	  else 
	  {
		// Set error message 
		$_POST['message']['content'] = $result['message'];
		$_POST['message']['type'] = "danger";

		// Fill in the username field that the user tried to login with
		$submitted_username = $_POST['username'];
		$submitted_name= $_POST['name'];
		$submitted_phone=$_POST['username'];
		$submitted_description=$_POST['description'];
		$submitted_public_loc=$_POST['public_location'];
		$submitted_url=$_POST['url'];
	  }
	} 
  
?>

    <div class="container">
      <div class="row">
        <div class="col-md-1">
        </div>
        <div class="col-md-10">
          <h4></h4>
		  <h1>Rate <?php echo "Placeholder Text" ?></h1>
          <button type="button" class="btn btn-sm btn-warning">Return</button>
          <hr/>
        </div>
        <div class="col-md-1">
        </div>
      </div>
    </div>

    <div class="container marketing">

      <!-- Three columns of text below the carousel -->
      <div class="row">
        <div class="col-lg-1"></div>
        <div class="col-lg-3">
          <center>
            <img class="img-thumbnail" height="200" width="200" src="../inc/img/2.jpg" alt="Generic placeholder image">
            <ul class="pagination">
              <li class="active"><a href="#">1 <span class="sr-only">(current)</span></a></li>
              <li class="disabled"><a href="#">2</a></li>
              <li class="disabled"><a href="#">3</a></li>
              <li class="disabled"><a href="#">4</a></li>
            </ul>

          </center>
        </div><!-- /.col-lg-4 -->
        <div class="col-lg-5">	  
		  <h3>Please rate your purchase (from 1 to 10):</h3>
		  <p><input type="text" name="rating" style="width: 28px;">
          <h3>- Any feedback on the item?</h3>
		  <textarea name="feedback" rows="5" cols="40" style="width: 445px; height: 140px;"></textarea>	  
		  
		 
		  <center><button type="button" class="btn btn-primary" type = "submit">Submit Rating</button>
          <br/>
        </div>
        <div class="col-lg-2">
          <h4>Seller Info</h4>
          <p><a>userName123</a></p>
          <p>Items Sold: 254</p>
          <p>Items Bought: 14</p>
          <p>Location: United Kingdom</p>
          <p><button type="button" class="btn btn-sm btn-default">Contact Seller</button></p>


        </div>
       
        <div class="col-lg-1"></div>
      </div><!-- /.row -->



      <?php require_once("../inc/footer.php"); ?>
