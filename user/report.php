<?php 
    // Title:       user/report.php
    // Desc:        Used for admins to send a report to telemarketers.
    // Date:        March 22, 2014
    // Version:     1.0
    // Author:      John Meanor

	require_once("../inc/header.php");
	checkPermissions();
    // Only admins have access to this page.
    if (!isAdmin()) {

        header("Location: ../home.php"); 
        die("Not authorized administrator."); 
    }
    
    $wasMailed = false;

    $report = getReport($db);

    if (!empty($_GET['mail']) && $_GET['mail'] == "true"){
        // sends an email to the telemarketer
        //mail("telemarketer@telemarketercompany.com","New eAuction Report",$report['data']);
        $wasMailed = true;
    }

    date_default_timezone_set('America/New_York');
    setlocale(LC_MONETARY, 'en_US');
?>

<div class="container">
        

        <div class="row">
            <div class="col-lg-12">
                <h3>Telemarketer Report</h3>
                <h5><span class="glyphicon glyphicon-time"></span> <?php echo date("D M d, Y G:i a"); ?></h5>
                <?php if ($wasMailed) { ?><div class="alert alert-success">Report sent to telemarketer! </div><?php } ?>
            </div>
        </div>
        <!-- /.row -->


        <div class="row">
            <div class="col-sm-12">
                <?php if ($report['success'] == false) echo $report['message']; else { ?>
                <table class="table table-hover">
                    <tr>
                        <th>Name</th>
                        <th>Gender</th>
                        <th>Age</th>
                        <th>Annual Income</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Location</th>
                    </tr>
                    <?php foreach($report['data'] as $row) { ?>
                    <tr>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['gender']; ?></td>
                        <td><?php echo $row['age']; ?></td>
                        <td>$<?php echo number_format($row['annual_income'], 2); ?></td>
                        <td><?php echo $row['phone_number']; ?></td>
                        <td><a href="mailto:<?php echo $row['email']; ?>"><?php echo $row['email']; ?></a></td>
                        <td><?php echo $row['public_location']; ?></td>
                    </tr>
                    <?php }// foreach?>
                </table>
                <a class="btn btn-info" href="report.php?mail=true">Email Report</a>
                <span class=""><button class="btn btn-primary" onclick="printReport()">Print Report</button></span>
                <?php }//else ?>
            </div>
        </div>
        <!-- /.row -->

        <script>
            function printReport()
            {
                window.print();
            }
        </script>


        <?php require_once("../inc/footer.php"); ?>