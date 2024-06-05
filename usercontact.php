<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['alogin'])==0)
{   
	header('location:index.php');
}
else{ 
	$result=mysqli_query($dbcon, "SELECT * FROM `branches`");		
    $row=mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">    
	<link rel="stylesheet" href="css/jquery.dataTables.min.css">   
	<title>Contac Us | Foundation Library Management System</title>
</head>
<body class="top-navbar-fixed">
	<div class="main-wrapper">
		<!-- ========== TOP NAVBAR ========== -->
		<?php include('includes/usertopbar.php');?>   
		<!-----End Top bar-->
		<div class="content-wrapper">
			<div class="content-container">
			<!-- ========== LEFT SIDEBAR ========== -->
				<?php include('includes/userleftbar.php');?>                   
				<!-- /.left-sidebar -->
				<div class="main-page">

					<div class="container-fluid">
						<div class="row page-title-div">
							<div class="col-md-6">
								<h2 class="title">Contac US</h2>
							</div>                                
						</div>
						<!-- /.row -->
						<div class="row breadcrumb-div">
							<div class="col-md-6">
								<ul class="breadcrumb">
									<li><a href="userdashboard.php"><i class="fa fa-home"></i> Home /&nbsp;</a></li>
									<li class="active">Contac US</li>
								</ul>
							</div>                               
						</div>
						<!-- /.row -->
					</div>
					<!-- /.container-fluid -->

					<div class="container">                 
						<form name="signup" method="get" class="form-controlr"> 
							<br>
							<fieldset class="border">   
							<div class="row">
                                <div class="col-sm-6 ">
                                    <?php echo $row['name'];?>
                                </div>									
							</div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <?php echo $row['address1'];?>
                                </div>									
							</div> 
                            <div class="row">
                                <div class="col-sm-6">
                                    <?php echo $row['city'];?>
                                </div>									
							</div> 
                            <div class="row">
                                <div class="col-sm-6">
                                    <?php echo $row['country'];?>
                                </div>									
							</div> 
                            <div class="row">
                                <div class="col-sm-6">
                                    <a href="mailto:"><?php echo $row['email'];?></a>                                 
                                </div>									
							</div> 
                            <br>
                            <div class="row justify-content-center">
                                <div class="col-sm-10">
                                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3685.1250670373033!2d75.7492669108783!3d22.536987279431603!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3962f7071c2789c7%3A0x7b5b982c709d2098!2sMCTE!5e0!3m2!1sen!2sin!4v1703734282790!5m2!1sen!2sin" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>                                   
                                </div>									
							</div> 
							</fieldset>
						</form>														
					</div>				
	                    <?php include('includes/footer.php');?> 

				</div> 			

			</div>

		</div>	
	</div>	   
	
</body>
</html>
<?php 
mysqli_close($dbcon);
} ?>

