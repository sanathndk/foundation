<?php
session_start();
error_reporting(0);
include('includes/config.php');
include('includes/activity.php');

logAction($dbcon, "Edit_author");

if(strlen($_SESSION['alogin'])==0) {   
    header('location:index.php');
    exit();
}

$id = intval($_GET['id']);
$result = mysqli_query($dbcon,"SELECT * FROM `author` WHERE authorid=$id");
$row = mysqli_fetch_array($result);

if(isset($_POST['btnsave'])) {
    // Trim inputs
    $name = trim($_POST['name']);
    $address = trim($_POST['address']);
    $country = trim($_POST['country']);
    $contactno = trim($_POST['contactno']);
    $email = trim($_POST['email']);
    $dob = trim($_POST['dob']);
    $yrdied = trim($_POST['yrdied']);        
    $bio = trim($_POST['bio']);
    $publications = trim($_POST['pub']);
    $awards = trim($_POST['awards']);
    $references = trim($_POST['ref']);

    // Check for duplicate author name
    $check = mysqli_prepare($dbcon, "SELECT COUNT(*) FROM author WHERE name=? AND authorid != ?");
    mysqli_stmt_bind_param($check, 'si', $name, $id);
    mysqli_stmt_execute($check);
    mysqli_stmt_bind_result($check, $count);
    mysqli_stmt_fetch($check);
    mysqli_stmt_close($check);

    if($count > 0){
        echo "<script>alert('Author name already exists! Please use a different name.'); window.history.back();</script>";
        exit();
    }

    // Update author
    $sql = "UPDATE author SET name=?, address=?, country=?, mobile=?, email=?, dob=?, dateofdied=?, bio=?, publications=?, awards=?, ref=? WHERE authorid=?";
    $stmt = mysqli_prepare($dbcon, $sql);
    mysqli_stmt_bind_param($stmt,'sssssssssssi', $name, $address, $country, $contactno, $email, $dob, $yrdied, $bio, $publications, $awards, $references, $id);

    if(mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Author updated successfully!'); window.location='add-author.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error updating data: " . mysqli_error($dbcon) . "'); window.history.back();</script>";
    }
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">    
    <link rel="stylesheet" href="css/jquery.dataTables.min.css">  
    <link rel="icon" href="img/logo.png" type="image/png">
    <title>Edit Author | Foundation Library Management System</title>
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
                            <h2 class="title">Update Author</h2>
                        </div>                                
                    </div>
                    <div class="row breadcrumb-div">
                        <div class="col-md-6">
                            <ul class="breadcrumb">
                                <li><a href="dashboard.php"><i class="fa fa-home"></i> Home /&nbsp;</a></li>
                                <li><a href="#">Catalogs /&nbsp; </a></li>
                                <li class="active">Update Author</li>
                            </ul>
                        </div>                               
                    </div>
                </div>

                <div class="container">                 
                    <form method="post" class="form-controlr"> 
                        <br>
                        <fieldset class="border">                                
                            <div class="row p-2">
                                <div class="col-sm-2 text-end">
                                    <label class="form-label">Author Name:</label>
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($row['name']); ?>" required>
                                </div>                                
                                <div class="col-sm-2 text-end">
                                    <label class="form-label">Address:</label>
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" name="address" value="<?php echo htmlspecialchars($row['address']); ?>"> 
                                </div>
                            </div>        

                            <div class="row p-2">
                                <div class="col-sm-2 text-end">
                                    <label class="form-label">Country:</label>
                                </div>
                                <div class="col-sm-4">
                                    <input type="search" name="country" class="form-control" id="country" value="<?php echo htmlspecialchars($row['country']); ?>">
                                    <div id="resultcountry"></div>               
                                </div>
                                <div class="col-sm-2 text-end">
                                    <label class="form-label">Contact No:</label>
                                </div>
                                <div class="col-sm-4">
                                    <input type="number" class="form-control" name="contactno" value="<?php echo htmlspecialchars($row['mobile']); ?>">
                                </div>
                            </div>

                            <div class="row p-2">
                                <div class="col-sm-2 text-end">
                                    <label class="form-label">Email:</label>
                                </div>
                                <div class="col-sm-4">
                                    <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($row['email']); ?>">
                                </div>
                                <div class="col-sm-2 text-end">
                                    <label class="form-label">Year Born:</label>
                                </div>
                                <div class="col-sm-4">
                                    <input type="number" class="form-control" name="dob" value="<?php echo htmlspecialchars($row['dob']); ?>">
                                </div>
                            </div>

                            <div class="row p-2">
                                <div class="col-sm-2 text-end">
                                    <label class="form-label">Year Died:</label>
                                </div>
                                <div class="col-sm-4">
                                    <input type="number" class="form-control" name="yrdied" value="<?php echo htmlspecialchars($row['dateofdied']); ?>">
                                </div>
                            </div>

                            <div class="row p-2">
                                <div class="col-sm-2 text-end">
                                    <label class="form-label">Bio:</label>
                                </div>
                                <div class="col-sm-4">
                                    <textarea name="bio" class="form-control"><?php echo htmlspecialchars($row['bio']); ?></textarea>
                                </div>
                                <div class="col-sm-2 text-end">
                                    <label class="form-label">Publications:</label>
                                </div>
                                <div class="col-sm-4">
                                    <textarea name="pub" class="form-control"><?php echo htmlspecialchars($row['publications']); ?></textarea>
                                </div>
                            </div>

                            <div class="row p-2">
                                <div class="col-sm-2 text-end">
                                    <label class="form-label">Awards:</label>
                                </div>
                                <div class="col-sm-4">
                                    <textarea name="awards" class="form-control"><?php echo htmlspecialchars($row['awards']); ?></textarea>
                                </div>
                                <div class="col-sm-2 text-end">
                                    <label class="form-label">References:</label>
                                </div>
                                <div class="col-sm-4">
                                    <textarea name="ref" class="form-control"><?php echo htmlspecialchars($row['ref']); ?></textarea>
                                </div>
                            </div>                            

                            <div class="row p-2">
                                <div class="col-sm-2 text-end"></div>
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

<script>
    // Country search
    function country(str) {  
        if (str.length == 0) {
            document.getElementById("resultcountry").innerHTML = "";
            document.getElementById("resultcountry").style.display = "none";
            return;
        } else {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("resultcountry").innerHTML = this.responseText;
                    document.getElementById("resultcountry").style.display = "block";
                }
            };
            xmlhttp.open("GET", "search.php?q=" + str + "&field=c", true);
            xmlhttp.send();
        }
    }

    document.getElementById("country").addEventListener("input", function() {
        country(this.value);
    });

    document.getElementById("resultcountry").addEventListener("click", function(e) {
        if (e.target.classList.contains("result-item")) {
            document.getElementById("country").value = e.target.textContent;
            this.style.display = "none";
        }
    });
</script>
</body>
</html>

<?php 
mysqli_close($dbcon);
?>
