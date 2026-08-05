<?php
include('includes/config.php');

$response = ["success" => false, "count" => 0];

if(isset($_POST['memberID'])) {

    $memberID = $_POST['memberID'];

    // 🔍 Check if member exists
    $check = mysqli_query($dbcon, "
    SELECT * FROM member 
    WHERE cardnumber='$memberID'
    OR regtnumber='$memberID'
    OR SUBSTRING_INDEX(cardnumber, '/', -1)='$memberID'
    OR SUBSTRING_INDEX(regtnumber, '/', -1)='$memberID'
    ");
    

    if(mysqli_num_rows($check) > 0){

        $member = mysqli_fetch_assoc($check);
        $member_id = $member['cardnumber'];

        // 🔢 Count books
        $query = mysqli_query($dbcon, "
            SELECT COUNT(*) AS total 
            FROM issuedbook 
            WHERE membernumber='$member_id'
            AND RetrunStatus=0
        ");

        $row = mysqli_fetch_assoc($query);

        $response["success"] = true;
        $response["count"] = $row['total'];
    }
}

echo json_encode($response);
?>