<?php
$page_title = "Book Details - Library Management System";
include 'includes/header.php';
include 'config/database.php';
include 'includes/functions.php';

$book_id = sanitize($_GET['id'] ?? '');

if (empty($book_id) || !is_numeric($book_id)) {
    header("Location: books.php");
    exit();
}

$stmt = $conn->prepare("SELECT * FROM books WHERE id = ?");
$stmt->bind_param("i", $book_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: books.php");
    exit();
}

$book = $result->fetch_assoc();

// Check if user already reserved this book
$is_reserved = false;
if (isLoggedIn()) {
    $check = $conn->prepare("SELECT id FROM reservations WHERE book_id = ? AND user_id = ? AND status != 'cancelled'");
    $check->bind_param("ii", $book_id, $_SESSION['user_id']);
    $check->execute();
    $is_reserved = $check->get_result()->num_rows > 0;
}

$stmt->close();
?>

<div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-md-4 mb-4">
            <?php if (!empty($book['image']) && file_exists('assets/images/books/' . $book['image'])): ?>
                <img src="assets/images/books/<?php echo htmlspecialchars($book['image']); ?>" class="img-fluid rounded shadow" alt="<?php echo htmlspecialchars($book['title']); ?>">
            <?php else: ?>
                <div class="bg-light rounded shadow d-flex align-items-center justify-content-center" style="height: 400px;">
                    <i class="fas fa-book fa-10x text-secondary"></i>
                </div>
            <?php endif; ?>
        </div>
        <div class="col-md-8">
            <h1><?php echo htmlspecialchars($book['title']); ?></h1>
            <p class="text-muted h5 mb-3">by <?php echo htmlspecialchars($book['author']); ?></p>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Category:</strong> <span class="badge bg-primary"><?php echo htmlspecialchars($book['category']); ?></span>
                </div>
                <div class="col-md-6">
                    <strong>Published Year:</strong> <?php echo $book['published_year']; ?>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Availability:</strong>
                    <?php if ($book['available'] > 0): ?>
                        <span class="badge bg-success"><i class="fas fa-check-circle"></i> Available (<?php echo $book['available']; ?>/<?php echo $book['quantity']; ?>)</span>
                    <?php else: ?>
                        <span class="badge bg-danger"><i class="fas fa-times-circle"></i> Not Available</span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="mb-4">
                <h5>Description</h5>
                <p><?php echo htmlspecialchars($book['description']); ?></p>
            </div>

            <div class="mb-4">
                <?php if (isLoggedIn()): ?>
                    <?php if ($is_reserved): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> You have already reserved this book.
                        </div>
                    <?php elseif ($book['available'] > 0): ?>
                        <form method="POST" action="reserve_book.php">
                            <input type="hidden" name="book_id" value="<?php echo $book_id; ?>">
                            <button type="submit" class="btn btn-lg btn-success">
                                <i class="fas fa-bookmark"></i> Reserve Book
                            </button>
                        </form>
                    <?php else: ?>
                        <button class="btn btn-lg btn-secondary" disabled>
                            <i class="fas fa-times-circle"></i> Not Available
                        </button>
                    <?php endif; ?>
                <?php else: ?>
                    <p>
                        <a href="login.php" class="btn btn-lg btn-primary">
                            <i class="fas fa-sign-in-alt"></i> Login to Reserve
                        </a>
                    </p>
                <?php endif; ?>
            </div>

            <a href="books.php" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back to Books
            </a>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
