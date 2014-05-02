<?php 
    // Title:       user/register.php
    // Desc:        Displays the page that allows user to register account
    // Date:        March 22, 2014
    // Version:     1.0
    // Author:      John Meanor
  require_once("../inc/functions.php");

  $submitted_username="";
  $submitted_name="";
  $submitted_email="";
  $submitted_phone="";
  $submitted_description="";
  $submitted_public_loc="";
  $submitted_url="";
  $submitted_revenue = "";
  $submitted_category = "";
  $submitted_poc = "";
  $submitted_age = "";
  $submitted_gender = "";
  $submitted_income = "";


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


        if(empty($_POST['description']) || strlen($_POST['description']) < 1 ) 
        { 
            $_POST['message']['content'] = "You must enter a personal statement."; 
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

        if ($isCompany) {
          // Check company fields
          if(empty($_POST['revenue'])) 
          { 
              $_POST['message']['content'] = "You must enter the annual revenue for your company."; 
              $_POST['message']['type'] = "danger";
              $fields_ok = false;
          } else {
            $submitted_revenue=$_POST['revenue'];                
          }
          if(empty($_POST['category'])) 
          { 
              $_POST['message']['content'] = "You must enter a category for your company."; 
              $_POST['message']['type'] = "danger";
              $fields_ok = false;
          } else {
            $submitted_category=$_POST['category'];                
          }
          if(empty($_POST['point_of_contact'])) 
          { 
              $_POST['message']['content'] = "You must enter a name for the point of contact for your company."; 
              $_POST['message']['type'] = "danger";
              $fields_ok = false;
          } else {
            $submitted_poc=$_POST['point_of_contact'];                
          }

          $type = "company";
        } else {
          if(empty($_POST['age']) || $_POST['age'] < 1) 
          { 
              $_POST['message']['content'] = "You must enter your age."; 
              $_POST['message']['type'] = "danger";
              $fields_ok = false;
          } else {
            $submitted_age=$_POST['age'];                
          }
          if(empty($_POST['gender'])) 
          { 
              $_POST['message']['content'] = "You must choose a gender."; 
              $_POST['message']['type'] = "danger";
              $fields_ok = false;
          } else {
            $submitted_gender=$_POST['gender'];                
          }
          if(empty($_POST['annual_income']) || $_POST['annual_income'] < 1) 
          { 
              $_POST['message']['content'] = "You must enter your annual income."; 
              $_POST['message']['type'] = "danger";
              $fields_ok = false;
          } else {
            $submitted_income=$_POST['annual_income'];                
          }
          $type = "person";
        }


        if($fields_ok) {
            
            $result = register($_POST['username'], $_POST['password'], $_POST['email'], $_POST['name'], $phone, $type, 
                              $submitted_description, $submitted_public_loc, $submitted_url,
                              $submitted_revenue, $submitted_category, $submitted_poc,
                              $submitted_age, $submitted_gender, $submitted_income,
                               $db);

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

        <form id="regform" class="form-signin" action="register.php" method="POST"> 
          <h2 class="form-signin-heading">Register</h2>
          <div class="alert alert-info">All fields are required.</div>
            <input class="form-control" type="text" name="username" placeholder="Username"  value="<?php echo $submitted_username; ?>" autofocus /> 
            <input class="form-control" type="text" name="name" placeholder="Your Name" value="<?php echo $submitted_name; ?>" />
            <input class="form-control" type="text" name="email" placeholder="Email" value="" />
            <input class="form-control" type="text" name="phone_number" placeholder="Phone Number" value="<?php echo $submitted_phone; ?>" /> 
            <input class="form-control"  rows="4" type="password" name="password" placeholder="Password" value="">
            <textarea class="form-control" name="description" placeholder="Enter a brief personal statement here." value="<?php echo $submitted_description; ?>"></textarea>
            <input class="form-control" type="text" name="public_location" placeholder="University Park, PA" value="<?php echo $submitted_public_loc; ?>" />
            <input class="form-control" type="text" name="url" placeholder="http://www.psu.edu" value="<?php echo $submitted_url; ?>" /></p>
            <p><input id="isPerson"  type="radio" name="isCompany" value="0" checked/> I'm registering for personal use. <span class="glyphicon glyphicon-briefcase"></span></p>
            <p><input id="isCompany" type="radio" name="isCompany" value="1"> I'm registering for corporate use. <span class="glyphicon glyphicon-user"></span></p>
            
            <div id="companyfields">
              <label for="revenue">Annual Revenue $</label>
              <input id="revenue" type="number" name="revenue" min="1" />
              <input id="category" class="form-control" type="text" name="category" placeholder="Industry" />
              <input id="point_of_contact" class="form-control" type="text" name="point_of_contact" placeholder="Point of Contact Name" />
            </div>

            <div id="personfields">
              <label for="age">Age</label>
              <input id="age" type="number" name="age" min="1"/>
              <label for="annual_income">Annual Income $</label>
              <input id="annual_income" type="number" name="annual_income" min="1" />
              <p><input id="gender_m" class="" type="radio" name="gender" value="M" checked /> M <input id="gender_F" class="" type="radio" name="gender" value="F" checked /> F</p>
            </div>
            <br />
            <button  class="btn btn-lg btn-primary btn-block" type="submit">Register</button>
            <a href="login.php" class="btn btn-lg btn-block btn-default">Log In </a>
        </form>
      </div>


      <script>
        $("#companyfields").hide();

        $( "#isCompany" ).on( "click", function() {
          $( "#companyfields" ).show("fast");
          $("#personfields").hide("fast");   
        });

        $( "#isPerson" ).on( "click", function() {
          $( "#companyfields" ).hide("fast");
          $("#personfields").show("fast");   
        });
      </script>


    </body>
</html>