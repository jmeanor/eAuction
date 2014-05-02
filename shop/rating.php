<?php 
    // Title:       rating.php
    // Desc:        Displays rating page. 
    // Date:        April 14, 2014
    // Version:     1.0
    // Author:      Tom Byrne

    require_once("../inc/header.php");
	require_once("../inc/header.php");
	$item_info = itemInfo($_GET['iid'], $db);
?>

<style type="text/css">
  body {
    padding-top: 40px;
    padding-bottom: 40px;
    background-color: #eee;
  }
</style>

    <div class="container">
	  <h1>Feedback on <?php echo $item_info['item_data']['name'] ?></h1>
    </div>

    <div class="container marketing">
	  <body>
	  <div class="container">
	  <div class="row">
	  <div class="col-lg-3">
      </div>    
	    <div class="col-lg-6">
		<p>Feedback consists of a numeric score from 1.0 to 10.0 (example: 5.10) and a text response.</p>
		<form id="responseform" class="form-create" action="../user/boughtItems.php?id=<?php echo $_GET['id'] ?>&iid=<?php echo $_GET['id']?>" method="POST"> 
			<p><b>Score:</b> <input type='text' name='score' placeholder="Between 1.0 and 10.0" /></p>
			<p><b>Feedback:</b> <textarea class="form-control" name="feedback"></textarea></p>
			<button  class="btn btn-lg btn-primary btn-block" type="submit">Submit Feedback</button> 
		</form>
		</div>
		
	  <div class="col-lg-3">
      </div>    
      </div><!-- /.row -->
	  </div>
	  
      <?php require_once("../inc/footer.php"); ?>
