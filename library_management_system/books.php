<?php
$page_title = "Browse Books - Library Management System";
include 'includes/header.php';
include 'config/database.php';
include 'includes/functions.php';

$search = sanitize($_GET['search'] ?? '');
$category = sanitize($_GET['category'] ?? '');

// Get categories for filter
$categories = $conn->query("SELECT DISTINCT category FROM books ORDER BY category");

// Build query
$query = "SELECT * FROM books WHERE 1=1";
$params = [];
$types = "";

if (!empty($search)) {
    $search_param = "%$search%";
    $query .= " AND (title LIKE ? OR author LIKE ?)";
    $params[] = $search_param;
    $params[] = $search_param;
    $types .= "ss";
}

if (!empty($category)) {
    $query .= " AND category = ?";
    $params[] = $category;
    $types .= "s";
}

$query .= " ORDER BY title";

$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$books = $stmt->get_result();
?>

<div class="container mt-5 mb-5">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="mb-3"><i class="fas fa-book"></i> Browse Books</h1>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="row mb-4">
        <div class="col-md-8">
            <form method="GET" action="" class="d-flex gap-2">
                <input type="text" name="search" class="form-control" placeholder="Search by title or author..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Search</button>
            </form>
        </div>
        <div class="col-md-4">
            <form method="GET" action="">
                <select name="category" class="form-select" onchange="this.form.submit()">
                    <option value="">All Categories</option>
                    <?php while ($cat = $categories->fetch_assoc()): ?>
                        <option value="<?php echo htmlspecialchars($cat['category']); ?>" <?php echo $category === $cat['category'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cat['category']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </form>
        </div>
    </div>

    <!-- Books Grid -->
    <div class="row">
        <?php if ($books->num_rows > 0): ?>
            <?php while ($book = $books->fetch_assoc()): ?>
                <div class="col-md-3 mb-4">
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
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?php echo htmlspecialchars($book['title']); ?></h5>
                            <p class="card-text text-muted small">by <?php echo htmlspecialchars($book['author']); ?></p>
                            <p class="card-text"><small class="badge bg-primary"><?php echo htmlspecialchars($book['category']); ?></small></p>
                            <p class="card-text"><small class="text-muted"><?php echo $book['published_year']; ?></small></p>
                            <p class="card-text mt-auto">
                                <?php if ($book['available'] > 0): ?>
                                    <small class="text-success"><i class="fas fa-check-circle"></i> Available (<?php echo $book['available']; ?>)</small>
                                <?php else: ?>
                                    <small class="text-danger"><i class="fas fa-times-circle"></i> Not Available</small>
                                <?php endif; ?>
                            </p>
                            <a href="book_detail.php?id=<?php echo $book['id']; ?>" class="btn btn-sm btn-primary w-100">
                                <i class="fas fa-eye"></i> View Details
                            </a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-md-12">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> No books found.
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
