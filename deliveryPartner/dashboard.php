<?php
session_start();
include("../includes/db_connect.php");

// Check login
if (!isset($_SESSION['partner_id'])) {
    header("Location: login.php");
    exit;
}

$partner_id = $_SESSION['partner_id'];

// Fetch assigned bookings
$query = "SELECT * FROM bookings WHERE assigned_partner_id = '$partner_id' ORDER BY booking_date DESC";
$result = mysqli_query($con, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delivery Dashboard | AutoStream</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="dashboard-container">
    <header>
        <h2>Welcome, Delivery Partner</h2>
        <a href="logout.php" class="logout-btn">Logout</a>
    </header>

    <section class="orders-section">
        <h3>Assigned Deliveries</h3>
        <table>
            <tr>
                <th>Booking ID</th>
                <th>User ID</th>
                <th>Spare ID</th>
                <th>Quantity</th>
                <th>Address</th>
                <th>City</th>
                <th>Pincode</th>
                <th>Status</th>
                <th>Update Status</th>
                <th>Location</th>
                <th>Update Location</th>
            </tr>

            <?php while ($order = mysqli_fetch_assoc($result)) { ?>
                <tr id="row_<?= $order['booking_id'] ?>">
                    <td><?= $order['booking_id'] ?></td>
                    <td><?= $order['user_id'] ?></td>
                    <td><?= $order['spare_id'] ?></td>
                    <td><?= $order['quantity'] ?></td>
                    <td><?= htmlspecialchars($order['delivery_address']) ?></td>
                    <td><?= htmlspecialchars($order['delivery_city']) ?></td>
                    <td><?= htmlspecialchars($order['delivery_pincode']) ?></td>

                    <!-- Current status -->
                    <td class="status-cell"><?= htmlspecialchars($order['delivery_status'] ?? 'Pending') ?></td>

                    <!-- Update status -->
                    <td>
                        <select class="status-select" data-id="<?= $order['booking_id'] ?>">
                            <option value="">Select</option>
                            <option value="Picked Up">Picked Up</option>
                            <option value="In Transit">In Transit</option>
                            <option value="Out for Delivery">Out for Delivery</option>
                            <option value="Delivered">Delivered</option>
                            <option value="Failed Attempt">Failed Attempt</option>
                        </select>
                    </td>

                    <!-- Current location -->
                    <td class="location-cell"><?= htmlspecialchars($order['current_location'] ?? '-') ?></td>

                    <!-- Update location -->
                    <td>
                        <input type="text" class="location-input" 
                               data-id="<?= $order['booking_id'] ?>" 
                               placeholder="Enter location">
                        <button class="update-location-btn" data-id="<?= $order['booking_id'] ?>">Save</button>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </section>
</div>

<script src="../assets/js/dashboard.js"></script>
</body>
</html>
