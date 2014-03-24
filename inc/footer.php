	<?php 
	// Title:		footer.php
	// Desc:		Included at the bottom of all logged in pages
	// Date:		March 22, 2014
	// Version:		1.0
	// Author:		John Meanor

// Check which directory the file is from to include the correct files.
if (strpos($_SERVER['PHP_SELF'], '/shop/') !== FALSE || 
  strpos($_SERVER['PHP_SELF'], '/user/') !== FALSE  ) {
	$path = "../";
} else {
  $path = "";
}
?>
	<div class="container">
      <div class="row">
        <div class="col-md-12">
        	<hr>
			<footer>
				<p><span class="glyphicon glyphicon-globe"></span> About Us | &copy; eAuction 2014</p>
			</footer>
		</div>
	</div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="<?php echo $path; ?>inc/js/jquery-1.10.2.min.js"></script>
    <script src="<?php echo $path; ?>inc/js/bootstrap.min.js"></script>
  </body>
</html>