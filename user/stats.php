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

    $report = getReport($db);

    if (!empty($_GET['mail']) && $_GET['mail'] == "true"){
        // sends an email to the telemarketer
        //mail("telemarketer@telemarketercompany.com","New eAuction Report",$report['data']);
        $wasMailed = true;
    }
    setlocale(LC_MONETARY, 'en_US');

    $catStats = getCategoryStats($db);
    $error = false;

    $j = 0;
    $total = 0;
    //var_dump($catStats);
    // $catData = array();
    // if ($catStats['success']) {
    //     for ($i=2; $i<=16; $i++) {
    //         if (isset($catStats['data'][$j]) && $catStats['data'][$j]['category_id'] == (string)$i) {
    //             $catData[$i] = (int)$catStats['data'][$j]['count'];
    //             $j++;
    //         }
    //         else {
    //             $catData[$i] = 0;
    //         }
    //     }

    // } else 
    //     $error = true;
    // $total2 = $catData[2] + $catData[3] + $catData[4] + $catData[5] + $catData[8] + $catData[7] + $catData[6];
    // $total10 = $catData[10] + $catData[12] + $catData[13] + $catData[14] + $catData[11];
    // $total = $total2 + $total10 + $catData[9] + $catData[15] + $catData[16];

    // $total4 = $catData[4]+$catData[5]+$catData[6]+$catData[7]+$catData[8];
    // echo "<p>";
    // var_dump($total);
    // echo "<p>";
    // var_dump($catData);

    $sales = getAverageSale($db);
?>

<link rel="stylesheet" type="text/css" href="../inc/css/jchartfx.css" />
    <script type="text/javascript" src="../inc/js/jchartfx/jchartfx.system.js"></script>
    <script type="text/javascript" src="../inc/js/jchartfx/jchartfx.coreVector.js"></script>
<div class="container">
        

        <div class="row">
            <div class="col-lg-12">
                <h3>Auction Statistics</h3>
                <h5><span class="glyphicon glyphicon-time"></span> <?php echo date("D M d, Y G:i a"); ?></h5>
                <?php if ($wasMailed) { ?><div class="alert alert-success">Report sent to telemarketer! </div><?php } ?>
            </div>
        </div>
        <!-- /.row -->

        <hr/>
        
        <div class="row">
            <div class="col-lg-6">
                <div id="container2" class=""></div>
            </div>
            <div class="col-lg-6">
                <div id="container3" class=""></div>
            </div>
        </div> 
        <!-- /.row -->
        

        


        <script src="http://code.highcharts.com/highcharts.js"></script>
        <script src="http://code.highcharts.com/modules/exporting.js"></script>

        <script type="text/javascript" language="javascript">
            $(document).ready(function($){
                
            


        $(function () {
            $('#container2').highcharts({
                chart: {
                    type: 'pie',
                    options3d: {
                        enabled: true,
                        alpha: 45,
                        beta: 0
                    }
                },
                title: {
                    text: 'Sold items by category'
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        depth: 35,
                        dataLabels: {
                            enabled: true,
                            format: '{point.name}'
                        }
                    }
                },
                series: [{
                    type: 'pie',
                    name: 'Auction Category',
                    data: [
                        <?php foreach($catStats['data'] as $catStat) {
                            echo '[\''.$catStat['name'].'\','.$catStat['count'].'],';
                        } ?>
                    ]
                }]
            });
        });

        $(function () {
            $('#container3').highcharts({
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Average Sales Price'
                },
                subtitle: {
                    text: ''
                },
                xAxis: {
                    type: 'category',
                    labels: {
                        rotation: -45,
                        align: 'right',
                        style: {
                            fontSize: '13px',
                            fontFamily: 'Verdana, sans-serif'
                        }
                    }
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Selling Price (USD $)'
                    }
                },
                legend: {
                    enabled: false
                },
                tooltip: {
                    pointFormat: 'Population in 2008: <b>{point.y:.1f} millions</b>',
                },
                series: [{
                    name: 'Population',
                    data: [ 
                        <?php foreach($sales['data'] as $sale) {
                            echo '[\''.$sale['name'].'\','.$sale['avg_p'].'],';
                        } ?>
                    ],
                    dataLabels: {
                        enabled: true,
                        rotation: -90,
                        color: '#FFFFFF',
                        align: 'right',
                        x: 4,
                        y: 10,
                        style: {
                            fontSize: '13px',
                            fontFamily: 'Verdana, sans-serif',
                            textShadow: '0 0 3px black'
                        }
                    }
                }]
            });
        });


        });

        </script>

        <script>
            function printReport()
            {
                window.print();
            }
        </script>


        <?php require_once("../inc/footer.php"); ?>