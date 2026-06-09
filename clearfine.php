<?php
session_start();
error_reporting(0);
include('includes/config.php');

// check admin login
if(strlen($_SESSION['alogin'])==0){
    header('location:index.php');
    exit();
}

if(isset($_GET['id'])){

    $membernumber = $_GET['id'];

    $stmt = mysqli_prepare($dbcon,
    "UPDATE member SET fine_pending=0, fine_comment=NULL WHERE cardnumber=?");

    mysqli_stmt_bind_param($stmt,'s',$membernumber);

    if(mysqli_stmt_execute($stmt)){
        echo "<script>
        alert('Fine comment cleared successfully');
        window.location='member-list.php';
        </script>";
    }else{
        echo "<script>
        alert('Error clearing fine');
        window.history.back();
        </script>";
    }

}else{
    echo "<script>
    alert('Invalid request');
    window.history.back();
    </script>";
}
?>