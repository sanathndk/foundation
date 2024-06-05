<?php
session_start();
include('includes/config.php');
error_reporting(0);
$token=rand();
if(strlen($_SESSION['alogin'])==0) {   
header('location:index.php');
}
else{ 
    if(isset($_POST['change'])) {
      if ($_SESSION['csrf_token']==$_POST['csrf_token']) {
        $current_password=md5($_POST['password']);
        $newpassword=md5($_POST['newpassword']);
        $confirmpassword=md5($_POST['confirmpassword']);
        $username=$_SESSION['user_id'];

        if (empty($current_password) && empty($newpassword) && empty($username)) {
          $error="All fields mandatory";  
        }else{
          $sql =mysqli_prepare($dbcon,"SELECT password FROM member where userid=? and password=?");
          mysqli_stmt_bind_param($sql,'ss',$username, $current_password);
          mysqli_stmt_execute($sql);
          $result=mysqli_stmt_get_result($sql);
          $row=mysqli_fetch_assoc($result);
          $hashed_password=trim($row['password']);

           // Verify current password
        if ($current_password===$hashed_password) {
          
            $change =mysqli_prepare($dbcon,"UPDATE `member` SET `password`=? WHERE `userid`=?");  
            mysqli_stmt_bind_param($change,'ss', $newpassword, $username);

            if (mysqli_stmt_execute($change)) {   
                $msg="Your Password succesfully changed.";
            }else {
              $error='Something went wrong please try again.';	
            }
          } else {
            $error = "Current password is incorrect.";
          }          
        }
      }else{
        $error="Invalid Authentication."; 
      }
    }
  
}
  $_SESSION['csrf_token']=$token;
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="AS Indika" />
    <link rel="icon" href="img/logo.png" type="image/png">

    <title>Change Password | Foundation Library Management System</title>
   
  <style>
    .errorWrap {
    padding: 10px;
    margin: 0 0 20px 0;
    background: #fff;
    border-left: 4px solid #dd3d36;
    -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
    box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
}
.succWrap{
    padding: 10px;
    margin: 0 0 20px 0;
    background: #fff;
    border-left: 4px solid #5cb85c;
    -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
    box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
}
    </style>
</head>
<script type="text/javascript">
function valid()
{
if(document.chngpwd.newpassword.value!= document.chngpwd.confirmpassword.value)
{
alert("New Password and Confirm Password Field do not match  !!");
document.chngpwd.confirmpassword.focus();
return false;
}
return true;
}
</script>

<body>
   
<body class="top-navbar-fixed">
	<div class="main-wrapper">
		<!-- ========== TOP NAVBAR ========== -->
		<?php include('includes/usertopbar.php');?>   
		<!-----End Top bar-->
		<div class="content-wrapper">
			<div class="content-container">
			<!-- ========== LEFT SIDEBAR ========== -->
				<?php include('includes/userleftbar.php');?>                   
				<!-- /.left-sidebar -->
				<div class="main-page">
					<div class="container-fluid">
						<div class="row page-title-div">
							<div class="col-md-6">
								<h2 class="title">Change Password</h2>
							</div>                                
						</div>
						<!-- /.row -->
						<div class="row breadcrumb-div">
							<div class="col-md-6">
								<ul class="breadcrumb">
									<li><a href="userdashboard.php"><i class="fa fa-home"></i> Home /&nbsp;</a></li>
									<li><a href="#">Administration /&nbsp; </a></li>
									<li class="active">Change Password</li>
								</ul>
							</div>                               
						</div>
						<!-- /.row -->
					</div>
					<!-- /.container-fluid -->
          <div class="container">
     
        <?php if($error){?><div class="errorWrap"><strong>ERROR</strong>:<?php echo htmlentities($error); ?> </div><?php } 
                else if($msg){?><div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?> </div><?php }?>            
        <!--LOGIN PANEL START-->       
         <form role="form" method="post" onSubmit="return valid();" name="chngpwd">
					<fieldset class="border">                               

            <div class="row p-2">
              <div class="col-sm-2 text-end">
                <label class="form-label">Current Password:</label>
              </div>
              <div class="col-sm-4">
                <input class="form-control" type="password" name="password" autocomplete="off" required  />										
              </div>
            </div>            

            <div class="row p-2">
              <div class="col-sm-2 text-end">
                <label class="form-label">Enter New Password</label>
              </div>
              <div class="col-sm-4">
                <input class="form-control" type="password" name="newpassword" autocomplete="off" required  />
                <input type="hidden" name="csrf_token" value="<?php echo $token?>">
              </div>
            </div>

            <div class="row p-2">
              <div class="col-sm-2 text-end">
                <label class="form-label">Confirm Password </label>
              </div>
              <div class="col-sm-4">
                <input class="form-control"  type="password" name="confirmpassword" autocomplete="off" required  />
                <button type="submit" name="change" class="btn btn-danger">Chnage </button>
              </div>
            </div>
            </fieldset>
          </form>
        </div>
      </div>
    </div>
  </div>  
  <!---LOGIN PABNEL END-->            
           
  </div>
     <!-- CONTENT-WRAPPER SECTION END-->
 <?php include('includes/footer.php');?>

</body>
</html>
