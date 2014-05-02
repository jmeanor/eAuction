<?php 
  require_once("../inc/functions.php");

	 if (!empty($_GET['userid'])) {
	  $data = getProfileData($_GET['userid'], $db);
	  $item_data = getItemData($_GET['userid'], $db);
	}
    else {
      $data = getProfileData($_SESSION['user']['user_id'], $db);
	  $item_data = getItemData($_SESSION['user']['user_id'], $db);
    }  
  $submitted_name = $data['user_data']['name'];
  $submitted_email = $data['user_data']['email'];
  $submitted_phone = $data['user_data']['phone_number'];
  $submitted_description=$data['user_data']['description'];
  $submitted_public_loc=$data['user_data']['public_location'];
  $submitted_url=$data['user_data']['url'];


    // If the user has entered form information to log in with.
    if (!empty($_POST))
    {
        // variable used to determine if form is okay to submit to API
        $fields_ok = true;

		if(empty($_POST['name']))
		{
			$_POST['message']['content'] = "Please enter a valid name.";
			$_POST['message']['type'] = "danger";
			$fields_ok = false;
		}
		
        if(empty($_POST['phone_number'])) 
        { 
            $_POST['message']['content'] = "Please enter a valid phone number using only numbers."; 
            $_POST['message']['type'] = "danger";
            $fields_ok = false;
        } 
		
        else 
		{
          $phone = preg_replace('/[^0-9]/', '', $_POST['phone_number']);
          if(strlen($phone) !== 10) {
              $_POST['message']['content'] = "Please enter a valid phone number using only numbers."; 
              $_POST['message']['type'] = "danger";
              $fields_ok = false;
          }
        }

        if(!filter_var($_POST['url'], FILTER_VALIDATE_URL)) 
        { 
            $_POST['message']['content'] = "Please enter a company URL with the full path. Ex: http://www.psu.edu"; 
            $_POST['message']['type'] = "danger";
            $fields_ok = false;
        } else {
            $submitted_url=$_POST['url'];                
        }
        
         
        // Make sure the user entered a valid E-Mail address 
        // filter_var is a useful PHP function for validating form input, see: 
        // http://us.php.net/manual/en/function.filter-var.php 
        // http://us.php.net/manual/en/filter.filters.php 
        if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) 
        { 
            $_POST['message']['content'] = "Not a valid email address."; 
            $_POST['message']['type'] = "danger";
            $fields_ok = false;
        } 

        if($fields_ok) {
            
            $result = updateUser($data['user_data']['user_id'], $_POST['name'], $_POST['email'], $_POST['phone_number'], $_POST['description'], $_POST['public_location'], $_POST['url'], $db);

              if ($result['success'])
              {
                header("Location: profile.php"); 
                die("Redirecting to: profile.php"); 
              }
              else 
              {
                // Fill in the username field that the user tried to login with
				$submitted_name=$_POST['name'];
                $submitted_email=$_POST['email'];
                $submitted_phone=$_POST['phone'];
                $submitted_phone=$_POST['description'];
                $submitted_public_loc=$_POST['public_location'];
                $submitted_url=$_POST['url'];
              }
          }
    }

     require("../inc/header.php"); 
?> 

<style type="text/css">
  body {
    padding-top: 40px;
    padding-bottom: 40px;
    background-color: #eee;
  }

  .form-signin {
    max-width: 330px;
    padding: 15px;
    margin: 0 auto;
  }
  .form-signin .form-signin-heading,
  .form-signin .checkbox {
    margin-bottom: 10px;
  }
  .form-signin .checkbox {
    font-weight: normal;
  }
  .form-signin .form-control {
    position: relative;
    font-size: 16px;
    height: auto;
    padding: 10px;
    -webkit-box-sizing: border-box;
       -moz-box-sizing: border-box;
            box-sizing: border-box;
  }
  .form-signin .form-control:focus {
    z-index: 2;
  }
  .form-signin input[type="text"] {
    margin-bottom: -1px;
    border-bottom-left-radius: 0;
    border-bottom-right-radius: 0;
  }
  .form-signin input[type="password"] {
    margin-bottom: 0px;
    border-top-left-radius: 0;
    border-top-right-radius: 0;
  }
</style>

</head>
    <body>
      <div class="container">
        <div class="row">
          <h1></h1>
          <?php if (isset($_POST['message']) && $_POST['message']['type'] == "danger") echo '<div class="alert alert-danger">'.$_POST['message']['content'].'</div>'; ?>
        </div>


        <form id="updateform" class="form-signin" action="updateInfo.php" method="POST"> 
          <h2 class="form-signin-heading">Update Information</h2>
            <input class="form-control" type="text" name="name" placeholder="Name" value="<?php echo $submitted_name?>" />
            <input class="form-control" type="text" name="email" placeholder="Email" value="<?php echo $submitted_email?>" />
            <input class="form-control" type="text" name="phone_number" placeholder="Phone Number" value="<?php echo $submitted_phone?>" /> 
            <input class="form-control" type="text" name="description" placeholder="Enter a brief personal statement here." value="<?php echo $submitted_description ?>"/>
            <input class="form-control" type="text" name="public_location" placeholder="University Park, PA" value="<?php echo $submitted_public_loc ?>" />
            <input class="form-control" type="text" name="url" placeholder="website (http://www.psu.edu)" value="<?php echo $submitted_url ?>" /></p>
            <br />
            <button  class="btn btn-lg btn-primary btn-block" type="submit" hr="../user/profile.php">Update</button>
        </form>
      </div>
    </body>
</html>