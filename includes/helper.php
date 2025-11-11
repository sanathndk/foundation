<?php
// Check if date is weekend
function isWeekend($date){
    $day = date('N', strtotime($date));
    return ($day == 6 || $day == 7); // Sat=6, Sun=7
}

// Get all holidays between two dates
function getHolidays($startDate, $endDate, $conn){
    $holidays = [];
    $sql = "SELECT holiday_date FROM holidays WHERE holiday_date BETWEEN ? AND ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $startDate, $endDate);
    $stmt->execute();
    $result = $stmt->get_result();
    while($row = $result->fetch_assoc()){
        $holidays[] = $row['holiday_date'];
    }
    $stmt->close();
    return $holidays;
}

// Calculate due date (skip weekends & holidays)
function calculateDueDate($issueDate, $loanDays, $conn){
    $currentDate = strtotime($issueDate);
    $addedDays = 0;

    $holidays = getHolidays($issueDate, date('Y-m-d', strtotime("+$loanDays days", strtotime($issueDate))), $conn);

    while($addedDays < $loanDays){
        $currentDate = strtotime("+1 day", $currentDate);
        $dayStr = date('Y-m-d', $currentDate);

        if(isWeekend($dayStr) || in_array($dayStr, $holidays)){
            continue;
        }
        $addedDays++;
    }

    return date('Y-m-d', $currentDate);
}

// Calculate fine (skip weekends & holidays)
function calculateFine($returnDate, $actualReturnDate, $finePerDay, $conn){
    $current = strtotime($returnDate);
    $end = strtotime($actualReturnDate);
    $fineDays = 0;

    $holidays = getHolidays($returnDate, $actualReturnDate, $conn);

    while($current < $end){
        $current = strtotime("+1 day", $current);
        $dayStr = date('Y-m-d', $current);

        if(isWeekend($dayStr) || in_array($dayStr, $holidays)) continue;

        $fineDays++;
    }

    return $fineDays * $finePerDay;
}


?>
