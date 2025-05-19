<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <title>Καλάθι</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
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

<?php include 'header.php'; ?>
<?php
require_once 'db_connection.php';?>

    <main class="container my-4">
        <a href="index.php" class="btn btn-secondary mb-3">← Πίσω στο κατάστημα</a>
            <h2>Καλάθι Αγορών</h2>
            <div id="cart-container"></div>

            <div id="checkout-section" class="mt-5 text-end" style="display: none;">
                <a href="checkout.php" class="btn btn-success">
                Ολοκλήρωση Παραγγελίας
                </a>
            </div>

        
        <script>
            document.addEventListener('DOMContentLoaded', function () {
            const cart = JSON.parse(localStorage.getItem('cart')) || [];

            if (cart.length === 0) {
                document.getElementById('cart-container').innerHTML = "<p>Το καλάθι σας είναι άδειο.</p>";
                return;
            }

            const productIds = cart.map(item => item.id);

            fetch('get_products.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ ids: productIds })
            })
            .then(res => res.json())
            .then(products => {
                let html = `<table class="table table-bordered text-center">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Προϊόν</th>
                                        <th>Τιμή</th>
                                        <th>Ποσότητα</th>
                                        <th>Σύνολο</th>
                                        <th>Ενέργειες</th>
                                    </tr>
                                </thead>
                                <tbody>`;

                let total = 0;

                products.forEach(product => {
                    const item = cart.find(i => i.id == product.id);
                    const quantity = item ? item.quantity : 0;
                    const price = parseFloat(product.price);
                    const subtotal = quantity * price;
                    total += subtotal;

                    html += `<tr>
                                <td>${product.name}</td>
                                <td>${price.toFixed(2)} €</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-success me-1 update-qty" data-id="${product.id}" data-action="increase">+</button>
                                    ${quantity}
                                    <button class="btn btn-sm btn-outline-danger ms-1 update-qty" data-id="${product.id}" data-action="decrease">-</button>
                                </td>
                                <td>${subtotal.toFixed(2)} €</td>
                                <td>
                                    <button class="btn btn-sm btn-danger remove-item" data-id="${product.id}">Αφαίρεση</button>
                                </td>
                            </tr>`;
                });

                html += `</tbody></table>`;
                html += `<h4 class="text-end">Σύνολο: <strong>${total.toFixed(2)} €</strong></h4>`;

                document.getElementById('cart-container').innerHTML = html;
                document.getElementById('checkout-section').style.display = 'block';

                // Ενέργειες κουμπιών
                document.querySelectorAll('.update-qty').forEach(btn => {
                    btn.addEventListener('click', function () {
                        const productId = this.dataset.id;
                        const action = this.dataset.action;
                        updateCart(productId, action);
                    });
                });

                document.querySelectorAll('.remove-item').forEach(btn => {
                    btn.addEventListener('click', function () {
                        const productId = this.dataset.id;
                        removeItem(productId);
                    });
                });

                function updateCart(productId, action) {
                    let cart = JSON.parse(localStorage.getItem('cart')) || [];
                    const item = cart.find(i => i.id == productId);

                    if (action === 'increase') {
                        if (item) item.quantity++;
                    } else if (action === 'decrease') {
                        if (item.quantity > 1) {
                            item.quantity--;
                        } else {
                            cart = cart.filter(i => i.id != productId);
                        }
                    }

                    localStorage.setItem('cart', JSON.stringify(cart));
                    location.reload();
                }

                function removeItem(productId) {
                    let cart = JSON.parse(localStorage.getItem('cart')) || [];
                    cart = cart.filter(i => i.id != productId);
                    localStorage.setItem('cart', JSON.stringify(cart));
                    location.reload();
                }
            })
            .catch(error => {
                console.error("Σφάλμα:", error);
                document.getElementById('cart-container').innerHTML = "<p>Σφάλμα κατά την φόρτωση του καλαθιού.</p>";
            });
        });
        </script>
    </main>

    <?php include 'footer.php'; ?>
<div>
