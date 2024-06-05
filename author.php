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
	<title>List of Author | Foundation Library Management System</title>
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
								<h2 class="title">List of Author</h2>
							</div>                                
						</div>
						<!-- /.row -->
						<div class="row breadcrumb-div">
							<div class="col-md-6">
								<ul class="breadcrumb">
									<li><a href="dashboard.php"><i class="fa fa-home"></i> Home /&nbsp;</a></li>
									<li><a href="#">Catalogs /&nbsp; </a></li>
									<li class="active">List of Author</li>
								</ul>
							</div>                               
						</div>
						<!-- /.row -->
					</div>
					<!-- /.container-fluid -->

					<div class="container">                 
						<form name="signup" method="post" class="form-controlr"> 
						<br>						
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
    <script src="js/jquery-3.7.0.js"></script>
	<script src="js/jquery.dataTables.min.js"></script>
    <script src="js/dataTables.buttons.min.js"></script>
    <script src="js/buttons.print.min.js"></script>
    <script src="js/jszip.min.js"></script>
    <script src="js/pdfmake.min.js"></script>
    <script src="js/vfs_fonts.js"></script>
    <script src="js/buttons.html5.min.js"></script>      
    <script src="js/dataTables.bootstrap5.min.js"></script>    
    <script src="js/buttons.bootstrap5.min.js"></script>    
    <script src="js/buttons.colVis.min.js"></script>  

    <script>
		// new DataTable('#dataTables');   
        $(document).ready(function() {
        $('#dataTables').DataTable( {
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        } );
    } );

    // table.buttons().container()
    // .appendTo( '#dataTables .col-md-6:eq(0)' );

	</script>
	
</body>
</html>
<?php 
mysqli_close($dbcon);
} ?>