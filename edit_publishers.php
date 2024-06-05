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
    $result=mysqli_query($dbcon,"SELECT * FROM `publishersr` WHERE pubid=$id");
    $row=mysqli_fetch_array($result);
	
	if(isset($_POST['btnsave']))
	{
	// Save Record
		$name=$_POST['name'];
		$address=$_POST['address'];
		$country=$_POST['country'];
		$contactno=$_POST['contactno'];
		$fax=$_POST['fax'];
		$email=$_POST['email'];
		$web=$_POST['web'];
		
		$sql="UPDATE publishersr SET pubname=?, pubaddress=?, pubcountry=?, pubmobile=?, pubfax=?, pubemail=?, pubwebsite=? WHERE pubid=?";
		$result=mysqli_prepare($dbcon, $sql);
		
		if ($result){
			mysqli_stmt_bind_param($result,'sssssssi',$name,$address,$country,$contactno,$fax,$email,$web,$id);
			if (mysqli_stmt_execute($result)) {
				echo "Record updated successfully";
				header('location:add-publishers.php');
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

	<title>Edit Publishers | Foundation Library Management System</title>
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
								<h2 class="title">Add Publishers</h2>
							</div>                                
						</div>
						<!-- /.row -->
						<div class="row breadcrumb-div">
							<div class="col-md-6">
								<ul class="breadcrumb">
									<li><a href="dashboard.php"><i class="fa fa-home"></i> Home /&nbsp;</a></li>
									<li><a href="#">Catalogs /&nbsp; </a></li>
									<li class="active">Add Publishers</li>
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
										<label for="inputname" class="form-label">Publisher Name:</label>
									</div>
									<div class="col-sm-4">
										<input type="text" class="form-control" id="inputname" name="name" value="<?php echo htmlentities($row['pubname'])?>" >
									</div>
								
									<div class="col-sm-2 text-end">
										<label for="inputaddress" class="form-label">Address:</label>
									</div>
									<div class="col-sm-4">
										<input type="text" class="form-control" id="inputaddress" name="address" value="<?php echo htmlentities($row['pubname'])?>"> 
									</div>
								</div>		

                                <div class="row p-2">
									<div class="col-sm-2 text-end">
										<label for="inputcountry" class="form-label">Country:</label>
									</div>
									<div class="col-sm-4">
									<input type="search" name="country" class="form-control" title="Enter search keyword" id="country" value="<?php echo htmlentities($row['pubcountry'])?>">
                            			<div id="resultcountry"></div>             
									</div>
								
									<div class="col-sm-2 text-end">
										<label for="inputcontact" class="form-label">Contac No:</label>
									</div>
									<div class="col-sm-4">
										<input type="number" class="form-control" id="inputcontact" name="contactno" value="<?php echo htmlentities($row['pubmobile'])?>">
									</div>
								</div>

								<div class="row p-2">
									<div class="col-sm-2 text-end">
										<label for="inputfax" class="form-label">Fax No:</label>
									</div>
									<div class="col-sm-4">
										<input type="number" class="form-control" id="inputfax" name="fax" value="<?php echo htmlentities($row['pubfax'])?>">
									</div>
								
									<div class="col-sm-2 text-end">
										<label for="inputemail" class="form-label">Email:</label>
									</div>
									<div class="col-sm-4">
										<input type="email" class="form-control" id="inputemail" name="email" value="<?php echo htmlentities($row['pubemail'])?>">
									</div>
								</div>                                

                                <div class="row p-2">
									<div class="col-sm-2 text-end">
										<label for="inputweb" class="form-label">Web sit:</label>
									</div>
									<div class="col-sm-4">
										<input type="text" class="form-control" id="inputweb" name="web" value="<?php echo htmlentities($row['pubwebsite'])?>">
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