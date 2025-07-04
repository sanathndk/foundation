<?php
session_start();
error_reporting(0);
include('includes/config.php');
logAction($dbcon, "view_image");


header("Location: index.php");
exit;


if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid ID.");
}

$id = intval($_GET['id']);

$sql = mysqli_prepare($dbcon, "SELECT title, image FROM ppt WHERE id = ?");
mysqli_stmt_bind_param($sql, 'i', $id);
mysqli_stmt_execute($sql);
mysqli_stmt_bind_result($sql, $title, $image);
mysqli_stmt_fetch($sql);
mysqli_stmt_close($sql);

$hasImage = (!empty($image) && file_exists($image));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Image Preview</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Image Preview: <?php echo htmlspecialchars($title); ?></h4>
        </div>
        <div class="card-body text-center">

            <?php if ($hasImage): ?>
                <img src="<?php echo $image; ?>" class="img-fluid" style="max-height: 600px;" alt="Uploaded Image">
            <?php else: ?>
                <p class="text-danger">No image available for preview.</p>
            <?php endif; ?>

        </div>
        <div class="card-footer text-end">
            <a href="userppt.php" class="btn btn-secondary btn-sm">Back</a>
        </div>
    </div>
</div>
</body>
</html>