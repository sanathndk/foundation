<?php
session_start();
error_reporting(0);
include('includes/config.php');
include('includes/activity.php');

logAction($dbcon, "Edit_Holiday");

if(strlen($_SESSION['alogin'])==0){   
    header('location:index.php');
} else {

    // Get holiday id from query string
    if(!isset($_GET['id']) || empty($_GET['id'])) {
        header('location:add_holiday.php');
        exit();
    }

    $holidayId = intval($_GET['id']);

    // Fetch holiday data
    $stmt = mysqli_prepare($dbcon, "SELECT * FROM holidays WHERE id=?");
    mysqli_stmt_bind_param($stmt, "i", $holidayId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $holiday = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if(!$holiday) {
        header('location:add_holiday.php');
        exit();
    }

    // Update holiday
    if(isset($_POST['btnupdate'])) {
        $holiday_date = $_POST['holiday_date'];
        $description = $_POST['description'];

        $updateStmt = mysqli_prepare($dbcon, "UPDATE holidays SET holiday_date=?, description=? WHERE id=?");
        mysqli_stmt_bind_param($updateStmt, "ssi", $holiday_date, $description, $holidayId);

        if(mysqli_stmt_execute($updateStmt)) {
            echo "<script>alert('Holiday updated successfully!'); window.location='add_holiday.php';</script>";
        } else {
            echo "Error updating holiday: " . mysqli_error($dbcon);
        }

        mysqli_stmt_close($updateStmt);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Edit Holiday | Library Management System</title>
    <link rel="icon" href="img/logo.png" type="image/png">
</head>
<body class="top-navbar-fixed">
    <div class="main-wrapper">
        <?php include('includes/topbar.php'); ?>
        <div class="content-wrapper">
            <div class="content-container">
                <?php include('includes/leftbar.php'); ?>

                <div class="main-page">
                    <div class="container-fluid">
                        <div class="row page-title-div">
                            <div class="col-md-6">
                                <h2 class="title">Edit Holiday</h2>
                            </div>
                        </div>
                    </div>

                    <div class="container">
                        <form method="post" class="form-controlr">
                            <fieldset class="border">
                                <div class="row p-2">
                                    <div class="col-sm-2 text-end">
                                        <label for="holiday_date" class="form-label">Holiday Date:</label>
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="date" name="holiday_date" class="form-control" value="<?php echo $holiday['holiday_date']; ?>" required>
                                    </div>

                                    <div class="col-sm-2 text-end">
                                        <label for="description" class="form-label">Description:</label>
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="text" name="description" class="form-control" value="<?php echo $holiday['description']; ?>" required>
                                    </div>
                                </div>

                                <div class="d-grid gap-2 d-md-flex justify-content-md-center mt-3">
                                    <div class="col-sm-1">
                                        <button type="submit" class="btn btn-primary btn-md" name="btnupdate">
                                            <i class="bi bi-pencil-square"></i> &nbsp;Update
                                        </button>
                                    </div>
                                    <div class="col-sm-1">
                                        <a href="add_holiday.php" class="btn btn-secondary btn-md">Cancel</a>
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
	<script src="js/jquery.dataTables.min.js"></script>
	<script>
		new DataTable('#dataTables');  
	</script>
</body>
</html>
<?php 
mysqli_close($dbcon);
} 
?>
