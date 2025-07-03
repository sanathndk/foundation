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
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { padding: 20px; }
    table { font-size: 0.9rem; }
    pre { white-space: pre-wrap; }
  </style>
</head>
<body>
  <h3>User Login & Activity History</h3>

  <table class="table table-bordered table-hover">
    <thead class="table-dark">
      <tr>
        <th>#</th>
        <th>Username</th>
        <th>IP Address</th>
        <th>Login Time</th>
        <th>Logout Time</th>
        <th>Session ID</th>
        <th>User Actions</th>
      </tr>
    </thead>
    <tbody>
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
</body>
</html>
