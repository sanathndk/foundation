<?php
session_start();
error_reporting(0);
include('includes/config.php');

// Search keyword
$keyword = isset($_GET['keyword']) ? mysqli_real_escape_string($dbcon, trim($_GET['keyword'])) : '';

$catalog_result = $ppt_result = null;

if (!empty($keyword)) {
    // Search in catalog
    $catalog_sql = "SELECT * FROM catalog 
                    WHERE title LIKE '%$keyword%' 
                    OR booknumber LIKE '%$keyword%' 
                    OR author LIKE '%$keyword%' 
                    OR author2 LIKE '%$keyword%' 
                    OR isbn LIKE '%$keyword%' 
                    OR publisher LIKE '%$keyword%'";
    $catalog_result = mysqli_query($dbcon, $catalog_sql);

    // Search in ppt
    $ppt_sql = "SELECT * FROM ppt 
                WHERE title LIKE '%$keyword%' 
                OR booknumber LIKE '%$keyword%' 
                OR type LIKE '%$keyword%' 
                OR author LIKE '%$keyword%' 
                OR language LIKE '%$keyword%'";
    $ppt_result = mysqli_query($dbcon, $ppt_sql);
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Search | Library Management System</title>
    <link rel="icon" href="img/logo.png" type="image/png">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/main.css" media="screen" >
    <link rel="stylesheet" href="css/jquery.dataTables.min.css">   
    <link href="css/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-dark text-light">
<form method="get" action="opac.php">
    <div class="row align-items-center d-flex p-3">
        <div class="col-2 d-flex"> 
            <a href="index.php" class="logo d-flex">
                <img src="img/logo.png" alt="Foundation" class="d-flex">
                <span class="d-none d-lg-block d-flex">DSCSC</span>
            </a>
        </div>
        
        <div class="col-md-5 offset-md-2 d-flex">
            <input type="search" name="keyword" class="form-control d-flex" placeholder="Enter search keyword" value="<?php echo htmlspecialchars($keyword); ?>">    
            <div class="col-md-1 d-flex"><a href="loging.php" class="btn text-light d-flex"><i class="bi bi-person-lock"></i>&nbsp;Login</a> </div>            
        </div>
    </div>
</form>

<div class="container-fluid bg-light text-dark p-4 rounded">
    <h4 class="mb-4">Search Results for: <em><?php echo htmlspecialchars($keyword); ?></em></h4>

    <!-- Books Catalog Results -->
    <?php if ($catalog_result && mysqli_num_rows($catalog_result) > 0): ?>
        <h5 class="text-primary">Book Catalog</h5>
        <table id="catalogTable" class="table table-bordered">
            <thead>
                <tr class="table-secondary">
                    <th>Ser</th>
                    <th>Book ID</th>
                    <th>Title</th>
                    <th>ISBN</th>
                    <th>Author</th>
                    <th>Publisher</th>
                    <th>Rack No</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php $ser=1; while($row = mysqli_fetch_assoc($catalog_result)): ?>
                    <tr>
                        <td class="text-center"><?php echo $ser++?></td>
                        <td><?php echo $row['booknumber']?></td>
                        <td><?php echo $row['title']?></td>
                        <td><?php echo $row['isbn']?></td>
                        <td><?php echo $row['author']?></td>
                        <td><?php echo $row['publisher']?></td>
                        <td><?php echo $row['classificationNo'].' '.$row['ItemNo']?></td>
                        <?php
                            if ($row['checkedin']==1) {
                                echo "<td class='text-success'><strong>Available</strong></td>";
                            } else {
                                echo "<td class='text-danger'><strong>Not Available</strong></td>";
                            }
                        ?>                                              
                    </tr>    
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <!-- PPT/Research Results -->
    <?php if ($ppt_result && mysqli_num_rows($ppt_result) > 0): ?>
        <h5 class="text-primary mt-5">Research & PPT</h5>
        <table id="pptTable" class="table table-bordered">
            <thead>
                <tr class="table-secondary">
                    <th>Ser</th>
                    <th>PPT ID</th>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Author</th>
                    <th>Language</th>
                    <th>Year</th>
                </tr>
            </thead>
            <tbody>
                <?php $ser=1; while($row = mysqli_fetch_assoc($ppt_result)): ?>
                    <tr>
                        <td class="text-center"><?php echo $ser++?></td>
                        <td><?php echo $row['booknumber']?></td>
                        <td><?php echo $row['title']?></td>
                        <td><?php echo $row['type']?></td>
                        <td><?php echo $row['author']?></td>
                        <td><?php echo $row['language']?></td>
                        <td><?php echo $row['research_year']?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>

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

<script>
$(document).ready(function() {
    $('#catalogTable, #pptTable').DataTable({
        dom: 'Bfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
    });
});
</script>
</body>
</html>
<?php 
mysqli_close($dbcon);
?>
