<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['alogin'])==0)
{   
header("Location: index.php"); 
}
else{
    $sysdate=date('Y-m-d');
    $totalfine=0;
    ?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Foundation Library Management System</title>
        <link rel="icon" href="img/logo.png" type="image/png">
        <script src="js/charts.js"></script>   
        <script src="js/chart.js"></script>
    </head>
    <body class="top-navbar-fixed">
        <div class="main-wrapper">
            <?php include('includes/topbar.php');?>
            <div class="content-wrapper">
                <div class="content-container">
                    <?php include('includes/leftbar.php');?>  
                    <div class="main-page">
                        <div class="container-fluid">
                            <div class="row page-title-div">
                                <div class="col-sm-8">
                                    <h2 class="title">Foundation Library Management System</h2>                                  
                                </div>
                                <!-- /.col-sm-8 -->
                             </div>
                            <!-- /.row -->                      
                        </div>
                        <!-- /.container-fluid -->
                        <section class="section">
                            <div class="container-fluid">
                                <!-- 1st row -->
                                <div class="row">
                                    <!-- Registed Members -->
                                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                        <a class="dashboard-stat bg-primary" href="manage-member.php">
                                            <?php 
                                            $results= mysqli_query($dbcon,"SELECT borrowernumber from member");
                                            $totalmember=mysqli_num_rows($results);                                          
                                            ?>
                                            <span class="number counter"><?php echo htmlentities($totalmember);?></span>
                                            <span class="name">Registered Members</span>
                                            <span class="bg-icon"><i class="bi bi-people"></i></span>
                                        </a>
                                        <!-- /.dashboard-stat -->
                                    </div>
                                    <!-- /.col-lg-3 col-md-3 col-sm-6 col-xs-12 -->
                                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                        <!-- Total Books/Magazine -->
                                        <a class="dashboard-stat bg-success" href="catalog_reports.php">
                                            <?php 
                                            $sql =mysqli_query($dbcon,"SELECT * from catalog");
                                            $totalbooks=mysqli_num_rows($sql);
                                            ?>
                                            <span class="number counter"><?php echo htmlentities($totalbooks);?></span>
                                            <span class="name">Total Catalogue</span>
                                            <span class="bg-icon"><i class="bi bi-journals"></i></i></span>
                                        </a>
                                        <!-- /.dashboard-stat -->
                                    </div>

                                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                        <!-- Total Circulations -->
                                        <a class="dashboard-stat bg-warning" href="author.php">
                                            <?php 
                                            $author =mysqli_query($dbcon,"SELECT * from author");
                                            $totalauthor=mysqli_num_rows($author);
                                            ?>
                                            <span class="number counter"><?php echo htmlentities($totalauthor);?></span>
                                            <span class="name">Total Authors</span>
                                            <span class="bg-icon"><i class="bi bi-vector-pen"></i></span>
                                        </a>
                                        <!-- /.dashboard-stat -->
                                    </div>

                                         <!-- New Arrival -->
                                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                        <a class="dashboard-stat bg-info" href="new_arrival.php">
                                            <?php 
                                                $new =mysqli_query($dbcon,"SELECT * FROM `catalog` ORDER BY `catalog`.`bookid` DESC LIMIT 5");
                                                $arrivals=mysqli_num_rows($new);                                          
                                            ?>
                                            <span class="number counter"><?php echo htmlentities($arrivals);?></span>
                                            <span class="name">New Arrivals</span>
                                            <span class="bg-icon"><i class="bi bi-journal-arrow-up"></i></span>
                                        </a>
                                        <!-- /.dashboard-stat -->
                                    </div>
                                </div>
                                <!-- end 1st row -->
                                <br>
                                <div class="row">
                                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                        <!-- Total Circulations -->
                                        <a class="dashboard-stat bg-success" href="issued_books.php">
                                            <?php 
                                            $sql2 =mysqli_query($dbcon,"SELECT * from  issuedbook");
                                            $totalissued=mysqli_num_rows($sql2);
                                            ?>
                                            <span class="number counter"><?php echo htmlentities($totalissued);?></span>
                                            <span class="name">Total Circulation</span>
                                            <span class="bg-icon"><i class="bi bi-arrow-repeat"></i></span>
                                        </a>
                                        <!-- /.dashboard-stat -->
                                    </div>

                                    <!-- Total Issued Books -->
                                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                        <a class="dashboard-stat bg-primary" href="Pending_bookdeails.php">
                                        <?php 
                                            $sql3 =mysqli_query($dbcon,"SELECT * from  issuedbook where RetrunStatus=0;");
                                            $issuedbook=mysqli_num_rows($sql3);
                                            ?>
                                            <span class="number counter"><?php echo htmlentities($issuedbook);?></span>
                                            <span class="name">Total Issued Books</span>
                                            <span class="bg-icon"><i class="bi bi-journal-arrow-down"></i></span>
                                        </a>
                                    </div>

                                    <!-- Overdue book-->
                                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                        <a class="dashboard-stat bg-danger" href="overdue_book.php">
                                        <?php 
                                            
                                            $over =mysqli_query($dbcon,"SELECT * from `issuedbook_view` WHERE `RetrunStatus`=0 && `ReturnDate`<= '$sysdate'");

                                            $overdue=mysqli_num_rows($over);
                                            ?>
                                            <span class="number counter"><?php echo htmlentities($overdue);?></span>
                                            <span class="name">Overdue Books</span>
                                            <span class="bg-icon"><i class="bi bi-alarm"></i></span>
                                        </a>
                                    </div>                          
                                         <!-- Overdue book-->
                                         <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                        <a class="dashboard-stat bg-warning" href="#">
                                        <?php 
                                            
                                            $fine =mysqli_query($dbcon,"SELECT * from `issuedbook_view`");                                            
                                            while ($overdueamt=mysqli_fetch_assoc($fine)) {
                                                $totalfine=$totalfine+$overdueamt['fine'];
                                            }                                            
                                            ?>
                                            <span class="number counter"><?php echo htmlentities($totalfine).'.00';?></span>
                                            <span class="name">Total Fines Received</span>
                                            <span class="bg-icon"><i class="bi bi-currency-rupee"></i></span>
                                        </a>
                                    </div>                         
                                </div>
                                <br>
                                <div class="row justify-content-center align-items-center">                                               
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 bg-light">
                                        <div class="col-6">
                                            <canvas id="myPieChart"></canvas>
                                            <!-- <ul class="chart-legend" id="legend"></ul> -->
                                    </div>
                                    <?php
                                    $data = [
                                        'Available Catalog' => ($totalbooks-$issuedbook),
                                        'Overdue Book' => $overdue,
                                        'Issued Books' => ($issuedbook-$overdue),
                                    ];
                                    ?>
                                        <!-- <div id="myChart" div> -->
                                        <!-- <script>
                                            function getData() {
                                                return [
                                                    { type: 'Available Catalog', count: <?php echo ($totalbooks-$issuedbook)?> },
                                                    { type: 'Overdue Book', count: <?php echo $overdue?> },
                                                    { type: 'Issued Books', count: <?php echo ($issuedbook-$overdue)?> },
                                                ];
                                        }
                                        </script>                                    -->

                                    </div> 

                                    <?php                                    
                                        $new =mysqli_query($dbcon,"SELECT * FROM `category`");
                                        $it=0;$phy=0;$rel=0;$soc=0;$lan=0;$mat=0;$tec=0;$art=0;$lit=0;$his=0;
                                        while ($category=mysqli_fetch_assoc($new)) {

                                            $new1 =mysqli_query($dbcon,"SELECT * FROM `catalog` WHERE `category`=$category[categorycode]");                                                
                                            $catalog=mysqli_fetch_assoc($new1);
                                            if (mysqli_num_rows($new1) > 0) {
                                                if ($catalog['category']==0){
                                                    $it=mysqli_num_rows($new1);
                                                }elseif($catalog['category']==100){
                                                    $phy=mysqli_num_rows($new1);
                                                }elseif($catalog['category']==200){
                                                    $rel=mysqli_num_rows($new1);
                                                }elseif($catalog['category']==300){
                                                    $soc=mysqli_num_rows($new1);
                                                }elseif($catalog['category']==400){
                                                    $lan=mysqli_num_rows($new1);
                                                }elseif($catalog['category']==500){
                                                    $mat=mysqli_num_rows($new1);
                                                }elseif($catalog['category']==600){
                                                    $tec=mysqli_num_rows($new1);
                                                }elseif($catalog['category']==700){
                                                    $art=mysqli_num_rows($new1);
                                                }elseif($catalog['category']==800){
                                                    $lit=mysqli_num_rows($new1);
                                                }elseif($catalog['category']==900){
                                                    $his=mysqli_num_rows($new1);
                                                }
                                            }
                                        }
                                        ?>                                            
                                       
                                    <div class="col-lg-6 col-md-6 col-sm-4 col-xs-12">
                                        <div id="myChart"></div>
                                        <script>
                                            function getData() {
                                                return [
                                                    { type: 'Computer science', count: <?php echo $it?> },
                                                    { type: 'Philosophy and Psychology', count: <?php echo $phy?> },
                                                    { type: 'Religion', count: <?php echo $rel?> },
                                                    { type: 'Social sciences', count: <?php echo $soc?> },
                                                    { type: 'Language', count: <?php echo $lan?> },
                                                    { type: 'Mathematics', count: <?php echo $mat?> },
                                                    { type: 'Technology', count: <?php echo $tec?> },
                                                    { type: 'Arts', count: <?php echo $art?> },
                                                    { type: 'Literature', count: <?php echo $lit?> },
                                                    { type: 'History', count: <?php echo $his?> },
                                                ];
                                        }
                                        </script>  
                                    </div>                                                                           
                                </div>  
                            </div>
                            <!-- /.container-fluid -->
                        </section>
                        <!-- /.section -->
                    </div>
                    <!-- /.main-page -->                    
                </div>
                <!-- /.content-container -->
            </div>
            <!-- /.content-wrapper -->
        </div>
        <!-- /.main-wrapper -->     
    </body>
    
<script >
    // Summary of Catalogue
    const data = getData();
    const numFormatter = new Intl.NumberFormat('en-US');
    const total = data.reduce((sum, d) => sum + d['count'], 0);

    const options = {
        container: document.getElementById('myChart'),
        data,
        title: {
            text: 'Summary of Catalogue',
    },
    series: [
        {
            type: 'pie',
            calloutLabelKey: 'type',
            angleKey: 'count',
            sectorLabelKey: 'count',
            calloutLabel: {
                enabled: false,
            },
            sectorLabel: {
                formatter: ({ datum, sectorLabelKey }) => {
                    const value = datum[sectorLabelKey];
                    return numFormatter.format(value);
                },
            },
            innerRadiusRatio: 0.6,
            innerLabels: [
                {
                    text: numFormatter.format(total),
                    fontSize: 24,
                },
                {
                    text: 'Total',
                    fontSize: 16,
                    margin: 10,
                },
            ],
            tooltip: {
                renderer: ({ datum, calloutLabelKey, title, sectorLabelKey }) => {
                    return {
                        title,
                        content: `${datum[calloutLabelKey]}: ${numFormatter.format(datum[sectorLabelKey])}`,
                    };
                },
            },
            strokeWidth: 3,
        },
    ],
};
    const chart = agCharts.AgCharts.create(options);
</script>

<!-- Summary of Circulations -->
<script>
    var ctx = document.getElementById('myPieChart').getContext('2d');
    var myPieChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            datasets: [{
                data: <?php echo json_encode(array_values($data)); ?>,
                backgroundColor: [
                    'rgba(1, 92, 0, 0.7)',
                    'rgba(255, 0, 0, 0.7)',
                    'rgba(0, 0, 245, 0.7)',
                ],
            }],
            labels: <?php echo json_encode(array_keys($data)); ?>,
        },
        options: {
            plugins: {
                legend: {
                    display: true, // Hide the default legend
                    position: 'bottom', // Adjust the position as needed (e.g., 'top', 'left', 'bottom')
                },
                title: {
                    display: true,
                    text: 'Summary of Circulations',
                    fontSize: 24,
                },                
            },
        },      
    });

    // Custom legend
    var legend = document.getElementById('legend');
    var labels = myPieChart.data.labels;
    var colors = myPieChart.data.datasets[0].backgroundColor;

    labels.forEach(function(label, index) {
        var listItem = document.createElement('li');
        listItem.innerHTML = '<span style="background-color:' + colors[index] + '"></span>' + label;
        legend.appendChild(listItem);
    });
</script>
</html>
<?php } ?>
