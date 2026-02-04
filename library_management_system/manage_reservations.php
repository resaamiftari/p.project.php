<?php
$page_title = "Manage Reservations - Library Management System";
include 'includes/header.php';
include 'config/database.php';
include 'includes/functions.php';

requireAdmin();

// Get reservations grouped by status
$query = "
    SELECT r.id, r.book_id, r.user_id, r.status, r.reservation_date, 
           b.title, u.username, u.emri
    FROM reservations r
    JOIN books b ON r.book_id = b.id
    JOIN users u ON r.user_id = u.id
    ORDER BY r.reservation_date DESC
";

$reservations = $conn->query($query);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $res_id = sanitize($_POST['res_id'] ?? '');
    $new_status = sanitize($_POST['status'] ?? '');

    if (!empty($res_id) && !empty($new_status)) {
        $stmt = $conn->prepare("UPDATE reservations SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $new_status, $res_id);
        $stmt->execute();
        $stmt->close();
        
        header("Location: manage_reservations.php");
        exit();
    }
}
?>

<div class="container mt-5 mb-5">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1><i class="fas fa-tasks"></i> Manage Reservations</h1>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Book</th>
                    <th>Reserved Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($res = $reservations->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $res['id']; ?></td>
                        <td><?php echo htmlspecialchars($res['username'] . ' (' . $res['emri'] . ')'); ?></td>
                        <td><?php echo htmlspecialchars($res['title']); ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($res['reservation_date'])); ?></td>
                        <td>
                            <?php
                            $status_class = 'badge bg-secondary';
                            if ($res['status'] == 'pending') $status_class = 'badge bg-warning';
                            if ($res['status'] == 'approved') $status_class = 'badge bg-success';
                            if ($res['status'] == 'rejected') $status_class = 'badge bg-danger';
                            ?>
                            <span class="<?php echo $status_class; ?>"><?php echo ucfirst($res['status']); ?></span>
                        </td>
                        <td>
                            <?php if ($res['status'] != 'cancelled'): ?>
                                <form method="POST" action="" style="display: inline;">
                                    <input type="hidden" name="res_id" value="<?php echo $res['id']; ?>">
                                    <select name="status" class="form-select form-select-sm d-inline-block w-auto" onchange="this.form.submit()">
                                        <option value="<?php echo $res['status']; ?>"><?php echo ucfirst($res['status']); ?></option>
                                        <option value="pending">Pending</option>
                                        <option value="approved">Approved</option>
                                        <option value="rejected">Rejected</option>
                                    </select>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
