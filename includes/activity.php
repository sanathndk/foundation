<?php

function logAction($dbcon, $action) {
    if (!isset($_SESSION['session_id'])) return;

    $sessionId = $_SESSION['session_id'];
    $fullAction = $action . " at " . date('Y-m-d H:i:s');

    $sql = "UPDATE user_logs 
            SET action = CONCAT(IFNULL(action, ''), ?, '\n') 
            WHERE session_id = ?";

    if ($stmt = $dbcon->prepare($sql)) {
        $stmt->bind_param("ss", $fullAction, $sessionId);
        $stmt->execute();
        $stmt->close();
    }
}
?>
