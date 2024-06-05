<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['alogin'])==0)
{   
	header('location:index.php');
}
else{ 
	if(isset($_GET['id'])){
        $id=$_GET['id'];
        $query= mysqli_query($dbcon,"SELECT * FROM `salutation` WHERE id=$id");
        $row=mysqli_fetch_assoc($query);
    }

	if(isset($_POST['btnsave'])){
		// Save Record
		$itemcode=$_POST['itemcode'];
		$description=$_POST['description'];

		$sql="UPDATE `salutation` SET `desc`=? WHERE `id`=?";
		$result=mysqli_prepare($dbcon, $sql);		

		if ($result){
			mysqli_stmt_bind_param($result,'ss', $description, $id);
			if (mysqli_stmt_execute($result)) {
				echo "Record updated successfully";
				header('location:add_salutation.php');
			} else{
				echo "Error inserting data: " .mysqli_error($dbcon);
			}
		} else{
			echo "Error Connection: " .mysqli_error($dbcon);
		}	
    }
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">    
	<link rel="stylesheet" href="css/jquery.dataTables.min.css">   
	<link rel="icon" href="img/logo.png" type="image/png">

	<title>Edit Salutation | Foundation Library Management System</title>
</head>
<body class="top-navbar-fixed">
	<div class="main-wrapper">
		<!-- ========== TOP NAVBAR ========== -->
		<?php include('includes/topbar.php');?>   
		<!-----End Top bar-->
		<div class="content-wrapper">
			<div class="content-container">
			<!-- ========== LEFT SIDEBAR ========== -->
				<?php include('includes/leftbar.php');?>                   
				<!-- /.left-sidebar -->
				<div class="main-page">

					<div class="container-fluid">
						<div class="row page-title-div">
							<div class="col-md-6">
								<h2 class="title">Edit Salutation</h2>
							</div>                                
						</div>
						<!-- /.row -->
						<div class="row breadcrumb-div">
							<div class="col-md-6">
								<ul class="breadcrumb">
									<li><a href="dashboard.php"><i class="fa fa-home"></i> Home /&nbsp;</a></li>
									<li><a href="#">Administration /&nbsp; </a></li>
									<li class="active">Edit Salutation</li>
								</ul>
							</div>                               
						</div>
						<!-- /.row -->
					</div>
					<!-- /.container-fluid -->

					<div class="container">                 
						<form name="signup" method="post" class="form-controlr"> 
							<br>
							<fieldset class="border">   
							<div class="row p-2">
									<div class="col-sm-2 text-end">
										<label for="inputitemcode" class="form-label">Salutation:</label>
									</div>
									<div class="col-sm-4">
										<input type="text" class="form-control" id="inputitemcode" name="itemcode" value="<?php echo $row['code']?>" disabled required>
									</div>
								</div>
								<div class="row p-2">
									<div class="col-sm-2 text-end">
										<label for="inputdescription" class="form-label">Description:</label>
									</div>
									<div class="col-sm-4">
										<input type="text" class="form-control" id="inputdescription" name="description" value="<?php echo $row['desc']?>" required>
									</div>
								</div>	                               
													
								<div class="row p-2">
									<div class="col-sm-2 text-end">
									</div>
									<div class="col-sm-4">
										<button type="submit" class="btn btn-info btn-md" name="btnsave">Update</button>
									</div>
								</div>
							</fieldset>
						</form>						
									
					</div>					
				</div> 			
			</div>
		</div>	
	</div>	   
	<?php include('includes/footer.php');?>   

</body>
</html>
<?php 
mysqli_close($dbcon);
} ?>

