<?php
session_start();
include('includes/config.php');
include('includes/activity.php');

logAction($dbcon, "Loging");
$token=rand();
if(isset($_POST['login'])){

  if ($_SESSION['csrf_token']==$_POST['csrf_token']) {
    $username=$_POST['username'];
    $password=md5($_POST['password']);
    $status=1;

    $sql=mysqli_prepare($dbcon,"SELECT * FROM `member` WHERE `userid`=? && `password`=? && `status`=?");
    
    if ($sql) {
      mysqli_stmt_bind_param($sql,'sss',$username, $password, $status);
      mysqli_stmt_execute($sql);
      $result=mysqli_stmt_get_result($sql);
      if (mysqli_num_rows($result)>0) {
          $row=mysqli_fetch_array($result);
          $_SESSION['alogin'] = true;
          $_SESSION['user_id'] = $row['userid'];
          $_SESSION['user_group'] = $row['categorycode'];
          $_SESSION['user_name'] = $row['initials'].' '.$row['surname'];
          $_SESSION['image'] = $row['img'];

          $ip = $_SERVER['REMOTE_ADDR']; // Get user IP
          $loginTime = date('Y-m-d H:i:s'); // Current time
          $sessionId = session_id(); // Unique session
          $_SESSION['session_id'] = $sessionId; // Save for logout

          // Insert login info into user_logs
          $logSql = "INSERT INTO user_logs (username, ip_address, login_time, session_id) 
                    VALUES (?, ?, ?, ?)";
          $logStmt = $dbcon->prepare($logSql);
          $logStmt->bind_param("ssss", $row['userid'], $ip, $loginTime, $sessionId);
          $logStmt->execute();

          
          if ($_SESSION['user_group']=="Staff") {
            header('Location: dashboard.php');
          }else {
            header('Location: userdashboard.php');
          }
          exit;
      }else{
        $error = 'Invalied Username and Password or You are Inactiov User';
      }
    }else{
      $error = 'Something went wrong. please try again later';
    }
  }else{
    $error = 'Invalid authentication';
  }
}
$_SESSION['csrf_token']=$token;

?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login |  Library Management System</title>
  <link rel="icon" href="img/logo.png" type="image/png">

  <!-- Bootstrap CSS -->
  <link href="css/bootstrap.min.css" rel="stylesheet">

  <!-- Bootstrap Icons -->
  <link href="font/bootstrap-icons.css" rel="stylesheet">

  <!-- AdminLTE (Optional if needed) -->
  <link rel="stylesheet" href="css/adminlte.css">

  <style>
    body {
      background-color: #3c3c3c;
      color: white;
    }

    .login-container {
      max-width: 400px;
      margin: auto;
      padding: 2rem;
      background-color: #212529;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(255, 255, 255, 0.05);
    }

    .login-container img {
      max-width: 60%;
      margin-bottom: 1rem;
    }

    .login-container .form-control:focus {
      box-shadow: none;
    }

    .login-container p {
      font-size: 0.9rem;
    }
  </style>
</head>
<body class="d-flex align-items-center min-vh-100">
  <main class="login-container text-center">

    <form method="post">
      <img src="img/Army_Logo.png" alt="Army Logo">

      <h5 class="mb-3">User Login</h5>

      <div class="form-floating mb-3">
        <input type="text" class="form-control" id="floatingUsername" name="username" placeholder="Username" required>
        <label for="floatingUsername">User Name</label>
      </div>

      <div class="form-floating mb-3">
        <input type="password" class="form-control" id="floatingPassword" name="password" placeholder="Password" required>
        <label for="floatingPassword">Password</label>
      </div>

      <input type="hidden" name="csrf_token" value="<?php echo $token; ?>">

      <div class="form-check text-start mb-3">
        <input class="form-check-input" type="checkbox" id="rememberMe" name="remember">
        <label class="form-check-label" for="rememberMe">Remember me</label>
      </div>

      <button class="btn btn-primary w-100" type="submit" name="login">
        <i class="bi bi-box-arrow-in-right me-1"></i> Sign In
      </button>

      <?php if (isset($error)): ?>
        <div class="alert alert-danger mt-3 py-1" role="alert">
          <?php echo $error; ?>
        </div>
      <?php endif; ?>

      <p class="mt-4 text-muted small">Software Solution by Dte of IT - SL Army</p>
    </form>

  </main>

  <!-- Bootstrap Bundle JS -->
  <script src="/js/bootstrap.bundle.min.js"></script>
</body>
</html>


<?php
    // Close the database connection
    $dbcon->close();
?>
