<?php
session_start();
error_reporting(0);
include('includes/config.php');
include('includes/activity.php');

logAction($dbcon, "Get-rank");
if(strlen($_SESSION['alogin'])==0)
{   
	header('location:index.php');
}

else{ 
    if(isset($_POST['btnsave']))

	{
	// Save Record
		$service_code = $_POST['service_code'];
        $rank_name = $_POST['rank_name'];

        $sql="INSERT INTO ranks (service_code,rank_name) VALUES(?,?)";
		$result=mysqli_prepare($dbcon, $sql);		

		if ($result){
			mysqli_stmt_bind_param($result,'ss', $service_code, $rank_name);
			if (mysqli_stmt_execute($result)) {
				echo "Record updated successfully";
				header('location:get_rank.php');	
			} else{
				echo "Error inserting data: " .mysqli_error($dbcon);
			}
		} else{
			echo "Error Connection: " .mysqli_error($dbcon);
		}	

    }

        if (isset($_POST['service'])) {
             $service = mysqli_real_escape_string($dbcon, $_POST['service']);

            $sql = "SELECT rank_name FROM ranks WHERE service_code = '$service'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='{$row['rank_name']}'>{$row['rank_name']}</option>";
                }
            } else {
                echo "<option>No ranks found</option>";
            }
        }
       
	}
// Delete Record	
	if($_GET['id']<>null){
		$id=$_GET['id'];
		mysqli_query($dbcon,"DELETE FROM ranks WHERE rankid='$id'");
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

	<title>Add Rank |Library Management System</title>
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
								<h2 class="title">Add Rank</h2>
							</div>                                
						</div>
						<!-- /.row -->
						<div class="row breadcrumb-div">
							<div class="col-md-6">
								<ul class="breadcrumb">
									<li><a href="dashboard.php"><i class="fa fa-home"></i> Home /&nbsp;</a></li>
									<li><a href="#">Administration /&nbsp; </a></li>
									<li class="active">Add Rank</li>
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
										<label for="service_code" class="form-label">Service Code:</label>
									</div>
									<div class="col-sm-4">
										<input type="text" class="form-control" id="service_code" name="service_code" required>
									</div>
								</div>
								<div class="row p-2">
									<div class="col-sm-2 text-end">
										<label for="rank_name" class="form-label">Rank Name:</label>
									</div>
									<div class="col-sm-4">
										<input type="text" class="form-control" id="rank_name" name="rank_name" required>
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
								<h3>List of Rank</h3>
							</div>
						</div>
						<div class="row justify-content-md-center">							
							<div class="col-sm-8">			
								<table class="table table-striped table-bordered table-hover align-middle table-responsive" id="dataTables">										
									<thead>
										<tr class="text-center">
											<th class="text-center">Ser</th>
											<th class="text-center">Service Code</th>
											<th class="text-center">Rank Name</th>
											<th class="text-center">Action</th>
										</tr>
									</thead>
									
									<tbody class="table-group-divider">
									<?php																

									// Retrieve data for the current page	
									$sql="SELECT * FROM `ranks`";
									$result= mysqli_query($dbcon, $sql);									
									
									if (mysqli_num_rows($result) > 0) {												
									$ser=1;					
									// output data of each row
									while($row = mysqli_fetch_assoc($result)) {
									?>
										<tr>
											<td class="text-center"><?php echo $ser++?></td>
											<td><?php echo $row["service_code"]?></td>
											<td><?php echo $row["rank_name"]?></td>
											<td class="text-center">
												
												<a href="edit-rank.php?id=<?php echo $row['rankid']; ?>" class="btn btn-warning btn-sm" name="btnedit"><i class="bi bi-pencil-square"></i></a>
												<a href="add-rank.php?id=<?php echo $row['rankid']; ?>" onclick="return confirm('Are your sure Delete this record?');" class="btn btn-danger btn-sm" name="btndelete"><i class="bi bi-trash3"></i></a>
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



