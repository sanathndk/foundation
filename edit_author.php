<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0)
{   
	header('location:index.php');
}
else{ 
    $id=$_GET['id'];
	$result=mysqli_query($dbcon,"SELECT * FROM `author` WHERE authorid=$id");
	$row=mysqli_fetch_array($result);

	if(isset($_POST['btnsave']))
	{
	// Save Record
		$name=$_POST['name'];
		$address=$_POST['address'];
		$country=$_POST['country'];
		$contactno=$_POST['contactno'];
		$email=$_POST['email'];
		$dob=$_POST['dob'];
		$yrdied=$_POST['yrdied'];		
		$bio=$_POST['bio'];
		$publications=$_POST['pub'];
		$awards=$_POST['awards'];
		$references=$_POST['ref'];		

		$sql="UPDATE author SET name=?,address=?,country=?,mobile=?,email=?,dob=?,dateofdied=?,bio=?,publications=?,awards=?,ref=? WHERE authorid=?";
        $result=mysqli_prepare($dbcon, $sql);

		if ($result){
			mysqli_stmt_bind_param($result,'sssssssssssi',$name,$address,$country,$contactno,$email,$dob,$yrdied,$bio,$publications,$awards,$references,$id);
			if (mysqli_stmt_execute($result)) {
				echo "Data Update successfully";
				header('location:add-author.php');
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
	<link rel="stylesheet" href="css/jquery.dataTables.min.css">  
	<link rel="icon" href="img/logo.png" type="image/png">

	<title>Edit Author | Foundation Library Management System</title>
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
								<h2 class="title">Update Author</h2>
							</div>                                
						</div>
						<!-- /.row -->
						<div class="row breadcrumb-div">
							<div class="col-md-6">
								<ul class="breadcrumb">
									<li><a href="dashboard.php"><i class="fa fa-home"></i> Home /&nbsp;</a></li>
									<li><a href="#">Catalogs /&nbsp; </a></li>
									<li class="active">Update Author</li>
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
										<input type="text" class="form-control" id="inputname" name="name" value="<?php echo $row['name']; ?>" required>
									</div>								
									<div class="col-sm-2 text-end">
										<label for="inputaddress" class="form-label">Address:</label>
									</div>
									<div class="col-sm-4">
										<input type="text" class="form-control" id="inputaddress" name="address" value="<?php echo $row['address']; ?>"> 
									</div>
								</div>		

                                <div class="row p-2">
									<div class="col-sm-2 text-end">
										<label for="inputcountry" class="form-label">Country:</label>
									</div>
									<div class="col-sm-4">
										<input type="search" name="country" class="form-control" title="Enter search keyword" id="country" value="<?php echo $row['country']; ?>">
                            			<div id="resultcountry"></div>               
									</div>
								
									<div class="col-sm-2 text-end">
										<label for="inputcontact" class="form-label">Contac No:</label>
									</div>
									<div class="col-sm-4">
										<input type="number" class="form-control" id="inputcontact" name="contactno" value="<?php echo $row['mobile']; ?>">
									</div>
								</div>

                                <div class="row p-2">
									<div class="col-sm-2 text-end">
										<label for="inputemail" class="form-label">Email:</label>
									</div>
									<div class="col-sm-4">
										<input type="email" class="form-control" id="inputemail" name="email" value="<?php echo $row['email']; ?>">
									</div>
								
									<div class="col-sm-2 text-end">
										<label for="inputdob" class="form-label">Year born:</label>
									</div>
									<div class="col-sm-4">
										<input type="number" class="form-control" id="inputdob" name="dob" value="<?php echo $row['dob']; ?>">
									</div>
								</div>

                                <div class="row p-2">
									<div class="col-sm-2 text-end">
										<label for="inputyrdied" class="form-label">Year died:</label>
									</div>
									<div class="col-sm-4">
										<input type="number" class="form-control" id="inputyrdied" name="yrdied" value="<?php echo $row['dateofdied']; ?>">
									</div>
								</div>

                                <div class="row p-2">
									<div class="col-sm-2 text-end">
										<label for="inputbio" class="form-label">Bio:</label>
									</div>
									<div class="col-sm-4">
										<textarea name="bio" class="form-control" id="inputbio">
											<?php echo $row['bio']; ?>
										</textarea>
									</div>
								
									<div class="col-sm-2 text-end">
										<label for="inputpub" class="form-label">Publications:</label>
									</div>
									<div class="col-sm-4">
										<textarea name="pub" class="form-control" id="inputpub" >
										<?php echo $row['publications'];?>
										</textarea>
									</div>
								</div>

                                <div class="row p-2">
									<div class="col-sm-2 text-end">
										<label for="inputAwards" class="form-label">Awards:</label>
									</div>
									<div class="col-sm-4">
										<textarea name="awards" class="form-control" id="inputAwards">
											<?php echo $row['awards']; ?>
										</textarea>
									</div>
								
									<div class="col-sm-2 text-end">
										<label for="inputref" class="form-label">References:</label>
									</div>
									<div class="col-sm-4">
										<textarea name="ref" class="form-control" id="inputref">
											<?php echo htmlentities(trim($row['ref']));?>										
										</textarea>
									</div>
								</div>							
								<div class="row p-2">
									<div class="col-sm-2 text-end">
									</div>
									<div class="col-sm-4">
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
	<script>		
		// ************Country search************
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
