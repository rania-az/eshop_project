
<?php
require_once 'includes/db.php';
require_once 'header.php';
?>

<div class="container mt-4">
    <!-- Φόρμα Αναζήτησης -->
    <form method="GET" action="index.php" class="mb-4 d-flex" style="max-width: 500px;">
        <input type="text" name="search" class="form-control me-2" placeholder="Αναζήτηση προϊόντος..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
        <button type="submit" class="btn btn-success">Αναζήτηση</button>
    </form>
    
    
    <!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Κατάστημα Φοιτητικών Ειδών</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center mb-4"> Φοιτητικά Είδη</h1>
</div>
</body>

</html>

    <div class="row">
        <?php
        $search = $_GET['search'] ?? '';
        $searchParam = '%' . $search . '%';

        if ($search) {
            $stmt = $mysqli->prepare("SELECT * FROM products WHERE name LIKE ?");
            $stmt->bind_param("s", $searchParam);
        } else {
            $stmt = $mysqli->prepare("SELECT * FROM products");
        }

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($product = $result->fetch_assoc()) {
                ?>
                <div class="col-md-3 mb-3">
                    <div class="card h-100 d-flex flex-column">
                        <img src="images/<?= htmlspecialchars($product['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>" style="height: 200px; object-fit: contain;">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                            <p class="card-text"><?= number_format($product['price'], 2) ?> €</p>
                            <a href="product.php?id=<?= $product['id'] ?>" class="btn btn-success">Προβολή</a>

                        </div>
                    </div>
                </div>
                <?php
            }
        } else {
            echo "<p>Δεν βρέθηκαν προϊόντα με αυτό το όνομα.</p>";
        }

        $stmt->close();
        ?>
    </div>
</div>

<?php
require_once 'db_connection.php';
$result = $conn->query("SELECT * FROM products");
?>
<?php require_once 'footer.php'?>


   

