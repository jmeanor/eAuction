<?php 
    // Title:       shop/upload_test.php
    // Desc:        Page used to test uploading images  
    // Date:        March 22, 2014
    // Version:     1.0
    // Author:      John Meanor

   require_once("../inc/header.php");
   
   if(!empty($_FILES))
   {
      $file = uploadFile('file');
      die(var_dump($file));
   }
?>

   <div class="container">
      <form method='post' action='upload_test.php'  enctype="multipart/form-data">
         <input type='file' id='file' name='file' />
         <input type='submit' value='Submit' />
      </form>
   </div>

<?php require_once("../inc/footer.php"); ?>
