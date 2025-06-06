<?php
// (Temporarily enable error display—remove in production)
ini_set('display_errors', 1);
error_reporting(E_ALL);

include('includes/config.php');  // Adjust path if config.php is elsewhere

header('Content-Type: text/plain'); // So the browser sees plain HTML for <option> tags

if (!isset($_POST['service']) || $_POST['service'] === '') {
    // If no service was posted, return only the placeholder
    echo '<option value="">-- Select Salutation --</option>';
    exit;
}

$service = $_POST['service'];

// Prepare & execute to fetch only that service’s salutations
$stmt = mysqli_prepare(
    $dbcon,
    "SELECT code, `desc` FROM salutation WHERE service = ?"
);
if (!$stmt) {
    // On prepare failure, just return the placeholder
    echo '<option value="">-- Select Salutation --</option>';
    exit;
}

mysqli_stmt_bind_param($stmt, "s", $service);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Start with placeholder
echo '<option value="">-- Select Salutation --</option>' . "\n";

// Output each matching salutation
while ($row = mysqli_fetch_assoc($result)) {
    // code = abbreviation (e.g. “CPL”), desc = description (e.g. “Corporal”)
    $code = htmlspecialchars($row['code']);
    $desc = htmlspecialchars($row['desc']);
    echo "<option value=\"{$code}\">{$desc}</option>\n";
}
?>
