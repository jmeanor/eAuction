<?php 
  require_once("../inc/functions.php");

  $submitted_username="";
  $submitted_name="";
  $submitted_email="";
  $submitted_phone="";
  $submitted_description="";
  $submitted_public_loc="";
  $submitted_url="";


    // If the user has entered form information to log in with.
    if (!empty($_POST))
    {
        // variable used to determine if form is okay to submit to API
        $fields_ok = true;

        // Ensure that the user has entered a non-empty username 
        if(empty($_POST['username'])) 
        {   
            $_POST['message']['content'] = "Please enter a username."; 
            $_POST['message']['type'] = "danger";
            $fields_ok = false;
        } 
         
        // Ensure that the user has entered a non-empty password 
        if(empty($_POST['password'])) 
        { 
            $_POST['message']['content'] = "Please enter a password."; 
            $_POST['message']['type'] = "danger";
            $fields_ok = false;
        } 

        // Ensure that the user has entered a non-empty password 
        if(empty($_POST['name'])) 
        { 
            $_POST['message']['content'] = "Please enter your name."; 
            $_POST['message']['type'] = "danger";
            $fields_ok = false;
        } 

        if(empty($_POST['phone_number'])) 
        { 
            $_POST['message']['content'] = "Please enter a valid phone number using only numbers."; 
            $_POST['message']['type'] = "danger";
            $fields_ok = false;
        } 
        else {
          $phone = preg_replace('/[^0-9]/', '', $_POST['phone_number']);
          if(strlen($phone) !== 10) {
              $_POST['message']['content'] = "Please enter a valid phone number using only numbers."; 
              $_POST['message']['type'] = "danger";
              $fields_ok = false;
          }
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

        $isCompany = isset($_POST['isCompany']) && $_POST['isCompany']  ? "1" : "0";

        if (isset($_POST['isCompany'])) {
          // Check company fields

          if(empty($_POST['description']) || strlen($_POST['description']) < 1 ) 
          { 
              $_POST['message']['content'] = "You must enter a company description."; 
              $_POST['message']['type'] = "danger";
              $fields_ok = false;
          } else {
            $submitted_description=$_POST['description'];                
          }
          if(empty($_POST['public_location'])) 
          { 
              $_POST['message']['content'] = "You must enter the location of your company."; 
              $_POST['message']['type'] = "danger";
              $fields_ok = false;
          } else {
            $submitted_public_loc=$_POST['public_location'];                
          }
          if(!filter_var($_POST['url'], FILTER_VALIDATE_URL)) 
          { 
              $_POST['message']['content'] = "Please enter a company URL with the full path. Ex: http://www.psu.edu"; 
              $_POST['message']['type'] = "danger";
              $fields_ok = false;
          } else {
            $submitted_url=$_POST['url'];                
          }
          $type = "company";
        }
        else{
          $type = "";
        }


        if($fields_ok) {
            
            $result = register($_POST['username'], $_POST['password'], $_POST['email'], $_POST['name'], $phone, $type, $submitted_description, $submitted_public_loc, $submitted_url, $db);

              $submitted_username = ''; 

              if ($result['success'])
              {
                session_start();

                $_SESSION['message']['type'] = "success";
                $_SESSION['message']['content'] = "Account registered successfully! Please login below.";
                header("Location: login.php"); 
                die("Redirecting to: login.php"); 
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
    margin-bottom: 10px;
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

        <form id="regform" class="form-signin" action="register.php" method="POST"> 
          <h2 class="form-signin-heading">Register</h2>
          <div class="alert alert-info">All fields are required.</div>
            <input class="form-control" type="text" name="username" placeholder="Username"  value="<?php echo $submitted_username; ?>" autofocus /> 
            <input class="form-control" type="text" name="name" placeholder="Your Name" value="<?php echo $submitted_name; ?>" />
            <input class="form-control" type="text" name="email" placeholder="Email" value="" />
            <input class="form-control" type="text" name="phone_number" placeholder="Phone Number" value="<?php echo $submitted_phone; ?>" /> 
            <input class="form-control"  rows="4" type="password" name="password" placeholder="Password" value="">
            <p><input id="isCompany"  type="checkbox" name="isCompany" value="1"/> I'm a corporate user. <span class="glyphicon glyphicon-briefcase"></span></p>

            <div id="companyfields">
              <p><textarea class="form-control" name="description" placeholder="Enter a brief company description here." value="<?php echo $submitted_description; ?>"></textarea>
              <input class="form-control" type="text" name="public_location" placeholder="University Park, PA" value="<?php echo $submitted_public_loc; ?>" />
              <input class="form-control" type="text" name="url" placeholder="http://www.psu.edu" value="<?php echo $submitted_url; ?>" /></p>
            </div>

            <button  class="btn btn-lg btn-primary btn-block" type="submit">Register</button> 
        </form>
      </div>


      <script>
        $("#companyfields").hide();
        $( "#isCompany" ).on( "click", function() {
          $( "#companyfields" ).toggle("fast");   
        });
      </script>


    </body>
</html>