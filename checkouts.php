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
	if ($_SESSION['csrf_token']==$_POST['csrf_token']) {	
		if(isset($_POST['btncheckouts']) || (!empty($_POST['membernumber'])))
		{	
		$membernumber=$_POST['membernumber'];
		$booknumber=$_POST['booknumber'];
		$issuesdate=date('Y-m-d');
		$status=0;	
		$issuedbook;

		// Find the Member Group.
		$sql1=mysqli_query($dbcon,"SELECT * FROM `member` WHERE `cardnumber`='$membernumber'");
		$member=mysqli_fetch_assoc($sql1);
		$category=$member['categorycode'];
		$active=$member['status'];

		if ($active<>1) {
			$error = "<strong>$membernumber </strong> has not activated.";		
		}else{

			// Find the Item type (book or Magz).
			$sql2=mysqli_query($dbcon,"SELECT * FROM `catalog` WHERE `booknumber`='$booknumber'");
			$catalog=mysqli_fetch_assoc($sql2);
			$findtype=$catalog['itemtype'];
			$bookstatus=$catalog['status'];
			
			// Find the Item type.
			$sql3=mysqli_query($dbcon,"SELECT * FROM `finerules` WHERE `category`='$category' && itemtype = '$findtype'");
			$finerules=mysqli_fetch_assoc($sql3);
			$loanperiod=$finerules['loanperiod'];
			$checkoutallow=$finerules['checkoutallow'];

			// Check no of book issued
			$issuedbook=mysqli_query($dbcon,"SELECT * FROM issuedbook WHERE membernumber = '$membernumber' and RetrunStatus=0");

			if($catalog['status'] == 'A'){
				if ($checkoutallow<>0) {	
					if (mysqli_num_rows($issuedbook)<$checkoutallow) {				

						$sql4=mysqli_query($dbcon,"SELECT * FROM issuedbook WHERE `booknumber`='$booknumber' and RetrunStatus=0");			
				
						if (mysqli_num_rows($sql4)<>0) {
							$error = " <strong>$booknumber </strong> Already issued";		
						}else{
								//Calculate returnd date
								$returndate = date("Y-m-d", strtotime($loanperiod."days", strtotime($issuesdate)));
								// Insert to data in Issued Book Table
								$sql="INSERT INTO `issuedbook`(`membernumber`, `booknumber`, `IssuesDate`, `ReturnDate`, `RetrunStatus`) VALUES(?,?,?,?,?)";		
								$result = mysqli_prepare($dbcon,$sql);
								mysqli_stmt_bind_param($result,'sssss',$membernumber,$booknumber,$issuesdate,$returndate,$status);

								// Status Change in Catalog Table
								$update=mysqli_prepare($dbcon,"UPDATE `catalog` SET `checkedin`=? WHERE `booknumber`=?");				
								mysqli_stmt_bind_param($update,'ss',$status, $booknumber);	
										
								if (mysqli_stmt_execute($result) && mysqli_stmt_execute($update)) {
									$error1='The following items have been checked out:<strong><br>'.$booknumber.'</strong>';		
									// header('location:checkouts.php');
								} else{
									$error='Something went wrong please try again' .mysqli_error($dbcon);;		
								}					
							}	
					} else{
						$error = "Already issued <strong>Maximum </strong>number of books";		
					}
				} else{
					$error = "This member of the <strong>$category </strong> group is not allowed to borrow books";	
				}
			}else{
				if ($catalog['status'] == 'L') {
					$error = "This item is <strong>Lost</strong>";	
				}elseif ($catalog['status'] == 'D') {
					$error = "This item is <strong>Damage</strong>";	
				}
			}	
		}
	}else{
		$error = "Invalid <strong>authentication</strong>";	
	}

}
$_SESSION['csrf_token']=$token;
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Issue a new Book | Foundation Library Management System</title>

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
								<h2 class="title">Issue a new Book</h2>
							</div>                                
						</div>
						<!-- /.row -->
						<div class="row breadcrumb-div">
							<div class="col-md-6">
								<ul class="breadcrumb">
									<li><a href="dashboard.php"><i class="fa fa-home"></i> Home /&nbsp;</a></li>
									<li><a href="#">Circulations /&nbsp; </a></li>
									<li class="active">Checkouts</li>
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
										<label for="inputmember" class="form-label">Member ID:</label>
									</div>
									<div class="col-sm-4">
										<input type="text" class="form-control" id="inputmember" name="membernumber" placeholder="Enter Member ID" onkeydown="handleKeyPress(event)" required>
										<span class="text-danger font-weight-bold"><?php echo $error?></span>
									</div>
								</div>
								<div class="row p-2">
									<div class="col-sm-2 text-end">
										<label for="inputbook" class="form-label">Book ID:</label>
									</div>
									<div class="col-sm-4">
										<input type="text" class="form-control" id="inputbook" name="booknumber" placeholder="Enter Book ID" required>
										<span class="text-success font-weight-bold"><?php echo $error1?></span>
									</div>
								</div>		
								<div class="row p-2">
									<div class="col-sm-2 text-end">
									</div>
									<div class="col-sm-4">
										<input type="hidden" name="csrf_token" value="<?php echo $token?>">
										<button type="submit" class="btn btn btn-warning" name="btncheckouts"><i class="bi bi-arrow-bar-right"></i>&nbsp; Issue</button>
									</div>
								</div>	
							</fieldset>														
						</form>						
				</div> 
			</div>
		</div>
	</div>

	<div class="col"> 
		<?php include('includes/footer.php');?>                    
	</div>   	

</body>
</html>
<?php } ?>