<!DOCTYPE html>
<html>

<body>
  
<div style="background-color: #d42b0d; padding: 30px;">
    <div style="display: flex; justify-content: space-between; align-items: center; color: white;">
    <a class="navbar-brand" href="index.php" style=font-size:25px;><b>E-SHOP</b></a>
    
        <a href="cart.php" style="color:#2e86c1; text-decoration: none; background-color:white;"class="btn btn-outline-primary position-relative">
             Καλάθι🛒<span id="cart-count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"></span>
        </a>
    
    </div>
</div>



<script>
function updateCartCount() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    let totalCount = cart.reduce((sum, item) => sum + item.quantity, 0);
    const countSpan = document.getElementById('cart-count');
    if (countSpan) {
        countSpan.textContent = totalCount > 0 ? totalCount : '';
    }
}
updateCartCount();
</script>



