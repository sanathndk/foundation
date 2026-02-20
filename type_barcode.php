<?php
include('includes/config.php');

if (isset($_GET['type'])) {
    $type = $_GET['type'];
    $prefix = strtoupper(substr($type, 0, 3)); 

    $query = mysqli_query($dbcon, "SELECT booknumber FROM ppt WHERE booknumber LIKE '$prefix%' ORDER BY id DESC LIMIT 1");
    $row = mysqli_fetch_assoc($query);

    if ($row) {
        $lastNumber = intval(substr($row['booknumber'], 3)) + 1;
    } else {
        $lastNumber = 1;
    }

    $newBarcode = $prefix . str_pad($lastNumber, 4, '0', STR_PAD_LEFT); 
    echo $newBarcode;
}
?>
