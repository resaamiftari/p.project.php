<?php
include 'includes/functions.php';
include 'config/database.php';
$page_title = "Manage Books - Library Management System";
include 'includes/header.php';

requireAdmin();

$books = $conn->query("SELECT * FROM books ORDER BY title");
?>

<div class="container mt-5 mb-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1><i class="fas fa-book"></i> Manage Books</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="add_book.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Book
            </a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Category</th>
                    <th>Year</th>
                    <th>Quantity</th>
                    <th>Available</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($book = $books->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $book['id']; ?></td>
                        <td><?php echo htmlspecialchars($book['title']); ?></td>
                        <td><?php echo htmlspecialchars($book['author']); ?></td>
                        <td><?php echo htmlspecialchars($book['category']); ?></td>
                        <td><?php echo $book['published_year']; ?></td>
                        <td><?php echo $book['quantity']; ?></td>
                        <td><?php echo $book['available']; ?></td>
                        <td>
                            <a href="edit_book.php?id=<?php echo $book['id']; ?>" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="delete_book.php?id=<?php echo $book['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?');">
                                <i class="fas fa-trash"></i> Delete
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <?php if ($books->num_rows == 0): ?>
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> No books found. <a href="add_book.php">Add your first book</a>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
