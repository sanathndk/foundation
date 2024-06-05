<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0)
{   
	header('location:index.php');
}
else{ 
    $keyword=$_GET['keyword'];
   if (empty($keyword)) {
        echo "<script>alert('Enter key word);</script>";
   }else{
        $searchtype=$_GET['searchtype'];
       	$result=mysqli_query($dbcon,"SELECT * FROM catalog WHERE (title LIKE '%$keyword%' or booknumber LIKE '$keyword%' or author LIKE '%$keyword%' or author2 LIKE '%$keyword%' or isbn LIKE '%$keyword%' or publisher LIKE '%$keyword%')");     
   }
  

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">  
	<link rel="icon" href="img/logo.png" type="image/png">

	<title>Search | Foundation Library Management System</title>
</head>
<body class="top-navbar-fixed">
	<div class="main-wrapper">
		<!-- ========== TOP NAVBAR ========== -->
		<?php include('includes/usertopbar.php');?>   
		<!-----End Top bar-->
		<div class="content-wrapper">
			<div class="content-container">
			<!-- ========== LEFT SIDEBAR ========== -->
				<?php include('includes/userleftbar.php');?>                   
				<!-- /.left-sidebar -->
				<div class="main-page">

					<div class="container-fluid">
						<div class="row page-title-div">
							<div class="col-md-6">
								<h2 class="title">Search Results</h2>
							</div>                                
						</div>
						<!-- /.row -->
						<div class="row breadcrumb-div">
							<div class="col-md-6">
								<ul class="breadcrumb">
									<li><a href="userdashboard.php"><i class="fa fa-home"></i> Home /&nbsp;</a></li>
									<li class="active">Search</li>
								</ul>
							</div>                               
						</div>
						<!-- /.row -->
					</div>
					<div class="container">     
                        <div class="row justify-content-md-center">							
							<div class="col-sm-12">			
								<table class="table table-striped table-bordered table-hover align-middle table-responsive" id="dataTables">
																		
									<thead>
										<tr class="text-center">
											<th class="text-center">Ser</th>
											<th class="text-center">Book ID</th>
											<th class="text-center">Title</th>
											<th class="text-center">ISBN</th>
											<th class="text-center">Author</th>
											<th class="text-center">Publisher</th>
											<th class="text-center">Status</th>
										</tr>
									</thead>									
									<tbody class="table-group-divider">
									    <?php						
                                        if (mysqli_num_rows($result)>0) {
                                            $ser=1;
                                            while($row=mysqli_fetch_array($result)){
                                        ?>								
										<tr>
											<td class="text-center"><?php echo $ser++?></td>
											<td><?php echo $row['booknumber']?></td>
											<td><?php echo $row['title']?></td>
											<td><?php echo $row['isbn']?></td>
											<td ><?php echo $row['author']?></td>
											<td><?php echo $row['publisher']?></td>
                                            <?php
                                                if ($row['checkedin']==1) {
                                                    echo "<td class='text-success'><strong>Available</strong></<td>";
                                                }elseif ($row['checkedin']==0){
                                                    echo "<td class='text-danger'></strong>Not Available</strong></td>";
                                                }                                                
                                                ?>											
										</tr>														  											
									<?php
                                    }}?>
								</table>	 
							</div>														
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

	<script>
		new DataTable('#dataTables'); 		
	</script>
	
</body>
</html>
<?php 
mysqli_close($dbcon);
} ?>
