<?php require_once 'header.php'; ?>
<?php
require_once 'db_connection.php';

if (!isset($_GET['id'])) {
    echo "Μη έγκυρο προϊόν.";
    exit;
}

$id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    echo "Το προϊόν δεν βρέθηκε.";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($product['name']); ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
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

<main class="container my-5">
    <a href="index.php" class="btn btn-secondary mb-3">← Πίσω στο κατάστημα</a>
    <div class="row">
        <div class="col-md-3">
            <img src="images/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="img-fluid">
        </div>
        <div class="col-md-6">
            <h2><?php echo htmlspecialchars($product['name']); ?></h2>
            <p><?php echo htmlspecialchars($product['description']); ?></p>
            <h4><?php echo number_format($product['price'], 2); ?> €</h4>
            <button class="btn btn-success" onclick="addToCart(<?php echo $product['id']; ?>)">Προσθήκη στο καλάθι</button>
        </div>
    </div>
</main>

<?php require_once 'footer.php'; ?>

<script>
function addToCart(productId) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    const index = cart.findIndex(item => item.id === productId);
    if (index >= 0) {
        cart[index].quantity += 1;
    } else {
        cart.push({ id: productId, quantity: 1 });
    }
    localStorage.setItem('cart', JSON.stringify(cart));
    alert("Το προϊόν προστέθηκε στο καλάθι!");
}
</script>

</body>
</html>
