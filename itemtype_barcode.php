<?php
include('includes/config.php');

if (isset($_GET['type'])) {
    $typeCode = $_GET['type']; // this is the itemcode from itemtypes table
    $prefix = strtoupper($typeCode);

    // Fetch the latest booknumber from the catalog table using this prefix
    $query = mysqli_query($dbcon, "SELECT booknumber FROM catalog WHERE booknumber LIKE '$prefix%' ORDER BY booknumber DESC LIMIT 1");
    $row = mysqli_fetch_assoc($query);

    if ($row) {
        // Extract the numeric part and increment
        $lastNumber = intval(substr($row['booknumber'], strlen($prefix))) + 1;
    } else {
        $lastNumber = 1;
    }

    // Combine prefix and new number
    $newBarcode = $prefix . str_pad($lastNumber, 4, '0', STR_PAD_LEFT);
    echo $newBarcode;
}
?>
