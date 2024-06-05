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
        if ($searchtype=='catalog') {
       		$result=mysqli_query($dbcon,"SELECT * FROM catalog WHERE (title LIKE '%$keyword%' or booknumber LIKE '$keyword%' or author LIKE '%$keyword%' or author2 LIKE '%$keyword%' or isbn LIKE '%$keyword%' or publisher LIKE '%$keyword%')");     
		}elseif($searchtype=='member'){
			$result=mysqli_query($dbcon,"SELECT * FROM member WHERE (surname LIKE '%$keyword%' or cardnumber LIKE '$keyword%' or firstname LIKE '%$keyword%' or middle_name LIKE '%$keyword%' or othernames LIKE '%$keyword%' or initials LIKE '%$keyword%')");    
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

	<title>Search | Foundation Library Management System</title>
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
								<h2 class="title">Search Results</h2>
							</div>                                
						</div>
						<!-- /.row -->
						<div class="row breadcrumb-div">
							<div class="col-md-6">
								<ul class="breadcrumb">
									<li><a href="dashboard.php"><i class="fa fa-home"></i> Home /&nbsp;</a></li>
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
									<?php
        							if ($searchtype=='catalog') {									
									?>										
									<thead>
										<tr class="text-center">
											<th class="text-center">Ser</th>
											<th class="text-center">Book ID</th>
											<th class="text-center">Title</th>
											<th class="text-center">DDC No</th>
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
											<td><a href="edit_cataloging.php?id=<?php echo $row['booknumber']?>"> <?php echo $row['booknumber']?></a></td>
											<td><?php echo $row['title']?></td>
											<td><?php echo $row['classificationNo'].' '.$row['ItemNo']?></td>
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
									</tbody>
									<?php
											}
											} 
										}elseif($searchtype=='member'){
										?>	
										<thead>
										<tr class="text-center">
											<th class="text-center">Ser</th>
											<th class="text-center">Rank</th>
											<th class="text-center">Member ID</th>
											<th class="text-center">Name with Initials</th>
											<th class="text-center">Other Name</th>
											<th class="text-center">Contac No</th>
											<th class="text-center">Email</th>

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
											<td><?php echo $row['title']?></td>
											<td><a href="edit_member.php?id=<?php echo $row['borrowernumber']?>"> <?php echo $row['cardnumber']?></a></td>
											<td><?php echo $row['initials'].' '.$row['surname'] ?></td> 
											<td><?php echo $row['firstname'].' '.$row['middle_name'].' '.$row['othernames'] ?></td> 
											<td ><?php echo $row['mobile']?></td>
											<td><?php echo $row['email']?></td>                                           											
										</tr>															  											
									</tbody>
										<?php
										}}
									}?>
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
