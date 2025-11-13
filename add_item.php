<?php
session_start();
error_reporting(0);
include('includes/config.php');
include('includes/activity.php');

logAction($dbcon, "Add_item");

if(strlen($_SESSION['alogin']) == 0){   
	header('location:index.php');
	exit;
}

if(isset($_POST['btnsave'])){
	$itemcode = trim($_POST['itemcode']);
	$description = trim($_POST['description']);

	//  Check for duplicates before inserting
	$check_sql = "SELECT * FROM itemtypes WHERE itemcode = ? OR description = ?";
	$check_stmt = mysqli_prepare($dbcon, $check_sql);
	mysqli_stmt_bind_param($check_stmt, 'ss', $itemcode, $description);
	mysqli_stmt_execute($check_stmt);
	$check_result = mysqli_stmt_get_result($check_stmt);

	if(mysqli_num_rows($check_result) > 0){
		echo "<script>alert(' Item code or name already exists! Please use another.');</script>";
	} else {
		//  Insert new item only if not duplicate
		$sql = "INSERT INTO `itemtypes`(`itemcode`, `description`) VALUES (?, ?)";
		$result = mysqli_prepare($dbcon, $sql);		

		if ($result){
			mysqli_stmt_bind_param($result, 'ss', $itemcode, $description);

			if (mysqli_stmt_execute($result)) {
				echo "<script>alert(' Item type added successfully!');window.location='add_item.php';</script>";
				exit();
			} else {
				echo "<script>alert(' Error inserting data: " . mysqli_error($dbcon) . "');</script>";
			}
		} else {
			echo "<script>alert(' Database connection error: " . mysqli_error($dbcon) . "');</script>";
		}
	}
}

//  Delete Record
if(!empty($_GET['id'])){
	$id = intval($_GET['id']);
	mysqli_query($dbcon, "DELETE FROM itemtypes WHERE itemid='$id'");
	header('location:add_item.php');
	exit;
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
	<title>Library Management System | Item Types</title>
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
								<h2 class="title">Item Types</h2>
							</div>                                
						</div>
						<div class="row breadcrumb-div">
							<div class="col-md-6">
								<ul class="breadcrumb">
									<li><a href="dashboard.php"><i class="fa fa-home"></i> Home /&nbsp;</a></li>
									<li><a href="#">Administration /&nbsp; </a></li>
									<li class="active">Item Types</li>
								</ul>
							</div>                               
						</div>
					</div>

					<div class="container">                 
						<form name="signup" method="post" onsubmit="return validateDuplicate();"> 
							<br>
							<fieldset class="border">   
								<div class="row p-2">
									<div class="col-sm-2 text-end">
										<label for="inputitemcode" class="form-label">Item Code:</label>
									</div>
									<div class="col-sm-4">
										<input type="text" class="form-control" id="inputitemcode" name="itemcode" required>
									</div>
								</div>
								<div class="row p-2">
									<div class="col-sm-2 text-end">
										<label for="inputdescription" class="form-label">Item Name:</label>
									</div>
									<div class="col-sm-4">
										<input type="text" class="form-control" id="inputdescription" name="description" required>
									</div>
								</div>	                               
								<div class="row p-2">
									<div class="col-sm-2 text-end"></div>
									<div class="col-sm-4">
										<button type="submit" class="btn btn-primary btn-md" name="btnsave">
											<i class="bi bi-diagram-3"></i> &nbsp;Save
										</button>
									</div>
								</div>
							</fieldset>
						</form>	

						<!-- Item list -->
						<div class="row justify-content-md-center mt-4"> 
							<div class="col-sm-8">
								<h3>List of Items</h3>
								<table class="table table-striped table-bordered table-hover align-middle table-responsive" id="dataTables">										
									<thead>
										<tr class="text-center">
											<th>Ser</th>
											<th>Code</th>
											<th>Description</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody class="table-group-divider">
									<?php																
									$sql="SELECT * FROM `itemtypes`";
									$result= mysqli_query($dbcon, $sql);									
									if (mysqli_num_rows($result) > 0) {												
										$ser=1;					
										while($row = mysqli_fetch_assoc($result)) {
									?>
										<tr>
											<td class="text-center"><?php echo $ser++?></td>
											<td><?php echo $row["itemcode"]?></td>
											<td><?php echo $row["description"]?></td>
											<td class="text-center">
												<a href="edit_item.php?id=<?php echo $row['itemid']; ?>" class="btn btn-warning btn-sm">
													<i class="bi bi-pencil-square"></i>
												</a>
											</td>
										</tr>
									<?php } } ?>						  											
									</tbody>
								</table>	
							</div>														
						</div>						
					</div>
					<?php include('includes/footer.php');?> 
				</div> 			
			</div>
		</div>	
	</div>	   

	<script src="js/jquery-3.7.0.js"></script>
	<script src="js/jquery.dataTables.min.js"></script>
	<script>
		new DataTable('#dataTables');

		function validateDuplicate(){
			let code = document.getElementById('inputitemcode').value.trim().toLowerCase();
			let desc = document.getElementById('inputdescription').value.trim().toLowerCase();
			let rows = document.querySelectorAll('#dataTables tbody tr');
			for (let row of rows){
				let existingCode = row.cells[1].innerText.trim().toLowerCase();
				let existingDesc = row.cells[2].innerText.trim().toLowerCase();
				if (code === existingCode || desc === existingDesc){
					alert(' Item code or name already exists!');
					return false;
				}
			}
			return true;
		}
	</script>
</body>
</html>
<?php 
mysqli_close($dbcon);
?>
