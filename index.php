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
        .container {
            position: relative;
            max-width: 1800px;
            margin: 0 auto;
        }

        .container img {vertical-align: middle;}

        .container .content {
            position: relative;
            bottom: 100;
            background: rgb(0, 0, 0); /* Fallback color */
            background: rgba(0, 0, 0, 0.5); /* Black background with 0.5 opacity */
            color: #f1f1f1;
            width: 100%;
            padding: 10px;

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
            <p class="mt-4  text-light"> Software Solution by Dte of IT - SL Army</p>
            <!--&copy; <?php echo date('Y'); ?> -->
        </div>

    </div>
</form>
     <!-- hyper links -->
      <div class="container">
            <div class="content">
                <h2 class="section-title">DATABASES</h2>
                <span class="underline center"></span>
                <p class="lead">You can access below databases</p>    
                
                <div class="container">             
                    <div class="row p-2">
                        <div class="col-sm-2 text-end">
                            <h4>EMERALD</h4><a href="https://www.emerald.com/insight/"><img src="img/e.png" alt="emerald" style="width: 100px; height:90px;"></a>
                            <br></div>
                        <div class="col-sm-2 text-end">
                            <h4>HEINONLINE</h4><a href="https://heinonline.org/HOL/login-hol"><img src="img/hein.png" alt="heinonline" style="width: 100px; height:90px;"></a>
                            <br></div>
                        <div class="col-sm-2 text-end">
                            <h4>TURNITIN</h4><a href="https://www.turnitin.com/"><img src="img/turnitin.png" alt="turnitin" style="width: 100px; height:90px;"></a>
                            <br></div>
                        <div class="col-sm-2 text-end">
                            <h4>LEEE</h4><a href="https://ieeexplore.ieee.org/Xplore/home.jsp?reload=true"><img src="img/ieee.png" alt="ieee" style="width: 100px; height:90px;"></a>
                        </div>
                        <div class="col-sm-2 text-end">
                            <h4>TAYLOR & FRANCIS</h4><a href="https://www.taylorfrancis.com/"><img src="img/taylor_&_francis.png" alt="taylor" style="width: 100px; height:90px;"></a>
                        </div>
                        <div class="col-sm-2 text-end">
                            <h4>RESEARC4LIFE</h4><a href="https://www.research4life.org/"><img src="img/reserach.png" alt="reserach" style="width: 100px; height:90px;"></a>
                        </div>
                    </div>

                    <div class="row p-2">
                        <div class="col-sm-2 text-end">
                            <h4>HINARI</h4><a href="https://www.who.int/hinari/en/"><img src="img/hinari.png" alt="hinari" style="width: 100px; height:90px;"></a>
                        </div>
                        <div class="col-sm-2 text-end">
                            <h4>IR & KDU</h4><a href="http://ir.kdu.ac.lk/"><img src="img/kdu.jpg" alt="kdu" style="width: 100px; height:90px;"></a>
                        </div>
                        <div class="col-sm-2 text-end">
                            <h4>PAST PAPERS</h4><a href="http://192.248.104.12/"><img src="img/past.jpg" alt="past_paper" style="width: 100px; height:90px;"></a>
                        </div>
                        <div class="col-sm-2 text-end">
                            <h4>PRESENTATION</h4><a href="#"><img src="img/pp.png" alt="presantation" style="width: 100px; height:90px;"></a>
                        </div>
                    </div>
                </div>                    
            </div>
      </div>


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