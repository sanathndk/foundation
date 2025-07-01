<?php
session_start();
error_reporting(1);
include('includes/config.php');

if(strlen($_SESSION['alogin'])==0) {   
    header('location:index.php');    
}

$id = intval($_GET['id']);
$sql = mysqli_query($dbcon, "SELECT * FROM ppt WHERE id = $id");
$row = mysqli_fetch_assoc($sql);

if (isset($_POST['btnsave'])) {
    // save data
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

    // old file paths initially
    $filepath = $row['image'];
    $abstractFilePath = $row['abstract'];

    $error = '';

    // new image uploaded, process it
    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image'];
        if ($image['size'] <= 200000) {
            $imageDetails = pathinfo($image['name']);
            $imageExt = strtolower($imageDetails['extension']);
            $allowedImageExt = ['jpg', 'jpeg', 'png'];

            if (in_array($imageExt, $allowedImageExt)) {
                $newImagePath = 'img/' . uniqid('image_') . '.' . $imageExt;
                if (move_uploaded_file($image['tmp_name'], $newImagePath)) {
                    $filepath = $newImagePath;
                } else {
                    $error = "Failed to upload image.";
                }
            } else {
                $error = "Only JPG, JPEG, and PNG files are allowed for images.";
            }
        } else {
            $error = "Image size must be under 200KB.";
        }
    }

    //  new abstract uploaded, process it
    if (empty($error) && !empty($_FILES['abstract']['name'])) {
        $abstract = $_FILES['abstract'];
        $fileDetails = pathinfo($abstract['name']);
        $fileExt = strtolower($fileDetails['extension']);
        $allowed = ['pdf', 'ppt', 'pptx'];

        if (in_array($fileExt, $allowed)) {
            $newAbstractPath = 'uploads/' . uniqid('abstract_') . '.' . $fileExt;
            if (move_uploaded_file($abstract['tmp_name'], $newAbstractPath)) {
                $abstractFilePath = $newAbstractPath;
            } else {
                $error = "Failed to upload abstract file.";
            }
        } else {
            $error = "Only PDF, PPT, and PPTX files are allowed.";
        }
    }

            $sql_update = "UPDATE ppt SET
            type=?, category=?, title=?, description=?, publish_date=?,
            research_year=?, booknumber=?, author=?, author2=?, language=?, checkedin=?,
            image=?, abstract=? WHERE id=?";

            $stmt = mysqli_prepare($dbcon, $sql_update);
            if (!$stmt) {
                die("Prepare failed: " . mysqli_error($dbcon));
            }

            mysqli_stmt_bind_param($stmt, 'sssssissssissi',
                $type, $category, $title, $description, $date, $year, $booknumber,
                $author, $author2, $language, $checkedin,
                $filepath, $abstractFilePath, $id);

            if (mysqli_stmt_execute($stmt)) {
                header('Location: ppt.php');
                exit;
            } else {
                die("Execute failed: " . mysqli_stmt_error($stmt));
            }

}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1"> 
	<link rel="icon" href="img/logo.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Library Management System | Update Research and PPT</title>
</head>

<body class="top-navbar-fixed">
	<div class="main-wrapper">
		<?php include('includes/topbar.php');?>   
		<div class="content-wrapper">
			<div class="content-container">
				<?php include('includes/leftbar.php');?>                   
				<div class="main-page">

					<div class="container-fluid">
						<div class="row page-title-div">
							<div class="col-md-6">
								<h2 class="title">Update Research and PPT</h2>
							</div>                                
						</div>

						<div class="row breadcrumb-div">
							<div class="col-md-6">
								<ul class="breadcrumb">
									<li><a href="dashboard.php"><i class="fa fa-home"></i> Home /&nbsp;</a></li>
									<li><a href="ppt.php">Research and PPT /&nbsp;</a></li>
									<li class="active">Update Research and PPT</li>
								</ul>
							</div>                               
						</div>
					</div>

                    <?php if (!empty($msg)): ?>
                        <div class="alert alert-success"><?php echo $msg; ?></div>
                    <?php elseif (!empty($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <div class="container">                 
						<form name="signup" method="post" enctype="multipart/form-data"> 
							<br>
							<fieldset class="border">  
                                <div class="row p-2">  
                                    <div class="col-sm-3 text-end">
                                        <label for="abstract" class="form-label">Abstract (PDF or PPT):</label>
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="file" class="form-control" name="abstract" accept=".pdf,.ppt,.pptx">
                                        <?php if(!empty($row['abstract'])): ?>
                                            <small>Current: <a href="<?php echo htmlspecialchars($row['abstract']); ?>" target="_blank">View file</a></small>
                                        <?php endif; ?>
                                    </div>
        
                                    <div class="col-sm-4 text-end">
                                        <label for="image" class="form-label">Attachment:</label>
                                    </div>
                                    <div class="col-sm-2 text-end">         
                                        <input type="file" class="form-control" id="image" name="image" accept=".jpg,.jpeg,.png">
                                        <?php if(!empty($row['image'])): ?>
                                            <small>Current: <a href="<?php echo htmlspecialchars($row['image']); ?>" target="_blank">View image</a></small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            
                                <div class="row p-2">  
                                    <div class="col-sm-2 text-end">
                                        <label for="type" class="form-label">Type:</label>
                                    </div>
                                    <div class="col-sm-4">
                                        <select id="type" class="form-select" name="type" required>
                                            <option value="" disabled>Select Type</option>
                                            <option value="Research" <?php if($row['type'] == 'Research') echo 'selected'; ?>>Research</option>
                                            <option value="PPT" <?php if($row['type'] == 'PPT') echo 'selected'; ?>>PPT</option>
                                        </select>
                                    </div>

                                    <div class="col-sm-2 text-end">
                                        <label for="inputCategory" class="form-label">Category:<i class="text-danger font-weight-bold">*</i></label>       
                                    </div>
                                    <div class="col-sm-4">        
                                        <select id="inputCategory" class="form-select" name="category" required>
                                            <option value="" disabled>Select Category</option>
                                            <?php
                                            $sqlCat = mysqli_query($dbcon, "SELECT * FROM `category`");
                                            if (mysqli_num_rows($sqlCat) > 0) {
                                                while ($catRow = mysqli_fetch_array($sqlCat)) {
                                                    $selectedCat = ($catRow['categorycode'] == $row['category']) ? 'selected' : '';
                                                    echo "<option value='" . htmlspecialchars($catRow['categorycode']) . "' $selectedCat>" . htmlspecialchars($catRow['description']) . " (" . htmlspecialchars($catRow['categorycode']) . ")</option>";
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
                                        <input type="text" class="form-control" id="inputTitle" name="title" value="<?php echo htmlspecialchars($row['title']); ?>" required>            
                                    </div>

                                    <div class="col-sm-2 text-end">
                                        <label for="Inputdesc" class="form-label">Description:</label>
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" id="Inputdesc" name="description" value="<?php echo htmlspecialchars($row['description']); ?>">
                                    </div>
                                </div>
                                    
                                <div class="row p-2">                           
                                    <div class="col-sm-2 text-end">
                                        <label for="Inputdate" class="form-label">Publish Date:</label>
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="date" class="form-control" id="Inputdate" name="date" value="<?php echo htmlspecialchars($row['publish_date']); ?>">
                                    </div>

                                    <div class="col-sm-2 text-end">
                                        <label for="Inputyear" class="form-label">Research Year:</label>
                                    </div>
                                    <div class="col-sm-4">
                                        <select name="year" class="form-control" id="year">
                                            <?php
                                            $currentYear = date("Y");
                                            for ($y = $currentYear; $y >= 1985; $y--) {
                                                $selected = ($y == $row['research_year']) ? "selected" : "";
                                                echo "<option value='$y' $selected>$y</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                
                                <div class="row p-2">
                                    <div class="col-sm-2 text-end">
                                        <label for="inputBarcode" class="form-label">Barcode:<i class="text-danger font-weight-bold">*</i></label>
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" id="inputBarcode" name="booknumber" value="<?php echo htmlspecialchars($row['booknumber']); ?>" required>
                                    </div>
                            
                                    <div class="col-sm-2 text-end">
                                        <label for="inputauthor" class="form-label">Author:<i class="text-danger font-weight-bold">*</i></label>
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="search" name="author" class="form-control" title="Enter search keyword" id="author" value="<?php echo htmlspecialchars($row['author']); ?>" required> 
                                        <div id="resultauthor"></div>
                                        <a href="add-author.php" target="_blank">Add Author</a>
                                    </div>
                                </div>

                                <div class="row p-2">
                                    <div class="col-sm-2 text-end">
                                        <label for="author2" class="form-label">Author 2:</label>
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="search" name="author2" class="form-control" title="Enter search keyword" id="author2" value="<?php echo htmlspecialchars($row['author2']); ?>">
                                        <div id="resultauthor2"></div>
                                    </div>
                                    
                                    <div class="col-sm-2 text-end">
                                        <label for="inputLanguage" class="form-label">Language:<i class="text-danger font-weight-bold">*</i></label>
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" id="inputLanguage" name="language" value="<?php echo htmlspecialchars($row['language']); ?>"> 
                                    </div>
                                </div>  

								<div class="row p-2">
									<div class="col-sm-2 text-end">
									</div>
									<div class="col-sm-4">
										<button type="submit" class="btn btn-info btn-md" name="btnsave">Update</button>
									</div>
								</div>
							</fieldset>
						</form>		
					</div>
                    <?php include('includes/footer.php');?> 
				</div> 			
			</div>
		</div>	
	</div>	
    
 <script src="js/jquery-3.7.0.js"></script>
 <script src="js/search.js"></script>
 <script src="js/jquery.dataTables.min.js"></script>
 <script>
    new DataTable('#dataTables');
 </script>	                 
</body>
</html>

<?php 
mysqli_close($dbcon);
?>
