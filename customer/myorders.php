<?php
session_start();
$currentPage = "";
include("header.php");
include("../includes/db_connect.php");

// Check user login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$success = "";
$error = "";

// Handle cancel request
if (isset($_POST['cancel_booking_id'])) {
    $booking_id = intval($_POST['cancel_booking_id']);

    // Fetch booking to validate ownership
    $stmt = $con->prepare("
        SELECT b.booking_id, b.spare_id, b.quantity, s.stock 
        FROM bookings b
        JOIN spares s ON b.spare_id = s.spare_id
        WHERE b.booking_id = ? AND b.user_id = ?
    ");
    $stmt->bind_param("ii", $booking_id, $user_id);
    $stmt->execute();
    $booking = $stmt->get_result()->fetch_assoc();

    if ($booking) {
        $spare_id = $booking['spare_id'];
        $quantity = $booking['quantity'];

        // Start transaction
        $con->begin_transaction();
        try {
            // Increment stock back
            $stmt1 = $con->prepare("UPDATE spares SET stock = stock + ? WHERE spare_id = ?");
            $stmt1->bind_param("ii", $quantity, $spare_id);
            $stmt1->execute();

            // Delete booking (or you could mark as cancelled with a status column instead)
            $stmt2 = $con->prepare("DELETE FROM bookings WHERE booking_id = ?");
            $stmt2->bind_param("i", $booking_id);
            $stmt2->execute();

            $con->commit();
            $success = "Order cancelled successfully.";
        } catch (Exception $e) {
            $con->rollback();
            $error = "Failed to cancel order. Try again.";
        }
    } else {
        $error = "Order not found or not yours.";
    }
}

// Fetch all orders for the user
$stmt = $con->prepare("
    SELECT b.booking_id, b.quantity, b.booking_date, 
           s.spare_name, s.price, s.image_path
    FROM bookings b
    JOIN spares s ON b.spare_id = s.spare_id
    WHERE b.user_id = ?
    ORDER BY b.booking_date DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$orders = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Orders</title>
  <link rel="stylesheet" href="../assets/css/myorders.css">
</head>
<body>
<div class="container">
    <h2>My Orders</h2>

    <?php if ($success): ?><p class="success"><?php echo $success; ?></p><?php endif; ?>
    <?php if ($error): ?><p class="error"><?php echo $error; ?></p><?php endif; ?>

    <?php if ($orders->num_rows > 0): ?>
        <table border="1" cellpadding="10" cellspacing="0">
            <tr>
                <th>Spare</th>
                <th>Image</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
            <?php while ($row = $orders->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['spare_name']); ?></td>
                    <td><img src="../<?php echo htmlspecialchars($row['image_path']); ?>" width="80"></td>
                    <td><?php echo $row['quantity']; ?></td>
                    <td>₹<?php echo number_format($row['price'], 2); ?></td>
                    <td>₹<?php echo number_format($row['price'] * $row['quantity'], 2); ?></td>
                    <td><?php echo $row['booking_date']; ?></td>
                    <td>
                        <form action="track_order.php" method="get" style="display:inline-block; margin-left:5px;">
                             <input type="hidden" name="booking_id" value="<?php echo $row['booking_id']; ?>">
                             <button type="submit" name="track">Track Order</button>
                        </form>
                        <form method="post" onsubmit="return confirm('Cancel this order?');">
                            <input type="hidden" name="cancel_booking_id" value="<?php echo $row['booking_id']; ?>">
                            <button type="submit">Cancel Order</button>
                        </form>                        
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>You have no orders yet.</p>
    <?php endif; ?>
</div>
</body>
</html>
