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
		$branchcode=$_POST['code'];
		$name=$_POST['name'];
		$address1=$_POST['address1'];
		$address2=$_POST['address2'];
		$zip=$_POST['zip'];
		$city=$_POST['city'];
		$state=$_POST['state'];
		$country=$_POST['country'];
		$phone=$_POST['phone'];
		$fax=$_POST['fax'];
		$email=$_POST['email'];
		$url=$_POST['url'];

		$sql="INSERT INTO `branches`(`branchcode`, `name`, `address1`, `address2`, `zip`, `city`, `state`, `country`, `phone`, `fax`, `email`, `url`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";
		$result=mysqli_prepare($dbcon,$sql);

		if($result) {
			mysqli_stmt_bind_param($result,'ssssssssssss',$branchcode, $name, $address1, $address2, $zip, $city, $state, $country, $phone, $fax, $email, $url);
			if(mysqli_stmt_execute($result)) {
				echo "Record updated successfully";
				header('location:add-publishers.php');
			} else{
				echo "Error inserting data: " .mysqli_error($dbcon);
			}
		} else{
			echo "Error Connection: " .mysqli_error($dbcon);
		}
	}
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<link rel="icon" href="img/logo.png" type="image/png">

	<meta name="viewport" content="width=device-width, initial-scale=1">       
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
								<h2 class="title">Add Library Information</h2>
							</div>                                
						</div>
						<!-- /.row -->
						<div class="row breadcrumb-div">
							<div class="col-md-6">
								<ul class="breadcrumb">
									<li><a href="dashboard.php"><i class="fa fa-home"></i> Home /&nbsp;</a></li>
									<li><a href="#">Administration /&nbsp; </a></li>
									<li class="active">Add Library</li>
								</ul>
							</div>                               
						</div>
						<!-- /.row -->
					</div>
					<!-- /.container-fluid -->

					<div class="container">                 
						<form  name="signup" method="post" onSubmit="return valid();">
							<br>
							<fieldset class="border">    
								<div class="row p-2">
									<div class="col-sm-2 text-end">
										<label for="inputcode" class="form-label">Library Code:<i class="text-danger font-weight-bold">*</i></label>
									</div>
									<div class="col-sm-4">
										<input type="text" class="form-control" id="inputcode" name="code" required>
									</div>
								
									<div class="col-sm-2 text-end">
										<label for="inputname" class="form-label">Library Name:<i class="text-danger font-weight-bold">*</i></label>
									</div>
									<div class="col-sm-4">
										<input type="text" class="form-control" id="inputname" name="name" required>
									</div>
								</div>

								<div class="row p-2">
									<div class="col-sm-2 text-end">
										<label for="inputaddress" class="form-label">Address:</label>
									</div>
									<div class="col-sm-4">
										<input type="text" class="form-control" id="inputaddress" name="address1">
									</div>
								
									<div class="col-sm-2 text-end">
										<label for="inputaddress2" class="form-label">Address 2:</label>
									</div>
									<div class="col-sm-4">
										<input type="text" class="form-control" id="inputaddress2" name="address2">
									</div>
								</div>	

								<div class="row p-2">
									<div class="col-sm-2 text-end">
										<label for="inputcity" class="form-label">City:</label>
									</div>
									<div class="col-sm-4">
										<input type="text" class="form-control" id="inputcity" name="city">
									</div>
								
									<div class="col-sm-2 text-end">
										<label for="inputState" class="form-label">State:</label>
									</div>
									<div class="col-sm-4">
										<input type="text" class="form-control" id="inputState" name="state">
									</div>
								</div>	

								<div class="row p-2">
									<div class="col-sm-2 text-end">
										<label for="inputZip" class="form-label">Zip/Postal Code:</label>
									</div>
									<div class="col-sm-4">
										<input type="text" class="form-control" id="inputZip" name="zip">
									</div>
								
									<div class="col-sm-2 text-end">
										<label for="inputcountry" class="form-label">Country:</label>
									</div>
									<div class="col-sm-4">
										<select id="inputType" class="form-select" name ="country">
										<?php
											$sql=mysqli_query($dbcon, "SELECT * FROM `countries`");

											if (mysqli_num_rows($sql)>0) {
											while ($row=mysqli_fetch_array($sql)) {
												echo "<option value='" . $row['sortname'] . "'>" . $row['name'] . "</option>";
											}
											}
										?>
										</select>										
									</div>
								</div>	
                               
                                <div class="row p-2">
									<div class="col-sm-2 text-end">
										<label for="inputcontact" class="form-label">Contac No:</label>
									</div>
									<div class="col-sm-4">
										<input type="number" class="form-control" id="inputcontact" name="contactno">
									</div>
								
									<div class="col-sm-2 text-end">
										<label for="inputfax" class="form-label">Fax No:</label>
									</div>
									<div class="col-sm-4">
										<input type="number" class="form-control" id="inputfax" name="fax">
									</div>
								</div>

                                <div class="row p-2">
									<div class="col-sm-2 text-end">
										<label for="inputemail" class="form-label">Email:</label>
									</div>
									<div class="col-sm-4">
										<input type="number" class="form-control" id="inputemail" name="phone">
									</div>
								
									<div class="col-sm-2 text-end">
										<label for="inputweb" class="form-label">Web sit:</label>
									</div>
									<div class="col-sm-4">
										<input type="url" class="form-control" id="inputweb" name="url">
									</div>
								</div>
							
								<div class="row p-2">
									<div class="col-sm-2 text-end">
									</div>
									<div class="col-sm-4">
										<button type="submit" class="btn btn-primary btn-md" name="btnsave"><i class="bi bi-house-add-fill"></i> &nbsp;Save</button>
									</div>
									
								</div>
							</fieldset>
						</form>							
					</div>
					<div class="col"> 
						<?php include('includes/footer.php');?>                    
					</div> 
				</div>				
			</div> 			
		</div>
	</div>
	  
</body>
</html>




