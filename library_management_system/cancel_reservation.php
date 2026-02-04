<?php
include 'includes/functions.php';
include 'config/database.php';

requireLogin();

$reservation_id = sanitize($_GET['id'] ?? '');

if (empty($reservation_id) || !is_numeric($reservation_id)) {
    header("Location: my_reservations.php");
    exit();
}

// Get reservation details
$stmt = $conn->prepare("SELECT book_id FROM reservations WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $reservation_id, $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $reservation = $result->fetch_assoc();
    
    // Update reservation status to cancelled
    $update = $conn->prepare("UPDATE reservations SET status = 'cancelled' WHERE id = ?");
    $update->bind_param("i", $reservation_id);
    $update->execute();
    $update->close();
    
    // Increase available count for the book
    $book_update = $conn->prepare("UPDATE books SET available = available + 1 WHERE id = ?");
    $book_update->bind_param("i", $reservation['book_id']);
    $book_update->execute();
    $book_update->close();
}

$stmt->close();
header("Location: my_reservations.php?success=Reservation cancelled");
exit();
?>
