<?php
$page_title = "Dashboard - Library Management System";
include 'includes/header.php';
include 'config/database.php';
include 'includes/functions.php';

requireLogin();

// Get statistics
$books_count = $conn->query("SELECT COUNT(*) as count FROM books")->fetch_assoc()['count'];
$users_count = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
$reservations_count = $conn->query("SELECT COUNT(*) as count FROM reservations WHERE user_id = " . $_SESSION['user_id'])->fetch_assoc()['count'];

// Get recent books
$recent_books = $conn->query("SELECT * FROM books ORDER BY created_at DESC LIMIT 6");
?>

<div class="container mt-5 mb-5">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="mb-3"><i class="fas fa-home"></i> Dashboard</h1>
            <p class="text-muted">Welcome, <?php echo $_SESSION['username']; ?>!</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-book"></i> Total Books</h5>
                    <p class="card-text display-4"><?php echo $books_count; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-bookmark"></i> My Reservations</h5>
                    <p class="card-text display-4"><?php echo $reservations_count; ?></p>
                </div>
            </div>
        </div>
        <?php if (isAdmin()): ?>
            <div class="col-md-4 mb-3">
                <div class="card text-white bg-warning">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-users"></i> Total Users</h5>
                        <p class="card-text display-4"><?php echo $users_count; ?></p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h3 class="mb-3">Quick Actions</h3>
        </div>
        <div class="col-md-4 mb-3">
            <a href="books.php" class="btn btn-lg btn-outline-primary w-100">
                <i class="fas fa-search"></i> Browse Books
            </a>
        </div>
        <div class="col-md-4 mb-3">
            <a href="my_reservations.php" class="btn btn-lg btn-outline-success w-100">
                <i class="fas fa-bookmark"></i> My Reservations
            </a>
        </div>
        <?php if (isAdmin()): ?>
            <div class="col-md-4 mb-3">
                <a href="add_book.php" class="btn btn-lg btn-outline-info w-100">
                    <i class="fas fa-plus"></i> Add Book
                </a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Recently Added Books -->
    <div class="row">
        <div class="col-md-12">
            <h3 class="mb-3"><i class="fas fa-star"></i> Featured Books</h3>
        </div>
        <?php while ($book = $recent_books->fetch_assoc()): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 book-card">
                    <div class="book-image-container">
                        <?php if (!empty($book['image']) && file_exists('assets/images/books/' . $book['image'])): ?>
                            <img src="assets/images/books/<?php echo htmlspecialchars($book['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($book['title']); ?>">
                        <?php else: ?>
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="min-height: 250px;">
                                <i class="fas fa-book fa-5x text-secondary"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($book['title']); ?></h5>
                        <p class="card-text text-muted">by <?php echo htmlspecialchars($book['author']); ?></p>
                        <p class="card-text"><small class="text-muted"><?php echo htmlspecialchars($book['category']); ?> • <?php echo $book['published_year']; ?></small></p>
                        <a href="book_detail.php?id=<?php echo $book['id']; ?>" class="btn btn-sm btn-primary w-100">
                            <i class="fas fa-eye"></i> View Details
                        </a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
