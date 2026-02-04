<?php
$page_title = "Profile - Library Management System";
include 'includes/header.php';
include 'config/database.php';
include 'includes/functions.php';

requireLogin();

$stmt = $conn->prepare("SELECT id, emri, username, email, is_admin, created_at FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();
?>

<div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="card shadow">
                <div class="card-body p-4">
                    <h2 class="card-title mb-4">
                        <i class="fas fa-user-circle"></i> User Profile
                    </h2>

                    <div class="mb-3">
                        <label class="form-label"><strong>Full Name:</strong></label>
                        <p><?php echo htmlspecialchars($user['emri']); ?></p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><strong>Username:</strong></label>
                        <p><?php echo htmlspecialchars($user['username']); ?></p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><strong>Email:</strong></label>
                        <p><?php echo htmlspecialchars($user['email']); ?></p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><strong>Role:</strong></label>
                        <p>
                            <?php if ($user['is_admin'] == 1): ?>
                                <span class="badge bg-danger">Administrator</span>
                            <?php else: ?>
                                <span class="badge bg-primary">Regular User</span>
                            <?php endif; ?>
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><strong>Member Since:</strong></label>
                        <p><?php echo date('d/m/Y', strtotime($user['created_at'])); ?></p>
                    </div>

                    <a href="dashboard.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
