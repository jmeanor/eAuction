<?php 
    // Title:       shop/newAuction.php
    // Desc:        Displays bidding page.
    // Date:        April 14, 2014
    // Version:     1.0
    // Author:      Tom Byrne
    require_once("../inc/header.php");
	require_once("../inc/functions.php");
	
	if (!empty($_SESSION['userid'])) {
	  $data = getProfileData($_GET['userid'], $db);
    }
    else {
      $data = getProfileData($_SESSION['user']['user_id'], $db);
    }

	$submitted_user_id = $data['user_data']['user_id'];
	$submitted_item_name="";
	$submitted_item_description="";
	$submitted_starting_price="";	
	$submitted_buy_it_now_price="";
	$submitted_reserve_price="";
	$submitted_url="";
	$submitted_location="";
	$submitted_template="";
	$submitted_picture="";

	// Get the keywords available for the user to use. 
	$keywords = getKeywords($db);
	
	// If the user has entered form information to log in with.
    if (!empty($_POST))
    {
        // variable used to determine if form is okay to submit to API
        $fields_ok = true;

        // Ensure that the user has entered a non-empty item name 
        if(empty($_POST['item_name'])) 
        {   
            $_POST['message']['content'] = "Please enter an item name."; 
            $_POST['message']['type'] = "danger";
            $fields_ok = false;
        } 
		
        // Ensure that the user has entered a non-empty description 
        if(empty($_POST['description'])) 
        { 
            $_POST['message']['content'] = "Please enter a description of the item."; 
            $_POST['message']['type'] = "danger";
            $fields_ok = false;
        } 

        // Ensure that the user has entered a non-empty item name 
        if(empty($_POST['keywords'])) 
        {   
            $_POST['message']['content'] = "Please select at least one keyword."; 
            $_POST['message']['type'] = "danger";
            $fields_ok = false;
        } 

	    if($fields_ok) {
	    	//if there's a new kw to add to the database, add it first.
	    	if($_POST['new_kw']!= "") {
	    		addNewKeyword($_POST['new_kw'], $db);
	    		array_push($_POST['keywords'], $_POST['new_kw']);
	    	}

	      $result = create_item($submitted_user_id, $_POST['item_name'], $_POST['description'], $_POST['keywords'], $_POST['starting_price'], $_POST['buy_it_now_price'], $_POST['reserve_price'], $_POST['location'], $_POST['url'], $_POST['template'], $db);

	      $pic_upload = uploadFile('pic', $result['item_id'], $db );
	      if(!$pic_upload) die("NJASDLKAD");

		  if ($result['success'])
		  {
			header("Location: item.php?id=".$result['item_id']); 
			die("Redirecting to new item's page (ID:".$result['item_id']); 
		  }
		  else 
		  {
			// Set error message 
			$_POST['message']['content'] = $result['message'];
			$_POST['message']['type'] = "danger";

			// Fill in the username field that the user tried to login with
			$submitted_item_name= $_POST['item_name'];
			$submitted_item_description= $_POST['description'];
			$submitted_starting_price=$_POST['starting_price'];
			$submitted_buy_it_now_price=$_POST['buy_it_now_price'];
			$submitted_reserve_price=$_POST['reserve_price'];
			$submitted_location=$_POST['location'];
			$submitted_url=$_POST['url'];
			$submitted_template=$_POST['template'];
			$submitted_picture=$_POST['pic'];
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
	  <h1>Create an Auction</h1>
      <button type="button" class="btn btn-sm btn-warning">Return to Home</button>
    </div>

    <div class="container marketing">
	  <body>
	  <div class="container">
	  <div class="row">

	    <h1></h1>
		<?php if (isset($_POST['message']) && $_POST['message']['type'] == "danger") echo '<div class="alert alert-danger">'.$_POST['message']['content'].'</div>'; ?>
	  </div>

	  <form id="newitemform" class="form-create" action="newAuction.php" method="POST" enctype="multipart/form-data"> 
	    <div class="alert alert-info">All fields are required.</div>
        <p>Item Name: <input class="form-control" type="text" name="item_name" placeholder="Item Name"  value="<?php echo $submitted_item_name; ?>"/> </p>
        <p>Item Description: <input class="form-control" type="text" name="description" placeholder="Item Description" value="<?php echo $submitted_item_description; ?>" /> </p>
        <p>Add keywords to maximize your auction viewership.</p>
        	<p><select required name="keywords[]" class="form-control" multiple size="6" >
        		<?php foreach ($keywords as $keyword) { ?>
        		<option value="<?php echo $keyword['keyword']; ?>"><?php echo $keyword['keyword']; ?></option>
        		<?php } ?>
        	</select>
        </p>
        <p><b>Optionally</b> add one of your own keywords, if it's not listed. <input class="form-control" type="text" name="new_kw"></input> </p>
        <p>Starting Price: <input class="form-control" type="text" name="starting_price" placeholder="Starting Price" value="<?php echo $submitted_starting_price; ?>" /> </p>
        <p>Buy-It-Now Price: <input class="form-control" type="text" name="buy_it_now_price" placeholder="Buy It Now Price" value="<?php echo $submitted_buy_it_now_price; ?>" />  </p>
		<p>Reserve Price: <input class="form-control" type="text" name="reserve_price" placeholder="Reserve Price" value="<?php echo $submitted_reserve_price; ?>" />  </p>
        <p>Location of Item: <input class="form-control"  type="text" name="location" placeholder="Location" value="<?php echo $submitted_location; ?>" /> </p>
		<p>URL for Item: <input class="form-control" type="url" name="url" placeholder="URL" value="<?php echo $submitted_url; ?>" /> </p>
		<p>Template for Item</p>
		<p><input type="radio" name="template" value="1"> 1</p>
		<p><input type="radio" name="template" value="2"> 2</span></p>
		<p><input type="radio" name="template" value="3"> 3</span></p>
		<p>Upload Pictures!</p>
		<p><input type="file" class="form-control" name="pic" accept="image/*" value="<?php echo $submitted_picture;?>"></p>


	    <button  class="btn btn-lg btn-primary btn-block" type="submit">Create Auction</button> 
      </form>
      </div>     
      <div class="col-lg-1"></div>
      </div><!-- /.row -->
	  
      <?php require_once("../inc/footer.php"); ?>
