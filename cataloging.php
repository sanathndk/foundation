<?php
session_start();
error_reporting(0);
include('includes/config.php');
$token=rand();

if(strlen($_SESSION['alogin'])==0)
{   
	header('location:index.php');
}
else{ 
	if(isset($_POST['btnsave'])){

    if ($_SESSION['csrf_token']==$_POST['csrf_token']) {
      // Save Record
      $image=$_FILES['image'];      
      $booknumber=$_POST['booknumber'];
      $itemtype=$_POST['itemtype'];
      $title=$_POST['title'];
      $isbn=$_POST['isbn'];
      $issn=$_POST['issn'];
      $author=$_POST['author'];
      $author2=$_POST['author2'];
      $language=$_POST['language'];
      $category=$_POST['category'];
      $editionnumber=$_POST['editionnumber'];
      $classificationNo=$_POST['classificationNo'];
      $itemno=$_POST['itemno'];
      $publisher=$_POST['publisher'];
      $placeofpublisher=$_POST['placeofpublisher'];
      $publicationyear=$_POST['publicationyear'];
      $volume=$_POST['volume'];
      $pages=$_POST['pages'];
      $price=$_POST['price'];
      $dateacquired=$_POST['dateacquired'];
      $collectioncode=$_POST['collectioncode'];
      $status=$_POST['status'];
      $checkedin=1;
      
    // Find the Book ID is already taken?
      $sql = "SELECT * FROM catalog WHERE booknumber = '$booknumber'";
      $result = mysqli_query($dbcon, $sql);
      $count = mysqli_num_rows($result);

    if ($count > 0) {
      $error = "Sorry, the Number '$booknumber' is already taken.";
    } else {   
        
      if (empty($image['size'])) {
        $sql="INSERT INTO `catalog`(`booknumber`, `itemtype`, `title`, `isbn`, `issn`, `author`, `author2`, `Language`, `category`, `editionnumber`, `classificationNo`, `ItemNo`, `publisher`, `placeofpublisher`, `publicationyear`, `volume`, `pages`, `price`, `dateacquired`, `collectioncode`, `status`, `checkedin`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $result=mysqli_prepare($dbcon, $sql);
          mysqli_stmt_bind_param($result,'ssssssssssssssssssssss', $booknumber, $itemtype, $title, $isbn, $issn, $author, $author2, $language, $category, $editionnumber, $classificationNo, $itemno, $publisher, $placeofpublisher, $publicationyear, $volume, $pages, $price, $dateacquired, $collectioncode, $status, $checkedin);
          
          if (mysqli_stmt_execute($result)) {
            $msg = 'Book registed successfuly. <strong>Book id is '.$booknumber.'<strong>'; 
            // header('location:cataloging.php');
          } else{
              $error1 = '<strong>Something went wrong please try again<strong>'; 
          }       
      }elseif ($image['size']<=200000) {

            $imagedetails=pathinfo($image['name']);
            $allwextention=array('jpg','jpeg','png');

            if (in_array($imagedetails['extension'],$allwextention)) {
              $filepath='img/'.uniqid().'.'.$imagedetails['extension'];
              if (move_uploaded_file($image['tmp_name'],$filepath)) {
                $sql="INSERT INTO `catalog`(`booknumber`, `itemtype`, `title`, `isbn`, `issn`, `author`, `author2`, `Language`, `category`, `editionnumber`, `classificationNo`, `ItemNo`, `publisher`, `placeofpublisher`, `publicationyear`, `volume`, `pages`, `price`, `dateacquired`, `collectioncode`, `status`, `checkedin`, `img`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                $result=mysqli_prepare($dbcon, $sql);
                  mysqli_stmt_bind_param($result,'sssssssssssssssssssssss', $booknumber, $itemtype, $title, $isbn, $issn, $author, $author2, $language, $category, $editionnumber, $classificationNo, $itemno, $publisher, $placeofpublisher, $publicationyear, $volume, $pages, $price, $dateacquired, $collectioncode, $status, $checkedin, $filepath);
                  
                  if (mysqli_stmt_execute($result)) {
                    $msg = 'Book registed successfuly. <strong>Book id is '.$booknumber.'<strong>'; 
                    // header('location:cataloging.php');
                  } else{
                      $error1 = '<strong>Something went wrong please try again<strong>'; 
                  }               
              }
          }else{
            $error1 = '<strong>Should be add image file only<strong>'; 
          }

        }else{
          $error1 = '<strong>Image should be less than 200 KB<strong>'; 
        }        
      }
    }else{
          echo "<script>Alert('Invalied Authantication'))<script>";	
    }
	}	
$_SESSION['csrf_token']=$token;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="img/logo.png" type="image/png">
  	<title>Cataloging | Foundation Library Management System</title>
</head>
<body>
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
                              <h2 class="title">Catalogue</h2>
                          </div>                                
                      </div>
                      <!-- /.row -->
                      <div class="row breadcrumb-div">
                          <div class="col-md-6">
                              <ul class="breadcrumb">
                                  <li><a href="dashboard.php"><i class="fa fa-home"></i> Home /&nbsp;</a></li>
                                  <li><a href="#">Catalogs /&nbsp;</a></li>
                                  <li class="active">Catalogue</li>
                              </ul>
                          </div>                               
                      </div>
                      <!-- /.row -->
                  </div>
                  <br>
                  <div class="container">
                    <form name="signup" method="post" onSubmit="return valid();" enctype="multipart/form-data">  
                      <!-- Cataloging -->      
                      <fieldset class="border p-3">
                        <legend class="w-auto">Catalogue:</legend>
                        <div class="row p-2">  
                          <div class="col-sm-2 text-end"></div>                          
                        </div>
                        <div class="row p-2">  
                          <div class="col-sm-2 text-end"></div>
                          <div class="col-sm-8 text-success">         
                            <span><?php echo $msg?></span>
                          </div>               
                        </div> 

                        <div class="row p-2">  
                          <div class="col-sm-2 text-end"></div>
                          <div class="col-sm-2 text-end">         
                            <input type="file" class="form-control" id="image" name="image">
                          </div>               
                        </div>  
                        <div class="row p-2">  
                          <div class="col-sm-2 text-end"></div>
                          <div class="col-sm-4 text-danger">         
                            <span><?php echo $error1?></span>
                          </div>               
                        </div> 
                        <div class="row p-2">
                          <div class="col-sm-2 text-end">
                            <label for="inputType" class="form-label">Item  Type:</label>       
                          </div>
                          <div class="col-sm-4">        
                            <select id="inputType" class="form-select" name ="itemtype" required> 
                            <!-- Load Item Type in database -->
                              <?php
                                $sql=mysqli_query($dbcon, "SELECT * FROM `itemtypes`");

                                if (mysqli_num_rows($sql)>0) {
                                  while ($row=mysqli_fetch_array($sql)) {
                                    echo "<option value='" . $row['itemcode'] . "'>" . $row['description'] . "</option>";
                                  }
                                }
                              ?>
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
                            <label for="inputISBN" class="form-label">ISBN:</label>
                          </div>
                          <div class="col-sm-4">
                            <input type="number" class="form-control" id="inputISBN" name="isbn">
                          </div>
                        </div>

                        <div class="row p-2">
                          <div class="col-sm-2 text-end">
                            <label for="inputISSN" class="form-label">ISSN:</label>
                          </div>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" id="inputISSN" name="issn">
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
                      
                      </fieldset>

                      <!--Dewey decimal classification -->
                      <fieldset class="border p-3">
                        <legend class="w-auto">Dewey Decimal Classification:</legend>

                        <div class="row p-2">
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
                        
                          <div class="col-sm-2 text-end">        
                            <label for="inputEditionNo" class="form-label">Edition number:</label>
                          </div>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" id="inputEditionNo" name="editionnumber">
                          </div>
                        </div>

                        <div class="row p-2">
                          <div class="col-sm-2 text-end">        
                            <label for="inputClasfNo" class="form-label">Classification number:<i class="text-danger font-weight-bold">*</i></label>
                          </div>
                          <div class="col-sm-4">
                            <input type="number" class="form-control" id="inputClasfNo" placeholder="898.02" step="0.01" value="<?php echo $row['categorycode'] ?>" name="classificationNo" required> 
                          </div>
                       
                          <div class="col-sm-2 text-end">        
                            <label for="inputItemno" class="form-label">Item number:<i class="text-danger font-weight-bold">*</i></label>
                          </div>
                          <div class="col-sm-4">
                            <input type="text" class="form-control text-uppercase" id="inputItemno" maxlength="3" name="itemno" required>
                          </div>
                        </div>

                      </fieldset>

                      <!-- Publication & Physical Description   -->
                      <fieldset class="border p-3">
                        <legend class="w-auto">Publication & Physical Description  :</legend>

                        <div class="row p-2">
                          <div class="col-sm-2 text-end">        
                            <label for="inputPublisher" class="form-label">Name of Publisher:<i class="text-danger font-weight-bold">*</i></label>
                          </div>
                          <div class="col-sm-4">
                            <input type="search" name="publisher" class="form-control" title="Enter search keyword" id="inputpublisher">
                            <div id="resultpublisher"></div>      
                            <a href="add-publishers.php" target="_blank">Add Publisher</a>
                          </div>                         
                        
                          <div class="col-sm-2 text-end">        
                            <label for="inputPlacepub" class="form-label">Place of Publication:</label>
                          </div>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" id="inputPlacepub" name="placeofpublisher">
                          </div>
                        </div>

                        <div class="row p-2">
                          <div class="col-sm-2 text-end">        
                            <label for="inputYear" class="form-label">Year:</label>
                          </div>
                          <div class="col-sm-4">
                            <input type="year" class="form-control" id="inputYear" name="publicationyear">
                          </div>
                       
                          <div class="col-sm-2 text-end">        
                            <label for="inputSeriesName" class="form-label">Series Name:</label>
                          </div>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" id="inputSeriesName" name="volume" >
                          </div>
                        </div>

                        <div class="row p-2">
                          <div class="col-sm-2 text-end">        
                            <label for="inputPages" class="form-label">Pages:<i class="text-danger font-weight-bold">*</i></label>
                          </div>
                          <div class="col-sm-4">
                            <input type="number" class="form-control" id="inputPages" name="pages" required>
                          </div>
                       
                          <div class="col-sm-2 text-end">        
                            <label for="inputPrice" class="form-label">Price:<i class="text-danger font-weight-bold">*</i></label>
                          </div>
                          <div class="col-sm-4">
                            <input type="number" class="form-control" id="inputPrice" name="price" step="0.01" required>
                          </div>
                        </div>    

                        <div class="row p-2">
                          <div class="col-sm-2 text-end">
                            <label for="inputRegDate" class="form-label">Date acquired:</label>
                          </div>
                          <div class="col-sm-4">
                            <input type="date" class="form-control" id="inputRegDate" value="<?= date('Y-m-d'); ?>" name="dateacquired" readonly>
                          </div>
                        
                          <div class="col-sm-2 text-end">
                            <label for="inputColCode" class="form-label">Collection code:<i class="text-danger font-weight-bold">*</i></label>       
                          </div>
                          <div class="col-sm-2">        
                            <select id="inputColCode" class="form-select" name="collectioncode" required>
                              <option selected value="L">Lending</option>
                              <option value="R">Reference</option>
                            </select>
                          </div>            
                        </div>

                        <div class="row p-2">
                          <div class="col-sm-2 text-end">
                            <label for="inputStatus" class="form-label">Status:</label>       
                          </div>
                          <div class="col-sm-2">        
                            <select id="inputStatus" class="form-select" required name="status">
                              <option selected value="A">Availabale</option>
                              <option value="N">Not Availabale</option>
                              <option value="L">Lost</option>
                              <option value="D">Damage</option> 
                            </select>
                          </div>            
                        </div>
                      </fieldset>     

                      <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                        <div class="col-sm-1">  
                          <input type="hidden" name="csrf_token" value="<?php echo $token?>">      
                          <button type="submit" class="btn btn-primary btn-md" name="btnsave"><i class="bi bi-journal-plus"></i>&nbsp;Save</button>
                        </div>
                      </div>
                    </form>
                  </div>           
                    <!-- CONTENT-WRAPPER SECTION END-->
                <?php include('includes/footer.php');?>
      </div>
    </div>
  </div> 

<script src="js/search.js"></script>

</body>
</html>

<?php 
mysqli_close($dbcon);
}?>