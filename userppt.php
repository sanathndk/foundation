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
        <title>Library System Dashboard</title>
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
                                <div class="col-sm-10">            
                                <table class="table table-striped table-bordered table-hover" id="dataTables">
                                    <thead>
                                    <tr class="text-center">
                                        <th>Ser</th>
                                        <th>Type</th>
                                        <th>Book ID</th>
                                        <th>Name</th>                                       
                                        <th>Author</th>
                                        <th>Language</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $cnt = 1;
                                    $query = mysqli_query($dbcon, "SELECT * FROM `ppt` ORDER BY `id` DESC");
                                    if (mysqli_num_rows($query) > 0) {
                                        while ($row = mysqli_fetch_assoc($query)) {
                                    ?>
                                    <tr>
                                        <td class="text-center"><?php echo $cnt++; ?></td>
                                        <td><?php echo htmlspecialchars($row['type']); ?></td>
                                        <td><?php echo htmlspecialchars($row['booknumber']); ?></td>
                                        <td><?php echo htmlspecialchars($row['title']); ?></td>                                        
                                        <td><?php echo htmlspecialchars($row['author']); ?></td>
                                        <td><?php echo htmlspecialchars($row['language']); ?></td>
                                        <td class="text-center">
                                        <a href="#" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#pptModal<?php echo $row['id']; ?>"><i class="bi bi-eye"></i></a>                                        
                                        <div class="modal fade" id="pptModal<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="pptModalLabel<?php echo $row['id']; ?>" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="pptModalLabel<?php echo $row['id']; ?>">Details</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <?php
                                                    // Load partial or embed content
                                                    include 'ppt-show.php?id=' . $row['id'];
                                                    ?>
                                                </div>
                                                </div>
                                            </div>
                                            </div>

                                        <!-- <a href="ppt-download.php?id=<?php echo $row['id']; ?>" class="btn btn-success btn-sm"><i class="bi bi-download"></i></a>
                                        <a href="edit-ppt.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm"><i class="bi bi-pencil-square"></i></a>
                                        <a href="ppt.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure to delete?');" class="btn btn-danger btn-sm"><i class="bi bi-trash3"></i></a>
                                        </td> -->
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
