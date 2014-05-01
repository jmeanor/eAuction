<?php 
  require_once("../inc/functions.php");

	 if (!empty($_GET['userid'])) {
	  $data = getProfileData($_GET['userid'], $db);
	}
    else {
      $data = getProfileData($_SESSION['user']['user_id'], $db);
    }  
	$submitted_username = "";
	$submitted_sm_type = "";
  
    // If the user has entered form information to log in with.
    if (!empty($_POST))
    {
        // variable used to determine if form is okay to submit to API
        $fields_ok = true;

        if(empty($_POST['username'])) 
        { 
            $_POST['message']['content'] = "Please enter a social media username."; 
            $_POST['message']['type'] = "danger";
            $fields_ok = false;
        } 
        else {
          $submitted_username=$_POST['username'];
        }
		
        if(empty($_POST['sm_type']))
        { 
            $_POST['message']['content'] = "You must choose a social media type."; 
            $_POST['message']['type'] = "danger";
            $fields_ok = false;
        } 
		else {
            $submitted_sm_type=$_POST['sm_type'];                
        }

        if($fields_ok) {
            
            $result = addSM($data['user_data']['user_id'], $_POST['username'], $_POST['sm_type'], $db);
              if ($result['success'])
              {
                header("Location: profile.php"); 
                die("Redirecting to: profile.php"); 
              }
              else 
              {
                // Fill in the username field that the user tried to login with
                $submitted_sm_type=$_POST['sm_type'];
                $submitted_username=$_POST['username'];
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
        </div>

        <form id="addform" class="form-signin" action="addSocialMedia.php" method="POST"> 
          <h2 class="form-signin-heading">Add Social Media Username</h2>
            <input class="form-control" type="text" name="username" placeholder="Username" value="<?php echo $submitted_username?>" />
			<p>Type of Social Media</p>
			<p><input type="radio" name="sm_type" value="fb"> Facebook</p>
			<p><input type="radio" name="sm_type" value="tw"> Twitter</p>
            <br />
            <button  class="btn btn-lg btn-primary btn-block" type="submit" hr="../user/profile.php">Update</button>
        </form>
      </div>
    </body>
</html>