<?php
session_start();
error_reporting(0);
include('includes/config.php');
// if(strlen($_SESSION['alogin'])==0)
// {   
// 	header('location:index.php');
// }
// else{ 
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
    <meta name="author" content="AS Indika - Sri Lanka - 94716593406">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/main.css" media="screen" >
    <link rel="stylesheet" href="css/jquery.dataTables.min.css">   
    <link href="css/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
	<title>Search | Foundation Library Management System</title>

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
<body class="bg-dark">

<form method="get" action="opac.php">
    <div class="row align-items-center d-flex">
        <div class="col-2 d-flex"> 
            <a href="index.php" class="logo d-flex">
                <img src="img/logo.png" alt="Foundation" class="d-flex">
                <span class="d-none d-lg-block d-flex">Foundation</span>
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
                        <th class="text-center">Rack No</th>
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
						<td><?php echo $row['classificationNo'].' '.$row['ItemNo']?></td>
                        <?php
                            if ($row['checkedin']==1) {
                                echo "<td class='text-success'><strong>Available</strong></<td>";
                            }elseif ($row['checkedin']==0){
                                echo "<td class='text-danger'></strong>Not Available</strong></td>";
                            }                                                
                        ?>											
                    </tr>	
                    <?php
                        }
                    }
                    ?>														  											
                </tbody>									
            </table>	 
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
?>
