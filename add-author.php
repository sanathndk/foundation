<?php
session_start();
error_reporting(0);
include('includes/config.php');
$token=rand();
if(strlen($_SESSION['alogin'])==0)
{   
	header('location:index.php');
}
else{ 
	if(isset($_POST['btnsave'])){
		if ($_SESSION['csrf_token']==$_POST['csrf_token']) {
			// Save Record
			$name=$_POST['name'];
			$address=$_POST['address']; 
			$country=$_POST['country']; 
			$mobile=$_POST['mobile']; 
			$email=$_POST['email']; 
			$dob=$_POST['dob']; 
			$dateofdied=$_POST['dateofdied']; 
			$bio=$_POST['bio'];
			$publications=$_POST['publications']; 
			$awards=$_POST['awards']; 
			$ref=$_POST['ref'];
				
			$sql="INSERT INTO `author`(`name`, `address`, `country`, `mobile`, `email`, `dob`, `dateofdied`, `bio`, `publications`, `awards`, `ref`) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
			$result=mysqli_prepare($dbcon, $sql);
			
			if ($result){
				mysqli_stmt_bind_param($result,'sssssssssss',$name, $address, $country, $mobile, $email, $dob, $dateofdied, $bio, $publications, $awards, $ref);
				if (mysqli_stmt_execute($result)) {
					echo "<script>Alert('Record added successfully')</script>";
					// header('location:add-publishers.php');
				} else{
					echo "Error inserting data: " .mysqli_error($dbcon);
				}
			} else{
				echo "Error Connection: " .mysqli_error($dbcon);
			}
		}else{
			echo "<script>Alert('Invalid authentication')</script>";
		}
	// Delete Record	
	}elseif($_GET['id']<>""){
		$id=$_GET['id'];
		$delete=mysqli_prepare($dbcon,"DELETE FROM author WHERE authorid=?");
		mysqli_stmt_bind_param($delete,'s',$id);
		if (mysqli_stmt_execute($delete)) {
			echo "<script>Alert('Record deleted successfully.')</script>";
		}else{
			echo "<script>Alert('Something went wrong. please try again later')</script>";
		}
	}	
$_SESSION['csrf_token']=$token;
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">    
	<link rel="icon" href="img/logo.png" type="image/png">

	<title>Add Author | Foundation Library Management System</title>
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
								<h2 class="title">Add Author</h2>
							</div>                                
						</div>
						<!-- /.row -->
						<div class="row breadcrumb-div">
							<div class="col-md-6">
								<ul class="breadcrumb">
									<li><a href="dashboard.php"><i class="fa fa-home"></i> Home /&nbsp;</a></li>
									<li><a href="#">Catalogs /&nbsp; </a></li>
									<li class="active">Add Author</li>
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
										<label for="inputname" class="form-label">Author Name:</label>
									</div>
									<div class="col-sm-4">
										<input type="text" class="form-control" id="inputname" name="name" required>
									</div>
								
									<div class="col-sm-2 text-end">
										<label for="inputaddress" class="form-label">Address:</label>
									</div>
									<div class="col-sm-4">
										<input type="text" class="form-control" id="inputaddress" name="address">
									</div>
								</div>		

                                <div class="row p-2">
									<div class="col-sm-2 text-end">
										<label for="inputcountry" class="form-label">Country:</label>
									</div>
									<div class="col-sm-4">
									<input type="search" name="country" class="form-control" title="Enter search keyword" id="country">
                            			<div id="resultcountry"></div>             
									</div>
								
									<div class="col-sm-2 text-end">
										<label for="inputcontact" class="form-label">Contact Number:</label>
									</div>
									<div class="col-sm-4">
										<input type="number" class="form-control" id="inputcontact" name="mobile">
									</div>
								</div>

								<div class="row p-2">
									<div class="col-sm-2 text-end">
										<label for="inputemail" class="form-label">Email:</label>
									</div>
									<div class="col-sm-4">
										<input type="email" class="form-control" id="inputemail" name="email">
									</div>
								
									<div class="col-sm-2 text-end">
										<label for="inputyearborn" class="form-label">Year born:</label>
									</div>
									<div class="col-sm-4">
										<input type="number" class="form-control" id="inputyearborn" name="dob">
									</div>
								</div>                                

                                <div class="row p-2">
									<div class="col-sm-2 text-end">
										<label for="inputyeardied" class="form-label">Year died:</label>
									</div>
									<div class="col-sm-4">
										<input type="number" class="form-control" id="inputyeardied" name="dateofdied">
									</div>
								</div>

								<div class="row p-2">
									<div class="col-sm-2 text-end">
										<label for="inputbio" class="form-label">Bio:</label>
									</div>
									<div class="col-sm-4">
										<textarea name="bio" class="form-control" id="inputbio"></textarea>
									</div>
								
									<div class="col-sm-2 text-end">
										<label for="inputpub" class="form-label">Publications:</label>
									</div>
									<div class="col-sm-4">
										<textarea name="publications" class="form-control" id="inputpub"></textarea>
									</div>
								</div>

                                <div class="row p-2">
									<div class="col-sm-2 text-end">
										<label for="inputAwards" class="form-label">Awards:</label>
									</div>
									<div class="col-sm-4">
										<textarea name="awards" class="form-control" id="inputAwards"></textarea>
									</div>
								
									<div class="col-sm-2 text-end">
										<label for="inputref" class="form-label">References:</label>
									</div>
									<div class="col-sm-4">
										<textarea name="ref" class="form-control" id="inputref"></textarea>
									</div>
								</div>	
								<div class="row p-2">
									<div class="col-sm-2 text-end">
									</div>
									<div class="col-sm-4">
										<input type="hidden" name="csrf_token" value="<?php echo $token?>">
										<button type="submit" class="btn btn-primary btn-md" name="btnsave"><i class="bi bi-vector-pen"></i> &nbsp;Save</button>
									</div>
								</div>
							</fieldset>
						</form>						
						<div class="row justify-content-md-center"> 
							<div class="col-sm-10">
								<h3>List of Author</h3>
							</div>
						</div>
						<div class="row justify-content-md-center">							
							<div class="col-sm-10">			
								<table class="table table-striped table-bordered table-hover align-middle table-responsive" id="dataTables">										
									<thead>
										<tr class="text-center">
											<th class="text-center">Ser</th>
											<th class="text-center">Name</th>
											<th class="text-center">Country</th>
											<th class="text-center">Contact No</th>
											<th class="text-center">Email</th>
											<th class="text-center">Action</th>
										</tr>
									</thead>
									
									<tbody class="table-group-divider">
									<?php									
									// Retrieve data for the current page	
									$sql="SELECT * FROM `author`";
									$result= mysqli_query($dbcon, $sql);									
									
									if (mysqli_num_rows($result) > 0) {												
									$ser=1;					
									// output data of each row
									while($row = mysqli_fetch_assoc($result)) {
									?>
										<tr>
											<td class="text-center"><?php echo $ser++?></td>
											<td><?php echo $row["name"]?></td>
											<td><?php echo $row["country"]?></td>
											<td><?php echo $row["mobile"]?></td>
											<td><?php echo $row["email"]?></td>
											<td class="text-center">
												<a href="edit_author.php?id=<?php echo $row['authorid']; ?>" class="btn btn-warning btn-sm" name="btnedit"><i class="bi bi-pencil-square"></i>&nbsp;Edit</a>
												<a href="add-author.php?id=<?php echo $row['authorid']; ?>" onclick="return confirm('Are your sure Delete this record?');" class="btn btn-danger btn-sm" name="btndelete"><i class="bi bi-trash3"></i>&nbsp;Delete</a>
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