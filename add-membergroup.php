<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0)
{   
	header('location:index.php');
}
else{ 
	if(isset($_POST['btnsave']))
	{
	// Save Record
		$categorycode=$_POST['categorycode'];
		$description=$_POST['description'];
		$enrollmentfee=$_POST['enrollmentfee'];
		$limitation=$_POST['limitation'];		
		
		$sql="INSERT INTO `membergroups`(`categorycode`, `description`, `enrollmentfee`, `limitation`) VALUES (?,?,?,?)";
		$result=mysqli_prepare($dbcon, $sql);
		
		if ($result){
			mysqli_stmt_bind_param($result,'ssss',$categorycode, $description, $enrollmentfee, $limitation);
			if (mysqli_stmt_execute($result)) {
				echo "Record updated successfully";
				header('location:add-membergroup.php');
			} else{
				echo "Error inserting data: " .mysqli_error($dbcon);
			}
		} else{
			echo "Error Connection: " .mysqli_error($dbcon);
		}
	// Delete Record		
	}elseif($_GET['id']<>""){
		$id=$_GET['id'];
		$delete=mysqli_prepare($dbcon,"DELETE FROM membergroups WHERE id=?");
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
	<link rel="icon" href="img/logo.png" type="image/png">

	<title>Add Member Groups | Foundation Library Management System</title>
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
								<h2 class="title">Add Member Groups</h2>
							</div>                                
						</div>
						<!-- /.row -->
						<div class="row breadcrumb-div">
							<div class="col-md-6">
								<ul class="breadcrumb">
									<li><a href="dashboard.php"><i class="fa fa-home"></i> Home /&nbsp;</a></li>
									<li><a href="#">Administration /&nbsp; </a></li>
									<li class="active">Add Member Groups</li>
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
										<label for="inputcategorycode" class="form-label">Group code:</label>
									</div>
									<div class="col-sm-4">
										<input type="text" class="form-control" id="inputcategorycode" name="categorycode" required>
									</div>
								
									<div class="col-sm-2 text-end">
										<label for="inputdescription" class="form-label">Description:</label>
									</div>
									<div class="col-sm-4">
										<input type="text" class="form-control" id="inputdescription" name="description" required>
									</div>
								</div>		

                                <div class="row p-2">
									<div class="col-sm-2 text-end">
										<label for="inputenrollmentfee" class="form-label">Enrollment fee:</label>
									</div>
									<div class="col-sm-4">
										<input type="number" name="enrollmentfee" class="form-control" id="enrollmentfee" step="0.00" required>
									</div>
								
									<div class="col-sm-2 text-end">
										<label for="inputlimitation" class="form-label">Library limitations:</label>
									</div>
									<div class="col-sm-4">

									<select id="inputlimitation" class="form-select" name="limitation" required>
                                            <option selected value="all">All Library</option>
                                                <?php
                                                    $sql=mysqli_query($dbcon, "SELECT * FROM `branches`");
                                                    if (mysqli_num_rows($sql)>0) {
                                                    while ($row=mysqli_fetch_array($sql)) {
                                                        echo "<option value='" . $row['branchcode'] . "'>" . $row['name'] . "</option>";
                                                    }
                                                    }
                                                ?>
                                            </select>
									</div>
								</div>	
								<div class="d-grid gap-2 d-md-flex justify-content-md-center">
                                    <div class="col-sm-1">     
                                        <button type="submit" class="btn btn-primary btn-md" name="btnsave"><i class="bi bi-person-lines-fill"></i>&nbsp;Save</button>
                                    </div>
                                </div>
					 
							</fieldset>
						</form>						
						<div class="row justify-content-md-center"> 
							<div class="col-sm-8">
								<h3>List of Groups</h3>
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
											<th class="text-center">Enrollment Fee</th>
											<th class="text-center">Branch</th>
											<th class="text-center">Action</th>
										</tr>
									</thead>
									
									<tbody class="table-group-divider">
									<?php																

									// Retrieve data for the current page	
									$sql="SELECT * FROM `membergroups`";
									$result= mysqli_query($dbcon, $sql);									
									
									if (mysqli_num_rows($result) > 0) {												
									$ser=1;					
									// output data of each row
									while($row = mysqli_fetch_assoc($result)) {
									?>
										<tr>
											<td class="text-center"><?php echo $ser++?></td>
											<td><?php echo $row["categorycode"]?></td>
											<td><?php echo $row["description"]?></td>
											<td><?php echo $row["enrollmentfee"]?></td>
											<td><?php echo $row["limitation"]?></td>
											<td class="text-center">
												<a href="edit_membergroup.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm" name="btnedit"><i class="bi bi-pencil-square"></i>&nbsp;Edit</a>
												<a href="add-membergroup.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are your sure Delete this record?');" name="btndelete"><i class="bi bi-trash3"></i>&nbsp;Delete</a>
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
	<!-- <script src="js/search.js"></script> -->

	<script src="js/jquery-3.7.0.js"></script>
	<script src="js/jquery.dataTables.min.js"></script>

	<script>
		new DataTable('#dataTables');  
		
function country(str,resultContainerId) {  
    var resultContainerId="c";
    if (str.length == 0) {
        document.getElementById("resultcountry").innerHTML = "";
        document.getElementById("resultcountry").style.display = "none";
        return;
    } else {
        var xmlhttp = new XMLHttpRequest();

        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("resultcountry").innerHTML = this.responseText;
                document.getElementById("resultcountry").style.display = "block";
            }
        };
        xmlhttp.open("GET", "search.php?q=" + str + "&field=" + resultContainerId, true);
        xmlhttp.send();
    }
}

// Event listener for input changes
document.getElementById("country").addEventListener("input", function() {
    country(this.value);

});

// Event listener to handle result item clicks
document.getElementById("resultcountry").addEventListener("click", function(e) {
    if (e.target.classList.contains("result-item")) {
        document.getElementById("country").value = e.target.textContent;
        this.style.display = "none";

    }
});
	</script>
	
</body>
</html>
<?php 
mysqli_close($dbcon);
} ?>