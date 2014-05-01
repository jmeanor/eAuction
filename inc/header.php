<?php 
	// Title:		header.php
	// Desc:		Included on all logged in pages
	// Date:		March 22, 2014
	// Version:		1.0
	// Author:		John Meanor

// Check which directory the file is from to include the correct files.
if (strpos($_SERVER['PHP_SELF'], '/shop/') !== FALSE || 
  strpos($_SERVER['PHP_SELF'], '/user/') !== FALSE  ) {
	$path = "../";
  require_once("../inc/db.php");
  require_once("../inc/functions.php");
} else {
  $path = "";
  require_once("db.php");
  require_once("functions.php");
}

    if (!empty($_POST['search'])) {
		$search = $_POST["search"];
		$placeholder = "";
	}
	else {
	  	$search = "";
	  	$placeholder = "What do you want to shop for?";
	}
	

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!--<link rel="shortcut icon" href="../../assets/ico/favicon.ico">-->

    <title>Welcome to eAuction</title>

    <!-- Bootstrap core CSS -->
    <link href="<?php echo $path; ?>inc/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?php echo $path; ?>inc/css/jumbotron.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>
    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="<?php echo $path; ?>home.php">eAuction</a>
        </div>
        <div class="navbar-collapse collapse">
        <?php if (isLoggedIn()) {?>
          <ul class="nav navbar-nav pull-right">
                <li><a href="<?php echo $path; ?>home.php">Home</a>
                </li>
                <li><a href="<?php echo $path; ?>shop/results.php">Shop</a>
                </li>
                <li><a href="<?php echo $path; ?>user/profile.php">Profile</a>
                </li>
                <li><a href="<?php echo $path; ?>user/logout.php">Logout</a>
                </li>
            </ul>
          <?php } ?>
        </div><!--/.navbar-collapse -->
      </div>
    </div>

    <?php if (isLoggedIn()) { ?>
    <!-- Query Bar -->
    <div class="container">
      <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-10">
          <h2></h2>
          <form method="post" action="<?php echo $path; ?>shop/results.php">
            <div class="form-group">
                <div class="col-md-8  ">
                  <input type="search" class="input-lg form-control" name="search" id="search" value="<?php echo $search; ?>" placeholder="<?php echo $placeholder; ?>">
                </div>
                <div class="col-md-1">
                  <button type="submit" class="btn btn-lg btn-primary">Go</button></div>
              </div>
            </div>
          </form>
        </div>
        <div class="col-md-1"></div>
    </div>
    <hr />
    <?php } ?>