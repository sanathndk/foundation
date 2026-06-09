<?php
session_start();
error_reporting(0);
include('includes/config.php');
include('includes/activity.php');

logAction($dbcon, "member_history");

if(strlen($_SESSION['alogin'])==0){   
    header('location:index.php');
    exit;
}

$searchMember = "";
if(isset($_GET['membernumber'])){
    $searchMember = trim($_GET['membernumber']);
}

// Handle delete request
if(isset($_GET['delete_id']) && is_numeric($_GET['delete_id'])){
    $delete_id = intval($_GET['delete_id']);
    
    // First, get the book number to update catalog status
    $getBookSql = "SELECT booknumber, RetrunStatus FROM issuedbook WHERE issueid = ?";
    $stmt = mysqli_prepare($dbcon, $getBookSql);
    mysqli_stmt_bind_param($stmt, "i", $delete_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $bookData = mysqli_fetch_assoc($result);
    
    if($bookData){
        $booknumber = $bookData['booknumber'];
        $currentStatus = $bookData['RetrunStatus'];
        
        // If the book is currently issued (not returned), update catalog status
        if($currentStatus == 0){
            $updateCatalog = "UPDATE catalog SET checkedin = 1 WHERE booknumber = ?";
            $stmt2 = mysqli_prepare($dbcon, $updateCatalog);
            mysqli_stmt_bind_param($stmt2, "s", $booknumber);
            mysqli_stmt_execute($stmt2);
        }
        
        // Delete the record from issuedbook
        $deleteSql = "DELETE FROM issuedbook WHERE issueid = ?";
        $stmt3 = mysqli_prepare($dbcon, $deleteSql);
        mysqli_stmt_bind_param($stmt3, "i", $delete_id);
        
        if(mysqli_stmt_execute($stmt3)){
            $_SESSION['success_msg'] = "Record deleted successfully!";
        } else {
            $_SESSION['error_msg'] = "Error deleting record: " . mysqli_error($dbcon);
        }
    } else {
        $_SESSION['error_msg'] = "Record not found!";
    }
    
    // Redirect back to the same page with search parameter
    $redirectUrl = "member_history.php";
    if(!empty($searchMember)){
        $redirectUrl .= "?membernumber=" . urlencode($searchMember);
    }
    header("Location: " . $redirectUrl);
    exit;
}

// Display messages
if(isset($_SESSION['success_msg'])){
    $success_msg = $_SESSION['success_msg'];
    unset($_SESSION['success_msg']);
}
if(isset($_SESSION['error_msg'])){
    $error_msg = $_SESSION['error_msg'];
    unset($_SESSION['error_msg']);
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Member History | Library Management System</title>
<link rel="stylesheet" href="css/jquery.dataTables.min.css">
<link rel="icon" href="img/logo.png" type="image/png">
<style>
    .delete-btn {
        background-color: #dc3545;
        color: white;
        border: none;
        padding: 5px 10px;
        border-radius: 3px;
        cursor: pointer;
        font-size: 12px;
        transition: background-color 0.3s;
    }
    .delete-btn:hover {
        background-color: #c82333;
    }
    .confirm-delete {
        background-color: #ffc107;
        color: #000;
        border: none;
        padding: 5px 10px;
        border-radius: 3px;
        cursor: pointer;
        font-size: 12px;
        margin-left: 5px;
    }
    .confirm-delete:hover {
        background-color: #e0a800;
    }
    .alert {
        padding: 10px;
        margin-bottom: 15px;
        border-radius: 4px;
    }
    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
</style>
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
                        <h2 class="title">Member History</h2>
                    </div>
                </div>
                <div class="row breadcrumb-div">
                    <div class="col-md-6">
                        <ul class="breadcrumb">
                            <li><a href="dashboard.php"><i class="fa fa-home"></i> Home / </a></li>
                            <li><a href="#">Report / </a></li>
                            <li class="active">Member History</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Search Form -->
            <div class="container">
                <div class="row justify-content-md-center">
                    <div class="col-md-10">
                    
                    <!-- Display success/error messages -->
                    <?php if(isset($success_msg)): ?>
                        <div class="alert alert-success"><?php echo $success_msg; ?></div>
                    <?php endif; ?>
                    <?php if(isset($error_msg)): ?>
                        <div class="alert alert-danger"><?php echo $error_msg; ?></div>
                    <?php endif; ?>
                    
                        <form method="GET" class="mb-3">
                            <div class="row p-2">
                                <br>
                                <div class="row justify-content-center">
                                    <div class="col-md-4">
                                        <input type="text" name="membernumber" class="form-control" placeholder="Search Reg Number or Name" value="<?php echo htmlentities($searchMember); ?>">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-primary">Search</button>
                                    </div>
                                    <?php if(!empty($searchMember)): ?>
                                    <div class="col-md-2">
                                        <a href="member_history.php" class="btn btn-secondary">Clear Search</a>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>    
                        </form>

                        <!-- Table -->
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover" id="dataTables">
                                        <thead>
                                            <tr>
                                                <th>Ser</th>
                                                <th>Reg Number</th>
                                                <th>Member Name</th>
                                                <th>Book Name</th>
                                                <th>Book ID</th>
                                                <th>Issued Date</th>
                                                <th>Return Date</th>
                                                <th>Status</th>
                                                <th>Fine</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $cnt = 0;
                                                $hasRecords = false;

                                                // Base SQL query
                                                $sql = "SELECT ib.*, 
                                                            ibv.title,
                                                            m.initials, 
                                                            m.surname, 
                                                            m.firstname, 
                                                            m.regtnumber
                                                        FROM issuedbook ib
                                                        LEFT JOIN member m 
                                                            ON ib.membernumber = m.cardnumber
                                                        LEFT JOIN issuedbook_view ibv
                                                            ON ib.booknumber = ibv.booknumber";

                                                // Search condition
                                                $params = [];
                                                if($searchMember != ""){
                                                    $sql .= " WHERE (m.regtnumber LIKE ? 
                                                                OR m.initials LIKE ? 
                                                                OR m.surname LIKE ? 
                                                                OR m.firstname LIKE ?)";
                                                    
                                                    $search = "%".$searchMember."%";
                                                    $params = [$search, $search, $search, $search];
                                                }

                                                // Order
                                                $sql .= " ORDER BY ib.RetrunStatus ASC, ib.IssuesDate DESC";

                                                // Prepare
                                                $stmt = mysqli_prepare($dbcon, $sql);

                                                if($stmt === false){
                                                    echo '<tr><td colspan="10" class="text-center">SQL Error: '.mysqli_error($dbcon).'</td></tr>';
                                                } 
                                                else {

                                                    if(!empty($params)){
                                                        mysqli_stmt_bind_param($stmt, "ssss", ...$params);
                                                    }

                                                    mysqli_stmt_execute($stmt);
                                                    $result = mysqli_stmt_get_result($stmt);

                                                    if($result && mysqli_num_rows($result) > 0){
                                                        $hasRecords = true;
                                                        while($row = mysqli_fetch_assoc($result)){
                                                            $cnt++;

                                                            // Status
                                                            $status = ($row['RetrunStatus']==1)
                                                                ? "<span class='text-success'><strong>Returned</strong></span>"
                                                                : "<span class='text-warning'><strong>Issued</strong></span>";

                                                            // Member Name
                                                            $memberName = !empty($row['regtnumber'])
                                                                ? trim($row['initials'].' '.$row['surname'])
                                                                : "Unknown Member";

                                                            // FINE CALCULATION
                                                            $fine = 0;
                                                            $today = date('Y-m-d');
                                                            $returnDate = $row['ReturnDate'];

                                                            if($row['RetrunStatus'] == 0 && $today > $returnDate){
                                                                $daysLate = floor((strtotime($today) - strtotime($returnDate)) / (60*60*24));
                                                                $fine = $daysLate * 10; // Rs.10 per day
                                                            }

                                                            // Fine display
                                                            if($fine > 0){
                                                                $fineDisplay = "<span class='text-danger'><strong>Rs. ".number_format($fine,2)."</strong></span>";
                                                            } else {
                                                                $fineDisplay = "<span class='text-success'>No Fine</span>";
                                                            }

                                                            // Delete button with confirmation
                                                            $deleteUrl = "member_history.php?delete_id=" . $row['issueid'];
                                                            if(!empty($searchMember)){
                                                                $deleteUrl .= "&membernumber=" . urlencode($searchMember);
                                                            }
                                                            
                                                            $actionButtons = "
                                                                <button class='delete-btn' onclick='confirmDelete(" . $row['issueid'] . ", \"" . addslashes($row['title']) . "\")'>
                                                                    <i class='fa fa-trash'></i> Delete
                                                                </button>
                                                            ";

                                                            echo "<tr>
                                                                    <td class='text-center'>{$cnt}</td>
                                                                    <td>" . htmlentities($row['regtnumber'] ?? '') . "</td>
                                                                    <td>" . htmlentities($memberName) . "</td>
                                                                    <td>" . htmlentities($row['title'] ?? '') . "</td>
                                                                    <td>" . htmlentities($row['booknumber']) . "</td>
                                                                    <td>" . htmlentities($row['IssuesDate']) . "</td>
                                                                    <td>" . htmlentities($row['ReturnDate']) . "</td>
                                                                    <td>{$status}</td>
                                                                    <td>{$fineDisplay}</td>
                                                                    <td class='text-center'>{$actionButtons}</td>
                                                                   </tr>";
                                                        }

                                                    } else {
                                                        echo '<tr><td colspan="10" class="text-center">No records found</td></tr>';
                                                    }
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div> <!-- panel -->
                    </div> <!-- col-md-10 -->
                </div> <!-- row -->
            </div> <!-- container -->
            <?php include('includes/footer.php'); ?>
        </div> <!-- main-page -->
    </div>    
</div>
</div> <!-- main-wrapper -->

<script src="js/jquery-3.7.0.js"></script>
<script src="js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
    // Check if table has data before initializing DataTable
    var table = $('#dataTables');
    var hasRows = table.find('tbody tr').length > 0;
    
    if (hasRows) {
        // Check if the first row has colspan (which would mean no data message)
        var firstRowColspan = table.find('tbody tr:first td').attr('colspan');
        
        if (!firstRowColspan) {
            // Initialize DataTable only if there's actual data
            $('#dataTables').DataTable({
                dom: 'Bfrtip',
                searching: false,
                lengthChange: false,
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            });
        } else {
            // Just show a simple table without DataTable features
            console.log('No records found, skipping DataTable initialization');
        }
    } else {
        console.log('Table is empty, skipping DataTable initialization');
    }
});

function confirmDelete(issueId, bookTitle) {
    if(confirm("Are you sure you want to delete the record for book: '" + bookTitle + "'?\n\nThis action cannot be undone!")) {
        window.location.href = "member_history.php?delete_id=" + issueId + "<?php echo !empty($searchMember) ? '&membernumber=' . urlencode($searchMember) : ''; ?>";
    }
}
</script>
</body>
</html>