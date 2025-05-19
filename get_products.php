<?php
require_once 'db_connection.php';

$data = json_decode(file_get_contents("php://input"), true);

$ids = isset($data['ids']) ? $data['ids'] : [];

$placeholders = implode(',', array_fill(0, count($ids), '?'));
$types = str_repeat('i', count($ids));

$stmt = $conn->prepare("SELECT id, name, price FROM products WHERE id IN ($placeholders)");
$stmt->bind_param($types, ...$ids);
$stmt->execute();
$result = $stmt->get_result();

$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}
// Ο κώδικας που ανακτά τα προϊόντα
while ($row = $result->fetch_assoc()) {
    // Εκτυπώνουμε την τιμή για να δούμε τι επιστρέφει
    var_dump($row['price']); // Εκτυπώνουμε την τιμή του προϊόντος για να δούμε αν είναι σωστή
    $price = (float)$row['price'];  // Μετατρέπουμε την τιμή σε float
    $products[] = [
        'id' => $row['id'],
        'name' => $row['name'],
        'price' => $price
    ];
}


echo json_encode($products);
