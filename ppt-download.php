<?php
session_start();
include('includes/config.php');  

if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
    exit;
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die('No ID specified.');
}

$id = intval($_GET['id']);

// Get file paths from DB for this record
$sql = mysqli_prepare($dbcon, "SELECT abstract, image FROM ppt WHERE id = ?");
mysqli_stmt_bind_param($sql, 'i', $id);
mysqli_stmt_execute($sql);
mysqli_stmt_bind_result($sql, $abstract, $image);
mysqli_stmt_fetch($sql);
mysqli_stmt_close($sql);

// Decide which file to download: here I will show abstract first, fallback image if no abstract
if (!empty($abstract) && file_exists($abstract)) {
    $filePath = $abstract;
} elseif (!empty($image) && file_exists($image)) {
    $filePath = $image;
} else {
    die('No downloadable file Attachments.');
}

$fileName = basename($filePath);

// Set headers to force download
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . $fileName . '"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($filePath));

readfile($filePath);
exit;
?>
