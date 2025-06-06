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

    <title>Library Management System</title>
    <link rel="icon" href="img/logo.png" type="image/png">
</head>

<body class="bg-dark">
    
<div class="container-fluid">
    <form method="get" action="opac.php">  
    <div class="row">
        <div class="col-md-1 ms-auto"><a href="loging.php" class="btn text-light"><i class="bi bi-person-lock"></i>&nbsp;Login</a> </div>            
    </div>
    <div class="row justify-content-center align-items-center" style="height:10vh;">
        <div class="col-5 text-center">
            <div class="centered-div">
                <input type="search" name="keyword" class="form-control" placeholder="Enter search keyword" title="Enter search keyword">    
            </div>                        
        </div>
    </div>
</form>
<!-- Add this in your HTML file -->
<div class="container my-5">
    <div class="content text-center">
        <h2 class="section-title mb-4 text-light">Digital Library</h2>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-6 g-4">

            <!-- Card 1 -->
            <div class="col">
                <div class="card h-100 shadow-sm border-0">
                    <a href="https://www.emerald.com/insight/" target="_blank">
                        <img src="img/e.png" class="card-img-top p-3" alt="EMERALD" style="height: 100px; object-fit: contain;">
                    </a>
                    <div class="card-body text-center">
                        <h6 class="card-title">EMERALD</h6>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="col">
                <div class="card h-100 shadow-sm border-0">
                    <a href="https://heinonline.org/HOL/login-hol" target="_blank">
                        <img src="img/hein.png" class="card-img-top p-3" alt="HEINONLINE" style="height: 100px; object-fit: contain;">
                    </a>
                    <div class="card-body text-center">
                        <h6 class="card-title">HEINONLINE</h6>
                    </div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="col">
                <div class="card h-100 shadow-sm border-0">
                    <a href="https://www.turnitin.com/" target="_blank">
                        <img src="img/turnitin.png" class="card-img-top p-3" alt="TURNITIN" style="height: 100px; object-fit: contain;">
                    </a>
                    <div class="card-body text-center">
                        <h6 class="card-title">TURNITIN</h6>
                    </div>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="col">
                <div class="card h-100 shadow-sm border-0">
                    <a href="https://ieeexplore.ieee.org/Xplore/home.jsp?reload=true" target="_blank">
                        <img src="img/ieee.png" class="card-img-top p-3" alt="IEEE" style="height: 100px; object-fit: contain;">
                    </a>
                    <div class="card-body text-center">
                        <h6 class="card-title">IEEE</h6>
                    </div>
                </div>
            </div>

            <!-- Card 5 -->
            <div class="col">
                <div class="card h-100 shadow-sm border-0">
                    <a href="https://www.taylorfrancis.com/" target="_blank">
                        <img src="img/taylor_&_francis.png" class="card-img-top p-3" alt="TAYLOR & FRANCIS" style="height: 100px; object-fit: contain;">
                    </a>
                    <div class="card-body text-center">
                        <h6 class="card-title">TAYLOR & FRANCIS</h6>
                    </div>
                </div>
            </div>

            <!-- Card 6 -->
            <div class="col">
                <div class="card h-100 shadow-sm border-0">
                    <a href="https://www.research4life.org/" target="_blank">
                        <img src="img/reserach.png" class="card-img-top p-3" alt="RESEARCH4LIFE" style="height: 100px; object-fit: contain;">
                    </a>
                    <div class="card-body text-center">
                        <h6 class="card-title">RESEARCH4LIFE</h6>
                    </div>
                </div>
            </div>

            <!-- Card 7 -->
            <!-- <div class="col">
                <div class="card h-100 shadow-sm border-0">
                    <a href="https://www.who.int/hinari/en/" target="_blank">
                        <img src="img/hinari.png" class="card-img-top p-3" alt="HINARI" style="height: 100px; object-fit: contain;">
                    </a>
                    <div class="card-body text-center">
                        <h6 class="card-title">HINARI</h6>
                    </div>
                </div>
            </div> -->

            <!-- Card 8 -->
            <!-- <div class="col">
                <div class="card h-100 shadow-sm border-0">
                    <a href="http://ir.kdu.ac.lk/" target="_blank">
                        <img src="img/kdu.jpg" class="card-img-top p-3" alt="IR & KDU" style="height: 100px; object-fit: contain;">
                    </a>
                    <div class="card-body text-center">
                        <h6 class="card-title">IR & KDU</h6>
                    </div>
                </div>
            </div> -->

            <!-- Card 9 -->
            <!-- <div class="col">
                <div class="card h-100 shadow-sm border-0">
                    <a href="http://192.248.104.12/" target="_blank">
                        <img src="img/past.jpg" class="card-img-top p-3" alt="PAST PAPERS" style="height: 100px; object-fit: contain;">
                    </a>
                    <div class="card-body text-center">
                        <h6 class="card-title">PAST PAPERS</h6>
                    </div>
                </div>
            </div> -->

            <!-- Card 10 -->
            <!-- <div class="col">
                <div class="card h-100 shadow-sm border-0">
                    <a href="#">
                        <img src="img/pp.png" class="card-img-top p-3" alt="PRESENTATION" style="height: 100px; object-fit: contain;">
                    </a>
                    <div class="card-body text-center">
                        <h6 class="card-title">PRESENTATION</h6>
                    </div>
                </div>
            </div> -->

            <!-- Card 11 -->
            <div class="col">
                <div class="card h-100 shadow-sm border-0">
                    <a href="https://gutenberg.org/" target="_blank">
                        <img src="https://gutenberg.org/gutenberg/pg-logo-129x80.png" class="card-img-top p-3" alt="Project Gutenberg" style="height: 100px; object-fit: contain;">
                    </a>
                    <div class="card-body text-center">
                        <h6 class="card-title">PROJECT GUTENBERG</h6>
                    </div>
                </div>
            </div>

            <!-- Card 12 -->
            <div class="col">
                <div class="card h-100 shadow-sm border-0">
                    <a href="https://librivox.org/" target="_blank">
                        <img src="https://librivox.org/wp-content/themes/librivox/images/librivox-logo.png" class="card-img-top p-3" alt="Project Gutenberg" style="height: 100px; object-fit: contain;">
                    </a>
                    <div class="card-body text-center">
                        <h6 class="card-title">FREE PUBLIC DOMAIN AUDIO BOOKS</h6>
                    </div>
                </div>
            </div>

            <!-- Card 13 -->
            <div class="col">
                <div class="card h-100 shadow-sm border-0">
                    <a href="https://openlibrary.org/" target="_blank">
                        <img src="https://openlibrary.org/static/images/openlibrary-logo-tighter.svg" class="card-img-top p-3" alt="Project Gutenberg" style="height: 100px; object-fit: contain;">
                    </a>
                    <div class="card-body text-center">
                        <h6 class="card-title">OPEN LIBRARY</h6>
                    </div>
                </div>
            </div>

            <!-- Card 14 -->
            <div class="col">
                <div class="card h-100 shadow-sm border-0">
                    <a href="https://oapen.org/" target="_blank">
                        <img src="https://oapen.org/static-assets/images/layout/oapenlogo%2001%20colour%20-%20open%20access.png" class="card-img-top p-3" alt="Project Gutenberg" style="height: 100px; object-fit: contain;">
                    </a>
                    <div class="card-body text-center">
                        <h6 class="card-title">ONLINE LIBRARY & PUBLICATION PLATFORM</h6>
                    </div>
                </div>
            </div>            
            
            <!-- Card 15 -->
            <div class="col">
                <div class="card h-100 shadow-sm border-0">
                    <a href="https://www.doabooks.org/en" target="_blank">
                        <img src="https://www.doabooks.org/static-assets/images/layout/doab.png" class="card-img-top p-3" alt="Project Gutenberg" style="height: 100px; object-fit: contain;">
                    </a>
                    <div class="card-body text-center">
                        <h6 class="card-title">DIRECTORY OF OPEN ACCESS BOOKS</h6>
                    </div>
                </div>
            </div>

            <!-- Card 16 -->
            <div class="col">
                <div class="card h-100 shadow-sm border-0">
                    <a href="https://openresearchlibrary.org/" target="_blank">
                        <img src="https://patron-cdn-prod.biblioboard.com/static/media/logo-orl_951e920.png" class="card-img-top p-3" alt="Project Gutenberg" style="height: 100px; object-fit: contain;">
                    </a>
                    <div class="card-body text-center">
                        <h6 class="card-title">OPEN RESEARCH LIBRARY</h6>
                    </div>
                </div>
            </div>

        </div>
        <p class="mt-3">Software Solution by Dte of IT - SL Army</p>
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
