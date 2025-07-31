<?php
function generateCardNumber($dbcon) {
    $prefix = "CARD";

    $sql = "SELECT cardnumber FROM member 
            WHERE cardnumber LIKE '$prefix%' 
            ORDER BY cardnumber DESC LIMIT 1";

    $result = mysqli_query($dbcon, $sql);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        $lastNum = intval(substr($row['cardnumber'], strlen($prefix)));
        $nextNum = $lastNum + 1;
    } else {
        $nextNum = 1;
    }

    return $prefix . str_pad($nextNum, 4, '0', STR_PAD_LEFT); // CARD0001
}
?>