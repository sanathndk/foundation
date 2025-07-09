<?php
function generateCardNumber($dbcon) {
    // Use your actual table name instead of 'members' if different
    $result = mysqli_query($dbcon, "SELECT cardnumber FROM member ORDER BY id DESC LIMIT 1");

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $lastCard = $row['cardnumber']; // e.g. CARD00012

        // Extract number part, increment, and format
        $number = intval(substr($lastCard, 4)) + 1;
        return "CARD" . str_pad($number, 5, "0", STR_PAD_LEFT); // CARD00013
    } else {
        return "CARD00001";
    }
}
?>
