<?php
session_start();
error_reporting(0);
include('includes/config.php');
$token=rand();
if(strlen($_SESSION['alogin'])==0){   
	header('location:index.php');
}
else{ 
	if(isset($_POST['btnrenew']) || (!empty($_POST['bookid']))){
		if ($_SESSION['csrf_token']==$_POST['csrf_token']) {	

		$booknumber=$_POST['bookid'];
		$noofrenew=0;	

		// Find the Member Group.
		$sql1=mysqli_query($dbcon,"SELECT member.categorycode, issuedbook.ReturnDate, issuedbook.noofrenew FROM member INNER JOIN issuedbook ON member.cardnumber=issuedbook.membernumber WHERE issuedbook.booknumber='$booknumber' and issuedbook.RetrunStatus=0");

			if (mysqli_num_rows($sql1)>0) {
				$member=mysqli_fetch_assoc($sql1);
				$category=$member['categorycode'];
				$returndate=$member['ReturnDate'];
				$noofrenew=$member['noofrenew'];

				// Find the Item type (book or Magz).
				$sql2=mysqli_query($dbcon,"SELECT * FROM `catalog` WHERE `booknumber`='$booknumber'");
				$catalog=mysqli_fetch_assoc($sql2);
				$findtype=$catalog['itemtype'];
				
				// Find the Item type.
				$sql3=mysqli_query($dbcon,"SELECT * FROM `finerules` WHERE `category`='$category' && itemtype = '$findtype'");
				$finerules=mysqli_fetch_assoc($sql3);
				$renewalperiod=$finerules['renewalperiod'];
				$renewalallow=$finerules['renewalallow'];

				if ($noofrenew<$renewalallow) {
					$noofrenew=$noofrenew+1;
					$retrunstatus=0;
					//Calculate returnd date
					$returndate = date("Y-m-d", strtotime($renewalperiod."days", strtotime($returndate)));

					$result=mysqli_prepare($dbcon,"UPDATE `issuedbook` SET `ReturnDate`=?,`noofrenew`=? WHERE `booknumber`=? and `RetrunStatus`=?");
					mysqli_stmt_bind_param($result,'ssss',$returndate,$noofrenew,$booknumber,$retrunstatus);

					if (mysqli_stmt_execute($result)) {
						$error = "The $booknumber is renewed: <strong>$returndate</strong> ";		
						
					}else{
						$error='Something went wrong please try again' .mysqli_error($dbcon);			
					}				
				}else{
					$error = "<strong>Maximum</strong> number of times renew";					
				}
			}else {
				$error = "<strong>$booknumber </strong> already check in.";		
			}
		}else{
			$error = "Invalid <strong>authentication</strong>";	
		}
	}
	
$_SESSION['csrf_token']=$token;
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Renew a Book | Foundation Library Management System</title>
	<link rel="icon" href="img/logo.png" type="image/png">

	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
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
								<h2 class="title">Renew a Book</h2>
							</div>                                
						</div>
						<!-- /.row -->
						<div class="row breadcrumb-div">
							<div class="col-md-6">
								<ul class="breadcrumb">
									<li><a href="dashboard.php"><i class="fa fa-home"></i> Home /&nbsp;</a></li>
									<li><a href="#">Circulations /&nbsp; </a></li>
									<li class="active">Renew</li>
								</ul>
							</div>                               
						</div>
						<!-- /.row -->
					</div>
					<!-- /.container-fluid -->

					<div class="container">                 
						<form  name="signup" method="post">
							<br>
							<fieldset class="border">                                
								<div class="row p-2">
									<div class="col-sm-2 text-end">
										<label for="inputbook" class="form-label">Book ID:</label>
										<input type="hidden" name="csrf_token" value="<?php echo $token?>">
									</div>
									<div class="col-sm-4">
										<input type="text" class="form-control" id="inputbook" name="bookid" placeholder="Enter Book ID" required>
										<span class="text-success font-weight-bold"><?php echo $error?></span>
									</div>
								</div>		
								<div class="row p-2">
									<div class="col-sm-2 text-end">
									</div>
									<div class="col-sm-4">
										<button type="submit" class="btn btn btn-warning" name="btnrenew"><i class="bi bi-shuffle"></i> &nbsp;Renew</button>
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


