<?php
session_start();
error_reporting(0);
include('includes/config.php');
include('includes/activity.php');

logAction($dbcon, "edit_cataloging");

if(strlen($_SESSION['alogin'])==0) {   
    header('location:index.php');
    exit();
}

if (isset($_GET['id'])) {
    $booknumber = $_GET['id'];
    $sql = mysqli_query($dbcon, "SELECT * FROM catalog WHERE booknumber = '$booknumber'");    
    $result = mysqli_fetch_array($sql);
}

if(isset($_POST['btnupdate'])) {
    $bookimage = $_FILES['bookimage'];      
    $itemtype = $_POST['itemtype'];
    $title = $_POST['title'];
    $isbn = $_POST['isbn'];
    $issn = $_POST['issn'];
    $author = $_POST['author'];
    $author2 = $_POST['author2'];
    $language = $_POST['language'];
    $category = $_POST['category'];
    $editionnumber = $_POST['editionnumber'];
    $classificationNo = $_POST['classificationNo'];
    $itemno = $_POST['itemno'];
    $publisher = $_POST['publisher'];
    $placeofpublisher = $_POST['placeofpublisher'];
    $publicationyear = $_POST['publicationyear'];
    $volume = $_POST['volume'];
    $pages = $_POST['pages'];
    $price = $_POST['price'];
    $dateacquired = $_POST['dateacquired'];
    $collectioncode = $_POST['collectioncode'];
    $status = $_POST['status'];
    $checkedin = $result['checkedin'];

    if (empty($bookimage['size'])) {
        $sql1 = "UPDATE `catalog` SET `itemtype`=?, `title`=?, `isbn`=?, `issn`=?, `author`=?, `author2`=?, `Language`=?, `category`=?, `editionnumber`=?, `classificationNo`=?, `ItemNo`=?, `publisher`=?, `placeofpublisher`=?, `publicationyear`=?, `volume`=?, `pages`=?, `price`=?, `dateacquired`=?, `collectioncode`=?, `status`=?, `checkedin`=? WHERE `booknumber`=?";
        $stmt = mysqli_prepare($dbcon, $sql1);
        mysqli_stmt_bind_param($stmt,'ssssssssssssssssssssss', $itemtype, $title, $isbn, $issn, $author, $author2, $language, $category, $editionnumber, $classificationNo, $itemno, $publisher, $placeofpublisher, $publicationyear, $volume, $pages, $price, $dateacquired, $collectioncode, $status, $checkedin, $booknumber);

        if (mysqli_stmt_execute($stmt)) {
            echo "<script>
                    alert('Record updated successfully');
                    window.location.href='catalog_reports.php';
                  </script>";
            exit();
        } else {
            $errorMsg = mysqli_error($dbcon);
            echo "<script>alert('Something went wrong. Please try again. Error: $errorMsg');</script>";
        }
    } elseif ($bookimage['size'] <= 200000) {
        $imagedetails = pathinfo($bookimage['name']);
        $allowed = array('jpg','jpeg','png');

        if (in_array(strtolower($imagedetails['extension']), $allowed)) {
            $filepath = 'img/' . uniqid() . '.' . $imagedetails['extension'];
            if (move_uploaded_file($bookimage['tmp_name'], $filepath)) {
                $sql1 = "UPDATE `catalog` SET `itemtype`=?, `title`=?, `isbn`=?, `issn`=?, `author`=?, `author2`=?, `Language`=?, `category`=?, `editionnumber`=?, `classificationNo`=?, `ItemNo`=?, `publisher`=?, `placeofpublisher`=?, `publicationyear`=?, `volume`=?, `pages`=?, `price`=?, `dateacquired`=?, `collectioncode`=?, `status`=?, `checkedin`=?, `img`=? WHERE `booknumber`=?";
                $stmt = mysqli_prepare($dbcon, $sql1);
                mysqli_stmt_bind_param($stmt,'sssssssssssssssssssssss', $itemtype, $title, $isbn, $issn, $author, $author2, $language, $category, $editionnumber, $classificationNo, $itemno, $publisher, $placeofpublisher, $publicationyear, $volume, $pages, $price, $dateacquired, $collectioncode, $status, $checkedin, $filepath, $booknumber);

                if (mysqli_stmt_execute($stmt)) {
                    echo "<script>
                            alert('Record updated successfully with image');
                            window.location.href='catalog_reports.php';
                          </script>";
                    exit();
                } else {
                    $errorMsg = mysqli_error($dbcon);
                    echo "<script>alert('Something went wrong. Please try again. Error: $errorMsg');</script>";
                }
            } else {
                echo "<script>alert('Failed to upload image file');</script>";
            }
        } else {
            echo "<script>alert('Only JPG, JPEG, PNG files are allowed');</script>";
        }
    } else {
        echo "<script>alert('Image size must be less than 200 KB');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="img/logo.png" type="image/png">
    <title>Update Cataloging | Library Management System</title>
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
                              <h2 class="title">Update Cataloging</h2>
                          </div>                                
                      </div>
                      <div class="row breadcrumb-div">
                          <div class="col-md-6">
                              <ul class="breadcrumb">
                                  <li><a href="dashboard.php"><i class="fa fa-home"></i> Home /</a></li>
                                  <li><a href="#">Catalogs /</a></li>
                                  <li class="active">Update Cataloging</li>
                              </ul>
                          </div>                               
                      </div>
                  </div>
                  <br>
                  <div class="container">
                    <form method="post" enctype="multipart/form-data">  

                      <!-- Cataloging -->      
                      <fieldset class="border p-3">
                        <legend class="w-auto">Cataloging:</legend>
                        <div class="row p-2">  
                          <div class="col-sm-2 text-end"></div>
                          <div class="col-sm-2 text-end">
                            <img src="<?php echo htmlspecialchars($result['img']); ?>" class="rounded img-thumbnail" alt="Book Image">      
                          </div>
                        </div>
                        <div class="row p-2">  
                          <div class="col-sm-2 text-end"></div>
                          <div class="col-sm-2 text-end">         
                            <input type="file" class="form-control" name="bookimage" id="bookimage">
                          </div>               
                        </div>  

                        <div class="row p-2">
                          <div class="col-sm-2 text-end">
                            <label>Item Type:</label>       
                          </div>
                          <div class="col-sm-4">        
                            <select class="form-select" name="itemtype" required>
                              <?php
                                $sql2=mysqli_query($dbcon, "SELECT * FROM `itemtypes`");
                                if (mysqli_num_rows($sql2)>0) {
                                  while ($row2=mysqli_fetch_array($sql2)) {
                                    $selected = ($row2['itemcode']==$result['itemtype']) ? 'selected' : '';
                                    echo "<option value='".htmlspecialchars($row2['itemcode'])."' $selected>".htmlspecialchars($row2['description'])."</option>";
                                  }
                                }
                              ?>
                            </select>
                          </div>
                          <div class="col-sm-2 text-end">
                            <label>Title: *</label>
                          </div>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" name="title" value="<?php echo htmlspecialchars($result['title']); ?>" required>                
                          </div>
                        </div>

                        <div class="row p-2">
                          <div class="col-sm-2 text-end">
                            <label>Barcode: *</label>
                          </div>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($result['booknumber']); ?>" disabled>
                            <input type="hidden" name="booknumber" value="<?php echo htmlspecialchars($result['booknumber']); ?>">
                          </div>
                          <div class="col-sm-2 text-end">
                            <label>ISBN:</label>
                          </div>
                          <div class="col-sm-4">
                            <input type="number" class="form-control" name="isbn" value="<?php echo htmlspecialchars($result['isbn']); ?>">
                          </div>
                        </div>

                        <!-- Author & Language -->
                        <div class="row p-2">
                          <div class="col-sm-2 text-end">
                            <label>Author: *</label>
                          </div>
                          <div class="col-sm-4">
                            <input type="search" name="author" class="form-control" value="<?php echo htmlspecialchars($result['author']); ?>" required>
                          </div>
                          <div class="col-sm-2 text-end">
                            <label>Author 2:</label>
                          </div>
                          <div class="col-sm-4">
                            <input type="search" name="author2" class="form-control" value="<?php echo htmlspecialchars($result['author2']); ?>">
                          </div>
                        </div>

                        <div class="row p-2">
                          <div class="col-sm-2 text-end">
                            <label>Language: *</label>
                          </div>
                          <div class="col-sm-4">
                            <input type="text" name="language" class="form-control" value="<?php echo htmlspecialchars($result['Language']); ?>" required>
                          </div>
                        </div>
                      </fieldset>

                      <!-- Dewey Decimal Classification -->
                      <fieldset class="border p-3">
                        <legend class="w-auto">Dewey Decimal Classification:</legend>
                        <div class="row p-2">
                          <div class="col-sm-2 text-end">
                            <label>Category: *</label>       
                          </div>
                          <div class="col-sm-4">        
                            <select class="form-select" name="category" required>
                              <?php
                                $sql3=mysqli_query($dbcon, "SELECT * FROM `category`");
                                if(mysqli_num_rows($sql3)>0){
                                  while($row3=mysqli_fetch_array($sql3)){
                                    $selected = ($row3['categorycode']==$result['category']) ? 'selected' : '';
                                    echo "<option value='".htmlspecialchars($row3['categorycode'])."' $selected>".htmlspecialchars($row3['description'])." (".$row3['categorycode'].")</option>";
                                  }
                                }
                              ?>
                            </select>
                          </div>
                          <div class="col-sm-2 text-end">        
                            <label>Edition number:</label>
                          </div>
                          <div class="col-sm-4">
                            <input type="text" name="editionnumber" class="form-control" value="<?php echo htmlspecialchars($result['editionnumber']); ?>">
                          </div>
                        </div>

                        <div class="row p-2">
                          <div class="col-sm-2 text-end">        
                            <label>Classification number: *</label>
                          </div>
                          <div class="col-sm-4">
                            <input type="number" name="classificationNo" class="form-control" value="<?php echo htmlspecialchars($result['classificationNo']); ?>" step="0.01" required>
                          </div>
                          <div class="col-sm-2 text-end">        
                            <label>Item number: *</label>
                          </div>
                          <div class="col-sm-4">
                            <input type="text" name="itemno" class="form-control text-uppercase" maxlength="3" value="<?php echo htmlspecialchars($result['ItemNo']); ?>" required>
                          </div>
                        </div>
                      </fieldset>

                      <!-- Publication & Physical Description -->
                      <fieldset class="border p-3">
                        <legend class="w-auto">Publication & Physical Description:</legend>
                        <div class="row p-2">
                          <div class="col-sm-2 text-end">        
                            <label>Publisher: *</label>
                          </div>
                          <div class="col-sm-4">
                            <input type="search" name="publisher" class="form-control" value="<?php echo htmlspecialchars($result['publisher']); ?>">
                          </div>
                          <div class="col-sm-2 text-end">        
                            <label>Place of Publication:</label>
                          </div>
                          <div class="col-sm-4">
                            <input type="text" name="placeofpublisher" class="form-control" value="<?php echo htmlspecialchars($result['placeofpublisher']); ?>">
                          </div>
                        </div>

                        <div class="row p-2">
                          <div class="col-sm-2 text-end">        
                            <label>Year:</label>
                          </div>
                          <div class="col-sm-4">
                            <input type="text" name="publicationyear" class="form-control" value="<?php echo htmlspecialchars($result['publicationyear']); ?>">
                          </div>
                          <div class="col-sm-2 text-end">        
                            <label>Series Name:</label>
                          </div>
                          <div class="col-sm-4">
                            <input type="text" name="volume" class="form-control" value="<?php echo htmlspecialchars($result['volume']); ?>">
                          </div>
                        </div>

                        <div class="row p-2">
                          <div class="col-sm-2 text-end">        
                            <label>Pages: *</label>
                          </div>
                          <div class="col-sm-4">
                            <input type="number" name="pages" class="form-control" value="<?php echo htmlspecialchars($result['pages']); ?>" required>
                          </div>
                          <div class="col-sm-2 text-end">        
                            <label>Trade Price: *</label>
                          </div>
                          <div class="col-sm-4">
                            <input type="number" name="price" class="form-control" value="<?php echo htmlspecialchars($result['price']); ?>" step="0.01" required>
                          </div>
                        </div>

                        <div class="row p-2">
                          <div class="col-sm-2 text-end">
                            <label>Date acquired:</label>
                          </div>
                          <div class="col-sm-4">
                            <input type="date" name="dateacquired" class="form-control" value="<?php echo htmlspecialchars($result['dateacquired']); ?>">
                          </div>
                          <div class="col-sm-2 text-end">
                            <label>Collection code: *</label>
                          </div>
                          <div class="col-sm-2">
                            <select name="collectioncode" class="form-select" required>
                              <option value="L" <?php if($result['collectioncode']=='L') echo 'selected'; ?>>Lending</option>
                              <option value="R" <?php if($result['collectioncode']=='R') echo 'selected'; ?>>Reference</option>
                            </select>
                          </div>
                        </div>

                        <div class="row p-2">
                          <div class="col-sm-2 text-end">
                            <label>Status:</label>
                          </div>
                          <div class="col-sm-2">
                            <select name="status" class="form-select" required>
                              <option value="A" <?php if($result['status']=='A') echo 'selected'; ?>>Available</option>
                              <option value="N" <?php if($result['status']=='N') echo 'selected'; ?>>Not Available</option>
                              <option value="L" <?php if($result['status']=='L') echo 'selected'; ?>>Lost</option>
                              <option value="D" <?php if($result['status']=='D') echo 'selected'; ?>>Damage</option>
                            </select>
                          </div>
                        </div>
                      </fieldset>     

                      <div class="d-grid gap-2 d-md-flex justify-content-md-center mt-3">
                        <div class="col-sm-1">        
                          <button type="submit" class="btn btn-info btn-md" name="btnupdate">Update</button>
                        </div>
                      </div>
                    </form>
                  </div>
                  <?php include('includes/footer.php');?>
              </div>
          </div>
      </div> 
  </div> 

<script src="js/search.js"></script>
</body>
</html>

<?php 
mysqli_close($dbcon);
?>
