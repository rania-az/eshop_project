

<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit;
}
?>

<?php
include 'includes/db.php';
include 'header.php';

$query = "SELECT o.id, o.customer_name, o.customer_email, o.total_price, o.created_at, 
                 oi.product_id, oi.quantity, p.name AS product_name
          FROM orders o
          JOIN order_items oi ON o.id = oi.order_id
          JOIN products p ON oi.product_id = p.id
          ORDER BY o.created_at DESC";

$result = $mysqli->query($query);

$orders = [];

while ($row = $result->fetch_assoc()) {
    $orderId = $row['id'];
    if (!isset($orders[$orderId])) {
        $orders[$orderId] = [
            'customer_name' => $row['customer_name'],
            'customer_email' => $row['customer_email'],
            'total_price' => $row['total_price'],
            'created_at' => $row['created_at'],
            'items' => []
        ];
    }

    $orders[$orderId]['items'][] = [
        'product_name' => $row['product_name'],
        'quantity' => $row['quantity']
    ];
}
?>

<div class="container mt-5">
    <h2>Παραγγελίες</h2>
    <?php if (empty($orders)): ?>
        <p>Δεν υπάρχουν παραγγελίες.</p>
    <?php else: ?>
        <?php foreach ($orders as $id => $order): ?>
            <div class="card mb-3">
                <div class="card-header">
                    Παραγγελία #<?= $id ?> - <?= htmlspecialchars($order['customer_name']) ?> (<?= $order['customer_email'] ?>)
                </div>
                <div class="card-body">
                    <ul>
                        <?php foreach ($order['items'] as $item): ?>
                            <li><?= $item['product_name'] ?> - Ποσότητα: <?= $item['quantity'] ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <strong>Σύνολο: <?= number_format($order['total_price'], 2) ?> €</strong><br>
                    <small>Ημερομηνία: <?= $order['created_at'] ?></small>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>

<a href="logout.php">Αποσύνδεση</a>
