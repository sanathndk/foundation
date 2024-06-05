<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0){   
    header('location:index.php');
}
else{ 
    if (isset($_GET['inactive'])) {
        $id=$_GET['inactive'];
        $status=0;
        $sql=mysqli_prepare($dbcon,"UPDATE `member` SET `status`=? WHERE `borrowernumber`=?");
        mysqli_stmt_bind_param($sql,'ss', $status, $id);

        if (mysqli_stmt_execute($sql)) {
            echo "<script>alert('Member Inactive Successfully')</script>";
        }else{
            echo "<script>alert('Something went wrong please try again')</script>";
        }
    }elseif(isset($_GET['active'])) {
        $id=$_GET['active'];
        $status=1;
        $sql=mysqli_prepare($dbcon,"UPDATE `member` SET `status`=? WHERE `borrowernumber`=?");
        mysqli_stmt_bind_param($sql,'ss', $status, $id);

        if (mysqli_stmt_execute($sql)) {
            echo "<script>alert('Member Activated Successfully')</script>";
        }else{
            echo "<script>alert('Something went wrong please try again')</script>";
        }
    }

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

    <title>Manage Registed Members | Foundation Library Management System</title>

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
								<h2 class="title">Manage Members</h2>
							</div>                                
						</div>
						<!-- /.row -->
						<div class="row breadcrumb-div">
							<div class="col-md-6">
								<ul class="breadcrumb">
									<li><a href="dashboard.php"><i class="fa fa-home"></i> Home /&nbsp;</a></li>
									<li><a href="#">Administration /&nbsp; </a></li>
									<li class="active">Manage Members</li>
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
                                            <table class="table table-striped table-bordered table-hover" id="dataTables">
                                                <thead>
                                                    <tr>
                                                        <th>Ser</th>
                                                        <th>Member ID</th>
                                                        <th>Salutation</th>
                                                        <th>Name </th>
                                                        <th>Mobile Number</th>
                                                        <th>Email</th>
                                                        <th>Category</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                            <tbody>
                                                
                                            <?php 
                                            $query = mysqli_query($dbcon,"SELECT * from member");
                                            if(mysqli_num_rows($query) > 0)
                                            {                                                
                                                while ($row=mysqli_fetch_array( $query)) 
                                                {  $cnt++;?>                                      
                                                        <tr>
                                                            <td class="text-center"><?php echo htmlentities($cnt);?></td>
											                <td><a href="edit_member.php?id=<?php echo $row['borrowernumber']?>"> <?php echo $row['cardnumber']?></a></td>
                                                            <td><?php echo $row['title'];?></td>
                                                            <td><?php echo $row['initials'].' '.$row['surname'];?></td>
                                                            <td><?php echo $row['mobile'];?></td>
                                                            <td><?php echo $row['email'];?></td>
                                                            <td><?php echo $row['categorycode'];?></td>                                             
                                                            <td><?php if($row['status']==1)
                                                            {
                                                                echo htmlentities("Active");
                                                            } else {
                                                            echo htmlentities("Inactive");}
                                                        ?></td>
                                                        <td class="center">
                                        <?php if($row['status']==1)
                                        {?>
                                        <a href="manage-member.php?inactive=<?php echo htmlentities($row['borrowernumber']);?>" onclick="return confirm('Are you sure you want to block this Member?');">  <button class="btn btn-danger"><i class="bi bi-toggle-off"></i>&nbsp;Inactive</button>
                                        <?php } else {?>

                                            <a href="manage-member.php?active=<?php echo htmlentities($row['borrowernumber']);?>" onclick="return confirm('Are you sure you want to active this Member?');"><button class="btn btn-primary"><i class="bi bi-toggle2-off"></i>&nbsp;Active</button> 
                                            <?php } ?>
                                          
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
