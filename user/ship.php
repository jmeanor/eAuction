<?php 
    // Title:       home.php
    // Desc:        Main display for logged in users.
    // Date:        March 22, 2014
    // Version:     1.0
    // Author:      John Meanor
    require_once("../inc/functions.php");
    checkPermissions();

    // Only admins have access to this page.
    if (!isAdmin()) {

        header("Location: ../home.php"); 
        die("Not authorized administrator."); 
    }
    
    require_once("../inc/header.php");
    $wasMailed = false;
    $error = false;

    if (!empty($_POST['item'])){
        $result = markShipped($_POST['item'],$db);
        if (!$result['success'])
        {
            $error = $result['message'];
        }
        else {
            $wasMailed = true;
        }
    }


?>

<div class="container">
        

        <div class="row">
            <div class="col-lg-12">
                <h3><span class="glyphicon glyphicon-send"></span> Ship Package</h3>
                <h5><span class="glyphicon glyphicon-time"></span> <?php echo date("D M d, Y G:i a"); ?></h5>
                <?php if ($wasMailed) { ?><div class="alert alert-success">Delivery marked as shipped!</div><?php } ?>
                <?php if ($error) { ?><div class="alert alert-danger"><?php echo $error; ?></div><?php } ?>
            </div>
        </div>
        <!-- /.row -->
        <hr/>

        <div class="row" align="center">
            <div class="col-sm-12">
                <form action="ship.php" method="POST">
                    <label for="item">Item ID#</lablel>
                    <input class="form-control" type="number" min="0" id="item" name="item" />
                    
                    
                    <br /><br />
                    <button class="btn btn-lg btn-primary">Mark as Shipped</button>
                </form>

            </div>
        </div>
        <!-- /.row -->


        <?php require_once("../inc/footer.php"); ?>