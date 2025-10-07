<?php
session_start();
include("../includes/db_connect.php"); // path adjusted
$currentPage = 'profile';
include("header.php");
// Check login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../customerLogin.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user info
$stmt = $con->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Cart items (Pending bookings)
$cart_stmt = $con->prepare("
    SELECT b.booking_id, b.quantity, s.spare_name, s.price, b.delivery_status
    FROM bookings b
    JOIN spares s ON b.spare_id = s.spare_id
    WHERE b.user_id = ? AND b.delivery_status = 'Pending'
");
$cart_stmt->bind_param("i", $user_id);
$cart_stmt->execute();
$cart_items = $cart_stmt->get_result();

// All orders
$order_stmt = $con->prepare("
    SELECT b.booking_id, b.booking_date, b.quantity, s.spare_name, s.price, b.delivery_status, b.current_location
    FROM bookings b
    JOIN spares s ON b.spare_id = s.spare_id
    WHERE b.user_id = ? 
    ORDER BY b.booking_date DESC
");
$order_stmt->bind_param("i", $user_id);
$order_stmt->execute();
$orders = $order_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Profile</title>
<link rel="stylesheet" href="../assets/css/profile.css">
</head>
<body>
<div class="profile-wrapper">

    <h1>Welcome, <?= htmlspecialchars($user['name']) ?>!</h1>

    <?php
    if(isset($_SESSION['success_msg'])){
        echo "<p class='success-msg'>".$_SESSION['success_msg']."</p>";
        unset($_SESSION['success_msg']);
    }
    ?>

    <!-- Tabs -->
    <div class="tabs">
        <button class="tab-link active" onclick="openTab(event, 'profile')">Profile</button>
        <button class="tab-link" onclick="openTab(event, 'cart')">Cart</button>
        <button class="tab-link" onclick="openTab(event, 'orders')">Orders</button>
    </div>

    <!-- Profile Tab -->
    <div id="profile" class="tab-content active">
        <form action="update_profile.php" method="post" class="card">
            <label>Name</label>
            <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
            <label>Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
            <button type="submit">Update Profile</button>
        </form>
    </div>

    <!-- Cart Tab -->
    <div id="cart" class="tab-content">
        <?php if ($cart_items->num_rows > 0): ?>
            <?php while($item = $cart_items->fetch_assoc()): ?>
            <div class="card cart-card">
                <h3><?= htmlspecialchars($item['spare_name']) ?></h3>
                <p>Quantity: <?= $item['quantity'] ?></p>
                <p>Price: ₹<?= $item['price'] * $item['quantity'] ?></p>
                <p>Status: <?= $item['delivery_status'] ?></p>
                <a href="remove_cart.php?booking_id=<?= $item['booking_id'] ?>" class="btn-red">Remove</a>
            </div>
            <?php endwhile; ?>
            <a href="checkout.php" class="btn-red checkout-btn">Proceed to Checkout</a>
        <?php else: ?>
            <p class="empty-msg">Your cart is empty.</p>
        <?php endif; ?>
    </div>

    <!-- Orders Tab -->
    <div id="orders" class="tab-content">
        <?php if ($orders->num_rows > 0): ?>
            <?php while($order = $orders->fetch_assoc()): ?>
            <div class="card order-card">
                <h3>Booking ID: <?= $order['booking_id'] ?></h3>
                <p>Spare: <?= htmlspecialchars($order['spare_name']) ?></p>
                <p>Quantity: <?= $order['quantity'] ?></p>
                <p>Total: ₹<?= $order['price'] * $order['quantity'] ?></p>
                <p>Status: <?= $order['delivery_status'] ?></p>
                <p>Location: <?= $order['current_location'] ?? '-' ?></p>
                <p>Date: <?= $order['booking_date'] ?></p>
                <a href="track_order.php?booking_id=<?= $order['booking_id'] ?>" class="btn-red">Track</a>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="empty-msg">No orders yet.</p>
        <?php endif; ?>
    </div>

</div>

<script>
function openTab(evt, tabName) {
    let tabcontent = document.getElementsByClassName("tab-content");
    for (let i = 0; i < tabcontent.length; i++) tabcontent[i].style.display = "none";
    let tablinks = document.getElementsByClassName("tab-link");
    for (let i = 0; i < tablinks.length; i++) tablinks[i].classList.remove("active");
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.classList.add("active");
}

// Show first tab by default
document.addEventListener("DOMContentLoaded", () => {
    document.getElementById("profile").style.display = "block";
});
</script>
</body>
</html>
