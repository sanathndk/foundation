<?php
session_start();
include('includes/config.php');
include('includes/activity.php');

logAction($dbcon, "Download ppt or reserach");

if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
    exit;
}

if (!isset($_GET['id']) || !isset($_GET['type'])) {
    die('Invalid download request.');
}

$id = intval($_GET['id']);
$type = $_GET['type'];

// Fetch abstract and image paths
$sql = mysqli_prepare($dbcon, "SELECT abstract, image FROM ppt WHERE id = ?");
mysqli_stmt_bind_param($sql, 'i', $id);
mysqli_stmt_execute($sql);
mysqli_stmt_bind_result($sql, $abstract, $image);
mysqli_stmt_fetch($sql);
mysqli_stmt_close($sql);

// Choose file
if ($type == 'abstract') {
    $filePath = $abstract;
} elseif ($type == 'image') {
    $filePath = $image;
} else {
    die('Invalid type.');
}

if (!file_exists($filePath)) {
    die('File does not exist.');
}

// Send headers
$fileName = basename($filePath);
$mimeType = mime_content_type($filePath);

header('Content-Description: File Transfer');
header('Content-Type: ' . $mimeType);
header('Content-Disposition: attachment; filename="' . $fileName . '"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($filePath));

readfile($filePath);
exit;
?>
