<?php
session_start();
error_reporting(0);
include('includes/config.php');
include('includes/activity.php');

logAction($dbcon, "View_User_ppt");

if(strlen($_SESSION['alogin'])==0)
{   
header("Location: index.php"); 
}
else{
    ?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Library System Dashboard</title>
        <link rel="icon" href="img/logo.png" type="image/png">
        <meta name="viewport" content="width=device-width, initial-scale=1">

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
                                    <h2 class="title">Research & PPT </h2>                                  
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
                                <div class="row justify-content-md-center">	
                                <div class="col-sm-11">            
                                <table class="table table-striped table-bordered table-hover" id="dataTables">
                                    <thead>
                                    <tr class="text-center">
                                        <th>Ser</th>
                                        <th>Book No</th>
                                        <th>Type</th>
                                        <th>Category</th>                                       
                                        <th>Title</th>
                                        <th>Description</th>
                                        <th>Author</th>
                                        <th>Language</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $cnt = 1;
                                    $query = mysqli_query($dbcon, "
                                                SELECT ppt.*, category.description AS category_desc 
                                                FROM ppt 
                                                LEFT JOIN category ON ppt.category = category.categorycode 
                                                ORDER BY ppt.id DESC
                                            ");
                                    if (mysqli_num_rows($query) > 0) {
                                        while ($row = mysqli_fetch_assoc($query)) {
                                    ?>
                                    <tr>
                                        <td class="text-center"><?php echo $cnt++; ?></td>
                                        <td><?php echo htmlspecialchars($row['booknumber']); ?></td>
                                        <td><?php echo htmlspecialchars($row['type']); ?></td>
                                        <td><?php echo htmlspecialchars($row['category_desc']); ?></td>                                        
                                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                                        <td><?php echo htmlspecialchars($row['description']); ?></td>
                                        <td><?php echo htmlspecialchars($row['author']); ?></td>
                                        <td><?php echo htmlspecialchars($row['language']); ?></td>
                                        <td class="text-center">

                                            <a href='ppt-show.php?id=<?php echo $row['id']; ?>' class='btn btn-info btn-sm'><i class='bi bi-eye'></i></a>
                                </div>
                                    </tr>
                                    <?php
                                        }
                                    } else {
                                        echo '<tr><td colspan="6" class="text-center">No records found</td></tr>';
                                    }
                                    ?>
                                    </tbody>
                                </table>
                                </div>
                            </div>                            
                        </div>
                    </div>
                    <!--End Advanced Tables -->
                </div>
                </div>

                    <?php include('includes/footer.php');?>   
                    </div>
                    <!-- /.main-page -->                    
                </div>
                <!-- /.content-container -->
            </div>
            <!-- /.content-wrapper -->
        </div>
        
	<!-- <script src="js/search.js"></script> -->

	<!-- load once, after the table -->
<script src="js/jquery-3.7.0.js"></script>
<script src="js/jquery.dataTables.min.js"></script>
<script>
  new DataTable('#dataTables');
</script>
    </body>
</html>
<?php } ?>
