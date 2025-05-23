<?php
require '../config.php';

if (!isset($_SESSION['admin_id']) || !$_SESSION['admin_username'] ) {
    // If the user is not logged in or not an admin, redirect
    header('Location: login.php');
    exit;
}
// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['payment_id'], $_POST['new_status'])) {
    $stmt = $db->prepare("UPDATE payments SET payment_status = ? WHERE id = ?");
    $stmt->bind_param("si", $_POST['new_status'], $_POST['payment_id']);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_payments.php?status=" . ($_GET['status'] ?? 'all'));
    exit;
}

// Filter
$status = $_GET['status'] ?? 'all';
$condition = ($status !== 'all') ? "WHERE p.payment_status = ?" : "";

$sql = "
    SELECT 
        p.id AS payment_id, p.user_id, p.amount, p.payment_method, p.payment_status, p.payment_date, p.receipt_url,
        c.title AS course_title, c.price, c.discount, c.course_url
    FROM payments p
    JOIN courses c ON p.course_id = c.id
    $condition
    ORDER BY p.payment_date DESC
";

$stmt = ($status !== 'all') ? $db->prepare($sql) : $db->prepare(str_replace("WHERE p.payment_status = ?", "", $sql));

if ($status !== 'all') {
    $stmt->bind_param("s", $status);
}

$stmt->execute();
$result = $stmt->get_result();
$payments = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Payments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container my-5">
    <h2 class="mb-4">Manage Payments</h2>

    <!-- Filter -->
    <form class="mb-3">
        <div class="row">
            <div class="col-md-4">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="all" <?= $status === 'all' ? 'selected' : '' ?>>All</option>
                    <option value="pending" <?= $status === 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="completed" <?= $status === 'completed' ? 'selected' : '' ?>>Completed</option>
                    <option value="failed" <?= $status === 'failed' ? 'selected' : '' ?>>Failed</option>
                    <option value="refunded" <?= $status === 'refunded' ? 'selected' : '' ?>>Refunded</option>
                </select>
            </div>
        </div>
    </form>

    <!-- Table -->
    <div class="table-responsive shadow-sm bg-white p-3 rounded">
        <table class="table table-bordered align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>User ID</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Receipt</th>
                    <th>Course</th>
                    <th>Price</th>
                    <th>Discount</th>
                    <th>Course URL</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($payments as $pay): ?>
                <tr>
                    <td><?= $pay['payment_id'] ?></td>
                    <td><?= $pay['user_id'] ?></td>
                    <td>$<?= number_format($pay['amount'], 2) ?></td>
                    <td><?= ucfirst($pay['payment_method']) ?></td>
                    <td>
                        <form method="post" class="d-flex align-items-center">
                            <input type="hidden" name="payment_id" value="<?= $pay['payment_id'] ?>">
                            <select name="new_status" class="form-select form-select-sm me-2">
                                <?php foreach (['pending', 'completed', 'failed', 'refunded'] as $opt): ?>
                                    <option value="<?= $opt ?>" <?= $pay['payment_status'] === $opt ? 'selected' : '' ?>>
                                        <?= ucfirst($opt) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <button class="btn btn-sm btn-primary">Update</button>
                        </form>
                    </td>
                    <td><?= $pay['payment_date'] ?></td>
                    <td>
                        <?php if ($pay['receipt_url']): ?>
                            <a href="../api/uploads/<?= $pay['receipt_url'] ?>" target="_blank">View</a>
                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($pay['course_title']) ?></td>
                    <td>$<?= number_format($pay['price'], 2) ?></td>
                    <td><?= $pay['discount'] ?>%</td>
                    <td>
                        <?php if ($pay['course_url']): ?>
                            <a href="<?= $pay['course_url'] ?>" target="_blank">Link</a>
                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
