<?php 
session_start();
include('includes/config.php');
error_reporting(0);
$token=rand();
if (isset($_GET['id'])) {
    $id=$_GET['id'];	
    $result=mysqli_query($dbcon,"SELECT * FROM member WHERE (borrowernumber = '$id')"); 
    $row= mysqli_fetch_assoc($result);
}    
if(isset($_POST['btnsave'])){

    if ($_SESSION['csrf_token']==$_POST['csrf_token']) {        

        $memberphoto=$_FILES['memberphoto'];
        $title =$_POST['title'];  
        $cardnumber=$_POST['cardnumber']; 
        $surname=$_POST['surname']; 
        $firstname=$_POST['firstname']; 
        $middle_name=$_POST['middle_name']; 
        $othernames=$_POST['othernames']; 
        $initials=$_POST['initials']; 
        $regtnumber=$_POST['regtnumber']; 
        $dateofbirth=$_POST['dateofbirth']; 
        $gender=$_POST['gender']; 
        $address=$_POST['address']; 
        $address2=$_POST['address2']; 
        $city=$_POST['city']; 
        $state=$_POST['state']; 
        $zipcode=$_POST['zipcode']; 
        $country=$_POST['country']; 
        $mobile=$_POST['mobile']; 
        $mobile2=$_POST['mobile2']; 
        $email=$_POST['email']; 
        $email2=$_POST['email2']; 
        $primary_contact_method=$_POST['primary_contact_method']; 
        $B_address=$_POST['B_address']; 
        $B_address2=$_POST['B_address2']; 
        $B_city=$_POST['B_city']; 
        $B_state=$_POST['B_state']; 
        $B_zipcode=$_POST['B_zipcode']; 
        $B_country=$_POST['B_country']; 
        $altcontactname=$_POST['altcontactname']; 
        $altcontactmobil=$_POST['altcontactmobil']; 
        $altcontactaddress1=$_POST['altcontactaddress1']; 
        $altcontactaddress2=$_POST['altcontactaddress2']; 
        $altcontactcity=$_POST['altcontactcity']; 
        $altcontactstate=$_POST['altcontactstate']; 
        $altcontactzipcode=$_POST['altcontactzipcode']; 
        $altcontactcountry=$_POST['altcontactcountry']; 
        $altcontactemail=$_POST['altcontactemail']; 
        $relationship=$_POST['relationship']; 
        $idcard=$_POST['idcard']; 
        $passport=$_POST['passport']; 
        $branchcode=$_POST['branchcode']; 
        $categorycode=$_POST['categorycode']; 
        $dateenrolled=$_POST['dateenrolled']; 
        $dateexpiry=$_POST['dateexpiry'];     
        $userid=$_POST['userid']; 
        $password=md5($_POST['password']); 
        $status=1;

        if (empty($memberphoto['size'])) {
            $sql="UPDATE `member` SET `title`=?,`cardnumber`=?,`surname`=?,`firstname`=?,`middle_name`=?,`othernames`=?,`initials`=?,`regtnumber`=?,`dateofbirth`=?,`gender`=?,`address`=?,`address2`=?,`city`=?,`state`=?,`zipcode`=?,`country`=?,`mobile`=?,`mobile2`=?,`email`=?,`email2`=?,`primary_contact_method`=?,`B_address`=?,`B_address2`=?,`B_city`=?,`B_state`=?,`B_zipcode`=?,`B_country`=?,`altcontactname`=?,`altcontactmobil`=?,`altcontactaddress1`=?,`altcontactaddress2`=?,`altcontactcity`=?,`altcontactstate`=?,`altcontactzipcode`=?,`altcontactcountry`=?,`altcontactemail`=?,`relationship`=?,`idcard`=?,`passport`=?,`branchcode`=?,`categorycode`=?,`dateenrolled`=?,`dateexpiry`=?,`userid`=?,`password`=?,`status`=? WHERE `borrowernumber`=?";
            $result = mysqli_prepare($dbcon, $sql);

            if ($result) {
                mysqli_stmt_bind_param($result,'ssssssssssssssssssssssssssssssssssssssssssssssi',$title,$cardnumber,$surname,$firstname,$middle_name,$othernames,$initials,$regtnumber,$dateofbirth,$gender,$address,$address2,$city,$state,$zipcode,$country,$mobile,$mobile2,$email,$email2,$primary_contact_method,$B_address,$B_address2,$B_city,$B_state,$B_zipcode,$B_country,$altcontactname,$altcontactmobil,$altcontactaddress1,$altcontactaddress2,$altcontactcity,$altcontactstate,$altcontactzipcode,$altcontactcountry,$altcontactemail,$relationship,$idcard,$passport,$branchcode,$categorycode,$dateenrolled,$dateexpiry,$userid,$password,$status,$id);
                
                if (mysqli_stmt_execute($result)) {
                    $msg = 'Member Update successfuly.';  
                    header('location:dashboard.php');
                } else {
                    $error1 = '<strong>Something went wrong. Please try again<strong>'; 
                }
            } else{
                $error1 = '<strong>Error Connection:'.mysqli_error($dbcon).'<strong>'; 
            }  
        }elseif ($memberphoto['size']<=200000) {
            $imagedetails=pathinfo($memberphoto['name']);
            $allwextention=array('jpg','jpeg','png');

            if (in_array($imagedetails['extension'],$allwextention)) {
                $filepath='img/'.uniqid().'.'.$imagedetails['extension'];
                if (move_uploaded_file($memberphoto['tmp_name'],$filepath)) {
                  
                    $sql="UPDATE `member` SET `title`=?,`cardnumber`=?,`surname`=?,`firstname`=?,`middle_name`=?,`othernames`=?,`initials`=?,`regtnumber`=?,`dateofbirth`=?,`gender`=?,`address`=?,`address2`=?,`city`=?,`state`=?,`zipcode`=?,`country`=?,`mobile`=?,`mobile2`=?,`email`=?,`email2`=?,`primary_contact_method`=?,`B_address`=?,`B_address2`=?,`B_city`=?,`B_state`=?,`B_zipcode`=?,`B_country`=?,`altcontactname`=?,`altcontactmobil`=?,`altcontactaddress1`=?,`altcontactaddress2`=?,`altcontactcity`=?,`altcontactstate`=?,`altcontactzipcode`=?,`altcontactcountry`=?,`altcontactemail`=?,`relationship`=?,`idcard`=?,`passport`=?,`branchcode`=?,`categorycode`=?,`dateenrolled`=?,`dateexpiry`=?,`userid`=?,`password`=?,`status`=?,`img`=? WHERE `borrowernumber`=?";
                    $result = mysqli_prepare($dbcon, $sql);

                    if ($result) {
                        mysqli_stmt_bind_param($result,'sssssssssssssssssssssssssssssssssssssssssssssssi',$title,$cardnumber,$surname,$firstname,$middle_name,$othernames,$initials,$regtnumber,$dateofbirth,$gender,$address,$address2,$city,$state,$zipcode,$country,$mobile,$mobile2,$email,$email2,$primary_contact_method,$B_address,$B_address2,$B_city,$B_state,$B_zipcode,$B_country,$altcontactname,$altcontactmobil,$altcontactaddress1,$altcontactaddress2,$altcontactcity,$altcontactstate,$altcontactzipcode,$altcontactcountry,$altcontactemail,$relationship,$idcard,$passport,$branchcode,$categorycode,$dateenrolled,$dateexpiry,$userid,$password,$status,$filepath,$id);
                        
                        if (mysqli_stmt_execute($result)) {
                            $msg = 'Member Update successfuly.';  
                            header('location:dashboard.php');
                        } else {
                            $error1 = '<strong>Something went wrong. Please try again<strong>'; 
                        }
                    } else{
                        $error1 = '<strong>Error Connection:'.mysqli_error($dbcon).'<strong>'; 
                    }  
                }
            }else{
                $error = '<strong>Select an Image File<strong>';    
            }
        }
        else{
            $error = '<strong>Image should be less than 200 KB<strong>'; 
        }
        
    }else{
        $error1 = '<strong>Invalied Authentication<strong>'; 
    }

}
$_SESSION['csrf_token']=$token;
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
    	<meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="img/logo.png" type="image/png">
        <title>Member Registation | Foundation Library Management System</title>
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
                                    <h2 class="title">Member Registation</h2>
                                </div>                                
                            </div>
                            <!-- /.row -->
                            <div class="row breadcrumb-div">
                                <div class="col-md-6">
                                    <ul class="breadcrumb">
                                        <li><a href="dashboard.php"><i class="fa fa-home"></i> Home /&nbsp;</a></li>
                                        <li><a href="#">Circulations /&nbsp; </a></li>
                                        <li class="active">Add Member</li>
                                    </ul>
                                </div>                               
                            </div>
                            <!-- /.row -->
                        </div>
                        <!-- /.container-fluid -->

                        <div class="container">                 
                            <form name="signup" method="post" enctype="multipart/form-data">
							    <br>
                                <fieldset class="border p-3">
                                    <legend class="w-auto">Member identity:</legend>     
                                    <!-- Error msg Display -->
                                    <div class="row p-2">
                                        <div class="col-sm-2 text-end">
                                        </div>
                                        <div class="col-sm-4">       
                                             <span class="text-success font-weight-bold"><?php echo $msg?></span> 
                                             <span class="text-danger font-weight-bold"><?php echo $error1?></span> 
                                        </div>                              
                                    </div>            
                                    <div class="row p-2">
                                        <div class="col-sm-2 text-end">
                                            <label for="inputSalutation" class="form-label"></label>       
                                        </div>
                                        <div class="col-sm-2">      
                                        <img src="<?php echo $row['img']?>" class="rounded img-thumbnail rounded-circle" alt="Member Image" id="MemberPhoto">      

                                        </div>
                                    </div>

                                    <div class="row p-2">
                                        <div class="col-sm-2 text-end">
                                            <label for="inputSalutation" class="form-label">Member Image</label>       
                                        </div>
                                        <div class="col-sm-4">       
                                            <input type="file" class="form-control" id="memberphoto" name="memberphoto">                                            
                                        </div>
                                    
                                        <div class="col-sm-2 text-end">
                                            <label for="inputSalutation" class="form-label">Salutation:<i class="text-danger font-weight-bold">*</i></label>       
                                        </div>
                                        <div class="col-sm-2">    
                                            <select id="inputSalutation" class="form-select"  name="title" required> 
                                                <option value="<?php echo $row['title']?>"><?php echo $row['title']?></option>
                                                <?php
                                                    $sql1=mysqli_query($dbcon,"SELECT * FROM `salutation`");  
                                                    if (mysqli_num_rows($sql1)>0) {
                                                        while($row1=mysqli_fetch_assoc($sql1)){                                                        
                                                            echo "<option value='$row1[code]'>$row1[code]</option>";
                                                        }
                                                    }                                          
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- Error msg Display -->
                                    <div class="row p-2">
                                        <div class="col-sm-2 text-end">
                                        </div>
                                        <div class="col-sm-4">       
                                             <span class="text-danger font-weight-bold"><?php echo $error?></span> 
                                        </div>                              
                                    </div>
                                                
                                    <div class="row p-2">
                                        <div class="col-sm-2 text-end">
                                            <label for="inputSurname" class="form-label">Surname:<i class="text-danger font-weight-bold">*</i></label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" id="inputSurname" name="surname" value="<?php echo $row['surname']?>" required>
                                        </div>
                                    
                                        <div class="col-sm-2 text-end">
                                            <label for="inputInitials" class="form-label">Initials:<i class="text-danger font-weight-bold">*</i></label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" id="inputInitials" name="initials" value="<?php echo $row['initials']?>" required> 
                                        </div>
                                    </div>
                                    <div class="row p-2">
                                        <div class="col-sm-2 text-end">
                                            <label for="inputFname" class="form-label">First Name:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" name="firstname" id="inputFname" value="<?php echo $row['firstname']?>">
                                        </div>
                                   
                                        <div class="col-sm-2 text-end">
                                            <label for="inputMname" class="form-label">Middle Name:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" name="middle_name" id="inputMname" value="<?php echo $row['middle_name']?>">
                                        </div>
                                    </div>

                                    <div class="row p-2">
                                        <div class="col-sm-2 text-end">
                                            <label for="inputOname" class="form-label">Other Name:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" name="othernames" id="inputOname" value="<?php echo $row['othernames']?>">
                                        </div>

                                        <div class="col-sm-2 text-end">
                                            <label for="inputregtnumber" class="form-label">Army Number:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" name="regtnumber" id="inputregtnumber" value="<?php echo $row['regtnumber']?>">
                                        </div>                                       
                                    </div>

                                    <div class="row p-2">
                                        
                                        <div class="col-sm-2 text-end">
                                            <label for="inputGender" class="form-label">Gender:<i class="text-danger font-weight-bold">*</i></label>
                                        </div>
                                        <div class="form-check btn-group col-sm-4">
                                          
                                            <div class="form-check form-check-inline ">
                                                <input class="form-check-input " type="radio" name="gender" value="M" <?php if($row['gender'] == 'M'):?>checked<?php endif;?> required > 
                                                <label class="form-check-label" for="genMale">Male</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="gender" value="F" <?php if($row['gender'] == 'F'):?>checked<?php endif;?> required>  
                                                <label class="form-check-label" for="genFemale">Female</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="gender" value="O" <?php if($row['gender'] == 'O'):?>checked<?php endif;?> required>  
                                                <label class="form-check-label" for="genOther">Other</label>      
                                            </div>      
                                        </div>

                                        <div class="col-sm-2 text-end">
                                            <label for="inputdob" class="form-label">Date of Birth:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="date" class="form-control" name="dateofbirth" id="inputdob" value="<?php echo $row['dateofbirth']?>">
                                        </div>
                                    </div>
                                </fieldset>
                                <!--Main address -->
                                <fieldset class="border p-3">
                                    <legend class="w-auto">Main address:</legend>

                                    <div class="row p-2">
                                        <div class="col-sm-2 text-end">        
                                            <label for="inputAddress" class="form-label">Address:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" id="inputAddress" name="address" placeholder="1234 Main St" value="<?php echo $row['address']?>">
                                        </div>
                                    
                                        <div class="col-sm-2 text-end">        
                                            <label for="inputAddress2" class="form-label">Address 2:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" id="inputAddress2" name="address2" placeholder="Apartment, studio, or floor" value="<?php echo $row['address2']?>">
                                        </div>
                                    </div>

                                    <div class="row p-2">
                                        <div class="col-sm-2 text-end">        
                                            <label for="inputCity" class="form-label">City:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" id="inputCity" name="city" placeholder="City" value="<?php echo $row['city']?>">
                                        </div>
                                   
                                        <div class="col-sm-2 text-end">        
                                            <label for="inputState" class="form-label">State:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" id="inputState" name="state" placeholder="State" value="<?php echo $row['state']?>">
                                        </div>
                                    </div>

                                    <div class="row p-2">
                                        <div class="col-sm-2 text-end">        
                                            <label for="inputZip" class="form-label">Zip/ Postal Code:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" id="inputZip" name="zipcode" placeholder="Zip/ Postal Code" value="<?php echo $row['zipcode']?>">
                                        </div>
                                    
                                        <div class="col-sm-2 text-end">        
                                            <label for="inputCountry" class="form-label">Country:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="search" name="country" class="form-control"  title="Enter search keyword" id="country" value="<?php echo $row['country']?>">
                            			    <div id="resultcountry"></div>          
                                        </div>
                                    </div>
                                </fieldset>
                                <!-- Contact information -->
                                <fieldset class="border p-3">
                                    <legend class="w-auto">Contact information:</legend>

                                    <div class="row p-2">
                                        <div class="col-sm-2 text-end">        
                                            <label for="inputPriMob" class="form-label">Primary Mobile:<i class="text-danger font-weight-bold">*</i></label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="number" class="form-control" name="mobile" id="inputPriMob" value="<?php echo $row['mobile']?>" required >  
                                        </div>
                                   
                                        <div class="col-sm-2 text-end">        
                                            <label for="inputSecMob" class="form-label">Secondary Mobile:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="number" class="form-control" name="mobile2" id="inputSecMob" value="<?php echo $row['mobile2']?>">
                                        </div>
                                    </div>

                                    <div class="row p-2">
                                        <div class="col-sm-2 text-end">        
                                            <label for="inputPriEmail" class="form-label">Primary Email:<i class="text-danger font-weight-bold">*</i></label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="email" class="form-control" name="email" id="inputPriEmail" value="<?php echo $row['email']?>" required>  
                                        </div>
                                    
                                        <div class="col-sm-2 text-end">        
                                            <label for="inputSecEmail" class="form-label">Secondary Email:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="email" class="form-control" name="email2" id="inputSecEmail" value="<?php echo $row['email2']?>">
                                        </div>
                                    </div>        

                                    <div class="row p-2">
                                        <div class="col-sm-2 text-end">
                                            <label for="inputMainConMethod" class="form-label">Main Contact Method:<i class="text-danger font-weight-bold">*</i></label>       
                                        </div>
                                        <div class="col-sm-2">        
                                        <select id="inputMainConMethod" class="form-select" name="primary_contact_method" required>  
                                        <?php
                                            if ($row['primary_contact_method']=='PM') {
                                                echo "<option value='$row[primary_contact_method]'>Primary Mobile</option>";
                                                echo "<option value='SM'>Secondary Mobile</option>";
                                                echo "<option value='PE'>Primary Email</option>";
                                                echo "<option value='SE'>Secondary Email</option>";
                                            } elseif ($row['primary_contact_method']=='SE') {
                                                echo "<option value='$row[primary_contact_method]'>Secondary Mobile</option>";
                                                echo "<option value='PM'>Primary Mobile</option>";
                                                echo "<option value='PE'>Primary Email</option>";
                                                echo "<option value='SE'>Secondary Email</option>";                                                          
                                            } elseif ($row['primary_contact_method']=='PE') {
                                                echo "<option value='$row[primary_contact_method]'>Primary Email</option>";
                                                echo "<option value='PM'>Primary Mobile</option>";
                                                echo "<option value='SM'>Secondary Mobile</option>";
                                                echo "<option value='SE'>Secondary Email</option>";                                                                   
                                            } elseif ($row['primary_contact_method']=='SE') {
                                                echo "<option value='$row[primary_contact_method]'>Secondary Email</option>";
                                                echo "<option value='PM'>Primary Mobile</option>";
                                                echo "<option value='PE'>Primary Email</option>";
                                                echo "<option value='SM'>Secondary Mobile</option>";                                          
                                            }
                                            ?>                                 
                                        </select>
                                        </div>
                                    </div>
                                </fieldset>
                                <!--Alternate address -->
                                <fieldset class="border p-3">
                                    <legend class="w-auto">Alternate address:</legend>

                                    <div class="row p-2">
                                        <div class="col-sm-2 text-end">        
                                            <label for="inputAltAddress" class="form-label">Address:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" name="B_address" id="inputAltAddress" value="<?php echo $row['B_address']?>">
                                        </div>
                                   
                                        <div class="col-sm-2 text-end">        
                                            <label for="inputAltAddress2" class="form-label">Address 2:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" name="B_address2" id="inputAltAddress2" value="<?php echo $row['B_address2']?>">
                                        </div>
                                    </div>

                                    <div class="row p-2">
                                        <div class="col-sm-2 text-end">        
                                            <label for="inputAltCity" class="form-label">City:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" name="B_city" id="inputAltCity" value="<?php echo $row['B_city']?>">
                                        </div>
                                    
                                        <div class="col-sm-2 text-end">        
                                            <label for="inputAltState" class="form-label">State:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" name="B_state" id="inputAltState" value="<?php echo $row['B_state']?>">
                                        </div>
                                    </div>

                                    <div class="row p-2">
                                        <div class="col-sm-2 text-end">        
                                            <label for="inputAltZip" class="form-label">Zip/ Postal Code:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" name="B_zipcode" id="inputAltZip" value="<?php echo $row['B_zipcode']?>">
                                        </div>
                                  
                                        <div class="col-sm-2 text-end">        
                                            <label for="inputAltCountry" class="form-label">Country:</label>
                                        </div>
                                        <div class="col-sm-4">
                                        <input type="search" name="B_country" class="form-control" title="Enter search keyword" id="altcountry" value="<?php echo $row['B_country']?>">
                            			    <div id="resultaltcountry"></div> 
                                        </div>
                                    </div>
                                </fieldset>
                                <!--Alternate Contac => Alternate Table-->
                                
                                <fieldset class="border p-3">
                                    <legend class="w-auto">Alternate Contact:</legend>

                                    <div class="row p-2">
                                        <div class="col-sm-2 text-end">
                                            <label for="inputAltContname" class="form-label">Name:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" name="altcontactname" id="inputAltContname" value="<?php echo $row['altcontactname']?>">
                                        </div>
                                  
                                        <div class="col-sm-2 text-end">
                                            <label for="inputaltcontactmobil" class="form-label">Mobile:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="number" class="form-control" name="altcontactmobil" id="inputaltcontactmobil" value="<?php echo $row['altcontactmobil']?>">
                                        </div>
                                    </div>

                                    <div class="row p-2">
                                        <div class="col-sm-2 text-end">        
                                            <label for="inputAltContAddress" class="form-label">Address:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" name="altcontactaddress1" id="inputAltContAddress" value="<?php echo $row['altcontactaddress1']?>">
                                        </div>
                                   
                                        <div class="col-sm-2 text-end">        
                                            <label for="inputAltContAddress2" class="form-label">Address 2:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" name="altcontactaddress2" id="inputAltContAddress2" value="<?php echo $row['altcontactaddress2']?>">
                                        </div>
                                    </div>

                                    <div class="row p-2">
                                        <div class="col-sm-2 text-end">        
                                            <label for="inputAltContCity" class="form-label">City:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" name="altcontactcity" id="inputAltContCity" value="<?php echo $row['altcontactcity']?>">
                                        </div>
                                   
                                        <div class="col-sm-2 text-end">        
                                            <label for="inputAltContState" class="form-label">State:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" name="altcontactstate" id="inputAltContState" value="<?php echo $row['altcontactstate']?>">
                                        </div>
                                    </div>

                                    <div class="row p-2">
                                        <div class="col-sm-2 text-end">        
                                            <label for="inputAltContZip" class="form-label">Zip/ Postal Code:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" name="altcontactzipcode" id="inputAltContZip" value="<?php echo $row['altcontactzipcode']?>">
                                        </div>
                                   
                                        <div class="col-sm-2 text-end">        
                                            <label for="inputAltContCountry" class="form-label">Country:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="search" name="altcontactcountry" class="form-control" title="Enter search keyword" id="altcontcountry" value="<?php echo $row['altcontactcountry']?>">
                            			    <div id="resultcountry"></div>                
                                        </div>
                                    </div>

                                    <div class="row p-2">                                       
                                        <div class="col-sm-2 text-end">        
                                            <label for="inputAltContEmail" class="form-label">Email:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="email" class="form-control" name="altcontactemail" id="inputAltContEmail" value="<?php echo $row['altcontactemail']?>">
                                        </div>
                                        <div class="col-sm-2 text-end">        
                                            <label for="inputrelationship" class="form-label">Relationship:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" name="relationship" id="inputrelationship" value="<?php echo $row['relationship']?>">
                                        </div>
                                    </div>
                                </fieldset>
                                <!-- Library management -->
                                <fieldset class="border p-3">
                                    <legend class="w-auto">Library management:</legend>

                                    <div class="row p-2">
                                        <div class="col-sm-2 text-end">        
                                            <label for="inputCardNo" class="form-label">Card Number:<i class="text-danger font-weight-bold">*</i></label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" name="cardnumber" id="inputCardNo" value="<?php echo $row['cardnumber']?>" required>  <!--required-->
                                        </div>
                                   
                                        <div class="col-sm-2 text-end">        
                                            <label for="inputID" class="form-label">Identification Card No:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" name="idcard" id="inputID" value="<?php echo $row['idcard']?>">
                                        </div>
                                    </div>

                                    <div class="row p-2">
                                        <div class="col-sm-2 text-end">        
                                            <label for="inputPassport" class="form-label">Passport No:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" name="passport" id="inputPassport" value="<?php echo $row['passport']?>">
                                        </div>
                                  
                                        <div class="col-sm-2 text-end">
                                            <label for="inputLibrary" class="form-label">Library:<i class="text-danger font-weight-bold">*</i></label>       
                                        </div>
                                        <div class="col-sm-2">        
                                            <select id="inputLibrary" class="form-select" name="branchcode">
                                            <option selected value="all">All Library</option>
                                                <?php
                                                    $sql=mysqli_query($dbcon, "SELECT * FROM `branches`");
                                                    if (mysqli_num_rows($sql)>0) {
                                                    while ($branch=mysqli_fetch_array($sql)) {
                                                        echo "<option value='" . $branch['branchcode'] . "'>" . $branch['name'] . "</option>";
                                                    }
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row p-2">
                                        <div class="col-sm-2 text-end">
                                            <label for="inputCategory" class="form-label">Groups:<i class="text-danger font-weight-bold">*</i></label>       
                                        </div>
                                        <div class="col-sm-2">        
                                            <select id="inputCategory" class="form-select" name="categorycode" required> 
                                            <option value="<?php echo $row['categorycode']?>"><?php echo $row['categorycode']?></option>
                                            <?php
                                                $group=mysqli_query($dbcon,"SELECT * FROM membergroups");
                                                if (mysqli_num_rows($group)>0) {
                                                    while ($member=mysqli_fetch_array($group)){
                                                        echo "<option value=".$member['categorycode'].">". $member['description']."</option>";      

                                                    }
                                                }
                                            ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row p-2">
                                        <div class="col-sm-2 text-end">
                                            <label for="inputRegDate" class="form-label">Registration Date:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="date" class="form-control" name="dateenrolled" id="regdate" value="<?php echo $row['dateenrolled']?>">
                                        </div>
                                  
                                        <div class="col-sm-2 text-end">
                                            <label for="inputExpDate" class="form-label">Expiry Date:</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="date" class="form-control" name="dateexpiry" id="inputExpDate" value="<?php echo $row['dateexpiry']?>">
                                        </div>
                                    </div>
                                </fieldset>

                                <!-- OPAC/Staff interface login => Save User Table-->
                                
                                <fieldset class="border p-3">
                                    <legend class="w-auto">OPAC/Staff interface login:</legend>
                                    
                                    <div class="row p-2">
                                        <div class="col-sm-2 text-end">        
                                            <label for="inputUsername" class="form-label">Username:<i class="text-danger font-weight-bold">*</i></label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="username" class="form-control" name="userid" id="inputUsername" autocomplete="off" value="<?php echo $row['userid']?>" required>  
                                        </div>
                                    </div>
                                    <div class="row p-2">
                                        <div class="col-sm-2 text-end">        
                                            <label for="inputPassword" class="form-label">Password:<i class="text-danger font-weight-bold">*</i></label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="Password" class="form-control" name="password" id="inputPassword" placeholder="Enter password" value="<?php echo $row['password']?>" required> 
                                        </div>
                                    </div>

                                    <div class="row p-2">
                                        <div class="col-sm-2 text-end">        
                                            <label for="inputConfPassword" class="form-label">Confirm Password:<i class="text-danger font-weight-bold">*</i></label>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="Password" class="form-control" name="confpassword" id="inputConfPassword" placeholder="Enter password" value="<?php echo $row['password']?>" required>  
                                        </div>
                                    </div>
                                </fieldset>

                                <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                                    <div class="col-sm-1">     
                                        <input type="hidden" name="csrf_token" value="<?php echo $token?>">
                                        <button type="submit" class="btn btn-primary btn-md" name="btnsave">Save</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col"> 
                            <?php include('includes/footer.php');?>                    
                         </div>
                    </div>
                </div> 
            </div> 
        </div>   
	<script src="js/search.js"></script>

    </body>
</html>
<?php 
mysqli_close($dbcon);
?>