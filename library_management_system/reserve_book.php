<?php
include 'includes/functions.php';
include 'config/database.php';

requireLogin();

$book_id = sanitize($_POST['book_id'] ?? '');

if (empty($book_id) || !is_numeric($book_id)) {
    header("Location: books.php");
    exit();
}

// Check if book exists and is available
$stmt = $conn->prepare("SELECT available FROM books WHERE id = ?");
$stmt->bind_param("i", $book_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0 || $result->fetch_assoc()['available'] <= 0) {
    header("Location: books.php?error=Book not available");
    exit();
}

// Check if user already reserved this book
$check = $conn->prepare("SELECT id FROM reservations WHERE book_id = ? AND user_id = ? AND status != 'cancelled'");
$check->bind_param("ii", $book_id, $_SESSION['user_id']);
$check->execute();

if ($check->get_result()->num_rows > 0) {
    header("Location: book_detail.php?id=$book_id&error=Already reserved");
    exit();
}

// Create reservation
$insert = $conn->prepare("INSERT INTO reservations (book_id, user_id, status) VALUES (?, ?, 'pending')");
$insert->bind_param("ii", $book_id, $_SESSION['user_id']);

if ($insert->execute()) {
    // Decrease available count
    $update = $conn->prepare("UPDATE books SET available = available - 1 WHERE id = ?");
    $update->bind_param("i", $book_id);
    $update->execute();
    $update->close();
    
    header("Location: my_reservations.php?success=Book reserved successfully");
} else {
    header("Location: book_detail.php?id=$book_id&error=Reservation failed");
}

$insert->close();
$stmt->close();
$check->close();
exit();
?>
