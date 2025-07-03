<?php
function logAction($dbcon, $action) {
    if (!isset($_SESSION['session_id'])) return;

    $sessionId = $_SESSION['session_id'];

    // Add time to the message
    $fullAction = $action . " at " . date('Y-m-d H:i:s');

    // Append new action to the "action" column
    $sql = "UPDATE user_logs SET action = CONCAT(IFNULL(action, ''), ?, '\n') WHERE session_id = ?";
    $stmt = $dbcon->prepare($sql);
    $stmt->bind_param("ss", $fullAction, $sessionId);
    $stmt->execute();
}
?>
