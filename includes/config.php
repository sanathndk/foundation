<?php 

$dbcon=mysqli_connect('localhost','root','','foundation');

if(!$dbcon) {
   echo "<script>alert ('DB Not connected'.mysqli_error($dbcon))</script>";
}

// Function to secure against Cross-Site Scripting (XSS) attacks
// function secureXSS($text){
//    // Remove leading and trailing whitespaces
//    $text = trim($text);   
//    // Convert special characters to HTML entities
//    $text = htmlspecialchars($text);   
//    // Remove backslashes
//    $text = stripcslashes($text);   
//    // Return the secured text
//    return $text;
// }


?>