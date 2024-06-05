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

        $result=mysqli_query($dbcon,"SELECT * FROM `finerules` WHERE id=$id");
    	$rul=mysqli_fetch_assoc($result);

    }
	if(isset($_POST['btnsave'])){
        // Save Record
        $category=$_POST['category'];
        $itemtype=$_POST['itemtype'];
        $checkoutallow=$_POST['checkoutallow']; 
        $library=$_POST['library'];
        $loanperiod=$_POST['loanperiod'];
        $fineamount=$_POST['fineamount'];
        $renewalallow=$_POST['renewalallow'];
        $renewalperiod=$_POST['renewalperiod'];			
		
        
		$sql="UPDATE `finerules` SET `checkoutallow`=?,`library`=?,`loanperiod`=?,`fineamount`=?,`renewalallow`=?,`renewalperiod`=? WHERE `id`=?";
		$result=mysqli_prepare($dbcon, $sql);
		
		if ($result){
			mysqli_stmt_bind_param($result,'sssssss',$checkoutallow, $library, $loanperiod, $fineamount, $renewalallow, $renewalperiod, $id);
			if (mysqli_stmt_execute($result)) {
				echo "<script>alert('Record Updated Successfully'); window.location='add_fine_rules.php'</script>";
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

	<title>Edit Fine Rules | Foundation Library Management System</title>
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
								<h2 class="title">Edit Fine Rules</h2>
							</div>                                
						</div>
						<!-- /.row -->
						<div class="row breadcrumb-div">
							<div class="col-md-6">
								<ul class="breadcrumb">
									<li><a href="dashboard.php"><i class="fa fa-home"></i> Home /&nbsp;</a></li>
									<li><a href="#">Administration /&nbsp; </a></li>
									<li class="active">Edit Fine Rules</li>
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
										<label for="inputcategorycode" class="form-label">Member category:</label>
									</div>
									<div class="col-sm-4"> 
										<select name="category" id="patroncode" class="form-select" disabled required>
											<option value="<?php echo $rul['category']?>"><?php echo $rul['category']?></option>										
										</select>
									</div>
								
									<div class="col-sm-2 text-end">
										<label for="inputitemtype" class="form-label">Item type:</label>
									</div>
									<div class="col-sm-4">
										<select name="itemtype" id="itemtype" class="form-select" disabled  required>
											<option value="<?php echo $rul['itemtype'];?>"><?php echo $rul['itemtype'];?></option>										
										</select>
									</div>
								</div>		

                                <div class="row p-2">
									<div class="col-sm-2 text-end">
										<label for="checkoutallow" class="form-label">Current checkouts allowed:</label>
									</div>
									<div class="col-sm-4">
										<input type="number" name="checkoutallow" class="form-control" id="checkoutallow" value="<?php echo $rul['checkoutallow'];?>" required>                                
                                    </div>
									
									<div class="col-sm-2 text-end">
										<label for="library" class="form-label">Library limitations:</label>
									</div>
									<div class="col-sm-4">

									<select id="library" class="form-select" name="library" required>
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
								<div class="row p-2">
									<div class="col-sm-2 text-end">
										<label for="inputloanperiod" class="form-label">Loan period (Days):</label>
									</div>

									<div class="col-sm-4">
										<input type="number" name="loanperiod" class="form-control" id="inputloanperiod" value="<?php echo $rul['loanperiod'];?>" required>
                                    </div>
									
									<div class="col-sm-2 text-end">
										<label for="inputamount" class="form-label">Fine amount:</label>
									</div>
									<div class="col-sm-4">
										<input type="number" name="fineamount" class="form-control" id="inputamount" step="0.00" value="<?php echo $rul['fineamount'];?>" required>									
									</div>
								</div>	
								<div class="row p-2">
									<div class="col-sm-2 text-end">
										<label for="inputrenallowed" class="form-label">Renewals allowed:</label>
									</div>
									<div class="col-sm-4">
										<input type="number" name="renewalallow" class="form-control" id="inputrenallowed" value="<?php echo $rul['renewalallow'];?>" required>
									</div>
									
									<div class="col-sm-2 text-end">
										<label for="inputrenperiod" class="form-label">Renewal period (Days):</label>
									</div>
									<div class="col-sm-4">
										<input type="number" name="renewalperiod" class="form-control" id="inputrenperiod" value="<?php echo $rul['renewalperiod'];?>" required>									
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
	
</body>
</html>
<?php 
mysqli_close($dbcon);
} ?>