<?php 
    // Title:       user/register.php
    // Desc:        Enables a new user to register. 
    // Date:        March 22, 2014
    // Version:     1.0
    // Author:      John Meanor

    // Registration query here

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="../../assets/ico/favicon.ico">

    <title>Register with eAuction</title>

    <!-- Bootstrap core CSS -->
    <link href="../inc/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../inc/css/signin.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

  <div class="container">

    <form class="form-signin" role="form">
      <h2 class="form-signin-heading">Register</h2>
      <input type="username" class="form-control" placeholder="Username" required autofocus>
      <input type="email" class="form-control" placeholder="Email address" required autofocus>
      <input type="password" class="form-control" placeholder="Password" required>
      <label class="checkbox">
        <input type="checkbox" value="remember-me"> I'm a Corporate User
      </label>
      <button class="btn btn-lg btn-primary btn-block" type="submit">Register!</button>
       <a href="login.php" class="btn btn-lg btn-block btn-success">I'm already a user. </a>
    </form>

  </div> <!-- /container -->

  </body>
</html>