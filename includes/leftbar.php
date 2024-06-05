<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="AS Indika - Sri Lanka - 94716593406">
    <meta name="generator" content="Hugo 0.118.2">
   
    <link rel="stylesheet" href="css/bootstrap.min.css">  
    <link rel="stylesheet" href="css/main.css"  media="screen" >
    <link rel="stylesheet" href="css/bootstrap-icons/bootstrap-icons.css" >    
    <link rel="stylesheet" href="css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="css/buttons.bootstrap5.min.css">  

<div class="left-sidebar bg-black-300 box-shadow">
    <div class="sidebar-content">
        <div class="user-info closed">           
        </div>
        <div class="sidebar-nav">
            <ul class="side-nav color-gray">
                <li class="nav-header">
                    <span class="">Main Category</span>
                </li>
                <li>
                    <a href="dashboard.php"><i class="bi bi-speedometer"></i>&nbsp;<span>Dashboard</span> </a>
                </li>
                <!-- Catalogs Modules -->
                <li class="has-children" data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="false">
                    <a href="#"><i class="bi bi-book"></i>&nbsp;<span>Catalogue</span> <i class="bi bi-caret-right arrow"></i></a>
                    
                    <div class="collapse show" id="home-collapse">
                        <ul class="child-nav">
                            <li><a href="cataloging.php"><i class="bi bi-journal-plus"></i>&nbsp; <span>Cataloguing </span></a></li>
                            <li><a href="add-category.php"><i class="bi bi-tags-fill"></i> &nbsp;<span>Categories</span></a></li>      
                            <li><a href="add-author.php"><i class="bi bi-vector-pen"></i> &nbsp;<span>Author</span></a></li>  
                            <li><a href="add-publishers.php"><i class="bi bi-printer"></i>&nbsp;<span>Publishers</span></a></li>                                       
                        </ul>
                    </div>
                </li>

                <!-- Circulations Module -->
                <li class="has-children" data-bs-toggle="collapse" data-bs-target="#dashboard-collapse" aria-expanded="false">
                    <a href="#"><i class="bi bi-arrow-repeat"></i>&nbsp; <span>Circulations</span> <i class="bi bi-caret-right arrow"></i></a>
                    <div class="collapse" id="dashboard-collapse">
                        <ul class="child-nav">
                            <li><a href="add_member.php"><i class="bi bi-person-add"></i>&nbsp;<span>Add Member </span></a></li>
                            <li><a href="checkouts.php"><i class="bi bi-arrow-bar-right"></i>&nbsp; <span>Issue Book</span></a></li>
                            <li><a href="checkin_book.php"><i class="bi bi-arrow-bar-left"></i>&nbsp; <span>Return Book</span></a></li>
                            <li><a href="renew_book.php"><i class="bi bi-shuffle"></i> &nbsp;<span>Renew Book</span></a></li>
                        </ul>
                    </div>
                </li>

                <!-- Report Module -->
                <li class="has-children" data-bs-toggle="collapse" data-bs-target="#orders-collapse" aria-expanded="false">
                    <a href="#"><i class="bi bi-file-earmark-bar-graph"></i> &nbsp;<span>Reports</span> <i class="bi bi-caret-right arrow"></i></a>
                    <div class="collapse" id="orders-collapse">
                        <ul class="child-nav">
                            <li><a href="overdue_book.php"><i class="bi bi-box-arrow-up-left"></i>&nbsp; <span>Overdue Reports</span></a></li>                                 
                            <li><a href="catalog_reports.php"><i class="bi bi-journal-bookmark-fill"></i> &nbsp;<span>Catalogue Report</span></a></li>
                            <li><a href="issued_books.php"><i class="bi bi-arrow-left-right"></i> &nbsp;<span>Circulation Report</span></a></li>                                        
                        </ul>
                    </div>
                </li>

                <!-- Administration Module -->
                <li class="has-children" data-bs-toggle="collapse" data-bs-toggle="collapse" data-bs-target="#account-collapse" aria-expanded="false">
                    <a href="#"><i class="bi bi-gear-wide-connected"></i> &nbsp;<span>Administration</span> <i class="bi bi-caret-right arrow"></i></a>                    
                    <div class="collapse" id="account-collapse">
                        <ul class="child-nav">
                            <li><a href="manage-member.php"><i class="bi bi-people-fill"></i>&nbsp;<span>Users</span></a></li>
                            <li><a href="addlib.php"><i class="bi bi-house-add-fill"></i> &nbsp;<span>Library Information</span></a></li>                                           
                            <li><a href="add-membergroup.php"><i class="bi bi-person-lines-fill"></i>&nbsp;<span>Member Groups</span></a></li>
                            <li><a href="add_salutation.php"><i class="bi bi-star-fill"></i>&nbsp;<span>Salutation</span></a></li>
                            <li><a href="add_item.php"><i class="bi bi-diagram-3"></i> &nbsp;<span>Item types</span></a></li>                                           
                            <li><a href="add_fine_rules.php"><i class="bi bi-cash-coin"></i> &nbsp;<span>Fine rules</span></a></li>                            
                        </ul>
                    </div>

                    <li><a href="change-password.php"><i class="bi bi-key"></i> &nbsp;<span>Change Password</span></a></li>                                           
                </li>
            </div>
            <!-- /.sidebar-nav -->
        </div>
        <!-- /.sidebar-content -->
    </div>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery-3.7.0.js"></script>

 



