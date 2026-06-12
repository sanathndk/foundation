<?php 
session_start();
error_reporting(0);
include('includes/config.php');
include('includes/activity.php');
include('includes/helper.php');

logAction($dbcon, "checkin_book");

// Persistent CSRF token
if(empty($_SESSION['csrf_token'])){
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$token = $_SESSION['csrf_token'];

// Check login
if(strlen($_SESSION['alogin'])==0){   
    header('location:index.php');
    exit;
}

// --- Handle Fine Paid / Not Paid ---
if(isset($_POST['fine_status']) && !empty($_POST['booknumber'])){
    $booknumber = $_POST['booknumber'];
    $fine_status = $_POST['fine_status'];
    $comment = isset($_POST['comment']) ? $_POST['comment'] : '';
    $fine_pending = ($fine_status == 'Paid') ? 0 : 1;

    $stmt = $dbcon->prepare("UPDATE issuedbook SET fine_status=?, fine_comment=?, fine=? WHERE booknumber=?");
    $stmt->bind_param("ssds", $fine_status, $comment, $fine_pending, $booknumber);

    if($stmt->execute()){
        $_SESSION['success_msg'] = 'Fine status saved successfully';
    } else {
        $_SESSION['error_msg'] = 'Error saving fine status';
    }

    $stmt->close();
    header('location:checkin_book.php');
    exit;
}

// --- Handle Book Checkin ---
if(isset($_POST['btnCheckin'])){
    if(isset($_POST['csrf_token']) && $_POST['csrf_token'] == $_SESSION['csrf_token']){	
        $bookid = $_POST['bookid'];
        $status = '0';			
        $success = 0;			
        $sysdate = date('Y-m-d');

        $sql = mysqli_query($dbcon,"SELECT * FROM `issuedbook` WHERE `booknumber`='$bookid' AND `RetrunStatus`='$status'");
        $issuedbook = mysqli_fetch_assoc($sql);

        if(mysqli_num_rows($sql) > 0){
            $issueId = $issuedbook['issueid'];
            $membernumber = $issuedbook['membernumber'];
            $returndate = $issuedbook['ReturnDate'];
            $booknumber = $issuedbook['booknumber'];
            $success = 1;		
            $status = '1';

            // Member info
            $sql1 = mysqli_query($dbcon,"SELECT * FROM `member` WHERE `cardnumber`='$membernumber'");
            $member = mysqli_fetch_assoc($sql1);

            $category = $member['categorycode'];
            $rank = $member['title'];
            $cardnumber = $member['cardnumber'];
            $name = $member['surname'] . ' '  . ' ' . $member['initials'];
            

            // Book info
            $sql2 = mysqli_query($dbcon,"SELECT * FROM `catalog` WHERE `booknumber`='$booknumber'");
            $catalog = mysqli_fetch_assoc($sql2);
            $bookname = $catalog['title'];
            $findtype = $catalog['itemtype'];

            // Fine rules
            $sql3 = mysqli_query($dbcon,"SELECT * FROM `finerules` WHERE `category`='$category' AND itemtype='$findtype'");
            $finerules = mysqli_fetch_assoc($sql3);
            $fineamount = $finerules['fineamount'];

            if($returndate < $sysdate){
                $totfineamount = calculateFine($returndate, $sysdate, $fineamount, $dbcon);

                // Update issuedbook with fine
                $update = mysqli_prepare($dbcon,"UPDATE `issuedbook` SET `RetrunStatus`=?, `fine`=? WHERE `booknumber`=?");
                mysqli_stmt_bind_param($update,'sds', $status, $totfineamount, $booknumber);

                // Update catalog
                $update1 = mysqli_prepare($dbcon,"UPDATE `catalog` SET `checkedin`=? WHERE `booknumber`=?");				
                mysqli_stmt_bind_param($update1,'ss', $status, $booknumber);

                if(mysqli_stmt_execute($update) && mysqli_stmt_execute($update1)){
                    $showFine = true;
                } else {
                    $error = 'Something went wrong, please try again';
                }

            } else {
                // No fine
                $update = mysqli_prepare($dbcon,"UPDATE `issuedbook` SET `RetrunStatus`=? WHERE `booknumber`=?");				
                mysqli_stmt_bind_param($update,'ss', $status, $booknumber);

                $update1 = mysqli_prepare($dbcon,"UPDATE `catalog` SET `checkedin`=? WHERE `booknumber`=?");				
                mysqli_stmt_bind_param($update1,'ss', $status, $booknumber);

                if(mysqli_stmt_execute($update) && mysqli_stmt_execute($update1)){
                    $successMsg = "The book has been successfully checked in!";
                } else {
                    $error = 'Something went wrong, please try again';
                }
            }

        } else {
            $error = '<strong>Book already checked in!</strong>';	
        }

    } else {
        echo "<script>alert('Invalid authentication')</script>";
    }
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Check in | Library Management System</title>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" href="img/logo.png" type="image/png">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        #commentBox{display:none; margin-top:10px;}
    </style>
    <script>
        function showCommentBox(){
            document.getElementById('commentBox').style.display = 'block';
        }
    </script>
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
                                <h2 class="title">Return Book</h2>
                            </div>                                
                        </div>
                        <div class="row breadcrumb-div">
                            <div class="col-md-6">
                                <ul class="breadcrumb">
                                    <li><a href="dashboard.php"><i class="fa fa-home"></i> Home /&nbsp;</a></li>
                                    <li><a href="#">Circulations /&nbsp;</a></li>
                                    <li class="active">Return Book</li>
                                </ul>
                            </div>                               
                        </div>
                    </div>

                    <div class="container">

                        <!-- Flash messages -->
                        <?php if(isset($_SESSION['success_msg'])): ?>
                            <div class="alert alert-success">
                                <?php echo $_SESSION['success_msg']; unset($_SESSION['success_msg']); ?>
                            </div>
                        <?php endif; ?>
                        <?php if(isset($_SESSION['error_msg'])): ?>
                            <div class="alert alert-danger">
                                <?php echo $_SESSION['error_msg']; unset($_SESSION['error_msg']); ?>
                            </div>
                        <?php endif; ?>

                        <!-- Book Checkin Form -->
                        <form name="checkinForm" method="post">
                            <br>
                            <fieldset class="border">                                
                                <div class="row p-2">
                                    <div class="col-sm-2 text-end">
                                        <label for="Checkinbook" class="form-label">Book ID:</label>
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" id="Checkinbook" name="bookid" required placeholder="Enter Book ID">
                                        <input type="hidden" name="csrf_token" value="<?php echo $token; ?>">

                                        <?php if(isset($success) && $success == 1): ?>
                                            <div class='border p-2 mt-3'>
                                                <strong>Member ID:</strong> <?php echo htmlspecialchars($cardnumber); ?><br>
                                                <strong>Member Rank:</strong> <?php echo htmlspecialchars($rank); ?><br>
                                                <strong>Member Name:</strong> <?php echo htmlspecialchars($name); ?><br>
                                                <strong>Book ID:</strong> <?php echo htmlspecialchars($booknumber); ?><br>
                                                <strong>Book Name:</strong> <?php echo htmlspecialchars($bookname); ?><br>

                                                <?php if(isset($showFine) && $showFine && isset($totfineamount) && $totfineamount > 0): ?>
                                                    <strong>Fine Amount:</strong> Rs. <?php echo number_format($totfineamount, 2); ?><br><br>

                                                    <!-- Fine buttons -->
                                                    <form method="post">
                                                        <input type="hidden" name="booknumber" value="<?php echo htmlspecialchars($booknumber); ?>">
                                                        <button type="submit" name="fine_status" value="Paid" class="btn btn-success">Paid</button>
                                                        <button type="button" class="btn btn-danger" onclick="showCommentBox()">Not Paid</button>

                                                        <div id="commentBox">
                                                            <textarea name="comment" class="form-control" placeholder="Write comment here"></textarea>
                                                            <button type="submit" name="fine_status" value="Not Paid" class="btn btn-primary mt-2">Save Comment</button>
                                                        </div>
                                                    </form>
                                                <?php else: ?>
                                                    <span class='text-success'><?php echo isset($successMsg) ? $successMsg : ''; ?></span>
                                                <?php endif; ?>
                                            </div>
                                        <?php elseif(!empty($error)): ?>
                                            <span class='text-danger font-weight-bold'><?php echo $error; ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>	

                                <div class="row p-2">
                                    <div class="col-sm-2 text-end"></div>
                                    <div class="col-sm-4">
                                        <button type="submit" class="btn btn-warning" name="btnCheckin">
											<i class="bi bi-arrow-bar-left"></i>&nbsp;Return
										</button>
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
</body>
</html>