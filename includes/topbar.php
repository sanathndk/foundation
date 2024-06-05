
<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])=="")
    {   
    header("Location: index.php"); 
    }
    else{
      $image=$_SESSION['image'];
    ?>

 
  <link href="css/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="css/topbar.css" rel="stylesheet">

  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="dashboard.php" class="logo d-flex align-items-center">
        <img src="img/logo.png" alt="">
        <span class="d-none d-lg-block">Foundation</span>
      </a>
      <!-- <i class="bi bi-list toggle-sidebar-btn"></i> -->
    </div><!-- End Logo -->

    <div class="search-bar">
      <form class="search-form lign-items-center d-flex" method="get" action="dashboardsearch.php">
        <div class="lign-items-center col-3 ">
          <select class="form-select d-flex" value="searchtype" name="searchtype">
            <option name="catalog" value="catalog">Catalogue</option>            
            <option name="member" value="member">Member</option>
          </select>
        </div>
        <div class="col-auto">&nbsp;</div>
        <div class="d-flex col-10">
          <input type="search" name="keyword" placeholder="Enter search keyword" title="Enter search keyword">
          <button type="submit" title="Search" name="btnsearch"><i class="bi bi-search"></i></button>
        </div>
      </form>
    </div><!-- End Search Bar -->

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">       

        <li class="nav-item pe-3">       
          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">         
            <img src="<?php echo $_SESSION['image']?>" alt="Profile" class="rounded-circle">
            <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo $_SESSION['user_name']; ?></span>
          </a><!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6><?php echo $_SESSION['user_name']; ?></h6>
              <span><?php echo $_SESSION['user_group']; ?></span>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="profile.php?id=<?php echo $_SESSION['user_id']; ?>">
                <i class="bi bi-person"></i>
                <span>My Profile</span>
              </a>
            </li>
                      
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="contact.php">
              <i class="bi bi-mailbox-flag"></i>
                <span>Contac Us</span>
              </a>
            </li>

            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="logout.php">
                <i class="bi bi-box-arrow-right text-danger"></i>
                <span class="text-danger"><strong>Sign Out</strong> </span>
              </a>
            </li>

          </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->

      </ul>
    </nav><!-- End Icons Navigation -->
    <script src="js/main.js"></script>

  </header><!-- End Header -->
  <?php } ?>