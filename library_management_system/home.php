<?php
$page_title = "Welcome - Library Management System";
include 'includes/header.php';
?>

<style>
    .hero-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 100px 0;
        text-align: center;
    }
    .hero-section h1 {
        font-size: 3rem;
        font-weight: 700;
        margin-bottom: 20px;
        color: white;
    }
    .hero-section p {
        font-size: 1.3rem;
        margin-bottom: 30px;
        color: rgba(255, 255, 255, 0.9);
    }
    .feature-card {
        text-align: center;
        padding: 30px;
        margin-bottom: 30px;
    }
    .feature-icon {
        font-size: 3rem;
        margin-bottom: 15px;
        color: #3498db;
    }
</style>

<!-- Hero Section -->
<div class="hero-section">
    <div class="container">
        <h1><i class="fas fa-book"></i> Library Management System</h1>
        <p>Discover, Reserve, and Explore Your Favorite Books</p>
        <?php if (!isLoggedIn()): ?>
            <a href="register.php" class="btn btn-light btn-lg me-2"><i class="fas fa-user-plus"></i> Register</a>
            <a href="login.php" class="btn btn-outline-light btn-lg"><i class="fas fa-sign-in-alt"></i> Login</a>
        <?php else: ?>
            <a href="books.php" class="btn btn-light btn-lg"><i class="fas fa-book-open"></i> Browse Books</a>
        <?php endif; ?>
    </div>
</div>

<!-- Features Section -->
<div class="container mt-5 mb-5">
    <div class="row mb-5">
        <div class="col-md-12 text-center mb-5">
            <h2>Why Choose Our Library?</h2>
            <p class="text-muted">A modern platform for book lovers</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-search"></i></div>
                <h4>Browse Books</h4>
                <p class="text-muted">Search and discover thousands of books across multiple categories</p>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-bookmark"></i></div>
                <h4>Make Reservations</h4>
                <p class="text-muted">Reserve your favorite books and get them approved by our librarians</p>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-shield-alt"></i></div>
                <h4>Secure System</h4>
                <p class="text-muted">Your data is safe with our secure authentication and encryption</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-mobile-alt"></i></div>
                <h4>Responsive Design</h4>
                <p class="text-muted">Access from any device - desktop, tablet, or mobile phone</p>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-chart-bar"></i></div>
                <h4>Track Records</h4>
                <p class="text-muted">Keep track of all your reservations and borrowing history</p>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-cogs"></i></div>
                <h4>Admin Tools</h4>
                <p class="text-muted">Complete management system for librarians and administrators</p>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Section -->
<div class="container mb-5">
    <div class="row">
        <div class="col-md-12">
            <h2 class="text-center mb-4">By The Numbers</h2>
        </div>
        <?php
        if (isLoggedIn()) {
            include 'config/database.php';
            $books = $conn->query("SELECT COUNT(*) as count FROM books")->fetch_assoc()['count'];
            $users = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
            $reservations = $conn->query("SELECT COUNT(*) as count FROM reservations")->fetch_assoc()['count'];
        } else {
            $books = 40;
            $users = 0;
            $reservations = 0;
        }
        ?>
        <div class="col-md-4 text-center">
            <h3 class="text-primary"><?php echo $books; ?>+</h3>
            <p class="text-muted">Books Available</p>
        </div>
        <div class="col-md-4 text-center">
            <h3 class="text-success"><?php echo $users; ?>+</h3>
            <p class="text-muted">Active Users</p>
        </div>
        <div class="col-md-4 text-center">
            <h3 class="text-info"><?php echo $reservations; ?>+</h3>
            <p class="text-muted">Reservations Made</p>
        </div>
    </div>
</div>

<!-- CTA Section -->
<div style="background: #f8f9fa; padding: 60px 0;">
    <div class="container text-center">
        <h2>Ready to Get Started?</h2>
        <p class="text-muted mb-4">Join our community of book lovers today</p>
        <?php if (!isLoggedIn()): ?>
            <a href="register.php" class="btn btn-primary btn-lg me-2"><i class="fas fa-user-plus"></i> Create Account</a>
            <a href="setup.php" class="btn btn-outline-secondary btn-lg"><i class="fas fa-cogs"></i> Setup Info</a>
        <?php else: ?>
            <a href="books.php" class="btn btn-primary btn-lg"><i class="fas fa-book-open"></i> Browse Library</a>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
