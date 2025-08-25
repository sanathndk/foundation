<?php
include('includes/config.php');

// Reminder date: 2 days before due date
$reminderDate = date('Y-m-d', strtotime('+2 days'));

$stmt = mysqli_prepare($dbcon, $sql);
mysqli_stmt_bind_param($stmt, 's', $reminderDate);


// Get all books that are due soon and not yet returned
$sql = "
SELECT m.surname, m.email, i.Returndate, c.title
FROM issuedbook i
JOIN member m ON i.memberid = m.id
JOIN catalog c ON i.bookid = c.id
WHERE i.Returndate = ?
";

$stmt = mysqli_prepare($dbcon, $sql);
mysqli_stmt_bind_param($stmt, 's', $reminderDate);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

while ($row = mysqli_fetch_assoc($result)) {
    $name = $row['name'];
    $email = $row['email'];
    $bookTitle = $row['title'];
    $dueDate = $row['due_date'];

    // Email details
    $subject = "📚 Book Return Reminder";
    $message = "Dear $surname,\n\nThis is a kind reminder that your borrowed book \"$bookTitle\" is due for return on $dueDate.\n\nPlease return or renew the book to avoid penalties.\n\nThank you,\nLibrary Management";

    // Send the email
    $headers = "From: library@example.com\r\n"; // Replace with your email
    mail($email, $subject, $message, $headers);
    
    echo "Email sent to $email for book: $bookTitle<br>";
}
?>
