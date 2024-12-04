<?php
session_start();

$cart_count = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $quantity) {
        $cart_count += $quantity;
    }
}
echo $cart_count;
?>
