<?php
session_start();
error_reporting(0);
include('includes/config.php');
include('includes/activity.php');

logAction($dbcon, "Add_holiday");

if (strlen($_SESSION['alogin']) == 0) {   
    header('location:index.php');
    exit();
} else { 
    // Add Record
    if (isset($_POST['btnsave'])) {
        $holiday_date = $_POST['holiday_date'];
        $description  = $_POST['description'];

		// 2. Check duplicate holiday date
		$check = mysqli_prepare($dbcon,
			"SELECT id FROM holidays WHERE holiday_date = ?"
		);
		mysqli_stmt_bind_param($check, "s", $holiday_date);
		mysqli_stmt_execute($check);
		mysqli_stmt_store_result($check);

		if (mysqli_stmt_num_rows($check) > 0) {
			echo "<script>alert('This holiday date already exists!');window.history.back();</script>";exit();
		}

        // Validation: block past dates
        $today = date('Y-m-d');
        if ($holiday_date < $today) {
            echo "<script>alert('You cannot add holidays for past dates!');</script>";
        } else {
            $sql = "INSERT INTO holidays (holiday_date, description) VALUES (?, ?)";
            $stmt = mysqli_prepare($dbcon, $sql);

            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "ss", $holiday_date, $description);
                if (mysqli_stmt_execute($stmt)) {
                    echo "<script>alert('Holiday added successfully!'); window.location='add_holiday.php';</script>";
                } else {
                    echo "<script>alert('Error adding holiday');</script>";
                }
            } else {
                echo "Error preparing query: " . mysqli_error($dbcon);
            }
        }
    }

		// Delete Record
		if (isset($_GET['id']) && $_GET['id'] != "") {
		$id = intval($_GET['id']); // convert to integer for safety
		$delete = mysqli_query($dbcon, "DELETE FROM holidays WHERE id='$id'");
		
		if ($delete) {
			echo "<script>alert('Holiday deleted successfully!'); window.location='add_holiday.php';</script>";
			exit();
		} else {
			echo "<script>alert('Error deleting Holiday: ".mysqli_error($dbcon)."'); window.location='add_holiday.php';</script>";
		}
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
	<title>List of Holidays | Library Management System</title>
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
								<h2 class="title">Add Holidays</h2>
							</div>                                
						</div>

						<!-- /.row -->
						<div class="row breadcrumb-div">
							<div class="col-md-6">
								<ul class="breadcrumb">
									<li><a href="dashboard.php"><i class="fa fa-home"></i> Home /&nbsp;</a></li>
									<li><a href="#">Administration / &nbsp; </a></li>
									<li class="active">Add Holidays</li>
								</ul>
							</div>                               
						</div>
						<!-- /.row -->
					</div>

					<div class="container">                 
						<form method="post" class="form-controlr"> 
							<br>
							<fieldset class="border">                                
								<div class="row p-2">
									<div class="col-sm-2 text-end">
										<label for="holiday_date" class="form-label">Holiday Date:</label>
									</div>
									<div class="col-sm-4">
										<input type="date" name="holiday_date" class="form-control" required>
									</div>
								</div>
								<div class="row p-2">
									<div class="col-sm-2 text-end">
										<label for="description" class="form-label">Description:</label>
									</div>
									<div class="col-sm-4">
										<input type="text" name="description" class="form-control" required>
									</div>
								</div>	

								<div class="row p-2">
									<div class="col-sm-2 text-end">
									</div>
									<div class="col-sm-4">
										<button type="submit" class="btn btn-primary btn-md" name="btnsave"><i class="bi bi-tags-fill"></i> &nbsp;Save</button>
									</div>
								</div>

							</fieldset>
						</form>				
						<div class="row justify-content-md-center"> 
							<div class="col-sm-8">
								<h3>List of Holidays</h3>
							</div>
						</div>

						<div class="row justify-content-md-center">							
							<div class="col-sm-8">		
								<table class="table table-striped table-bordered table-hover align-middle table-responsive" id="dataTables">										
									<thead>
										<tr class="text-center">
											<th>Ser</th>
											<th>Date</th>
											<th>Description</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody class="table-group-divider">
									<?php																
									$sql="SELECT * FROM holidays ORDER BY holiday_date ASC";
									$result= mysqli_query($dbcon, $sql);									
									if (mysqli_num_rows($result) > 0) {												
										$ser=1;					
										while($row = mysqli_fetch_assoc($result)) {
									?>
										<tr>
											<td class="text-center"><?php echo $ser++?></td>
											<td class="text-center"><?php echo $row["holiday_date"]?></td>
											<td><?php echo $row["description"]?></td>
											<td class="text-center">
												<a href="edit_holiday.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm"><i class="bi bi-pencil-square"></i></a>
												<a href="add_holiday.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this holiday?');" class="btn btn-danger btn-sm"><i class="bi bi-trash3"></i></a>
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

?>
