<?php
session_start();
error_reporting(0);
include('includes/config.php');
include('includes/activity.php');

logAction($dbcon, "View Ppt");
$token=rand();


if(strlen($_SESSION['alogin'])==0)
{   
	header('location:index.php');
}
else{
if (isset($_POST['btnsave'])) {
    if ($_SESSION['csrf_token']==$_POST['csrf_token']){
        // save records
        $image      = $_FILES['image'];
        $abstract   = $_FILES['abstract'];
        $type       = $_POST['type'];
        $category   = $_POST['category'];
        $title      = $_POST['title'];
        $description = $_POST['description'];
        $date       = $_POST['date']; 
        $year       = $_POST['year'];
        $booknumber = $_POST['booknumber'];
        $author     = $_POST['author'];
        $author2    = $_POST['author2'];
        $language   = $_POST['language'];
        $checkedin  = 1;

        $filepath = '';
        $abstractFilePath = '';
        $error = '';
        $error1 = '';

        // Check duplicate booknumber
        $stmt = mysqli_prepare($dbcon, "SELECT id FROM ppt WHERE booknumber = ?");
        mysqli_stmt_bind_param($stmt, 's', $booknumber);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        $count = mysqli_stmt_num_rows($stmt);
        mysqli_stmt_close($stmt);

        if ($count > 0) {
            $error = "Sorry, the Book Number '$booknumber' is already taken.";
        } else {
           echo "OOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOO";
            }

            // Upload image
            if (!empty($image['name'])) {
                if ($image['size'] <= 200000) { // 200 KB
                    $imageDetails = pathinfo($image['name']);
                    $allowedImageExt = ['jpg', 'jpeg', 'png'];
                    $imageExt = strtolower($imageDetails['extension']);

                    if (in_array($imageExt, $allowedImageExt)) {
                        $filepath = 'img/' . uniqid('image_') . '.' . $imageExt;
                        if (!move_uploaded_file($image['tmp_name'], $filepath)) {
                            $error1 = "Failed to upload the image.";
                        }
                    } else {
                        $error1 = "Only JPG, JPEG, and PNG files are allowed.";
                    }
                } else {
                    $error1 = "Image must be less than 200 KB.";
                }
            }

            // Upload ppt or pdf
                if (!empty($abstract['name'])) {
                $fileName = basename($abstract['name']);
                $fileTmp = $abstract['tmp_name'];
                $fileSize = $abstract['size'];
                $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                $allowed = ['pdf', 'ppt', 'pptx'];

                if (in_array($fileExt, $allowed)) {
                    $uploadDir = "uploads/";
                    $newFileName = uniqid('abstract_') . "." . $fileExt;
                    $uploadPath = $uploadDir . $newFileName;

                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }

                    if (move_uploaded_file($fileTmp, $uploadPath)) {
                        $abstractFilePath = $uploadPath; 
                    } else {
                        $error1 = "Failed to upload abstract file.";
                    }
                } else {
                    $error1 = "Only PDF, PPT, and PPTX files are allowed.";
                }
            }

            // Save record to database
            if (empty($error) && empty($error1)) {
                $sql = "INSERT INTO ppt 
                    (booknumber, type, category, title, description, publish_date, research_year, author, author2, language, checkedin, image, abstract)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

                $stmt = mysqli_prepare($dbcon, $sql);
                mysqli_stmt_bind_param($stmt, 'ssssssisssiss',
                    $booknumber, $type, $category, $title, $description, $date, $year,
                    $author, $author2, $language, $checkedin,
                    $filepath, $abstractFilePath
                );

                if (mysqli_stmt_execute($stmt)) {
                    $msg = "PPT entry registered successfully. Book ID is <strong>$booknumber</strong>";
                } else {
                    $error = "Database error: " . mysqli_error($dbcon);
                }

                mysqli_stmt_close($stmt);
            }
        }

    } 
}

// Delete Record	
	if($_GET['id']<>""){
		$id=$_GET['id'];
		mysqli_query($dbcon,"delete from ppt where id='$id'");
	}

// $token = rand();
$_SESSION['csrf_token'] = $token;

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">  
	<link rel="icon" href="img/logo.png" type="image/png">

	<title>Add Reserach & PPT |  Library Management System</title>
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
								<h2 class="title">Add Reserach & PPT</h2>
							</div>                                
						</div>
						<!-- /.row -->
						<div class="row breadcrumb-div">
							<div class="col-md-6">
								<ul class="breadcrumb">
									<li><a href="dashboard.php"><i class="fa fa-home"></i> Home /&nbsp;</a></li>
									<li><a href="#">Catalogs /&nbsp; </a></li>
									<li class="active">Add Reserach & PPT</li>
								</ul>
							</div>                               
						</div>
						<!-- /.row -->
					</div>
					<!-- /.container-fluid -->
                     <br>
                    <div class="container">
                    <form name="signup" method="post" onSubmit="return valid();" enctype="multipart/form-data">  
                            
                      <div class="border p-3">
                        <!-- <legend class="w-auto">Catalogue:</legend> -->
                        <div class="row p-2">  
                            <div class="col-sm-2 text-end"></div>                          
                        </div>
                        
                        <div class="row p-2">  
                            <div class="col-sm-2 text-end"></div>
                            <div class="col-sm-8 text-success">         
                                <span><?php echo $msg?></span>
                            </div>               
                         
                        
                        <div class="row p-2">  
                            <div class="col-sm-3 text-end">
                                <label for="abstract" class="form-label">Abstract (PDF or PPT):</label>
                            </div>
                            <div class="col-sm-2">
                                <input type="file" class="form-control" id="abstract" name="abstract" accept=".pdf,.ppt,.pptx">
                            </div>
  
                            <div class="col-sm-4 text-end">
                                <label for="image" class="form-label">Attachment:</label>
                            </div>
                              <div class="col-sm-2 text-end">         
                              <input type="file" class="form-control" id="image" name="image">
                            </div>
                        </div>
                            
                        <div class="row p-2"></div>   
                            <div class="col-sm-2 text-end">
                                <label for="inputtype" class="form-label">Type:</label>
                            </div>
                            <div class="col-sm-4">
                                <select id="type" class="form-select" name="type" onchange="getBarcode(this.value)">
                                    <option value="" selected>Select Type</option>
                                    <option value="Research">Research</option>
                                    <option value="PPT">PPT</option> 
                                </select>
                            </div>
                            <div class="col-sm-2 text-end">
                                <label for="inputCategory" class="form-label">Category:<i class="text-danger font-weight-bold">*</i></label>       
                            </div>
                            <div class="col-sm-4">        
                                <select id="inputCategory" class="form-select" name="category">
                                    <!-- Load Category type of Database -->
                                    <?php
                                        $sql=mysqli_query($dbcon, "SELECT * FROM `category`");

                                        if (mysqli_num_rows($sql)>0) {
                                        while ($row=mysqli_fetch_array($sql)) {
                                            echo "<option value='" . $row['categorycode'] . "'>" .$row['description'] . "(".$row['categorycode'].")"."</option>";
                                        }
                                        }
                                    
                                    ?>
                                </select>
                            </div>                            
                        </div>

                        <div class="row p-2">
                            <div class="col-sm-2 text-end">
                                <label for="inputTitle" class="form-label">Title:<i class="text-danger font-weight-bold">*</i></label>
                            </div>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" id="inputTitle" name="title" required>            
                            </div>

                            <div class="col-sm-2 text-end">
                              <label for="Inputdesc" class="form-label">Description:</label>
                            </div>
                            <div class="col-sm-4">
                              <input type="text" class="form-control" id="Inputdesc" name="description">
                            </div>
                        </div>
                            
                        <div class="row p-2">                           
                            <div class="col-sm-2 text-end">
                              <label for="Inputdate" class="form-label">Publish Date:</label>
                            </div>
                            <div class="col-sm-4">
                              <input type="date" class="form-control" id="Inputdate" name="date">
                            </div>

                            <div class="col-sm-2 text-end">
                              <label for="Inputyear" class="form-label">Research Year:</label>
                            </div>
                            <div class="col-sm-4">
                                <select name="year" class="form-control" id="year" name="year">
                                        <?php
                                        $currentYear = date("Y");
                                        for ($y = $currentYear; $y >= 1985; $y--) {
                                            echo "<option value='$y'>$y</option>";
                                        }
                                        ?>
                                </select>
                            </div>
                        </div>
        
                        <div class="row p-2">
                            <div class="col-sm-2 text-end">
                                <label for="inputBarcode" class="form-label" readonly>Barcode:<i class="text-danger font-weight-bold">*</i></label>
                            </div>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" id="inputBarcode" name="booknumber" required>
                                    <?php if (isset($error)): ?>
                                    <span class="badge bg-danger fs-6"><?php echo $error; ?></span>
                                    <?php endif; ?>
                            </div>
                       
                            <div class="col-sm-2 text-end">
                                <label for="inputauthor" class="form-label">Author:<i class="text-danger font-weight-bold">*</i></label>
                            </div>
                            <div class="col-sm-4">
                              <input type="search" name="author" class="form-control" title="Enter search keyword" id="author" required> 
                              <div id="resultauthor"></div>
                              <a href="add-author.php" target="_blank">Add Author</a>
                            </div>
                        </div>

                        <div class="row p-2">
                          <div class="col-sm-2 text-end">
                            <label for="x" class="form-label">Author 2:</label>
                          </div>
                          <div class="col-sm-4">
                            <input type="search" name="author2" class="form-control" title="Enter search keyword" id="author2">
                            <div id="resultauthor2"></div>
                          </div>
                        
                          <div class="col-sm-2 text-end">
                            <label for="inputLanguage" class="form-label">Language:<i class="text-danger font-weight-bold">*</i></label>
                          </div>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" id="inputLanguage" name="language" required> 
                          </div>
                        </div>  

						<div class="row p-2">
							<div class="col-sm-2 text-end">
							</div>
							<div class="col-sm-4">
                                <input type="hidden" name="csrf_token" value="<?php echo $token?>">  
								<button type="submit" class="btn btn-primary btn-md" name="btnsave"><i class="bi bi-printer"></i>&nbsp;Save</button>
							</div>
						</div>
					</fieldset>
					</form>	
                    </div>

						
                    <div class="row justify-content-md-center"> 
							<div class="col-sm-10">
								<h3>List of Research & PPT</h3>
							</div>
					</div>
						<div class="row justify-content-md-center">							
                            <div class="col-sm-10">			
                                      <table class="table table-striped table-bordered table-hover align-middle table-responsive" id="dataTables">										
                                            <thead class="text-center">
                                                <tr>
                                                    <th>Ser</th>
                                                    <th>Research No</th>
                                                    <th>Type</th>
                                                    <th>Category</th>
                                                    <th>Title</th>
                                                    <th>Description</th>
                                                    <th>Author</th>
                                                    <th>Language</th>
                                                    <th>Action</th> 
                                                </tr>
                                            </thead>
                                            <tbody class="table-group-divider">
                                                <?php
                                                
                                                $sql = " SELECT ppt.*, category.description AS category_desc FROM ppt LEFT JOIN category ON ppt.category = category.categorycode ORDER BY ppt.id DESC";
                                                $result = mysqli_query($dbcon, $sql);
                                                if (mysqli_num_rows($result) > 0) {
                                                    $ser = 1;
                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                        echo "<tr>";
                                                        echo "<td class='text-center'>" . $ser++ . "</td>";
                                                        echo "<td>" . htmlspecialchars($row['booknumber']) . "</td>";
                                                        echo "<td>" . htmlspecialchars($row['type']) . "</td>";
                                                        echo "<td>" . htmlspecialchars($row['category_desc']) . "</td>";
                                                        echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                                                        echo "<td>" . htmlspecialchars($row['description']) . "</td>";  
                                                        echo "<td>" . htmlspecialchars($row['author']) . "</td>";
                                                        echo "<td>" . htmlspecialchars($row['language']) . "</td>";
                                                        echo "<td class='text-center'>
                                                                
                                                                
                                                                <a href='ppt-download.php?id={$row['id']}&type=abstract' class='btn btn-success btn-sm'><i class='bi bi-download'></i></a>
                                                                <a href='edit-ppt.php?id=" . $row['id'] . "' class='btn btn-warning btn-sm'><i class='bi bi-pencil-square'></i></a>
                                                                <a href='ppt.php?id=" . $row['id'] . "' onclick=\"return confirm('Are you sure to delete?');\" class='btn btn-danger btn-sm'><i class='bi bi-trash3'></i></a>
                                                            </td>";
                                                        echo "</tr>";
                                                    }
                                                } else {
                                                    echo "<tr><td colspan='9' class='text-center'>No records found</td></tr>";
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                  </div>													
                                </div>
                          </div>		
                    <?php include('includes/footer.php');?>  
            </div> 			
        </div>
    </div>	
   
    <script>
        function getBarcode(type) {
            if (type == "") {
                document.getElementById("inputBarcode").value = "";
                return;
            }

            const xhttp = new XMLHttpRequest();
            xhttp.onload = function () {
                document.getElementById("inputBarcode").value = this.responseText;
            }
            xhttp.open("GET", "type_barcode.php?type=" + type, true);
            xhttp.send();
        }
    </script>

            <!-- <script src="js/search.js"></script> -->
<script src="js/search.js"></script>
<script src="js/search.js"></script>
<script src="js/jquery-3.7.0.js"></script>
<script src="js/jquery.dataTables.min.js"></script>
<script>
    new DataTable('#dataTables');
</script>

</body>
</html>
<?php 
mysqli_close($dbcon);
?>
<!-- <a href='ppt-show.php?id=" . $row['id'] . "' class='btn btn-info btn-sm'><i class='bi bi-eye'></i></a> -->
 <!-- <a href='ppt-download.php?id={$row['id']}&type=image' class='btn btn-info btn-sm'><i class='bi bi-file-image'></i></a> -->