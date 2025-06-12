<?php
session_start();
error_reporting(0);
include('includes/config.php');

if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
    exit;
}

if (isset($_POST['btnsave'])) {
    if (isset($_SESSION['csrf_token']) && $_SESSION['csrf_token'] == $_POST['csrf_token']) {

        $image = $_FILES['image'];
        $type = $_POST['type'];
        $booknumber = $_POST['booknumber'];
        $itemtype = $_POST['itemtype'];
        $title = $_POST['title'];
        $isbn = $_POST['isbn'];
        $issn = $_POST['issn'];
        $author = $_POST['author'];
        $author2 = $_POST['author2'];
        $language = $_POST['language'];
        $checkedin = 1;

        // Check for duplicate book number
        $stmt = mysqli_prepare($dbcon, "SELECT * FROM ppt WHERE booknumber = ?");
        mysqli_stmt_bind_param($stmt, 's', $booknumber);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        $count = mysqli_stmt_num_rows($stmt);

        if ($count > 0) {
            $error = "Sorry, the Book Number '$booknumber' is already taken.";
        } else {
            if (empty($image['size'])) {
                $sql = "INSERT INTO ppt (booknumber, type, itemtype, title, isbn, issn, author, author2, language, checkedin) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($dbcon, $sql);
                mysqli_stmt_bind_param($stmt, 'sssssssssi', $booknumber, $type, $itemtype, $title, $isbn, $issn, $author, $author2, $language, $checkedin);
                if (mysqli_stmt_execute($stmt)) {
                    $msg = "PPT entry registered successfully. Book ID is <strong>$booknumber</strong>";
                } else {
                    $error = "Something went wrong. Please try again.";
                }
            } elseif ($image['size'] <= 200000) {
                $imagedetails = pathinfo($image['name']);
                $allowed_extensions = ['jpg', 'jpeg', 'png'];
                if (in_array(strtolower($imagedetails['extension']), $allowed_extensions)) {
                    $filepath = 'img/' . uniqid() . '.' . $imagedetails['extension'];
                    if (move_uploaded_file($image['tmp_name'], $filepath)) {
                        $sql = "INSERT INTO ppt (booknumber, type, itemtype, title, isbn, issn, author, author2, language, checkedin, image) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                        $stmt = mysqli_prepare($dbcon, $sql);
                        mysqli_stmt_bind_param($stmt, 'sssssssssss', $booknumber, $type, $itemtype, $title, $isbn, $issn, $author, $author2, $language, $checkedin, $filepath);
                        if (mysqli_stmt_execute($stmt)) {
                            $msg = "PPT entry registered successfully. Book ID is <strong>$booknumber</strong>";
                        } else {
                            $error = "Something went wrong. Please try again.";
                        }
                    } else {
                        $error1 = "Failed to upload the image.";
                    }
                } else {
                    $error1 = "Only JPG, JPEG, and PNG files are allowed.";
                }
            } else {
                $error1 = "Image must be less than 200 KB.";
            }
        }
    } else {
        $error = "Invalid authentication.";
    }   
}

// Delete Record	
	if($_GET['id']<>""){
		$id=$_GET['id'];
		mysqli_query($dbcon,"delete from ppt where id='$id'");
	}


$token = rand();
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
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                      <!-- Cataloging -->      
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
                            <div class="col-sm-2 text-end"></div>
                            <div class="col-sm-2 text-end">         
                            <input type="file" class="form-control" id="image" name="image">
                            </div>

                            <div class="col-sm-1 text-end"></div>
                            <div class="col-sm-1 text-end"></div>

                            
                        </div>

                        <div class="row p-2">  
                          <div class="col-sm-2 text-end"></div>
                          <div class="col-sm-4 text-danger">         
                            <span><?php echo $error1?></span>
                          </div>               
                        </div> 
                        <div class="row p-2">
                          <div class="col-sm-2 text-end">
                            <label for="inputtype" class="form-label">Type:</label>
                            </div>
                            <div class="col-sm-4">
                                <select id="type" class="form-select" name="type" required>
                                    <option value="" selected>Select Type</option>
                                    <option value="Research">Research</option>
                                    <option value="PPT">PPT</option> 
                                </select>
                            </div>
                        
                          <div class="col-sm-2 text-end">
                            <label for="inputTitle" class="form-label">Title:<i class="text-danger font-weight-bold">*</i></label>
                          </div>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" id="inputTitle" name="title" required>            
                          </div>
                        </div>

                        <div class="row p-2">
                          <div class="col-sm-2 text-end">
                            <label for="inputBarcode" class="form-label">Barcode:<i class="text-danger font-weight-bold">*</i></label>
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
                                          <thead>
                                              <tr class="text-center">
                                                  <th>Ser</th>
                                                  <th>Type</th>
                                                  <th>Item Type</th>
                                                  <th>Title</th>
                                                  <th>Book No</th>
                                                  <th>Language</th>
                                                  <th>Action</th> 
                                              </tr>
                                          </thead>
                                          <tbody class="table-group-divider">
                                              <?php
                                              $sql = "SELECT * FROM ppt ORDER BY id DESC";
                                              $result = mysqli_query($dbcon, $sql);
                                              if (mysqli_num_rows($result) > 0) {
                                                  $ser = 1;
                                                  while ($row = mysqli_fetch_assoc($result)) {
                                                      echo "<tr>";
                                                      echo "<td class='text-center'>" . $ser++ . "</td>";
                                                      echo "<td>" . htmlspecialchars($row['type']) . "</td>";
                                                      echo "<td>" . htmlspecialchars($row['itemtype']) . "</td>";
                                                      echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                                                      echo "<td>" . htmlspecialchars($row['booknumber']) . "</td>";
                                                      echo "<td>" . htmlspecialchars($row['language']) . "</td>";
                                                      echo "<td class='text-center'>
                                                              <a href='ppt-show.php?id=" . $row['id'] . "' class='btn btn-info btn-sm'><i class='bi bi-eye'></i></a>
                                                              <a href='ppt-download.php?id=" . $row['id'] . "' class='btn btn-success btn-sm'><i class='bi bi-download'></i></a>
                                                              <a href='edit-ppt.php?id=" . $row['id'] . "' class='btn btn-warning btn-sm'><i class='bi bi-pencil-square'></i></a>
                                                              <a href='ppt.php?id=" . $row['id'] . "' onclick=\"return confirm('Are you sure to delete?');\" class='btn btn-danger btn-sm'><i class='bi bi-trash3'></i></a>
                                                            </td>";
                                                      echo "</tr>";
                                                  }
                                              } else {
                                                  echo "<tr><td colspan='7' class='text-center'>No records found</td></tr>";
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
    	   
            
            <!-- <script src="js/search.js"></script> -->

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
