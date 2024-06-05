<?php 
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0){   
	header('location:index.php');
}else{ 
	$token=rand();
	if(isset($_POST['btnCheckin'])){
		// if(!empty($_POST["bookid"])) {

		if ($_SESSION['csrf_token']==$_POST['csrf_token']) {	
		
			$bookid=$_POST['bookid'];
			$status=0;			
			$success=0;			
			$fine=0;	
			$sysdate=date('Y-m-d');

			$sql=mysqli_query($dbcon,"SELECT * FROM `issuedbook` WHERE `booknumber`= '$bookid' && `RetrunStatus`=$status");			
			$issuedbook=mysqli_fetch_assoc($sql);

			if (mysqli_num_rows($sql)>0) {
				$membernumber=$issuedbook['membernumber'];
				$returndate = $issuedbook['ReturnDate'];
				$booknumber=$issuedbook['booknumber'];
				$success=1;		
				$status=1;

				// Find the Member Group.
				$sql1=mysqli_query($dbcon,"SELECT * FROM `member` WHERE `cardnumber`='$membernumber'");
				$member=mysqli_fetch_assoc($sql1);
				$category=$member['categorycode'];
				
				// Find the Item type (book or Magz).
				$sql1=mysqli_query($dbcon,"SELECT * FROM `catalog` WHERE `booknumber`='$booknumber'");
				$catalog=mysqli_fetch_assoc($sql1);
				$findtype=$catalog['itemtype'];
				
				// Find the Item type.
				$sql2=mysqli_query($dbcon,"SELECT * FROM `finerules` WHERE `category`='$category' && itemtype = '$findtype'");
				$finerules=mysqli_fetch_assoc($sql2);
				$fineamount=$finerules['fineamount'];
				
				if ($returndate<$sysdate){

					// Calculate Date differnts
					$diff = strtotime($sysdate) - strtotime($returndate);
					$noofdate = floor($diff / (60 * 60 * 24));		

					// Calculate fine Amount
					$totfineamount=$fineamount*$noofdate;
					
					// Status Change in issuedbook Table
					$update=mysqli_prepare($dbcon,"UPDATE `issuedbook` SET `RetrunStatus`=?,`fine`=? WHERE `booknumber`=?");				
					mysqli_stmt_bind_param($update,'sss',$status, $totfineamount,$booknumber);					

					// Status Change in Catalog Table
					$update1=mysqli_prepare($dbcon,"UPDATE `catalog` SET `checkedin`=? WHERE `booknumber`=?");				
					mysqli_stmt_bind_param($update1,'ss',$status, $booknumber);					

					if (mysqli_stmt_execute($update) && mysqli_stmt_execute($update1)) {
						$error='The book has been currently overdue: <strong><br>Fine Amount is Rs. '.number_format($totfineamount, 2).'</strong>';			
					} else{
						$error='Something went wrong please try again';		
					}
				} else{
					// Status Change in issuedbook Table
					$update=mysqli_prepare($dbcon,"UPDATE `issuedbook` SET `RetrunStatus`=? WHERE `booknumber`=?");				
					mysqli_stmt_bind_param($update,'ss',$status, $booknumber);					

					// Status Change in Catalog Table
					$update1=mysqli_prepare($dbcon,"UPDATE `catalog` SET `checkedin`=? WHERE `booknumber`=?");				
					mysqli_stmt_bind_param($update1,'ss',$status, $booknumber);					

					if (mysqli_stmt_execute($update) && mysqli_stmt_execute($update1)) {
						$error='The book has been <strong>successfully </strong>checked in';
					} else{
						$error='Something went wrong please try again';		
					}
				}		
			}else{				
				$error='<strong>Book already checking!</strong>';				
			}	
		}else{
			echo "<script>alert('Invalid authentication')</script>";
		}
	}
}

$_SESSION['csrf_token']=$token;
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Check in | Foundation Library Management System</title>

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
								<h2 class="title">Return Book</h2>
							</div>                                
						</div>
						<!-- /.row -->
						<div class="row breadcrumb-div">
							<div class="col-md-6">
								<ul class="breadcrumb">
									<li><a href="dashboard.php"><i class="fa fa-home"></i> Home /&nbsp;</a></li>
									<li><a href="#">Circulations /&nbsp; </a></li>
									<li class="active">Return Book</li>
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
										<label for="Checkinbook" class="form-label">Book ID:</label>
									</div>
									<div class="col-sm-4">
										<input type="text" class="form-control" id="Checkinbook" name="bookid" required placeholder="Enter Book ID">
										<input type="hidden" name="csrf_token" value="<?php echo $token?>">
										<?php							
											if ($success==1){
											?>
												<span class="text-success font-weight-bold"><?php echo $error?></span>
											<?php
											}else{
											?>
												<span class="text-danger font-weight-bold"><?php echo $error?></span>
											<?php
											}										
										?>
									</div>																	
								</div>	
								<div class="row p-2">
									<div class="col-sm-2 text-end">
									</div>
									<div class="col-sm-4">
										<button type="submit" class="btn btn-warning" name="btnCheckin"><i class="bi bi-arrow-bar-left"></i>&nbsp;Return</button>
									</div>
								</div>
							</fieldset>														
						</form>					
					</div>
				</div> 
			</div>
		</div>
	</div>

	<div class="col"> 
		<?php include('includes/footer.php');?>                    
	</div>   
</body>
</html>