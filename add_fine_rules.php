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
	// Save Record
	$category=$_POST['category'];
	$itemtype=$_POST['itemtype'];
	$checkoutallow=$_POST['checkoutallow']; 
	$library=$_POST['library'];
	$loanperiod=$_POST['loanperiod'];
	$fineamount=$_POST['fineamount'];
	$renewalallow=$_POST['renewalallow'];
	$renewalperiod=$_POST['renewalperiod'];			
		
		$sql="INSERT INTO `finerules`(`category`, `itemtype`, `checkoutallow`, `library`, `loanperiod`, `fineamount`, `renewalallow`, `renewalperiod`) VALUES (?,?,?,?,?,?,?,?)";
		$result=mysqli_prepare($dbcon, $sql);
		
		if ($result){
			mysqli_stmt_bind_param($result,'ssssssss',$category, $itemtype, $checkoutallow, $library, $loanperiod, $fineamount, $renewalallow, $renewalperiod);
			if (mysqli_stmt_execute($result)) {
				echo "Record updated successfully";
				header('location:add_fine_rules.php');
			} else{
				echo "Error inserting data: " .mysqli_error($dbcon);
			}
		} else{
			echo "Error Connection: " .mysqli_error($dbcon);
		}
		
	}	
	// Delete Record	
	if($_GET['id']<>""){
		$id=$_GET['id'];
		mysqli_query($dbcon,"delete from finerules where id='$id'");
	}	
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1"> 
    <link rel="icon" href="img/logo.png" type="image/png">

	<title>Add Fine Rules | Foundation Library Management System</title>
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
								<h2 class="title">Add Fine Rules</h2>
							</div>                                
						</div>
						<!-- /.row -->
						<div class="row breadcrumb-div">
							<div class="col-md-6">
								<ul class="breadcrumb">
									<li><a href="dashboard.php"><i class="fa fa-home"></i> Home /&nbsp;</a></li>
									<li><a href="#">Administration /&nbsp; </a></li>
									<li class="active">Add Fine Rules</li>
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
										<select name="category" id="patroncode" class="form-select" required>
											<option></option>
										<?php
											$sql1=mysqli_query($dbcon,"SELECT * FROM `membergroups`");
											if (mysqli_num_rows($sql1)>0) {
												while ($row1 = mysqli_fetch_assoc($sql1)) {													
													echo "<option value='$row1[categorycode]'>$row1[categorycode]</option>";
												}
											}
										?>
										</select>
									</div>
								
									<div class="col-sm-2 text-end">
										<label for="inputitemtype" class="form-label">Item type:</label>
									</div>
									<div class="col-sm-4">
										<select name="itemtype" id="itemtype" class="form-select" required>
											<option></option>
										<?php
											$sql2=mysqli_query($dbcon,"SELECT * FROM `itemtypes`");
											if (mysqli_num_rows($sql2)>0) {
												while ($row2 = mysqli_fetch_assoc($sql2)) {													
													echo "<option value='$row2[itemcode]'>$row2[description]</option>";
												}
											}
										?>
										</select>
									</div>
								</div>		

                                <div class="row p-2">
									<div class="col-sm-2 text-end">
										<label for="checkoutallow" class="form-label">Current checkouts allowed:</label>
									</div>
									<div class="col-sm-4">
										<input type="number" name="checkoutallow" class="form-control" id="checkoutallow" required>
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
										<input type="number" name="loanperiod" class="form-control" id="inputloanperiod" required>
									</div>
									
									<div class="col-sm-2 text-end">
										<label for="inputamount" class="form-label">Fine amount:</label>
									</div>
									<div class="col-sm-4">
										<input type="number" name="fineamount" class="form-control" id="inputamount" step="0.00" required>									
									</div>
								</div>	
								<div class="row p-2">
									<div class="col-sm-2 text-end">
										<label for="inputrenallowed" class="form-label">Renewals allowed:</label>
									</div>
									<div class="col-sm-4">
										<input type="number" name="renewalallow" class="form-control" id="inputrenallowed" required>
									</div>
									
									<div class="col-sm-2 text-end">
										<label for="inputrenperiod" class="form-label">Renewal period (Days):</label>
									</div>
									<div class="col-sm-4">
										<input type="number" name="renewalperiod" class="form-control" id="inputrenperiod" required>									
									</div>
								</div>	
								<div class="d-grid gap-2 d-md-flex justify-content-md-center">
                                    <div class="col-sm-1">     
                                        <button type="submit" class="btn btn-primary btn-md" name="btnsave"><i class="bi bi-cash-coin"></i> &nbsp;Save</button>
                                    </div>
                                </div>
					 
							</fieldset>
						</form>						
						<div class="row justify-content-md-center"> 
							<div class="col-sm-12">
								<h3>List of Fine Rules</h3>
							</div>
						</div>
						<div class="row justify-content-md-center">							
							<div class="col-sm-12">			
								<table class="table table-striped table-bordered table-hover align-middle table-responsive" id="dataTables">										
									<thead>
										<tr class="text-center">
											<th class="text-center">Ser</th>
											<th class="text-center">Code</th>
											<th class="text-center">Item type</th>
											<th class="text-center">Checkouts allowed</th>
											<th class="text-center">Loan period</th>
											<th class="text-center">Fine amount</th>
											<th class="text-center">Renewals allowed</th>
											<th class="text-center">Renewal period</th>
											<th class="text-center">Action</th>
										</tr>
									</thead>
									
									<tbody class="table-group-divider">
									<?php																

									// Retrieve data for the current page	
									$sql="SELECT * FROM `finerules`";
									$result= mysqli_query($dbcon, $sql);									
									
									if (mysqli_num_rows($result) > 0) {												
									$ser=1;					
									// output data of each row
									while($row = mysqli_fetch_assoc($result)) {
									?>
										<tr>
											<td class="text-center"><?php echo $ser++?></td>
											<td><?php echo $row["category"]?></td>
											<td><?php echo $row["itemtype"]?></td>
											<td class="text-center"><?php echo $row["checkoutallow"]?></td>
											<td class="text-center"><?php echo $row["loanperiod"]?></td>
											<td class="text-end"><?php echo $row["fineamount"]?></td>
											<td class="text-center"><?php echo $row["renewalallow"]?></td>
											<td class="text-center"><?php echo $row["renewalperiod"]?></td>
											<td class="text-center">
												<a href="edit_fine_rules.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm" name="btnedit"><i class="bi bi-pencil-square"></i>&nbsp;Edit</a>
												<a href="add_fine_rules.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are your sure Delete this record?');" class="btn btn-danger btn-sm" name="btndelete"><i class="bi bi-trash3"></i>&nbsp;Delete</a>
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