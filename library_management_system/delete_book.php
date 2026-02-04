<?php
include 'includes/functions.php';
include 'config/database.php';

requireAdmin();

$book_id = sanitize($_GET['id'] ?? '');

if (empty($book_id) || !is_numeric($book_id)) {
    header("Location: manage_books.php");
    exit();
}

// Get book to delete image
$stmt = $conn->prepare("SELECT image FROM books WHERE id = ?");
$stmt->bind_param("i", $book_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $book = $result->fetch_assoc();
    $target_dir = "assets/images/books/";
    
    // Delete image file
    if (!empty($book['image']) && file_exists($target_dir . $book['image'])) {
        unlink($target_dir . $book['image']);
    }

    // Delete book from database
    $delete_stmt = $conn->prepare("DELETE FROM books WHERE id = ?");
    $delete_stmt->bind_param("i", $book_id);
    $delete_stmt->execute();
    $delete_stmt->close();
}

$stmt->close();
header("Location: manage_books.php");
exit();
?>
