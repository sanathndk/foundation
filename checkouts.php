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

// Store membernumber in session if submitted - KEEP IT ALWAYS
if(isset($_POST['membernumber'])){
    $_SESSION['membernumber'] = trim($_POST['membernumber']);
} elseif(!isset($_SESSION['membernumber'])) {
    $_SESSION['membernumber'] = ''; // Initialize if not set
}

$error = "";
$error1 = "";
$success_message = "";

$allFineComments = []; // Array to store ALL fine comments

// 🔴 DELETE INDIVIDUAL COMMENT
if(isset($_POST['delete_individual_comment']) && !empty($_POST['issue_id'])){
    $issueId = intval($_POST['issue_id']);

    $stmt = $dbcon->prepare("UPDATE issuedbook 
        SET fine_comment='', fine_status='Paid' 
        WHERE issueid=?");
    $stmt->bind_param("i", $issueId);

    if($stmt->execute()){
        $_SESSION['success_msg'] = "Fine comment removed successfully!";
    } else {
        $_SESSION['error_msg'] = "Error deleting comment!";
    }

    $stmt->close();
    
    // Preserve membernumber in session and redirect
    if(isset($_POST['member_number_for_redirect'])) {
        $_SESSION['membernumber'] = $_POST['member_number_for_redirect'];
    }
    
    header("Location: checkouts.php");
    exit;
}

// Handle PRG (Post/Redirect/Get) pattern for successful checkout
if(isset($_POST['btncheckouts']) && isset($_SESSION['csrf_token']) && isset($_POST['csrf_token'])) {
    if ($_SESSION['csrf_token'] == $_POST['csrf_token']) {
        // Process the checkout
        $membernumber = trim($_POST['membernumber']);
        $booknumber   = trim($_POST['booknumber']);
        $issuesdate   = date('Y-m-d');
        $status       = 0;

        // 🔍 Find member
        $sql1 = mysqli_query($dbcon,
            "SELECT * FROM member 
             WHERE cardnumber='$membernumber'
             OR regtnumber='$membernumber'"
        );

        if (mysqli_num_rows($sql1) == 0) {
            $_SESSION['error_msg'] = "Member not found!";
            $_SESSION['membernumber'] = $membernumber; // KEEP the member number
            header("Location: checkouts.php");
            exit;
        } 
        else {
            $member = mysqli_fetch_assoc($sql1);
            $member_id   = $member['cardnumber'];
            $membername  = $member['surname']." ".$member['firstname'];
            $category    = $member['categorycode'];
            $active      = $member['status'];

            // 🔴 GET ALL UNPAID FINE COMMENTS
            $checkFine = mysqli_query($dbcon,
                "SELECT issueid, fine_comment 
                 FROM issuedbook 
                 WHERE membernumber='$member_id' 
                 AND fine_status='Not Paid' 
                 AND fine_comment != ''"
            );

            if(mysqli_num_rows($checkFine) > 0){
                // Store ALL fine comments in session for popup display
                while($fineRow = mysqli_fetch_assoc($checkFine)){
                    $_SESSION['allFineComments'][] = [
                        'issueid' => $fineRow['issueid'],
                        'comment' => $fineRow['fine_comment']
                    ];
                }
                $_SESSION['error_msg'] = "This member has unpaid fines. Please clear them first!";
                $_SESSION['membernumber'] = $membernumber; // KEEP the member number
                header("Location: checkouts.php");
                exit;
            }

            else if ($active != 1) {
                $_SESSION['error_msg'] = "<strong>$membername</strong> is not activated!";
                $_SESSION['membernumber'] = $membernumber; // KEEP the member number
                header("Location: checkouts.php");
                exit;
            } 
            else {
                // 🔍 Find book
                $sql2 = mysqli_query($dbcon,"SELECT * FROM catalog WHERE booknumber='$booknumber'");
                if (mysqli_num_rows($sql2) == 0) {
                    $_SESSION['error_msg'] = "Book ID <strong>$booknumber</strong> not found.";
                    $_SESSION['membernumber'] = $membernumber; // KEEP the member number
                    header("Location: checkouts.php");
                    exit;
                } 
                else {
                    $catalog   = mysqli_fetch_assoc($sql2);
                    $bookname  = $catalog['title'];
                    $findtype  = $catalog['itemtype'];
                    $bookstatus= $catalog['status'];

                    // 🔍 Fine rules
                    $sql3 = mysqli_query($dbcon,"SELECT * FROM finerules 
                        WHERE category='$category' AND itemtype='$findtype'");
                    $finerules     = mysqli_fetch_assoc($sql3);
                    $loanperiod    = $finerules['loanperiod'];
                    $checkoutallow = $finerules['checkoutallow'];

                    // 🔍 Member issued books
                    $issuedbook = mysqli_query($dbcon,
                        "SELECT * FROM issuedbook 
                         WHERE membernumber='$member_id' AND RetrunStatus=0"
                    );

                    if ($bookstatus != 'A') {
                        if ($bookstatus == 'L') $_SESSION['error_msg'] = "This item is <strong>Lost</strong>";
                        elseif ($bookstatus == 'D') $_SESSION['error_msg'] = "This item is <strong>Damaged</strong>";
                        else $_SESSION['error_msg'] = "This item is not available.";
                        $_SESSION['membernumber'] = $membernumber; // KEEP the member number
                        header("Location: checkouts.php");
                        exit;
                    }
                    elseif ($checkoutallow == 0) {
                        $_SESSION['error_msg'] = "This member type <strong>$category</strong> cannot borrow books.";
                        $_SESSION['membernumber'] = $membernumber; // KEEP the member number
                        header("Location: checkouts.php");
                        exit;
                    }
                    elseif (mysqli_num_rows($issuedbook) >= $checkoutallow) {
                        $_SESSION['error_msg'] = "Member already issued <strong>maximum number</strong> of books.";
                        $_SESSION['membernumber'] = $membernumber; // KEEP the member number
                        header("Location: checkouts.php");
                        exit;
                    }
                    else {
                        // 🔍 Already issued?
                        $sql4 = mysqli_query($dbcon,
                            "SELECT * FROM issuedbook 
                             WHERE booknumber='$booknumber' AND RetrunStatus=0"
                        );

                        if (mysqli_num_rows($sql4) != 0) {
                            $_SESSION['error_msg'] = "Book <strong>$booknumber</strong> is already issued.";
                            $_SESSION['membernumber'] = $membernumber; // KEEP the member number
                            header("Location: checkouts.php");
                            exit;
                        }
                        else {
                            // ✅ ISSUE BOOK
                            $returndate = date("Y-m-d", strtotime("+$loanperiod days"));

                            $sql = "INSERT INTO issuedbook(membernumber,booknumber,IssuesDate,ReturnDate,RetrunStatus)
                                    VALUES(?,?,?,?,?)";
                            $stmt = mysqli_prepare($dbcon,$sql);
                            mysqli_stmt_bind_param($stmt,'sssss',
                                $member_id,$booknumber,$issuesdate,$returndate,$status
                            );

                            $update = mysqli_prepare($dbcon,
                                "UPDATE catalog SET checkedin=? WHERE booknumber=?"
                            );
                            mysqli_stmt_bind_param($update,'ss',$status,$booknumber);

                            if (mysqli_stmt_execute($stmt) && mysqli_stmt_execute($update)) {
                                $_SESSION['success_msg'] = "
                                    <div class='border p-2 mt-3'>
                                        <b>Checkout Successful!</b><br><br>
                                        Member: <strong>$membername</strong><br>
                                        Member ID: <strong>$member_id</strong><br><br>
                                        Book: <strong>$bookname</strong><br>
                                        Book ID: <strong>$booknumber</strong><br><br>
                                        Return Date: <strong>$returndate</strong>
                                    </div>
                                ";
                                // DO NOT clear membernumber - KEEP IT for next checkout
                                // $_SESSION['membernumber'] remains the same
                                header("Location: checkouts.php");
                                exit;
                            } else {
                                $_SESSION['error_msg'] = "Database Error: " . mysqli_error($dbcon);
                                $_SESSION['membernumber'] = $membernumber; // KEEP the member number
                                header("Location: checkouts.php");
                                exit;
                            }
                        }
                    }
                }
            }
        }
    }
}

// Display messages from session
if(isset($_SESSION['error_msg'])) {
    $error = $_SESSION['error_msg'];
    unset($_SESSION['error_msg']);
}

if(isset($_SESSION['success_msg'])) {
    $success_message = $_SESSION['success_msg'];
    unset($_SESSION['success_msg']);
}

// Retrieve fine comments from session
if(isset($_SESSION['allFineComments'])) {
    $allFineComments = $_SESSION['allFineComments'];
    unset($_SESSION['allFineComments']);
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
            $(document).ready(function() {

            function updateBookCount(memberID) {
                if(memberID != '') {
                    $.ajax({
                        url: "get_book_count.php",
                        method: "POST",
                        data: {memberID: memberID},
                        dataType: "json",
                        success: function(data) {
                            if(data.success) {
                                $('#bookCount').html(data.count);
                                $('#memberHistoryLink')
                                    .css({'pointer-events':'auto','opacity':'1'})
                                    .attr('href', 'member_history.php?membernumber=' + encodeURIComponent(memberID));
                            } else {
                                $('#bookCount').html('0');
                                $('#memberHistoryLink')
                                    .css({'pointer-events':'none','opacity':'0.6'})
                                    .attr('href', '#');
                            }
                        },
                        error: function() {
                            $('#bookCount').html('Error');
                            $('#memberHistoryLink')
                                .css({'pointer-events':'none','opacity':'0.6'})
                                .attr('href', '#');
                        }
                    });
                } else {
                    $('#bookCount').html('0');
                    $('#memberHistoryLink')
                        .css({'pointer-events':'none','opacity':'0.6'})
                        .attr('href', '#');
                }
            }

            // Initial load
            var initialMemberID = $('#membernumber').val().trim();
            if(initialMemberID) {
                updateBookCount(initialMemberID);
            }

            // Trigger when typing
            $('#membernumber').on('keyup change', function() {
                var memberID = $(this).val().trim();
                updateBookCount(memberID);
            });

            // Clear the booknumber field after successful checkout but keep membernumber
            <?php if(!empty($success_message)): ?>
            // Optional: Clear only the book number field after successful checkout
            // $('input[name="booknumber"]').val('');
            <?php endif; ?>
            
            });
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
                                    <input type="text" class="form-control" id="membernumber" name="membernumber" 
                                        placeholder="Enter Member ID or Registration Number" 
                                        value="<?php echo isset($_SESSION['membernumber']) ? htmlspecialchars($_SESSION['membernumber']) : ''; ?>" required>
                                    <?php if($error): ?>
                                        <span class="text-danger font-weight-bold"><?php echo $error; ?></span>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="col-sm-2 text-end">
                                    <label class="form-label">Current Books:</label>
                                </div>
                                <div class="col-sm-4">
                                    <span id="bookCount" class="fw-bold text-primary">0</span>
                                    <a href="#" id="memberHistoryLink" class="btn btn-success" style="pointer-events: none; opacity: 0.6; margin-right: 10px;">
                                        <i class="bi bi-person-lines-fill"></i>&nbsp; 
                                    </a>
                                </div>
                            </div>

                            <div class="row p-2">
                                <div class="col-sm-2 text-end">
                                    <label class="form-label">Book ID:</label>
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" name="booknumber" placeholder="Enter Book ID" required>
                                    <?php if($success_message): ?>
                                        <span class="text-success font-weight-bold"><?php echo $success_message; ?></span>
                                    <?php endif; ?>
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

<!-- POPUP TO SHOW ALL FINE COMMENTS WITH INDIVIDUAL DELETE BUTTONS -->
<?php if(!empty($allFineComments)): ?>
<div id="finePopup" style="
    position:fixed;
    top:20%;
    left:50%;
    transform:translateX(-50%);
    background:#ffe6e6;
    border:2px solid red;
    padding:20px;
    z-index:9999;
    width:450px;
    max-height:500px;
    overflow-y:auto;
    box-shadow:0 0 10px rgba(0,0,0,0.3);
    border-radius:8px;
">
    <h4 style="color:red; margin-top:0; margin-bottom:15px;">⚠ Pending Fine Warnings</h4>
    
    <?php foreach($allFineComments as $fc): ?>
    <div style="
        border:1px solid #ff9999; 
        border-radius:5px; 
        padding:10px; 
        margin-bottom:10px;
        background:#fff5f5;
    ">
        <div style="color:red; font-weight:bold; white-space:pre-line; margin-bottom:10px;">
            <?php echo nl2br(htmlspecialchars($fc['comment'])); ?>
        </div>
        
        <form method="post" style="display:inline-block;">
            <input type="hidden" name="issue_id" value="<?php echo $fc['issueid']; ?>">
            <input type="hidden" name="member_number_for_redirect" value="<?php echo isset($_SESSION['membernumber']) ? htmlspecialchars($_SESSION['membernumber']) : ''; ?>">
            <button type="submit" name="delete_individual_comment" 
                style="background:#dc3545; color:white; border:none; padding:5px 12px; border-radius:3px; cursor:pointer; font-size:12px;">
                ✗ Delete This
            </button>
        </form>
    </div>
    <?php endforeach; ?>

    <button type="button" 
        style="background:#6c757d; color:white; border:none; padding:8px 15px; border-radius:3px; cursor:pointer; margin-top:10px; width:100%;"
        onclick="document.getElementById('finePopup').style.display='none'">
        Close
    </button>
</div>
<?php endif; ?>

</body>
</html>