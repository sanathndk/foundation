<?php
session_start();
error_reporting(0);
include('includes/config.php');
include('includes/activity.php');

logAction($dbcon, "checkouts");

// Generate token
if(empty($_SESSION['csrf_token'])){
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$token = $_SESSION['csrf_token'];

if(strlen($_SESSION['alogin'])==0){   
    header('location:index.php');
    exit;
} else { 

    $error = $error1 = $returnDate = "";

    // Function to calculate due date skipping weekends & holidays
    function calculateDueDate($issueDate, $loanPeriod, $dbcon){
        $currentDate = new DateTime($issueDate);
        $daysAdded = 0;

        $holidays = [];
        $result = mysqli_query($dbcon, "SELECT holiday_date FROM holidays");
        while($row = mysqli_fetch_assoc($result)){
            $holidays[] = $row['holiday_date'];
        }

        while($daysAdded < $loanPeriod){
            $currentDate->modify('+1 day');
            $dayOfWeek = $currentDate->format('N'); // 1=Mon, 7=Sun
            $formattedDate = $currentDate->format('Y-m-d');
            if($dayOfWeek < 6 && !in_array($formattedDate, $holidays)){
                $daysAdded++;
            }
        }

        return $currentDate->format('Y-m-d');
    }

    if ($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['btncheckouts'])){

        if ($_SESSION['csrf_token'] != $_POST['csrf_token']){
            $error = "Invalid <strong>authentication</strong>";
        } else {

            $membernumber = trim($_POST['membernumber']);
            $booknumber   = trim($_POST['booknumber']);
            $issuesdate   = date('Y-m-d');
            $status       = 0;

            // Member info
            $sql1 = mysqli_query($dbcon,"SELECT * FROM `member` WHERE `cardnumber`='$membernumber'");
            $member = mysqli_fetch_assoc($sql1);
            $category = $member['categorycode'];
            $active = $member['status'];

            if ($active != 1) {
                $error = "<strong>$membernumber </strong> has not activated.";		
            } else {

                // Catalog info
                $sql2 = mysqli_query($dbcon,"SELECT * FROM `catalog` WHERE `booknumber`='$booknumber'");
                $catalog = mysqli_fetch_assoc($sql2);
                $findtype = $catalog['itemtype'];
                $bookstatus = $catalog['status'];

                // Fine rules
                $sql3 = mysqli_query($dbcon,"SELECT * FROM `finerules` WHERE `category`='$category' AND itemtype='$findtype'");
                $finerules = mysqli_fetch_assoc($sql3);
                $loanperiod = $finerules['loanperiod'];
                $checkoutallow = $finerules['checkoutallow'];

                // Already issued books
                $issuedbook = mysqli_query($dbcon,"SELECT * FROM issuedbook WHERE membernumber='$membernumber' AND RetrunStatus=0");

                if($catalog['status'] == 'A'){
                    if ($checkoutallow > 0){
                        if(mysqli_num_rows($issuedbook) < $checkoutallow){

                            $sql4 = mysqli_query($dbcon,"SELECT * FROM issuedbook WHERE booknumber='$booknumber' AND RetrunStatus=0");			

                            if(mysqli_num_rows($sql4) > 0){
                                $error = " <strong>$booknumber </strong> Already issued";		
                            } else {
                                // Calculate return date skipping weekends & holidays
                                $returnDate = calculateDueDate($issuesdate, $loanperiod, $dbcon);

                                // Insert issued book
                                $sql = "INSERT INTO `issuedbook`(`membernumber`,`booknumber`,`IssuesDate`,`ReturnDate`,`RetrunStatus`) VALUES(?,?,?,?,?)";		
                                $result = mysqli_prepare($dbcon,$sql);
                                mysqli_stmt_bind_param($result,'sssss',$membernumber,$booknumber,$issuesdate,$returnDate,$status);

                                // Update catalog
                                $update = mysqli_prepare($dbcon,"UPDATE `catalog` SET `checkedin`=? WHERE `booknumber`=?");				
                                mysqli_stmt_bind_param($update,'ss',$status,$booknumber);	

                                if(mysqli_stmt_execute($result) && mysqli_stmt_execute($update)){
                                    $error1 = 'The following items have been checked out:<strong><br>'.$booknumber.'</strong><br>Return Date: <strong>'.$returnDate.'</strong>';		
                                } else {
                                    $error = 'Something went wrong, please try again: '.mysqli_error($dbcon);		
                                }					
                            }
                        } else {
                            $error = "Already issued <strong>Maximum</strong> number of books";		
                        }
                    } else {
                        $error = "This member of the <strong>$category</strong> group is not allowed to borrow books";	
                    }
                } else {
                    if($catalog['status'] == 'L') $error = "This item is <strong>Lost</strong>";	
                    if($catalog['status'] == 'D') $error = "This item is <strong>Damaged</strong>";	
                }
            }
        }
    }

    // Refresh token
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
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
                    <form  name="signup" method="post">
                        <br>
                        <fieldset class="border">                                
                            <div class="row p-2">
                                <div class="col-sm-2 text-end">
                                    <label for="inputmember" class="form-label">Member ID:</label>
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" id="inputmember" name="membernumber" placeholder="Enter Member ID" required>
                                    <span class="text-danger font-weight-bold"><?php echo $error?></span>
                                </div>
                            </div>
                            <div class="row p-2">
                                <div class="col-sm-2 text-end">
                                    <label for="inputbook" class="form-label">Book ID:</label>
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" id="inputbook" name="booknumber" placeholder="Enter Book ID" required>
                                    <span class="text-success font-weight-bold"><?php echo $error1?></span>
                                </div>
                            </div>		
                            <div class="row p-2">
                                <div class="col-sm-2 text-end">
                                </div>
                                <div class="col-sm-4">
                                    <input type="hidden" name="csrf_token" value="<?php echo $token?>">
                                    <button type="submit" class="btn btn btn-warning" name="btncheckouts"><i class="bi bi-arrow-bar-right"></i>&nbsp; Issue</button>
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
<?php } ?>
