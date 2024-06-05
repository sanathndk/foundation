<?php
session_start();
error_reporting(0);
include('includes/config.php');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="AS Indika - Sri Lanka - 94716593406">
    <script src="js/jquery-3.7.0.js"></script>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: center center fixed;
            background-size: cover;
            height: 100vh;
            margin: 0;
            padding: 0;
            display: flex;
            /* align-items: center; */
            justify-content: center;
        }

        .content {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }
    </style>
    <title>Foundation Library Management System</title>
    <link rel="icon" href="img/logo.png" type="image/png">
</head>

<body class="bg-dark">
    
<div class="container-fluid">

<form method="get" action="opac.php">  
    <div class="row">
        <div class="col-md-1 ms-auto"><a href="loging.php" class="btn text-light"><i class="bi bi-person-lock"></i>&nbsp;Login</a> </div>            
    </div>
    <div class="row justify-content-center align-items-center" style="height:90vh;">
        <div class="col-5 text-center">
            <div class="centered-div">
                <input type="search" name="keyword" class="form-control" placeholder="Enter search keyword" title="Enter search keyword">    
            </div>
            <p class="mt-4  text-light">&copy;Foundation - 2023</p>
        </div>

    </div>

</form>
<script>
    // Array of background images
    var backgroundImages = [
        'img/image1.avif',
        'img/image2.jpg',
        'img/image3.jpg',
        'img/image4.jpg',
        'img/image5.jpg',
        'img/image6.jpg',
    ];

    // Function to change the background image
    function changeBackground() {
        var randomIndex = Math.floor(Math.random() * backgroundImages.length);
        var imageUrl = backgroundImages[randomIndex];
        $('body').css('background-image', 'url(' + imageUrl + ')');
    }
    // Change background image every 7 seconds (7000 milliseconds)
    setInterval(function () {
        changeBackground();
    }, 7000);

    
</script>

<script src="js/bootstrap.min.js"></script>

</body>
</html>

<?php
    mysqli_close($dbcon);
?>