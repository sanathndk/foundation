<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['alogin'])==0)
{   
header("Location: index.php"); 
}
else{
    ?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Foundation Library System Dashboard</title>
        <link rel="icon" href="img/logo.png" type="image/png">

    </head>
    <body class="top-navbar-fixed">
        <div class="main-wrapper">
            <?php include('includes/usertopbar.php');?>
            <div class="content-wrapper">
                <div class="content-container">
                    <?php include('includes/userleftbar.php');?>  
                    <div class="main-page">
                        <div class="container-fluid">
                            <div class="row page-title-div">
                                <div class="col-sm-8">
                                    <h2 class="title">Dashboard</h2>                                  
                                </div>
                                <!-- /.col-sm-8 -->
                             </div>
                            <!-- /.row -->                      
                        </div>
                        <!-- /.container-fluid -->
                        <div class="content-wrapper">
                    <div class="container">  
                        <div class="row justify-content-md-center">
                            <div class="col-md-12">
                                <!-- Advanced Tables -->
                                <div class="panel panel-default">                                
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered table-hover" id="dataTables">
                                                <thead>
                                                    <tr>
                                                        <th>Ser</th>
                                                        <th>Book Name</th>
                                                        <th>Book ID</th>
                                                        <th>ISBN </th>
                                                        <th>Issued Date</th>
                                                        <th>Return Date</th>
                                                        <th>Fines</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                            <tbody>
                                                
                                            <?php
                                            $query = mysqli_query($dbcon,"SELECT * FROM `issuedbook_view` WHERE `userid`='$_SESSION[user_id]' ORDER BY `RetrunStatus` ASC");
                                            if(mysqli_num_rows($query) > 0)
                                            {                                                
                                                while ($row=mysqli_fetch_array($query)) 
                                                {  $cnt++;?>                                      
                                                <tr>
                                                    <td class="text-center"><?php echo htmlentities($cnt);?></td>
                                                    <td><?php echo $row['title'];?></td>
                                                    <td><?php echo $row['booknumber']?></td>
                                                    <td><?php echo $row['isbn'];?></td>
                                                    <td><?php echo $row['IssuesDate'];?></td>
                                                    <td><?php echo $row['ReturnDate'];?></td>
                                                    <td><?php echo $row['fine'];?></td>
                                                    <!-- <td><?php echo $row['categorycode'];?></td>                                              -->
                                                    <td><?php if($row['RetrunStatus']==1)   
                                                    {
                                                        echo htmlentities("Check in");
                                                    } else {
                                                        echo htmlentities("Checkouts");}?></td>                                          
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
                    <!-- /.main-page -->                    
                </div>
                <!-- /.content-container -->
            </div>
            <!-- /.content-wrapper -->
        </div>
        <?php include('includes/footer.php');?>   
	<!-- <script src="js/search.js"></script> -->

	<script src="js/jquery-3.7.0.js"></script>
	<script src="js/jquery.dataTables.min.js"></script>

	<script>
		new DataTable('#dataTables');  
		</script>    
    </body>
</html>
<?php } ?>
