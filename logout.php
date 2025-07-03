<?php
session_start();
include('includes/config.php');
include('includes/activity.php');

logAction($dbcon, "Logout");

// Get session ID before destroying
$sessionId = $_SESSION['session_id'] ?? null;

if ($sessionId) {
    $logoutTime = date('Y-m-d H:i:s');
    $sql = "UPDATE user_logs SET logout_time = ? WHERE session_id = ?";
    $stmt = $dbcon->prepare($sql);
    $stmt->bind_param("ss", $logoutTime, $sessionId);
    $stmt->execute();
}

// Clear session variables
$_SESSION = array();
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 3600,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Unset all custom session values
unset($_SESSION['alogin']);
unset($_SESSION['user_id']);
unset($_SESSION['user_group']);
unset($_SESSION['user_name']);
unset($_SESSION['image']);
unset($_SESSION['session_id']);

session_destroy(); // Destroy the session
header("Location: index.php");
exit;
?>
