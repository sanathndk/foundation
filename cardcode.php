<?php
include('includes/config.php'); // connect to DB

$result = mysqli_query($dbcon, "SELECT cardnumber FROM members ORDER BY id DESC LIMIT 1");

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $lastCard = $row['cardnumber']; // e.g., CARD00005
    $number = intval(substr($lastCard, 4)) + 1;
    $newCard = "CARD" . str_pad($number, 5, "0", STR_PAD_LEFT);
} else {
    $newCard = "CARD00001"; // start first
}
?>
