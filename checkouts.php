<?php
session_start();
error_reporting(0);
include('includes/config.php');
include('includes/activity.php');

logAction($dbcon, "checkouts");
$token = rand();

if (strlen($_SESSION['alogin']) == 0) {   
    header('location:index.php');
    exit;
}

$error = "";
$error1 = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if ($_SESSION['csrf_token'] != $_POST['csrf_token']) {
        $error = "Invalid <strong>authentication</strong>";
    } 
    else if (isset($_POST['btncheckouts'])) {

        $membernumber = trim($_POST['membernumber']);
        $booknumber   = trim($_POST['booknumber']);
        $issuesdate   = date('Y-m-d');
        $status       = 0;

        // 🔍 Find member by ID OR Registration Number
        $sql1 = mysqli_query($dbcon,
            "SELECT * FROM member 
             WHERE cardnumber='$membernumber'
             OR regtnumber='$membernumber'"
        );

        if (mysqli_num_rows($sql1) == 0) {
            $error = "Member not found!";
        } 
        else {
            $member = mysqli_fetch_assoc($sql1);
            $member_id   = $member['cardnumber'];
            $membername  = $member['surname']." ".$member['firstname'];
            $category    = $member['categorycode'];
            $active      = $member['status'];

            if ($active != 1) {
                $error = "<strong>$membername</strong> is not activated!";
            } 
            else {
                // 🔍 Find book
                $sql2 = mysqli_query($dbcon,"SELECT * FROM catalog WHERE booknumber='$booknumber'");
                if (mysqli_num_rows($sql2) == 0) {
                    $error = "Book ID <strong>$booknumber</strong> not found.";
                } 
                else {
                    $catalog   = mysqli_fetch_assoc($sql2);
                    $bookname  = $catalog['title'];
                    $findtype  = $catalog['itemtype'];
                    $bookstatus= $catalog['status'];

                    // 🔍 Find fine rules
                    $sql3 = mysqli_query($dbcon,"SELECT * FROM finerules 
                        WHERE category='$category' AND itemtype='$findtype'");
                    $finerules     = mysqli_fetch_assoc($sql3);
                    $loanperiod    = $finerules['loanperiod'];
                    $checkoutallow = $finerules['checkoutallow'];

                    // 🔍 Check member issued books
                    $issuedbook = mysqli_query($dbcon,
                        "SELECT * FROM issuedbook 
                         WHERE membernumber='$member_id' AND RetrunStatus=0"
                    );

                    if ($bookstatus != 'A') {
                        if ($bookstatus == 'L') $error = "This item is <strong>Lost</strong>";
                        elseif ($bookstatus == 'D') $error = "This item is <strong>Damaged</strong>";
                        else $error = "This item is not available.";
                    }
                    elseif ($checkoutallow == 0) {
                        $error = "This member type <strong>$category</strong> cannot borrow books.";
                    }
                    elseif (mysqli_num_rows($issuedbook) >= $checkoutallow) {
                        $error = "Member already issued <strong>maximum number</strong> of books.";
                    }
                    else {
                        // 🔍 Check if book already issued
                        $sql4 = mysqli_query($dbcon,
                            "SELECT * FROM issuedbook 
                             WHERE booknumber='$booknumber' AND RetrunStatus=0"
                        );

                        if (mysqli_num_rows($sql4) != 0) {
                            $error = "Book <strong>$booknumber</strong> is already issued.";
                        }
                        else {
                            // 🔹 Issue book
                            $returndate = date("Y-m-d", strtotime("+$loanperiod days"));

                            $sql = "INSERT INTO issuedbook(membernumber,booknumber,IssuesDate,ReturnDate,RetrunStatus)
                                    VALUES(?,?,?,?,?)";
                            $stmt = mysqli_prepare($dbcon,$sql);
                            mysqli_stmt_bind_param($stmt,'sssss',
                                $member_id,$booknumber,$issuesdate,$returndate,$status
                            );

                            // Update catalog
                            $update = mysqli_prepare($dbcon,
                                "UPDATE catalog SET checkedin=? WHERE booknumber=?"
                            );
                            mysqli_stmt_bind_param($update,'ss',$status,$booknumber);

                            if (mysqli_stmt_execute($stmt) && mysqli_stmt_execute($update)) {
                                $error1 = "
                                    <div>
                                        <b>Checkout Successful!</b><br><br>
                                        Member: <strong>$membername</strong><br>
                                        Member ID: <strong>$member_id</strong><br><br>

                                        Book: <strong>$bookname</strong><br>
                                        Book ID: <strong>$booknumber</strong><br><br>

                                        Return Date: <strong>$returndate</strong>
                                    </div>
                                ";
                            } else {
                                $error = "Database Error: " . mysqli_error($dbcon);
                            }
                        }
                    }
                }
            }
        }
    }
}

$_SESSION['csrf_token'] = $token;
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Issue a new Book | Library Management System</title>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" href="img/logo.png" type="image/png">
    <meta name="viewport" content="width=device-width, initial-scale=1">       
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
                            <h2 class="title">Issue a new Book</h2>
                        </div>                                
                    </div>

                    <div class="row breadcrumb-div">
                        <div class="col-md-6">
                            <ul class="breadcrumb">
                                <li><a href="dashboard.php"><i class="fa fa-home"></i> Home /&nbsp;</a></li>
                                <li><a href="#">Circulations /&nbsp; </a></li>
                                <li class="active">Checkouts</li>
                            </ul>
                        </div>                               
                    </div>
                </div>

                <div class="container">                 
                    <form name="signup" method="post">
                        <br>
                        <fieldset class="border">                                
                            <div class="row p-2">
                                <div class="col-sm-2 text-end">
                                    <label class="form-label">Member ID / Regt No:</label>
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" name="membernumber" placeholder="Enter Member ID or Registration Number" required>
                                    <span class="text-danger font-weight-bold"><?php echo $error?></span>
                                </div>
                            </div>

                            <div class="row p-2">
                                <div class="col-sm-2 text-end">
                                    <label class="form-label">Book ID:</label>
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" name="booknumber" placeholder="Enter Book ID" required>
                                    <span class="text-success font-weight-bold"><?php echo $error1?></span>
                                </div>
                            </div>        

                            <div class="row p-2">
                                <div class="col-sm-2 text-end"></div>
                                <div class="col-sm-4">
                                    <input type="hidden" name="csrf_token" value="<?php echo $token?>">
                                    <button type="submit" class="btn btn-warning" name="btncheckouts">
                                        <i class="bi bi-arrow-bar-right"></i>&nbsp; Issue
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
