<?php
session_start();
error_reporting(0);
include('includes/config.php');
include('includes/activity.php');

logAction($dbcon, "cateloging");
$token=rand();

if(strlen($_SESSION['alogin'])==0)
{   
	header('location:index.php');
}
else{ 
	if(isset($_POST['btnsave'])){

    if ($_SESSION['csrf_token']==$_POST['csrf_token']) {
      // Get number of books to add
      $numberOfBooks = isset($_POST['inputmultiple']) ? intval($_POST['inputmultiple']) : 1;
      
      // Get all form data
      $image=$_FILES['image'];      
      $idGenerationMode = $_POST['id_generation_mode']; // 'auto' or 'manual'
      $baseBooknumber = $_POST['booknumber'];
      $manualBookIds = isset($_POST['manual_book_ids']) ? $_POST['manual_book_ids'] : '';
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
      
      // Counters for success and errors
      $successCount = 0;
      $errorMessages = array();
      $successMessages = array();
      
      // Process manual book IDs if provided
      $manualIdsArray = array();
      if($idGenerationMode == 'manual' && !empty($manualBookIds)) {
        // Split by comma or new line
        $manualIdsArray = preg_split('/[\s,]+/', $manualBookIds);
        $manualIdsArray = array_filter($manualIdsArray); // Remove empty values
        $manualIdsArray = array_slice($manualIdsArray, 0, $numberOfBooks); // Limit to number of books
      }
      
      // Loop to add multiple books
      for($i = 0; $i < $numberOfBooks; $i++) {
        // Generate or use manual book number
        if($idGenerationMode == 'manual' && isset($manualIdsArray[$i]) && !empty($manualIdsArray[$i])) {
          $booknumber = trim($manualIdsArray[$i]);
        } else if($i == 0) {
          $booknumber = $baseBooknumber;
        } else {
          // Auto-increment book number
          $booknumber = $baseBooknumber . '-' . ($i + 1);
        }
        
        // Check if book number already exists
        $sql = "SELECT * FROM catalog WHERE booknumber = '$booknumber'";
        $result = mysqli_query($dbcon, $sql);
        $count = mysqli_num_rows($result);

        if ($count > 0) {
          $errorMessages[] = "Book number '$booknumber' already exists";
          continue; // Skip this iteration
        }
        
        // Handle image upload (only for first book or if you want same image for all)
        $filepath = '';
        if ($i == 0 && !empty($image['size'])) {
          if ($image['size'] <= 200000) {
            $imagedetails = pathinfo($image['name']);
            $allwextention = array('jpg','jpeg','png');

            if (in_array($imagedetails['extension'], $allwextention)) {
              $filepath = 'img/' . uniqid() . '.' . $imagedetails['extension'];
              if (!move_uploaded_file($image['tmp_name'], $filepath)) {
                $filepath = '';
                $errorMessages[] = "Failed to upload image";
              }
            } else {
              $errorMessages[] = "Invalid image format";
            }
          } else {
            $errorMessages[] = "Image size should be less than 200 KB";
          }
        }
        
        // Insert book into database
        if(empty($filepath)) {
          $sql = "INSERT INTO `catalog`(`booknumber`, `itemtype`, `title`, `isbn`, `issn`, `author`, `author2`, `Language`, `category`, `editionnumber`, `classificationNo`, `ItemNo`, `publisher`, `placeofpublisher`, `publicationyear`, `volume`, `pages`, `price`, `dateacquired`, `collectioncode`, `status`, `checkedin`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
          $stmt = mysqli_prepare($dbcon, $sql);
          mysqli_stmt_bind_param($stmt,'ssssssssssssssssssssss', $booknumber, $itemtype, $title, $isbn, $issn, $author, $author2, $language, $category, $editionnumber, $classificationNo, $itemno, $publisher, $placeofpublisher, $publicationyear, $volume, $pages, $price, $dateacquired, $collectioncode, $status, $checkedin);
        } else {
          $sql = "INSERT INTO `catalog`(`booknumber`, `itemtype`, `title`, `isbn`, `issn`, `author`, `author2`, `Language`, `category`, `editionnumber`, `classificationNo`, `ItemNo`, `publisher`, `placeofpublisher`, `publicationyear`, `volume`, `pages`, `price`, `dateacquired`, `collectioncode`, `status`, `checkedin`, `img`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
          $stmt = mysqli_prepare($dbcon, $sql);
          mysqli_stmt_bind_param($stmt,'sssssssssssssssssssssss', $booknumber, $itemtype, $title, $isbn, $issn, $author, $author2, $language, $category, $editionnumber, $classificationNo, $itemno, $publisher, $placeofpublisher, $publicationyear, $volume, $pages, $price, $dateacquired, $collectioncode, $status, $checkedin, $filepath);
        }
        
        if (mysqli_stmt_execute($stmt)) {
          $successCount++;
          $successMessages[] = $booknumber;
        } else {
          $errorMessages[] = "Failed to add book '$booknumber'";
        }
        mysqli_stmt_close($stmt);
      }
      
      // Display results
      if($successCount > 0) {
        $msg = "<strong>Successfully added $successCount book(s)!</strong><br>";
        $msg .= "Book IDs: " . implode(", ", $successMessages);
        
        // Store book IDs for barcode generation
        $allBookIds = implode(",", $successMessages);
        echo "<script>
                if(confirm('Books added successfully! Do you want to download all barcodes now?')) {
                    window.open('barcode_generator.php?print=1&ids=" . urlencode($allBookIds) . "', '_blank');
                }
              </script>";
      }
      
      if(count($errorMessages) > 0) {
        $error1 = "<strong>Errors:</strong><br>" . implode("<br>", $errorMessages);
      }
      
    } else {
      echo "<script>alert('Invalid Authentication')</script>";	
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
    <title>Cataloging | Library Management System</title>
    <style>
        .mode-switch {
            margin-bottom: 15px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        .mode-option {
            display: inline-block;
            margin-right: 20px;
        }
        .manual-ids-area {
            margin-top: 10px;
            padding: 10px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            background-color: #fff;
        }
        .help-text {
            font-size: 12px;
            color: #6c757d;
            margin-top: 5px;
        }
    </style>
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
                          
                          <div class="col-sm-2 text-end">
                            <label class="form-label">ID Generation Mode:<i class="text-danger font-weight-bold">*</i></label>
                          </div>
                          <div class="col-sm-2">
                            
                              <label class="mode-option">
                                <input type="radio" name="id_generation_mode" value="auto" checked onclick="toggleIdMode()"> Auto Generate
                              </label>
                              <label class="mode-option">
                                <input type="radio" name="id_generation_mode" value="manual" onclick="toggleIdMode()"> Manual Entry
                              </label>                           
                          </div>

                          <div class="col-sm-2 text-end">
                            <label for="inputmultiple" class="form-label">Add multiple Books:<i class="text-danger font-weight-bold">*</i></label>
                          </div>
                          <div class="col-sm-2">
                            <input type="number" class="form-control" id="inputmultiple" name="inputmultiple" min="1" value="1" max="100" required>
                            <small class="text-muted">Enter number of copies to add</small>            
                          </div>
                        </div>  

                        <!-- ID Generation Mode Selection -->
                        <div class="row p-2">
                          
                        </div>

                        <!-- Auto Generate Section -->
                        <div id="auto-mode-section">
                          <div class="row p-2">
                            <div class="col-sm-2 text-end">
                              <label for="inputBarcode" class="form-label">Base Barcode:<i class="text-danger font-weight-bold">*</i></label>
                            </div>
                            <div class="col-sm-4">
                              <input type="text" class="form-control" id="inputBarcode" name="booknumber">
                              <small class="text-muted">Multiple books will use: BASE, BASE-2, BASE-3, etc.</small>
                            </div>
                          </div>
                        </div>

                        <!-- Manual Entry Section -->
                        <div id="manual-mode-section" style="display:none;">
                          <div class="row p-2">
                            <div class="col-sm-2 text-end">
                              <label for="manualBookIds" class="form-label">Book IDs/Barcodes:<i class="text-danger font-weight-bold">*</i></label>
                            </div>
                            <div class="col-sm-8">
                              <textarea class="form-control" id="manualBookIds" name="manual_book_ids" rows="5" placeholder="Enter book IDs manually"></textarea>
                              <small class="help-text">Enter unique book IDs.</small>
                            </div>
                          </div>
                        </div>

                        <div class="row p-2">  
                          <div class="col-sm-2 text-end"></div>
                          <div class="col-sm-8 text-danger">         
                            <span><?php echo $error1?></span>
                          </div>               
                        </div> 
                        
                        <div class="row p-2">
                          <div class="col-sm-2 text-end">
                            <label for="inputType" class="form-label">Item Type:</label>       
                          </div>
                          <div class="col-sm-4">        
                            <select id="inputType" class="form-select" name="itemtype" onchange="getBarcode(this.value)"> 
                              <option value="" selected>Select Item Type</option>
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
                            <label for="inputISBN" class="form-label">ISBN:</label>
                          </div>
                          <div class="col-sm-4">
                            <input type="number" class="form-control" id="inputISBN" maxlength="13" name="isbn" placeholder="10 or 13 digits">
                          </div>
                        
                          <div class="col-sm-2 text-end">
                            <label for="inputISSN" class="form-label">ISSN:</label>
                          </div>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" id="inputISSN" maxlength="8" name="issn" placeholder="8 digits">
                          </div>
                        </div>

                        <div class="row p-2">
                          <div class="col-sm-2 text-end">
                            <label for="inputauthor" class="form-label">Author:<i class="text-danger font-weight-bold">*</i></label>
                          </div>
                          <div class="col-sm-4">
                            <input type="search" name="author" class="form-control" title="Enter search keyword" id="author" required> 
                            <div id="resultauthor"></div>
                            <a href="add-author.php" target="_blank">Add Author</a>
                          </div>
                        
                          <div class="col-sm-2 text-end">
                            <label for="x" class="form-label">Author 2:</label>
                          </div>
                          <div class="col-sm-4">
                            <input type="search" name="author2" class="form-control" title="Enter search keyword" id="author2">
                            <div id="resultauthor2"></div>
                          </div>
                        </div>

                        <div class="row p-2">
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
                            <input type="number" class="form-control" id="inputClasfNo" placeholder="898.02" step="0.01" name="classificationNo" required> 
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
                        <legend class="w-auto">Publication & Physical Description:</legend>

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
                            <select class="year" class="form-control" id="inputYear" name="publicationyear">
                              <option value="">Select Year</option>
                              <?php
                                $currentYear = date('Y');
                                for ($year = $currentYear; $year >= 1950; $year--) {
                                  echo "<option value='$year'>$year</option>";
                                }
                              ?>
                            </select>
                          </div>
                       
                          <div class="col-sm-2 text-end">        
                            <label for="inputSeriesName" class="form-label">Series Name:</label>
                          </div>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" id="inputSeriesName" name="volume">
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
                              <option selected value="A">Available</option>
                              <option value="N">Not Available</option>
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

  <script>
    function toggleIdMode() {
      var mode = document.querySelector('input[name="id_generation_mode"]:checked').value;
      var autoSection = document.getElementById('auto-mode-section');
      var manualSection = document.getElementById('manual-mode-section');
      var baseBarcode = document.getElementById('inputBarcode');
      
      if (mode === 'auto') {
        autoSection.style.display = 'block';
        manualSection.style.display = 'none';
        baseBarcode.required = true;
        document.getElementById('manualBookIds').required = false;
      } else {
        autoSection.style.display = 'none';
        manualSection.style.display = 'block';
        baseBarcode.required = false;
        document.getElementById('manualBookIds').required = true;
      }
    }
    
    function getBarcode(typeCode) {
        if (typeCode == "") {
            document.getElementById("inputBarcode").value = "";
            return;
        }

        const xhttp = new XMLHttpRequest();
        xhttp.onload = function () {
            document.getElementById("inputBarcode").value = this.responseText;
        }
        xhttp.open("GET", "itemtype_barcode.php?type=" + typeCode, true);
        xhttp.send();
    }
    
    // Validate manual IDs count on form submission
    document.querySelector('form').addEventListener('submit', function(e) {
      var mode = document.querySelector('input[name="id_generation_mode"]:checked').value;
      var numBooks = parseInt(document.getElementById('inputmultiple').value);
      
      if (mode === 'manual') {
        var manualIds = document.getElementById('manualBookIds').value.trim();
        if (manualIds === '') {
          alert('Please enter manual book IDs');
          e.preventDefault();
          return false;
        }
        
        // Count the IDs
        var idsArray = manualIds.split(/[\s,]+/).filter(function(id) { return id.trim() !== ''; });
        if (idsArray.length < numBooks) {
          alert('You have entered ' + idsArray.length + ' book ID(s) but requested ' + numBooks + ' copies. Please enter at least ' + numBooks + ' book IDs.');
          e.preventDefault();
          return false;
        }
      } else if (mode === 'auto') {
        var baseBarcode = document.getElementById('inputBarcode').value.trim();
        if (baseBarcode === '') {
          alert('Please enter a base barcode for auto-generation');
          e.preventDefault();
          return false;
        }
      }
      
      return true;
    });
  </script>

  <script src="js/search.js"></script>

</body>
</html>

<?php 
mysqli_close($dbcon);
}?>