<?php 
    // Title:       shop/item.php
    // Desc:        Displays individual item auction page.  
    // Date:        March 22, 2014
    // Version:     1.0
    // Author:      John Meanor

    require_once("../inc/header.php");
  
?>

    <div class="container">
      <div class="row">
        <div class="col-md-1">
        </div>
        <div class="col-md-10">
          <h4></h4>
          <button type="button" class="btn btn-sm btn-warning">Back to Results</button>
          <hr />
        </div>
        <div class="col-md-1">
        </div>
      </div>
    </div>

    <div class="container marketing">

      <!-- Three columns of text below the carousel -->
      <div class="row">
        <div class="col-lg-1"></div>
        <div class="col-lg-3">
          <center>
            <img class="img-thumbnail" height="200" width="200" src="../inc/img/2.jpg" alt="Generic placeholder image">
            <ul class="pagination">
              <li class="active"><a href="#">1 <span class="sr-only">(current)</span></a></li>
              <li class="disabled"><a href="#">2</a></li>
              <li class="disabled"><a href="#">3</a></li>
              <li class="disabled"><a href="#">4</a></li>
            </ul>

          </center>
        </div><!-- /.col-lg-4 -->
        <div class="col-lg-5">
          <h2>Used Macbook Pro Retina 2007</h2>
          <b>Current Bid</b>: $589.50 <span class="badge">12</span><br />
          <b>Buy it Now Price:</b> $1100.00
          <h1></h1>
          <center><button type="button" class="btn btn-primary">Place Bid!</button> <button type="button" class="btn btn-success">Buy it Now</button> </center>
          <br/>
          <p>Donec sed odio dui. Etiam porta sem malesuada magna mollis euismod. Nullam id dolor id nibh ultricies vehicula ut id elit. Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Praesent commodo cursus magna.</p>
          <p>Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.</p>
          <p>Etiam porta sem malesuada magna mollis euismod. Cras mattis consectetur purus sit amet fermentum. Aenean lacinia bibendum nulla sed consectetur.</p>
          <p>Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.</p>
        </div>
        <div class="col-lg-2">
          <h4>Seller Info</h4>
          <p><a>userName123</a></p>
          <p>Items Sold: 254</p>
          <p>Items Bought: 14</p>
          <p>Location: United Kingdom</p>
          <p><button type="button" class="btn btn-sm btn-default">Contact Seller</button></p>


        </div>
       
        <div class="col-lg-1"></div>
      </div><!-- /.row -->



      <?php require_once("../inc/footer.php"); ?>
