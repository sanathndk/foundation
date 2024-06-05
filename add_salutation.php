<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['alogin'])==0)
{   
	header('location:index.php');
}
else{ 
	if(isset($_POST['btnsave']))	{
		// Save Record
		$itemcode=$_POST['itemcode'];
		$description=$_POST['description'];

		$sql="INSERT INTO `salutation`(`code`, `desc`) VALUES (?,?)";
		$result=mysqli_prepare($dbcon, $sql);		

		if ($result){
			mysqli_stmt_bind_param($result,'ss', $itemcode, $description);
			if (mysqli_stmt_execute($result)) {
				echo "Record updated successfully";
				header('location:add_salutation.php');
			} else{
				echo "Error inserting data: " .mysqli_error($dbcon);
			}
		} else{
			echo "Error Connection: " .mysqli_error($dbcon);
		}
	// Delete Record			
	}elseif($_GET['id']<>""){
		$id=$_GET['id'];
		$delete=mysqli_prepare($dbcon,"DELETE FROM salutation WHERE id=?");
		mysqli_stmt_bind_param($delete,'s',$id);
		if (mysqli_stmt_execute($delete)) {
			echo "<script>Alert('Record deleted successfully.')</script>";
		}else{
			echo "<script>Alert('Something went wrong. please try again later')</script>";
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
	<title>Add Salutation | Foundation Library Management System</title>
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
								<h2 class="title">Add Salutation</h2>
							</div>                                
						</div>
						<!-- /.row -->
						<div class="row breadcrumb-div">
							<div class="col-md-6">
								<ul class="breadcrumb">
									<li><a href="dashboard.php"><i class="fa fa-home"></i> Home /&nbsp;</a></li>
									<li><a href="#">Administration /&nbsp; </a></li>
									<li class="active">Add Salutation</li>
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
										<input type="text" class="form-control" id="inputitemcode" name="itemcode" required>
									</div>
								</div>
								<div class="row p-2">
									<div class="col-sm-2 text-end">
										<label for="inputdescription" class="form-label">Description:</label>
									</div>
									<div class="col-sm-4">
										<input type="text" class="form-control" id="inputdescription" name="description" required>
									</div>
								</div>	                               
													
								<div class="row p-2">
									<div class="col-sm-2 text-end">
									</div>
									<div class="col-sm-4">
										<button type="submit" class="btn btn-primary btn-md" name="btnsave"><i class="bi bi-star-fill"></i>&nbsp;Save</button>
									</div>
								</div>
							</fieldset>
						</form>						
						<div class="row justify-content-md-center"> 
							<div class="col-sm-8">
								<h3>List of Salutation</h3>
							</div>
						</div>
						<div class="row justify-content-md-center">							
							<div class="col-sm-8">			
								<table class="table table-striped table-bordered table-hover align-middle table-responsive" id="dataTables">										
									<thead>
										<tr class="text-center">
											<th class="text-center">Ser</th>
											<th class="text-center">Code</th>
											<th class="text-center">Description</th>
											<th class="text-center">Action</th>
										</tr>
									</thead>
									
									<tbody class="table-group-divider">
									<?php																

									// Retrieve data for the current page	
									$sql="SELECT * FROM `salutation`";
									$result= mysqli_query($dbcon, $sql);									
									
									if (mysqli_num_rows($result) > 0) {												
									$ser=1;					
									// output data of each row
									while($row = mysqli_fetch_assoc($result)) {
									?>
										<tr>
											<td class="text-center"><?php echo $ser++?></td>
											<td><?php echo $row["code"]?></td>
											<td><?php echo $row["desc"]?></td>
											<td class="text-center">
												<a href="edit_salutation.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm" name="btnedit"><i class="bi bi-pencil-square"></i>&nbsp;Edit</a>
												<a href="add_salutation.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are your sure Delete this record?');" class="btn btn-danger btn-sm" name="btndelete"><i class="bi bi-trash3"></i>&nbsp;Delete</a>
											</td>
										</tr>
										<?php
											}
											} 
										?>						  											
									</tbody>
								</table>	
							</div>														
						</div>						
					</div>					
				</div> 			
			</div>
		</div>	
	</div>	   
	<?php include('includes/footer.php');?>   

	<script src="js/jquery-3.7.0.js"></script>
	<script src="js/jquery.dataTables.min.js"></script>

	<script>
		new DataTable('#dataTables');  
	</script>
</body>
</html>
<?php 
mysqli_close($dbcon);
} ?>

