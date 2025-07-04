<?php
session_start();
error_reporting(0);
include('includes/config.php');

// if(strlen($_SESSION['alogin'])==0)
// {   
// 	header('location:index.php');
// }
// else{ 

    // search keyword
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
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">  
	<link rel="icon" href="img/logo.png" type="image/png">
    <meta name="author" content="AS Indika - Sri Lanka - 94716593406">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/main.css" media="screen" >
    <link rel="stylesheet" href="css/jquery.dataTables.min.css">   
    <link href="css/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
	<title>Search | Library Management System</title>

    <style>
.logo {
  line-height: 1;
}

@media (min-width: 1200px) {
  .logo {
    width: 280px;
  }
}

.logo img {
  max-height: 26px;
  margin-right: 6px;
}

.logo span {
  font-size: 26px;
  font-weight: 700;
  color: #ffffff;
  font-family: "Nunito", sans-serif;
}
</style>
</head>

<body class="bg-dark text-light">
<form method="get" action="opac.php">
    <div class="row align-items-center d-flex">
        <div class="col-2 d-flex"> 
            <a href="index.php" class="logo d-flex">
                <img src="img/logo.png" alt="Foundation" class="d-flex">
                <span class="d-none d-lg-block d-flex">DSCSC</span>
            </a>
        </div>
        
        <div class="col-md-5 offset-md-2 d-flex">
            <input type="search" name="keyword" class="form-control d-flex" placeholder="Enter search keyword" title="Enter search keyword">    
            <div class="col-md-1 d-flex"><a href="loging.php" class="btn text-light d-flex"><i class="bi bi-person-lock"></i>&nbsp;Loging</a> </div>            
        </div>
    </div>
</div>
</form>
	<!-- <div class="main-wrapper"> -->

<div class="container-fluid bg-light">
    <div class="row page-title-div">
        <div class="col-md-6">
            <h2 class="title">Search Results</h2>
        </div>                                
    </div>		

<!-- Results Section -->
<div class="container bg-light text-dark rounded p-5 mt-2">

    <h4 class="mb-4">Search Results for: <em><?php echo htmlspecialchars($keyword); ?></em></h4>

    <!-- Books Catalog Results -->
    <?php if ($catalog_result && mysqli_num_rows($catalog_result) > 0): ?>
        <h5 class="text-primary">Book Catalog</h5>
        <table id="dataTables" class="table table-bordered">
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
                <?php $i=1; while($row = mysqli_fetch_assoc($catalog_result)): ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo htmlspecialchars($row['booknumber']); ?></td>
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><?php echo htmlspecialchars($row['isbn']); ?></td>
                        <td><?php echo htmlspecialchars($row['author']); ?></td>
                        <td><?php echo htmlspecialchars($row['publisher']); ?></td>
                        <td><?php echo htmlspecialchars($row['classificationNo'] . ' ' . $row['ItemNo']); ?></td>
                        <td>
                            <?php echo ($row['checkedin']) 
                                ? '<span class="text-success fw-bold">Available</span>' 
                                : '<span class="text-danger fw-bold">Not Available</span>'; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <!-- PPT/Research Results -->
    <?php if ($ppt_result && mysqli_num_rows($ppt_result) > 0): ?>
        <h5 class="text-success mt-5"> Research / PPT</h5>
        <table id="dataTables" class="table table-bordered">
            <thead>
                <tr class="table-secondary">
                    <th>Ser</th>
                    <th>Book ID</th>
                    <th>Type</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Author</th>
                    <th>Language</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $j=1; while($row = mysqli_fetch_assoc($ppt_result)): ?>
                    <tr>
                        <td><?php echo $j++; ?></td>
                        <td><?php echo htmlspecialchars($row['booknumber']); ?></td>
                        <td><?php echo htmlspecialchars($row['type']); ?></td>
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><?php echo htmlspecialchars($row['description']); ?></td>
                        <td><?php echo htmlspecialchars($row['author']); ?></td>
                        <td><?php echo htmlspecialchars($row['language']); ?></td>
                        <td class="text-center">
                            <a href='ppt-searchshow.php?id=<?php echo $row['id']; ?>' class='btn btn-info btn-sm'><i class='bi bi-eye'></i></a>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <?php if ((!$catalog_result || mysqli_num_rows($catalog_result) == 0) && (!$ppt_result || mysqli_num_rows($ppt_result) == 0)): ?>
        <div class="alert alert-warning text-center mt-4">
            No results found for "<strong><?php echo htmlspecialchars($keyword); ?></strong>".
        </div>
    <?php endif; ?>
<?php include('includes/footer.php');?>
</div>
</div>

    <script src="js/jquery-3.7.0.js"></script>
    <script src="js/jquery.dataTables.min.js"></script>
    <script src="js/dataTables.buttons.min.js"></script>
    <script src="js/buttons.print.min.js"></script>
    <script src="js/jszip.min.js"></script>
    <script src="js/pdfmake.min.js"></script>
    <script src="js/vfs_fonts.js"></script>
    <script src="js/buttons.html5.min.js">

		// new DataTable('#dataTables');   
        $(document).ready(function() {
        $('#dataTables').DataTable({
            dom: 'Bfrtip', // layout for buttons
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });
    });

    // table.buttons().container()
    // .appendTo( '#dataTables .col-md-6:eq(0)' );
</script>
</body>
</html>
<?php 
mysqli_close($dbcon);
?>

