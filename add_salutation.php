<?php
session_start();
error_reporting(0);
include('includes/config.php');
include('includes/activity.php');

logAction($dbcon, "Add_salutation");

if (strlen($_SESSION['alogin']) == 0) {   
    header('location:index.php');
    exit;
}

// ✅ ADD NEW RECORD
if (isset($_POST['btnsave'])) {

    $service = trim($_POST['service']);
    $itemcode = trim($_POST['itemcode']);
    $description = trim($_POST['description']);

    // Check duplicate entry
    $check_sql = "SELECT * FROM salutation WHERE service=? AND (code=? OR `desc`=?)";
    $check_stmt = mysqli_prepare($dbcon, $check_sql);
    mysqli_stmt_bind_param($check_stmt, 'sss', $service, $itemcode, $description);
    mysqli_stmt_execute($check_stmt);
    $check_result = mysqli_stmt_get_result($check_stmt);

    if (mysqli_num_rows($check_result) > 0) {
        echo "<script>alert('⚠️ This salutation already exists for the selected service!');</script>";
    } else {
        // Insert new record
        $sql = "INSERT INTO `salutation`(`service`, `code`, `desc`) VALUES (?, ?, ?)";
        $result = mysqli_prepare($dbcon, $sql);

        if ($result) {
            mysqli_stmt_bind_param($result, 'sss', $service, $itemcode, $description);

            if (mysqli_stmt_execute($result)) {
                echo "<script>alert('✅ Record added successfully!');window.location='add_salutation.php';</script>";
                exit;
            } else {
                echo "<script>alert('❌ Error inserting data: " . mysqli_error($dbcon) . "');</script>";
            }
        } else {
            echo "<script>alert('❌ Database error: " . mysqli_error($dbcon) . "');</script>";
        }
    }
}

// ✅ DELETE RECORD
if (!empty($_GET['id'])) {
    $id = intval($_GET['id']);
    $delete = mysqli_prepare($dbcon, "DELETE FROM salutation WHERE id=?");
    mysqli_stmt_bind_param($delete, 'i', $id);
    if (mysqli_stmt_execute($delete)) {
        echo "<script>alert('🗑️ Record deleted successfully!');window.location='add_salutation.php';</script>";
        exit;
    } else {
        echo "<script>alert('❌ Something went wrong. Please try again later.');</script>";
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
	<title>Add Salutation | Foundation Library Management System</title>
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
								<h2 class="title">Add Salutation</h2>
							</div>                                
						</div>

						<div class="row breadcrumb-div">
							<div class="col-md-6">
								<ul class="breadcrumb">
									<li><a href="dashboard.php"><i class="fa fa-home"></i> Home /&nbsp;</a></li>
									<li><a href="#">Administration /&nbsp; </a></li>
									<li class="active">Add Salutation</li>
								</ul>
							</div>                               
						</div>
					</div>

					<div class="container">                 
						<form name="salutationForm" method="post" class="form-controlr" onsubmit="return validateDuplicate();"> 
							<br>
							<fieldset class="border">   
								<div class="row p-2">
									<div class="col-sm-2 text-end">
										<label for="service" class="form-label">Service:</label>
									</div>
									<div class="col-sm-4">        
										<select id="service" class="form-select" name="service" required>
											<option value="Ar">Army</option>
											<option value="N">Navy</option>
											<option value="A">Air Force</option>
											<option value="P">Police</option>
										</select>
									</div>   
								</div>
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
									<div class="col-sm-2 text-end"></div>
									<div class="col-sm-4">
										<button type="submit" class="btn btn-primary btn-md" name="btnsave">
											<i class="bi bi-star-fill"></i>&nbsp;Save
										</button>
									</div>
								</div>
							</fieldset>
						</form>		

						<hr>

						<div class="row justify-content-md-center"> 
							<div class="col-sm-8">
								<h3>List of Salutations</h3>
								<table class="table table-striped table-bordered table-hover align-middle table-responsive" id="dataTables">										
									<thead>
										<tr class="text-center">
											<th>Ser</th>
											<th>Service</th>
											<th>Salutation</th>
											<th>Description</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody class="table-group-divider">
									<?php																
									$sql = "SELECT * FROM `salutation`";
									$result = mysqli_query($dbcon, $sql);									
									if (mysqli_num_rows($result) > 0) {												
										$ser = 1;					
										while($row = mysqli_fetch_assoc($result)) {
									?>
										<tr>
											<td class="text-center"><?php echo $ser++; ?></td>
											<td><?php echo htmlspecialchars($row["service"]); ?></td>
											<td><?php echo htmlspecialchars($row["code"]); ?></td>
											<td><?php echo htmlspecialchars($row["desc"]); ?></td>
											<td class="text-center">
												<a href="edit_salutation.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">
													<i class="bi bi-pencil-square"></i>
												</a>
												<a href="add_salutation.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this record?');" class="btn btn-danger btn-sm">
													<i class="bi bi-trash3"></i>
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

		// ✅ Client-side duplicate check
		function validateDuplicate(){
			let service = document.getElementById('service').value.trim().toLowerCase();
			let code = document.getElementById('inputitemcode').value.trim().toLowerCase();
			let desc = document.getElementById('inputdescription').value.trim().toLowerCase();
			let rows = document.querySelectorAll('#dataTables tbody tr');

			for (let row of rows){
				let existingService = row.cells[1].innerText.trim().toLowerCase();
				let existingCode = row.cells[2].innerText.trim().toLowerCase();
				let existingDesc = row.cells[3].innerText.trim().toLowerCase();

				if (service === existingService && (code === existingCode || desc === existingDesc)){
					alert(' This salutation already exists for the selected service!');
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
