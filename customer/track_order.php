<?php
session_start();
$currentPage="";
include("header.php");
include("../includes/db_connect.php");

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Please login to view your orders.");
}

$user_id = $_SESSION['user_id'];

// Fetch order and delivery info directly from bookings
$query = "
    SELECT 
        b.booking_id,
        s.spare_name,
        b.booking_date,
        b.delivery_address,
        b.delivery_city,
        b.delivery_pincode,
        b.current_location,
        b.delivery_status,
        b.assigned_partner_id,
        d.name AS partner_name,
        d.phone AS partner_phone
    FROM bookings b
    JOIN spares s ON b.spare_id = s.spare_id
    LEFT JOIN delivery_partner d ON b.assigned_partner_id = d.id
    WHERE b.user_id = ?
    ORDER BY b.booking_date DESC
";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Track My Orders | AutoStream</title>
    <link rel="stylesheet" href="../assets/css/track_orders.css">
</head>
<body>
<div class="track-container">
    <h2>📦 Track Your Orders</h2>

    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Spare</th>
                    <th>Partner</th>
                    <th>Current Location</th>
                    <th>Status</th>
                    <th>Address</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td>#<?php echo $row['booking_id']; ?></td>
                        <td><?php echo htmlspecialchars($row['spare_name']); ?></td>
                        <td>
                            <?php 
                                if ($row['partner_name']) {
                                    echo $row['partner_name'] . "<br><small>" . $row['partner_phone'] . "</small>";
                                } else {
                                    echo "<em>Not assigned</em>";
                                }
                            ?>
                        </td>
                        <td><?php echo $row['current_location'] ?: "<em>Awaiting update</em>"; ?></td>
                        <td><?php echo $row['delivery_status'] ?: "<em>Pending</em>"; ?></td>
                        <td>
                            <?php echo htmlspecialchars($row['delivery_address'] . ', ' . $row['delivery_city'] . ' - ' . $row['delivery_pincode']); ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>You have no active orders.</p>
    <?php endif; ?>
</div>
</body>
</html>
