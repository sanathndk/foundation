<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0){   
    header('location:index.php');
}
else{ 
    $sysdate=date('Y-m-d');
   

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="AS Indika"/>
    <link rel="stylesheet" href="css/jquery.dataTables.min.css">  
    <link rel="icon" href="img/logo.png" type="image/png">

    <title>Overdue Book | Foundation Library Management System</title>

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
								<h2 class="title">Overdue Book</h2>
							</div>                                
						</div>
						<!-- /.row -->
						<div class="row breadcrumb-div">
							<div class="col-md-6">
								<ul class="breadcrumb">
									<li><a href="dashboard.php"><i class="fa fa-home"></i> Home /&nbsp;</a></li>
									<li><a href="#">Report /&nbsp; </a></li>
									<li class="active">Overdue Book</li>
								</ul>
							</div>                               
						</div>
						<!-- /.row -->
					</div>
					<!-- /.container-fluid -->
                <div class="content-wrapper">
                    <div class="container">  
                        <div class="row justify-content-md-center">
                            <div class="col-md-10">
                                <!-- Advanced Tables -->
                                <div class="panel panel-default">                                
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered table-hover" id="dataTables">
                                                <thead>
                                                    <tr>
                                                        <th>Ser</th>
                                                        <th>Book ID</th>
                                                        <th>Book Name</th>
                                                        <th>Member Name</th>
                                                        <th>ISBN </th>
                                                        <th>Issued Date</th>
                                                        <th>Return Date</th>
                                                    </tr>
                                                </thead>
                                            <tbody>
                                                
                                            <?php 

                                            $query = mysqli_query($dbcon,"SELECT * from `issuedbook_view` WHERE `RetrunStatus`=0 && `ReturnDate`<= '$sysdate'");
                                            if(mysqli_num_rows($query) > 0)
                                            {                                                
                                                while ($row=mysqli_fetch_array($query)) 
                                                {  $cnt++;?>                                      
                                                <tr>
                                                    <td class="text-center"><?php echo htmlentities($cnt);?></td>
                                                    <td><?php echo $row['booknumber'];?></td>
                                                    <td><?php echo $row['title'];?></td>                                                    
                                                    <td><?php echo $row['initials'].' '.$row['surname'];?></td>
                                                    <td><?php echo $row['isbn'];?></td>
                                                    <td><?php echo $row['IssuesDate'];?></td>
                                                    <td><?php echo $row['ReturnDate'];?></td>                                                                                        
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


