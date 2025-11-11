<?php
session_start();
error_reporting(0);
include('includes/config.php');
include('includes/activity.php');

logAction($dbcon, "Add_fine_rules");

if(strlen($_SESSION['alogin']) == 0){
    header('location:index.php');
    exit;
}

// CSRF token for form
if(!isset($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
$token = $_SESSION['csrf_token'];

$error = '';
$success = '';

if(isset($_POST['btnsave']) && $_POST['csrf_token'] == $_SESSION['csrf_token']) {

    // Collect and validate inputs
    $category = $_POST['category'];
    $itemtype = $_POST['itemtype'];
    $checkoutallow = intval($_POST['checkoutallow']);
    $library = $_POST['library'];
    $loanperiod = intval($_POST['loanperiod']);
    $fineamount = floatval($_POST['fineamount']);
    $renewalallow = intval($_POST['renewalallow']);
    $renewalperiod = intval($_POST['renewalperiod']);

    // Insert using prepared statement
    $sql = "INSERT INTO `finerules`(`category`, `itemtype`, `checkoutallow`, `library`, `loanperiod`, `fineamount`, `renewalallow`, `renewalperiod`) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($dbcon, $sql);

    if($stmt){
        mysqli_stmt_bind_param($stmt, "sssiidii", $category, $itemtype, $checkoutallow, $library, $loanperiod, $fineamount, $renewalallow, $renewalperiod);
        if(mysqli_stmt_execute($stmt)){
            $success = "Record added successfully!";
            header("location:add_fine_rules.php");
            exit;
        } else {
            $error = "Error inserting data: ".mysqli_error($dbcon);
        }
        mysqli_stmt_close($stmt);
    } else {
        $error = "Database error: ".mysqli_error($dbcon);
    }
}

// Delete record safely
if(isset($_GET['id'])){
    $id = intval($_GET['id']);
    $stmt = mysqli_prepare($dbcon, "DELETE FROM finerules WHERE id=?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("location:add_fine_rules.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Fine Rules | Library Management System</title>
    <meta charset="utf-8" />
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
                <h2>Add Fine Rules</h2>
            </div>

            <div class="container">
                <?php if($error) echo "<div class='text-danger'>$error</div>"; ?>
                <?php if($success) echo "<div class='text-success'>$success</div>"; ?>

                <form method="post">
                    <input type="hidden" name="csrf_token" value="<?php echo $token; ?>">
                    <div>
                        <label>Member category:</label>
                        <select name="category" required>
                            <option value="">Select</option>
                            <?php
                            $sql1 = mysqli_query($dbcon,"SELECT * FROM `membergroups`");
                            while($row = mysqli_fetch_assoc($sql1)){
                                echo "<option value='".htmlspecialchars($row['categorycode'])."'>".htmlspecialchars($row['categorycode'])."</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div>
                        <label>Item type:</label>
                        <select name="itemtype" required>
                            <option value="">Select</option>
                            <?php
                            $sql2 = mysqli_query($dbcon,"SELECT * FROM `itemtypes`");
                            while($row = mysqli_fetch_assoc($sql2)){
                                echo "<option value='".htmlspecialchars($row['itemcode'])."'>".htmlspecialchars($row['description'])."</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div>
                        <label>Checkouts allowed:</label>
                        <input type="number" name="checkoutallow" required>
                    </div>

                    <div>
                        <label>Library:</label>
                        <select name="library" required>
                            <option value="all">All Library</option>
                            <?php
                            $sql3 = mysqli_query($dbcon,"SELECT * FROM branches");
                            while($row = mysqli_fetch_assoc($sql3)){
                                echo "<option value='".htmlspecialchars($row['branchcode'])."'>".htmlspecialchars($row['name'])."</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div>
                        <label>Loan period (days):</label>
                        <input type="number" name="loanperiod" required>
                    </div>

                    <div>
                        <label>Fine amount:</label>
                        <input type="number" name="fineamount" step="0.01" required>
                    </div>

                    <div>
                        <label>Renewals allowed:</label>
                        <input type="number" name="renewalallow" required>
                    </div>

                    <div>
                        <label>Renewal period (days):</label>
                        <input type="number" name="renewalperiod" required>
                    </div>

                    <button type="submit" name="btnsave">Save</button>
                </form>

                <h3>List of Fine Rules</h3>
                <table border="1">
                    <thead>
                        <tr>
                            <th>Ser</th>
                            <th>Category</th>
                            <th>Item type</th>
                            <th>Checkouts</th>
                            <th>Loan period</th>
                            <th>Fine</th>
                            <th>Renewals</th>
                            <th>Renewal period</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM finerules";
                        $res = mysqli_query($dbcon,$sql);
                        $ser = 1;
                        while($row = mysqli_fetch_assoc($res)){
                            echo "<tr>
                                <td>".$ser++."</td>
                                <td>".htmlspecialchars($row['category'])."</td>
                                <td>".htmlspecialchars($row['itemtype'])."</td>
                                <td>".htmlspecialchars($row['checkoutallow'])."</td>
                                <td>".htmlspecialchars($row['loanperiod'])."</td>
                                <td>".htmlspecialchars($row['fineamount'])."</td>
                                <td>".htmlspecialchars($row['renewalallow'])."</td>
                                <td>".htmlspecialchars($row['renewalperiod'])."</td>
                                <td>
                                    <a href='edit_fine_rules.php?id=".$row['id']."'>Edit</a>
                                    <a href='add_fine_rules.php?id=".$row['id']."' onclick=\"return confirm('Are you sure?');\">Delete</a>
                                </td>
                            </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <?php include('includes/footer.php'); ?>
        </div>
    </div>
</div>
</div>
</body>
</html>
<?php mysqli_close($dbcon); ?>
