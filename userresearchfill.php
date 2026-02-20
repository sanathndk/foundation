<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['alogin'])==0)
{   
header("Location: index.php"); 
}
else{
    ?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Library System Dashboard</title>
        <link rel="icon" href="img/logo.png" type="image/png">

    </head>
    <body class="top-navbar-fixed">
        <div class="main-wrapper">
            <?php include('includes/usertopbar.php');?>
            <div class="content-wrapper">
                <div class="content-container">
                    <?php include('includes/userleftbar.php');?>  
                    <div class="main-page">
                        <div class="container-fluid">
                            <div class="row page-title-div">
                                <div class="col-sm-8">
                                    <h2 class="title">Research Fille</h2>                                  
                                </div>
                                <!-- /.col-sm-8 -->
                             </div>
                            <!-- /.row -->
						<div class="row breadcrumb-div">
							<div class="col-md-6">
								<ul class="breadcrumb">
									<li><a href="userdashboard.php"><i class="fa fa-home"></i> Home /&nbsp;</a></li>
									<li><a href="#">Circulations /&nbsp; </a></li>
									<li class="active">Reserach</li>
								</ul>
							</div>                               
						</div>
						<!-- /.row -->                     
                        </div>
                        <!-- /.container-fluid -->
                        <div class="content-wrapper">
                    <div class="container">  
                        <div class="row justify-content-md-center">
                            <div class="col-md-12">

                                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-6 g-4">

                                    <!-- Card 1 -->
                                    <div class="col">
                                        <div class="card h-100 shadow-sm border-0">
                                            <a href="#" target="_blank">
                                                <img src="img/heensaraya.jpg" class="card-img-top p-1" alt="HEENSARAYA" style="height: 200px; object-fit: contain;">
                                            </a>
                                            <div class="card-body text-center">
                                                <h6 class="card-title">HEENSARAYA</h6>
										            <a href="#" target="_blank"button type="submit" class="btn btn btn-primary" name="btnrenew">View&nbsp;&nbsp;<i class="bi bi-file-pdf"></abutton></i></a>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Card 2 -->
                                    <div class="col">
                                        <div class="card h-100 shadow-sm border-0">
                                            <a href="#" target="_blank">
                                                <img src="img/magulkama.jpg" class="card-img-top p-1" alt="MAGUL KAMA" style="height: 200px; object-fit: contain;">
                                            </a>
                                            <div class="card-body text-center">
                                                <h6 class="card-title">MAGUL KAMA</h6>
                                                    <a href="#" target="_blank"button type="submit" class="btn btn btn-primary" name="btnrenew">View&nbsp;&nbsp;<i class="bi bi-file-pdf"></abutton></i></a>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Card 3 -->
                                    <div class="col">
                                        <div class="card h-100 shadow-sm border-0">
                                            <a href="#" target="_blank">
                                                <img src="img/kaluwara.jpg" class="card-img-top p-1" alt="KALUWARA GADARA" style="height: 200px; object-fit: contain;">
                                            </a>
                                            <div class="card-body text-center">
                                                <h6 class="card-title">KALUWARA GADARA</h6>
                                                    <a href="#" target="_blank"button type="submit" class="btn btn btn-primary" name="btnrenew">View&nbsp;&nbsp;<i class="bi bi-file-pdf"></abutton></i></a>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Card 4 -->
                                    <div class="col">
                                        <div class="card h-100 shadow-sm border-0">
                                            <a href="#" target="_blank">
                                                <img src="img/harrypotter.jpg" class="card-img-top p-1" alt="HARRY POTTER" style="height: 200px; object-fit: contain;">
                                            </a>
                                            <div class="card-body text-center">
                                                <h6 class="card-title">HARRY POTTER</h6>
                                                    <a href="#" target="_blank"button type="submit" class="btn btn btn-primary" name="btnrenew">View&nbsp;&nbsp;<i class="bi bi-file-pdf"></abutton></i></a>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Card 5 -->
                                    <div class="col">
                                        <div class="card h-100 shadow-sm border-0">
                                            <a href="#" target="_blank">
                                                <img src="img/map.jpg" class="card-img-top p-1" alt="MAP" style="height: 200px; object-fit: contain;">
                                            </a>
                                            <div class="card-body text-center">
                                                <h6 class="card-title">MAP</h6>
                                                    <a href="#" button type="submit" class="btn btn btn-primary" name="btnrenew">View&nbsp;&nbsp;<i class="bi bi-file-pdf"></abutton></i></a>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Card 6 -->
                                    <div class="col">
                                        <div class="card h-100 shadow-sm border-0">
                                            <a href="#" target="_blank">
                                                <img src="img/659420030733f.jpg" class="card-img-top p-1" alt="POWER OF MIND" style="height: 200px; object-fit: contain;">
                                            </a>
                                            <div class="card-body text-center">
                                                <h6 class="card-title">POWER OF MIND</h6>
                                                    <a href="#" target="_blank"button type="submit" class="btn btn btn-primary" name="btnrenew">View&nbsp;&nbsp;<i class="bi bi-file-pdf"></abutton></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                

                                
                </div>
                    </div>
                    <!-- /.main-page -->                    
                </div>
                <!-- /.content-container -->
            </div>
            <!-- /.content-wrapper -->
        </div>
        <?php include('includes/footer.php');?>   
	<!-- <script src="js/search.js"></script> -->

	<script src="js/jquery-3.7.0.js"></script>
	<script src="js/jquery.dataTables.min.js"></script>

	<script>
		new DataTable('#dataTables');  
		</script>    
    </body>
</html>
<?php } ?>
