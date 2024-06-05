<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['alogin'])==0)
{   
	header('location:index.php');
}
else{ 
    if (isset($_GET['id'])) {
        $id=$_GET['id'];
        $sql=mysqli_query($dbcon,"SELECT * FROM `membergroups` WHERE id=$id");
        $result=mysqli_fetch_assoc($sql);
    }

    if(isset($_POST['btnsave'])){
	// Save Record
		$categorycode=$_POST['categorycode'];
		$description=$_POST['description'];
		$enrollmentfee=$_POST['enrollmentfee'];
		$limitation=$_POST['limitation'];	

		$sql2="UPDATE `membergroups` SET `description`=?,`enrollmentfee`=?,`limitation`=? WHERE `id`=?";
		$result2=mysqli_prepare($dbcon, $sql2);

		if ($result2){
			mysqli_stmt_bind_param($result2,'ssss', $description, $enrollmentfee, $limitation, $id);
			if (mysqli_stmt_execute($result2)) {
				echo "<script>alert('Member Groups Updated Successfully'); window.location='add-membergroup.php'</script>";
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
	<link rel="icon" href="img/logo.png" type="image/png">

	<title>Edit Member Groups | Foundation Library Management System</title>
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
								<h2 class="title">Edit Member Groups</h2>
							</div>                                
						</div>
						<!-- /.row -->
						<div class="row breadcrumb-div">
							<div class="col-md-6">
								<ul class="breadcrumb">
									<li><a href="dashboard.php"><i class="fa fa-home"></i> Home /&nbsp;</a></li>
									<li><a href="#">Administration /&nbsp; </a></li>
									<li class="active">Edit Member Groups</li>
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
										<input type="text" class="form-control" id="inputcategorycode" name="categorycode" value="<?php echo $result['categorycode']?>" disabled required>
									</div>
								
									<div class="col-sm-2 text-end">
										<label for="inputdescription" class="form-label">Description:</label>
									</div>
									<div class="col-sm-4">
										<input type="text" class="form-control" id="inputdescription" name="description" value="<?php echo $result['description']?>" required>
									</div>
								</div>		

                                <div class="row p-2">
									<div class="col-sm-2 text-end">
										<label for="inputenrollmentfee" class="form-label">Enrollment fee:</label>
									</div>
									<div class="col-sm-4">
										<input type="number" name="enrollmentfee" class="form-control" id="enrollmentfee" value="<?php echo $result['enrollmentfee']?>" step="0.00" required>
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