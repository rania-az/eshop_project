<?php include 'header.php'; ?>
<?php include 'includes/db.php'; ?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <title>Ολοκλήρωση Παραγγελίας</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
     <style>
        html, body {
            height: 100%;
        }
        body {
            display: flex;
            flex-direction: column;
        }
        main {
            flex: 1;
        }
    </style>
</head>
<body>
<div class="d-flex flex-column min-vh-100">

    <main class="container my-4 flex-grow-1">
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $cart = json_decode($_POST['cart'], true);

            if ($name && $email && !empty($cart)) {
                $total = 0;
                // Πάρε μόνο τα IDs από το καλάθι
                $ids = array_column($cart, 'id');
                $id_placeholders = implode(',', array_fill(0, count($ids), '?'));

                $types = str_repeat('i', count($ids));
                $stmt = $mysqli->prepare("SELECT id, price FROM products WHERE id IN ($id_placeholders)");
                $stmt->bind_param($types, ...$ids);
                $stmt->execute();
                $result = $stmt->get_result();

                // Φτιάξε map προϊόντων [id => price]
                $productPrices = [];
                while ($row = $result->fetch_assoc()) {
                    $productPrices[$row['id']] = $row['price'];
                }
                foreach ($cart as $item) {
            $product_id = $item['id'];
            $quantity = $item['quantity'];
            $price = $productPrices[$product_id] ?? 0;
            $total += $price * $quantity;
        }

        // Εισαγωγή παραγγελίας
        $stmt_order = $mysqli->prepare("INSERT INTO orders (customer_name, customer_email, total_price, created_at) VALUES (?, ?, ?, NOW())");
        $stmt_order->bind_param("ssd", $name, $email, $total);
        $stmt_order->execute();
        $order_id = $stmt_order->insert_id;

               $stmt_item = $mysqli->prepare("INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)");
                foreach ($cart as $item) {
                     $product_id = $item['id'];
                     $quantity = $item['quantity'];
                     $price = $productPrices[$product_id] ?? 0;
                     $stmt_item->bind_param("iii", $order_id, $product_id, $quantity);
                     $stmt_item->execute();
                     $total += $price * $quantity;
                }

                echo "<h2 class='text-success'>Η παραγγελία καταχωρήθηκε με επιτυχία!</h2>";
                echo "<p>Ευχαριστούμε, <strong>$name</strong>. Θα λάβεις επιβεβαίωση στο <strong>$email</strong>.</p>";
                echo "<script>localStorage.removeItem('cart');</script>";
            } else {
                echo "<div class='alert alert-danger'>Σφάλμα: Συμπληρώστε όλα τα πεδία.</div>";
            }
        }
        ?>

        <?php if ($_SERVER['REQUEST_METHOD'] !== 'POST'): ?>
            <h2>Ολοκλήρωση Παραγγελίας</h2>
            <form method="post" onsubmit="return prepareOrder()">
                <div class="mb-3">
                    <label>Όνομα:</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Email:</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <input type="hidden" name="cart" id="cart-data">
                <button type="submit" class="btn btn-success">Καταχώρηση</button>
            </form>
        <?php endif; ?>
    </main>

    <?php include 'footer.php'; ?>
</div>

<script>
function prepareOrder() {
    const cart = localStorage.getItem('cart');
    if (!cart) {
        alert("Το καλάθι είναι άδειο.");
        return false;
    }
    document.getElementById('cart-data').value = cart;
    return true;
}
</script>
</body>
</html>
