<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0)
    {   
header('location:index.php');
}
else{ 


?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="AS Indika" />
    <link rel="icon" href="img/logo.png" type="image/png">     
    <title>Manage Cataloging | Foundation Library Management System</title>

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
								<h2 class="title">Manage Cataloging</h2>
							</div>                                
						</div>
						<!-- /.row -->
						<div class="row breadcrumb-div">
							<div class="col-md-6">
								<ul class="breadcrumb">
									<li><a href="dashboard.php"><i class="fa fa-home"></i> Home /&nbsp;</a></li>
									<li><a href="#">Report /&nbsp; </a></li>
									<li class="active">Manage Cataloging</li>
								</ul>
							</div>                               
						</div>
						<!-- /.row -->
					</div>
					<!-- /.container-fluid -->
                <div class="content-wrapper">
                    <div class="container">  
                        <div class="row">
                            <div class="col-md-12">
                                <!-- Advanced Tables -->
                                <div class="panel panel-default">                                
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                             <table class="table table-sm table-striped table-bordered table-hover" id="dataTables">
                                                <thead >
                                                    <tr >
                                                        <th class="text-center">Ser</th>
                                                        <th class="text-center">Book ID</th>
                                                        <th class="text-center">Name</th>
                                                        <th class="text-center">Item Type</th>
                                                        <th class="text-center">ISBN </th>
                                                        <th class="text-center">Author</th>
                                                        <th class="text-center">DDC No</th>
                                                        <th class="text-center">Publisher</th>
                                                        <th class="text-center">Status</th>
                                                        <th class="text-center">Action</th>
                                                    </tr>
                                                </thead>
                                            <tbody>
                                                
                                            <?php 
                                            $query = mysqli_query($dbcon,"SELECT * from catalog");
                                            if(mysqli_num_rows($query) > 0)
                                            {
                                                
                                                while ($row=mysqli_fetch_array( $query)) 
                                                {  $cnt++;?>                                      
                                                        <tr class="odd gradeX">
                                                            <td class="text-center"><?php echo htmlentities($cnt);?></td>
                                                            <td><?php echo $row['booknumber'];?></td>
                                                            <td><?php echo $row['title'];?></td>
                                                            <td><?php echo $row['itemtype'];?></td>
                                                            <td><?php echo $row['isbn'];?></td>
                                                            <td><?php echo $row['author'];?></td>
                                                            <td><?php echo $row['classificationNo'].' '.$row['ItemNo'];?></td> 
                                                            <td><?php echo $row['publisher'];?></td>                                             
                                                            <td><?php if($row['status']=='A' && $row['checkedin']=='1')
                                                            {
                                                                echo "<span class='text-success font-weight-bold'><strong>Available</strong></span>";
                                                            } elseif($row['status']=='A' && $row['checkedin']=='0'){
                                                                echo "<span class='text-danger font-weight-bold'><strong>Not <br> Available</strong></span>";
                                                             }elseif($row['status']=='N') {
                                                                echo "<span class='text-danger font-weight-bold'><strong>Not <br> Available</strong></span>";
                                                            }elseif($row['status']=='L') {
                                                                echo htmlentities("Lost");
                                                            }elseif($row['status']=='D') {
                                                                echo htmlentities("Damage");
                                                            }                                                            
                                                        ?>                                                       
                                                        </td>

                                                        <td class="center">                                       
                                                            <a href="edit_cataloging.php?id=<?php echo htmlentities($row['booknumber']);?>" onclick="return confirm('Are you sure you want to edit this book?');">  <button class="btn btn-warning btn-sm"><i class="bi bi-pencil-square"></i>&nbsp;Edit</button>
                                          
                                            </td>
                                        </tr>
                                    <?php }} ?>                                      
                                    </tbody>
                                </table>
                            </div>                            
                        </div>
                    </div>
                    <!--End Advanced Tables -->
                </div>
            </div>            
        </div>
    </div>
     <!-- CONTENT-WRAPPER SECTION END-->
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
<?php } ?>
