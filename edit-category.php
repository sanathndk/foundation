<?php
session_start();
error_reporting(0);
include('includes/config.php');	
include('includes/activity.php');

logAction($dbcon, "Edit_category");	

if(strlen($_SESSION['alogin'])==0) {   
	header('location:index.php');
} else { 
	$code = $_GET['id'];
	$result = mysqli_query($dbcon, "SELECT * FROM `category` WHERE categoryid=$code");
	$row = mysqli_fetch_array($result);

	if(isset($_POST['btnSave'])) {	
		$name = $_POST['name'];

		// Use prepared statement for safety
		$updateStmt = $dbcon->prepare("UPDATE category SET description=? WHERE categoryid=?");
		$updateStmt->bind_param("si", $name, $code);

		if ($updateStmt->execute()) {
			echo "<script>alert('Category updated successfully!'); window.location='add-category.php';</script>";
			exit();
		} else {
			echo "<script>alert('Error updating data: " . mysqli_error($dbcon) . "');</script>";
		}
	}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1"> 
	<link rel="icon" href="img/logo.png" type="image/png">
	<title>Library Management System | Update Category</title>
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
							<div class="col-md-6">
								<h2 class="title">Update Category</h2>
							</div>                                
						</div>
						<div class="row breadcrumb-div">
							<div class="col-md-6">
								<ul class="breadcrumb">
									<li><a href="dashboard.php"><i class="fa fa-home"></i> Home /&nbsp;</a></li>
									<li><a href="#">Catalogs /&nbsp; </a></li>
									<li class="active">Update Category</li>
								</ul>
							</div>                               
						</div>
					</div>

					<div class="container">                 
						<form name="signup" method="post"> 
							<br>
							<fieldset class="border">                                
								<div class="row p-2">
									<div class="col-sm-2 text-end">
										<label for="inputCode" class="form-label">Category Code:</label>
									</div>
									<div class="col-sm-4">
										<input type="number" class="form-control" id="inputCode" name="code" 
											value="<?php echo $row['categorycode']; ?>" disabled>
									</div>
								</div>
								<div class="row p-2">
									<div class="col-sm-2 text-end">
										<label for="inputName" class="form-label">Category Name:</label>
									</div>
									<div class="col-sm-4">
										<input type="text" class="form-control" id="inputName" name="name" 
											value="<?php echo $row['description']; ?>" required>
									</div>
								</div>		
								<div class="row p-2">
									<div class="col-sm-2 text-end"></div>
									<div class="col-sm-4">
										<button type="submit" class="btn btn-info btn-md" name="btnSave">Update</button>
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
} 
?>
