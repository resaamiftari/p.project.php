<?php
$page_title = "Add Book - Library Management System";
include 'includes/header.php';
include 'config/database.php';
include 'includes/functions.php';

requireAdmin();

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = sanitize($_POST['title'] ?? '');
    $author = sanitize($_POST['author'] ?? '');
    $category = sanitize($_POST['category'] ?? '');
    $published_year = sanitize($_POST['published_year'] ?? '');
    $description = sanitize($_POST['description'] ?? '');
    $quantity = sanitize($_POST['quantity'] ?? '1');
    $image_name = '';

    // Validation
    if (empty($title)) $errors[] = "Title is required";
    if (empty($author)) $errors[] = "Author is required";
    if (empty($category)) $errors[] = "Category is required";
    if (empty($published_year)) $errors[] = "Published year is required";
    if (!is_numeric($published_year)) $errors[] = "Published year must be a number";
    if (!is_numeric($quantity) || $quantity < 1) $errors[] = "Quantity must be at least 1";

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
        $target_dir = "assets/images/books/";
        $file_name = basename($_FILES['image']['name']);
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($file_ext, $allowed)) {
            $errors[] = "Invalid image format. Allowed: jpg, jpeg, png, gif";
        } elseif ($_FILES['image']['size'] > 2000000) {
            $errors[] = "Image size is too large (max 2MB)";
        } else {
            $unique_name = time() . '_' . $file_name;
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0755, true);
            }
            if (move_uploaded_file($file_tmp, $target_dir . $unique_name)) {
                $image_name = $unique_name;
            } else {
                $errors[] = "Failed to upload image";
            }
        }
    }

    // Insert book
    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO books (title, author, category, published_year, description, image, quantity, available) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssiss" . ($image_name ? "s" : ""), $title, $author, $category, $published_year, $description, $image_name);
        
        if ($stmt->execute()) {
            $success = true;
        } else {
            $errors[] = "Failed to add book: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>

<div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h1 class="mb-4"><i class="fas fa-plus"></i> Add Book</h1>

            <?php if ($success): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    Book added successfully! <a href="manage_books.php">View all books</a>
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
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="author" class="form-label">Author *</label>
                                <input type="text" class="form-control" id="author" name="author" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="category" class="form-label">Category *</label>
                                <input type="text" class="form-control" id="category" name="category" list="categories" required>
                                <datalist id="categories">
                                    <option>Classic</option>
                                    <option>Fantasy</option>
                                    <option>Mystery</option>
                                    <option>Romance</option>
                                    <option>Science Fiction</option>
                                    <option>Horror</option>
                                    <option>Adventure</option>
                                    <option>Dystopian</option>
                                    <option>Allegory</option>
                                </datalist>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="published_year" class="form-label">Published Year *</label>
                                <input type="number" class="form-control" id="published_year" name="published_year" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="quantity" class="form-label">Quantity *</label>
                                <input type="number" class="form-control" id="quantity" name="quantity" value="1" min="1" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="4"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Book Image</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            <small class="form-text text-muted">Allowed formats: JPG, JPEG, PNG, GIF (Max 2MB)</small>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Add Book
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
