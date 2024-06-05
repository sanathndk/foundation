<?php
session_start();
include('includes/config.php');
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
<html lang="en" data-bs-theme="auto">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="AS Indika">
    <meta name="generator" content="Hugo 0.118.2">
    <title>User Loging | Foundation Library Management System</title>   
    <link rel="icon" href="img/logo.png" type="image/png">
    <link href="css/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">   
    <link href="css/bootstrap.min.css" rel="stylesheet">
      
    <style>
      html,
      body {
        height: 100%;
        background-color: #3c3c3c;
        color:white;
      }

      .form-signin {
        max-width: 330px;
        padding: 1rem;
      }

      .form-signin .form-floating:focus-within {
        z-index: 2;
      }

      .form-signin input[type="email"] {
        margin-bottom: -1px;
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0;
      }

      .form-signin input[type="password"] {
        margin-bottom: 10px;
        border-top-left-radius: 0;
        border-top-right-radius: 0;
      }
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }

      .b-example-divider {
        width: 100%;
        height: 3rem;
        background-color: rgba(0, 0, 0, .1);
        border: solid rgba(0, 0, 0, .15);
        border-width: 1px 0;
        box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15);
      }

      .b-example-vr {
        flex-shrink: 0;
        width: 1.5rem;
        height: 100vh;
      }

      .bi {
        vertical-align: -.125em;
        fill: currentColor;
      }

      .nav-scroller {
        position: relative;
        z-index: 2;
        height: 2.75rem;
        overflow-y: hidden;
      }

      .nav-scroller .nav {
        display: flex;
        flex-wrap: nowrap;
        padding-bottom: 1rem;
        margin-top: -1px;
        overflow-x: auto;
        text-align: center;
        white-space: nowrap;
        -webkit-overflow-scrolling: touch;
      }

      .bd-mode-toggle {
        z-index: 1500;
      }

      .bd-mode-toggle .dropdown-menu .active .bi {
        display: block !important;
      }
      </style>
</head>
<body class="d-flex align-items-center py-4">    
    <main class="form-signin w-100 m-auto">   

        <form method="post">
            <div class="col-md-auto text-center">
                <img class="mb-4" src="img/logo.png" alt="Foundation" width="72" height="68">
            </div>
            <h1 class="h3 mb-3 fw-normal">Sign in</h1>

            <div class="form-floating">
              <input type="text" class="form-control" id="floatingInput" name="username" required placeholder="username">
              <label for="floatingInput">User Name</label>
            </div>
            <div class="form-floating">
              <input type="password" class="form-control" id="floatingPassword" name="password" placeholder="Password" required>
              <label for="floatingPassword">Password</label>
              <input type="hidden" name="csrf_token" value="<?php echo $token?>">
            </div>

            <div class="form-check text-start my-3">
              <input class="form-check-input" type="checkbox" value="remember-me" id="flexCheckDefault">
              <label class="form-check-label" for="flexCheckDefault">Remember me </label>
            </div>
            <button class="btn btn-primary w-100 py-2" type="submit" name="login">Sign in</button>
        </form>
        <div class="form-floating text-center">
        <br>
          <?php if (isset($error)): ?>
            <p class="badge bg-danger fs-6"><?php echo $error; ?></p>
          <?php endif; ?>
          <p class="mt-5 mb-3">&copy;Foundation - 2023</p>
        </div>

    </main>
</body>
</html>

<?php
    // Close the database connection
    $dbcon->close();
?>
