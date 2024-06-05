<?php 
session_start();
include('includes/config.php');
error_reporting(0);

if (isset($_GET['id'])) {
    $id=$_GET['id'];	
    $result=mysqli_query($dbcon,"SELECT * FROM member WHERE (borrowernumber = '$id' or userid = '$id')"); 
    $row= mysqli_fetch_assoc($result);
}            

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link rel="icon" href="img/logo.png" type="image/png">

    	<meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Member Profile</title>
    </head>
    <body class="top-navbar-fixed">
        <div class="main-wrapper">
            <!-- ========== TOP NAVBAR ========== -->
            <?php include('includes/topbar.php');?>   
            <!-----End Top bar-->
            <div class="content-wrapper">
                <div class="content-container">
                <!-- ========== LEFT SIDEBAR ========== -->
                    <?php include('includes/leftbar.php');?>                   
                    <!-- /.left-sidebar -->
                    <div class="main-page">
                        <div class="container-fluid">
                            <div class="row page-title-div">
                                <div class="col-md-6">
                                    <h2 class="title">Member Profile</h2>
                                </div>                                
                            </div>
                            <!-- /.row -->
                            <div class="row breadcrumb-div">
                                <div class="col-md-6">
                                    <ul class="breadcrumb">
                                        <li><a href="dashboard.php"><i class="fa fa-home"></i> Home /&nbsp;</a></li>
                                        <li class="active">Profile</li>
                                    </ul>
                                </div>                               
                            </div>
                            <!-- /.row -->
                        </div>
                        <!-- /.container-fluid -->

                        <div class="container">                 
                            <form name="signup" method="post" enctype="multipart/form-data">
							    <br>
                                <fieldset class="border">
                                    <legend class="w-auto">Member identity:</legend>                                                    
                                    <div class="row">
                                        <div class="col-sm-2 text-end">
                                            <label for="inputSalutation" class="form-label"></label>       
                                        </div>
                                        <div class="col-sm-2">       
                                            <img src="<?php echo $row['img']?>" class="rounded img-thumbnail rounded-circle" alt="Member Image" id="MemberPhoto">      
                                        </div>
                                    </div>

                                    <div class="row">                                       
                                        <div class="col-sm-2 text-end">
                                            <label for="inputSalutation" class="form-label">Salutation:</label>       
                                        </div>
                                        <div class="col-sm-2">    
                                            <input type="text" class="form-control" id="inputsalutation" name="salutation" value="<?php echo $row['title']?>" disabled>                                            
                                        </div>
                                    </div>                                  
                                                
                                    <div class="row">
                                        <div class="col-sm-2 text-end">
                                            <label for="inputSurname" class="form-label">Surname:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" id="inputSurname" name="surname" value="<?php echo $row['surname']?>" disabled>
                                        </div>
                                    
                                        <div class="col-sm-2 text-end">
                                            <label for="inputInitials" class="form-label">Initials:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" id="inputInitials" name="initials" value="<?php echo $row['initials']?>" disabled>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-2 text-end">
                                            <label for="inputFname" class="form-label">First Name:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" name="firstname" id="inputFname" value="<?php echo $row['firstname']?>" disabled>
                                        </div>
                                   
                                        <div class="col-sm-2 text-end">
                                            <label for="inputMname" class="form-label">Middle Name:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" name="middle_name" id="inputMname" value="<?php echo $row['middle_name']?>" disabled>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-2 text-end">
                                            <label for="inputOname" class="form-label">Other Name:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" name="othernames" id="inputOname" value="<?php echo $row['othernames']?>" disabled> 
                                        </div>

                                        <div class="col-sm-2 text-end">
                                            <label for="inputregtnumber" class="form-label">Army Number:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" name="regtnumber" id="inputregtnumber" value="<?php echo $row['regtnumber']?>" disabled>
                                        </div>                                       
                                    </div>

                                    <div class="row">                                        
                                        <div class="col-sm-2 text-end">
                                            <label for="inputGender" class="form-label">Gender:</label>
                                        </div>
                                        <div class="col-sm-4">
                                                <?php                                                 
                                                if($row['gender']=='M'){
                                                  echo  "<label>Male</label>";
                                                }elseif($row['gender']=='F'){
                                                    echo  "<label>Female</label>";                                               
                                                }elseif($row['gender']=='O'){
                                                    echo  "<label>Other</label>";
                                                }                                                
                                                ?>                                            
                                        </div>
                                        <div class="col-sm-2 text-end">
                                            <label for="inputdob" class="form-label">Date of Birth:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="date" class="form-control" name="dateofbirth" id="inputdob" value="<?php echo $row['dateofbirth']?>" disabled>
                                        </div>
                                    </div>
                                </fieldset>
                                <!--Main address -->
                                <fieldset class="border">
                                    <legend class="w-auto">Main address:</legend>

                                    <div class="row">
                                        <div class="col-sm-2 text-end">        
                                            <label for="inputAddress" class="form-label">Address:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" id="inputAddress" name="address" value="<?php echo $row['address']?>" disabled>
                                        </div>
                                    
                                        <div class="col-sm-2 text-end">        
                                            <label for="inputAddress2" class="form-label">Address 2:</label>
                                        </div>
                                        <div class="col-sm-4"> 
                                            <input type="text" class="form-control" id="inputAddress2" name="address2" value="<?php echo $row['address2']?>" disabled>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-2 text-end">        
                                            <label for="inputCity" class="form-label">City:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" id="inputCity" name="city" value="<?php echo $row['city']?>" disabled>
                                        </div>
                                   
                                        <div class="col-sm-2 text-end">        
                                            <label for="inputState" class="form-label">State:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" id="inputState" name="state" value="<?php echo $row['state']?>" disabled>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-2 text-end">        
                                            <label for="inputZip" class="form-label">Zip/ Postal Code:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" id="inputZip" name="zipcode" value="<?php echo $row['zipcode']?>" disabled>
                                        </div>
                                    
                                        <div class="col-sm-2 text-end">        
                                            <label for="inputCountry" class="form-label">Country:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="search" name="country" class="form-control" id="country" value="<?php echo $row['country']?>" disabled>
                                        </div>
                                    </div>
                                </fieldset>
                                <!-- Contact information -->
                                <fieldset class="border">
                                    <legend class="w-auto">Contact information:</legend>

                                    <div class="row">
                                        <div class="col-sm-2 text-end">        
                                            <label for="inputPriMob" class="form-label">Primary Mobile:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="number" class="form-control" name="mobile" id="inputPriMob" value="<?php echo $row['mobile']?>" disabled>
                                        </div>
                                   
                                        <div class="col-sm-2 text-end">        
                                            <label for="inputSecMob" class="form-label">Secondary Mobile:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="number" class="form-control" name="mobile2" id="inputSecMob" value="<?php echo $row['mobile2']?>" disabled>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-2 text-end">        
                                            <label for="inputPriEmail" class="form-label">Primary Email:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="email" class="form-control" name="email" id="inputPriEmail" value="<?php echo $row['email']?>" disabled>  
                                        </div>
                                    
                                        <div class="col-sm-2 text-end">        
                                            <label for="inputSecEmail" class="form-label">Secondary Email:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="email" class="form-control" name="email2" id="inputSecEmail" value="<?php echo $row['email2']?>" disabled>
                                        </div>
                                    </div>      
                                  
                                </fieldset>
                                <!--Alternate address -->
                                <fieldset class="border">
                                    <legend class="w-auto">Alternate address:</legend>

                                    <div class="row">
                                        <div class="col-sm-2 text-end">        
                                            <label for="inputAltAddress" class="form-label">Address:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" name="B_address" id="inputAltAddress" value="<?php echo $row['B_address']?>" disabled>
                                        </div>
                                   
                                        <div class="col-sm-2 text-end">        
                                            <label for="inputAltAddress2" class="form-label">Address 2:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" name="B_address2" id="inputAltAddress2" value="<?php echo $row['B_address2']?>" disabled>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-2 text-end">        
                                            <label for="inputAltCity" class="form-label">City:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" name="B_city" id="inputAltCity" value="<?php echo $row['B_city']?>" disabled>
                                        </div>
                                    
                                        <div class="col-sm-2 text-end">        
                                            <label for="inputAltState" class="form-label">State:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" name="B_state" id="inputAltState" value="<?php echo $row['B_state']?>" disabled>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-2 text-end">        
                                            <label for="inputAltZip" class="form-label">Zip/ Postal Code:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" name="B_zipcode" id="inputAltZip" value="<?php echo $row['B_zipcode']?>" disabled>
                                        </div>
                                  
                                        <div class="col-sm-2 text-end">        
                                            <label for="inputAltCountry" class="form-label">Country:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="search" name="B_country" class="form-control" value="<?php echo $row['B_country']?>" disabled>
                                        </div>
                                    </div>
                                </fieldset>
                                <!--Alternate Contac -->
                                
                                <fieldset class="border">
                                    <legend class="w-auto">Alternate Contact:</legend>

                                    <div class="row">
                                        <div class="col-sm-2 text-end">
                                            <label for="inputAltContname" class="form-label">Name:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control"  value="<?php echo $row['altcontactname']?>" disabled>
                                        </div>
                                  
                                        <div class="col-sm-2 text-end">
                                            <label for="inputaltcontactmobil" class="form-label">Mobile:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="number" class="form-control"  value="<?php echo $row['altcontactmobil']?>" disabled>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-2 text-end">        
                                            <label for="inputAltContAddress" class="form-label">Address:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control"  value="<?php echo $row['altcontactaddress1']?>" disabled>
                                        </div>
                                   
                                        <div class="col-sm-2 text-end">        
                                            <label for="inputAltContAddress2" class="form-label">Address 2:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control"  value="<?php echo $row['altcontactaddress2']?>" disabled>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-2 text-end">        
                                            <label for="inputAltContCity" class="form-label">City:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control"  value="<?php echo $row['altcontactcity']?>" disabled>
                                        </div>
                                   
                                        <div class="col-sm-2 text-end">        
                                            <label for="inputAltContState" class="form-label">State:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" value="<?php echo $row['altcontactstate']?>" disabled>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-2 text-end">        
                                            <label for="inputAltContZip" class="form-label">Zip/ Postal Code:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" value="<?php echo $row['altcontactzipcode']?>" disabled>
                                        </div>
                                   
                                        <div class="col-sm-2 text-end">        
                                            <label for="inputAltContCountry" class="form-label">Country:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="search" name="altcontactcountry" class="form-control" value="<?php echo $row['altcontactcountry']?>" disabled>
                                        </div>
                                    </div>

                                    <div class="row ">                                       
                                        <div class="col-sm-2 text-end">        
                                            <label for="inputAltContEmail" class="form-label">Email:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="email" class="form-control"  value="<?php echo $row['altcontactemail']?>" disabled>
                                        </div>
                                        <div class="col-sm-2 text-end">        
                                            <label for="inputrelationship" class="form-label">Relationship:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control"  value="<?php echo $row['relationship']?>" disabled>
                                        </div>
                                    </div>
                                </fieldset>
                                <!-- Library management -->
                                <fieldset class="border">
                                    <legend class="w-auto">Library management:</legend>

                                    <div class="row ">
                                        <div class="col-sm-2 text-end">        
                                            <label for="inputCardNo" class="form-label">Card Number:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" value="<?php echo $row['cardnumber']?>" disabled>  
                                        </div>
                                   
                                        <div class="col-sm-2 text-end">        
                                            <label for="inputID" class="form-label">Identification Card No:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control"  value="<?php echo $row['idcard']?>" disabled>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-2 text-end">        
                                            <label for="inputPassport" class="form-label">Passport No:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control"  value="<?php echo $row['passport']?>" disabled>
                                        </div>
                                  
                                        <div class="col-sm-2 text-end">
                                            <label for="inputLibrary" class="form-label">Library:</label>       
                                        </div>
                                        <div class="col-sm-2">        
                                            <input type="text" class="form-control"  value="<?php echo $row['branchcode']?>" disabled>                                           
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-2 text-end">
                                            <label for="inputCategory" class="form-label">Groups:</label>       
                                        </div>
                                        <div class="col-sm-2">        
                                         <input type="text" class="form-control" value="<?php echo $row['categorycode']?>" disabled>                                         
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-2 text-end">
                                            <label for="inputRegDate" class="form-label">Registration Date:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="date" class="form-control" value="<?php echo $row['dateenrolled']?>" disabled>
                                        </div>
                                  
                                        <div class="col-sm-2 text-end">
                                            <label for="inputExpDate" class="form-label">Expiry Date:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="date" class="form-control" value="<?php echo $row['dateexpiry']?>" disabled>
                                        </div>
                                    </div>
                                </fieldset>

                                <!-- OPAC/Staff interface login => Save User Table-->
                                
                                <fieldset class="border">
                                    <legend class="w-auto">OPAC/Staff interface login:</legend>
                                    
                                    <div class="row">
                                        <div class="col-sm-2 text-end">        
                                            <label for="inputUsername" class="form-label">Username:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="username" class="form-control" value="<?php echo $row['userid']?>" disabled> 
                                    </div>                           
                                    
                                </fieldset>                               
                            </form>
                        </div>
                        <div class="col"> 
                            <?php include('includes/footer.php');?>                    
                         </div>
                    </div>
                </div> 
            </div> 
        </div>   

    </body>
</html>
<?php 
mysqli_close($dbcon);
?>