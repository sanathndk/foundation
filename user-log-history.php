<?php
session_start();
include('includes/config.php');

// Optional: Only Staff can access
if (!isset($_SESSION['user_group']) || $_SESSION['user_group'] !== 'Staff') {
    echo "Access Denied!";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Login History</title>
  <link rel="icon" href="img/logo.png" type="image/png">
  <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

           <div class="row justify-content-md-center"> 
                <div class="col-sm-10">
                    <h3>User Login & Activity History</h3>
                </div>
            </div>
				<div class="row justify-content-md-center">							
                    <div class="col-sm-11">			
                        <table class="table table-striped table-bordered table-hover align-middle table-responsive" id="dataTables">										
                            <thead class="text-center">
                                <tr>
                                    <th>Ser</th>
                                    <th>Username</th>
                                    <th>IP Address</th>
                                    <th>Login Time</th>
                                    <th>Logout Time</th>
                                    <th>Session ID</th>
                                    <th>User Actions</th> 
                                </tr>
                            </thead>
                                <tbody class="table-group-divider">
                                        <?php   
                                            $sql = "SELECT * FROM user_logs ORDER BY login_time DESC";
                                            $result = $dbcon->query($sql);
                                            $count = 1;
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<tr>";
                                                echo "<td>" . $count++ . "</td>";
                                                echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                                                echo "<td>" . $row['ip_address'] . "</td>";
                                                echo "<td>" . $row['login_time'] . "</td>";
                                                echo "<td>" . ($row['logout_time'] ?? '<span class=\"text-danger\">Still Logged In</span>') . "</td>";
                                                echo "<td><code>" . $row['session_id'] . "</code></td>";
                                                echo "<td><pre>" . htmlspecialchars($row['action']) . "</pre></td>";
                                                echo "</tr>";
                                            }
                                        ?>
                                </tbody>
                        </table>
                    </div>													
                </div>
                </div>		
                <?php include('includes/footer.php');?>  
            </div> 
</body>
</html>