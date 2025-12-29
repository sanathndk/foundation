<?php
session_start();
error_reporting(0);
include('includes/config.php');
include('includes/activity.php');

logAction($dbcon, "Add_category");

if(strlen($_SESSION['alogin']) == 0){   
	header('location:index.php');
	exit;
} else { 

	// ✅ SAVE NEW CATEGORY
	if(isset($_POST['btnsave'])) {
		$itemcode = trim($_POST['itemcode']);
		$description = trim($_POST['description']);

		// ✅ Check for duplicate category code or name
		$checkSql = "SELECT * FROM category WHERE categorycode = ? OR description = ?";
		$checkStmt = mysqli_prepare($dbcon, $checkSql);
		mysqli_stmt_bind_param($checkStmt, "ss", $itemcode, $description);
		mysqli_stmt_execute($checkStmt);
		$checkResult = mysqli_stmt_get_result($checkStmt);

		if(mysqli_num_rows($checkResult) > 0) {
			echo "<script>alert('Category Code or Name already exists!');</script>";
		} else {
			// ✅ Insert new record
			$sql = "INSERT INTO category (categorycode, description) VALUES(?, ?)";
			$result = mysqli_prepare($dbcon, $sql);

			if ($result) {
				mysqli_stmt_bind_param($result, 'ss', $itemcode, $description);
				if (mysqli_stmt_execute($result)) {
					echo "<script>alert('Category added successfully!'); window.location='add-category.php';</script>";
					exit;
				} else {
					echo "<script>alert('Error inserting data: " . mysqli_error($dbcon) . "');</script>";
				}
			} else {
				echo "<script>alert('Error preparing statement: " . mysqli_error($dbcon) . "');</script>";
			}
		}
	}

	// ✅ DELETE CATEGORY
	if(isset($_GET['id']) && !empty($_GET['id'])) {
		$id = $_GET['id'];
		$delete = mysqli_query($dbcon, "DELETE FROM category WHERE categoryid = '$id'");
		if($delete) {
			echo "<script>alert('Category deleted successfully!'); window.location='add-category.php';</script>";
			exit;
		} else {
			echo "<script>alert('Error deleting record: " . mysqli_error($dbcon) . "');</script>";
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
	<title>Add DDC Category | Library Management System</title>
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
								<h2 class="title">Add DDC Category</h2>
							</div>
						</div>
						<div class="row breadcrumb-div">
							<div class="col-md-6">
								<ul class="breadcrumb">
									<li><a href="dashboard.php"><i class="fa fa-home"></i> Home /&nbsp;</a></li>
									<li><a href="#">Catalogs /&nbsp;</a></li>
									<li class="active">Add DDC Category</li>
								</ul>
							</div>
						</div>
					</div>

					<div class="container">                 
						<form method="post" class="form-controlr"> 
							<br>
							<fieldset class="border">   
								<div class="row p-2">
									<div class="col-sm-2 text-end">
										<label for="inputcode" class="form-label">Category Code:</label>
									</div>
									<div class="col-sm-4">
										<input type="text" class="form-control" id="inputcode" name="itemcode" min="0" maxlength="3" required>
										<small class="text-muted">Add Only 3 Digits</small>
									</div>
								</div>
								<div class="row p-2">
									<div class="col-sm-2 text-end">
										<label for="inputdescription" class="form-label">Category Name:</label>
									</div>
									<div class="col-sm-4">
										<input type="text" class="form-control" id="inputdescription" name="description" maxlength="100" required>
										<small class="text-muted">Add Only 100 Characters</small>
									</div>
								</div>	      
								                         
								<div class="row p-2">
									<div class="col-sm-2 text-end"></div>
									<div class="col-sm-4">
										<button type="submit" class="btn btn-primary btn-md" name="btnsave"><i class="bi bi-tags-fill"></i> &nbsp;Save</button>
									</div>
								</div>
							</fieldset>
						</form>

						<div class="row justify-content-md-center"> 
							<div class="col-sm-8">
								<h3>List of DDC Category</h3>
							</div>
						</div>
						<div class="row justify-content-md-center">							
							<div class="col-sm-8">			
								<table class="table table-striped table-bordered table-hover align-middle table-responsive" id="dataTables">										
									<thead>
										<tr class="text-center">
											<th class="text-center">Ser</th>
											<th class="text-center">DDC Code</th>
											<th class="text-center">Description</th>
											<th class="text-center">Action</th>
										</tr>
									</thead>
									<tbody class="table-group-divider">
									<?php																
									$sql="SELECT * FROM category ORDER BY categoryid DESC";
									$result= mysqli_query($dbcon, $sql);									
									if (mysqli_num_rows($result) > 0) {												
										$ser=1;					
										while($row = mysqli_fetch_assoc($result)) {
									?>
										<tr>
											<td class="text-center"><?php echo $ser++?></td>
											<td><?php echo htmlspecialchars($row["categorycode"]); ?></td>
											<td><?php echo htmlspecialchars($row["description"]); ?></td>
											<td class="text-center">
												<a href="edit-category.php?id=<?php echo $row['categoryid']; ?>" class="btn btn-warning btn-sm"><i class="bi bi-pencil-square"></i></a>
												<a href="add-category.php?id=<?php echo $row['categoryid']; ?>" onclick="return confirm('Are you sure you want to delete this record?');" class="btn btn-danger btn-sm"><i class="bi bi-trash3"></i></a>
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
	</script>
</body>
</html>
<?php 
mysqli_close($dbcon);
} 
?>


