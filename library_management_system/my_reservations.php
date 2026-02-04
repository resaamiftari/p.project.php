<?php
$page_title = "My Reservations - Library Management System";
include 'includes/header.php';
include 'config/database.php';
include 'includes/functions.php';

requireLogin();

$stmt = $conn->prepare("
    SELECT r.id, r.book_id, r.status, r.reservation_date, b.title, b.author, b.image 
    FROM reservations r 
    JOIN books b ON r.book_id = b.id 
    WHERE r.user_id = ? 
    ORDER BY r.reservation_date DESC
");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$reservations = $stmt->get_result();
?>

<div class="container mt-5 mb-5">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1><i class="fas fa-bookmark"></i> My Reservations</h1>
        </div>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($_GET['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if ($reservations->num_rows > 0): ?>
        <div class="row">
            <?php while ($res = $reservations->fetch_assoc()): ?>
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="row g-0">
                            <div class="col-md-4">
                                <?php if (!empty($res['image']) && file_exists('assets/images/books/' . $res['image'])): ?>
                                    <img src="assets/images/books/<?php echo htmlspecialchars($res['image']); ?>" class="img-fluid rounded-start" alt="<?php echo htmlspecialchars($res['title']); ?>">
                                <?php else: ?>
                                    <div class="bg-light d-flex align-items-center justify-content-center" style="min-height: 200px;">
                                        <i class="fas fa-book fa-3x text-secondary"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($res['title']); ?></h5>
                                    <p class="card-text text-muted">by <?php echo htmlspecialchars($res['author']); ?></p>
                                    <p class="card-text">
                                        <small class="text-muted">
                                            <i class="fas fa-calendar"></i> <?php echo date('d/m/Y', strtotime($res['reservation_date'])); ?>
                                        </small>
                                    </p>
                                    <p class="card-text">
                                        Status: 
                                        <?php
                                        $status_class = 'badge bg-secondary';
                                        if ($res['status'] == 'pending') $status_class = 'badge bg-warning';
                                        if ($res['status'] == 'approved') $status_class = 'badge bg-success';
                                        if ($res['status'] == 'rejected') $status_class = 'badge bg-danger';
                                        ?>
                                        <span class="<?php echo $status_class; ?>"><?php echo ucfirst($res['status']); ?></span>
                                    </p>
                                    <a href="book_detail.php?id=<?php echo $res['book_id']; ?>" class="btn btn-sm btn-primary">View Book</a>
                                    <?php if ($res['status'] != 'cancelled'): ?>
                                        <a href="cancel_reservation.php?id=<?php echo $res['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Cancel this reservation?');">Cancel</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> You have no reservations. <a href="books.php">Browse books</a>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
