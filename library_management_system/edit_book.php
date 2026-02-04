<?php
include 'includes/functions.php';
include 'config/database.php';
$page_title = "Edit Book - Library Management System";
include 'includes/header.php';

requireAdmin();

$book_id = sanitize($_GET['id'] ?? '');

if (empty($book_id) || !is_numeric($book_id)) {
    header("Location: manage_books.php");
    exit();
}

$stmt = $conn->prepare("SELECT * FROM books WHERE id = ?");
$stmt->bind_param("i", $book_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: manage_books.php");
    exit();
}

$book = $result->fetch_assoc();
$stmt->close();

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = sanitize($_POST['title'] ?? '');
    $author = sanitize($_POST['author'] ?? '');
    $category = sanitize($_POST['category'] ?? '');
    $published_year = sanitize($_POST['published_year'] ?? '');
    $description = sanitize($_POST['description'] ?? '');
    $quantity = sanitize($_POST['quantity'] ?? '1');
    $image_name = $book['image'];

    // Validation
    if (empty($title)) $errors[] = "Title is required";
    if (empty($author)) $errors[] = "Author is required";
    if (empty($category)) $errors[] = "Category is required";
    if (!is_numeric($quantity) || $quantity < 1) $errors[] = "Quantity must be at least 1";

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
        $target_dir = "assets/images/books/";
        $file_name = basename($_FILES['image']['name']);
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($file_ext, $allowed)) {
            $errors[] = "Invalid image format. Allowed: jpg, jpeg, png, gif";
        } elseif ($_FILES['image']['size'] > 2000000) {
            $errors[] = "Image size is too large (max 2MB)";
        } else {
            $unique_name = time() . '_' . $file_name;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_dir . $unique_name)) {
                if (!empty($book['image']) && file_exists($target_dir . $book['image'])) {
                    unlink($target_dir . $book['image']);
                }
                $image_name = $unique_name;
            } else {
                $errors[] = "Failed to upload image";
            }
        }
    }

    // Update book
    if (empty($errors)) {
        $stmt = $conn->prepare("UPDATE books SET title=?, author=?, category=?, published_year=?, description=?, quantity=?, image=? WHERE id=?");
        $stmt->bind_param("sssissi", $title, $author, $category, $published_year, $description, $quantity, $image_name, $book_id);
        
        if ($stmt->execute()) {
            $success = true;
            $book['title'] = $title;
            $book['author'] = $author;
            $book['category'] = $category;
            $book['published_year'] = $published_year;
            $book['description'] = $description;
            $book['quantity'] = $quantity;
            $book['image'] = $image_name;
        } else {
            $errors[] = "Failed to update book: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>

<div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h1 class="mb-4"><i class="fas fa-edit"></i> Edit Book</h1>

            <?php if ($success): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    Book updated successfully!
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (!empty($errors)): ?>
                <?php foreach ($errors as $error): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo $error; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <div class="card shadow">
                <div class="card-body p-4">
                    <form method="POST" action="" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title *</label>
                            <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($book['title']); ?>" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="author" class="form-label">Author *</label>
                                <input type="text" class="form-control" id="author" name="author" value="<?php echo htmlspecialchars($book['author']); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="category" class="form-label">Category *</label>
                                <input type="text" class="form-control" id="category" name="category" value="<?php echo htmlspecialchars($book['category']); ?>" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="published_year" class="form-label">Published Year *</label>
                                <input type="number" class="form-control" id="published_year" name="published_year" value="<?php echo $book['published_year']; ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="quantity" class="form-label">Quantity *</label>
                                <input type="number" class="form-control" id="quantity" name="quantity" value="<?php echo $book['quantity']; ?>" min="1" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="4"><?php echo htmlspecialchars($book['description']); ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Book Image</label>
                            <?php if (!empty($book['image']) && file_exists('assets/images/books/' . $book['image'])): ?>
                                <div class="mb-2">
                                    <img src="assets/images/books/<?php echo htmlspecialchars($book['image']); ?>" style="max-width: 150px; height: auto;">
                                </div>
                            <?php endif; ?>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            <small class="form-text text-muted">Allowed formats: JPG, JPEG, PNG, GIF (Max 2MB)</small>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Book
                        </button>
                        <a href="manage_books.php" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
