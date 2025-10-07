<?php
session_start();
include("../includes/db_connect.php");

// Check user login
if (!isset($_SESSION['user_id'])) {
    die("Please login to proceed with payment.");
}

$user_id = $_SESSION['user_id'];

// Validate required GET parameters
if (!isset($_GET['booking_id'], $_GET['amount'], $_GET['spare'])) {
    die("Invalid payment request.");
}

$booking_id = intval($_GET['booking_id']);
$amount = floatval($_GET['amount']);
$spare_name = htmlspecialchars($_GET['spare']);
$error = "";

// Verify booking belongs to this user
$stmt = $con->prepare("SELECT * FROM bookings WHERE booking_id = ? AND user_id = ?");
$stmt->bind_param("ii", $booking_id, $user_id);
$stmt->execute();
$booking = $stmt->get_result()->fetch_assoc();

if (!$booking) {
    die("Booking not found or access denied.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (
        empty($_POST['payment_method']) ||
        empty($_POST['address']) ||
        empty($_POST['city']) ||
        empty($_POST['pincode'])
    ) {
        $error = "Please fill in all payment and address details.";
    } else {
        $payment_method = $_POST['payment_method'];
        $address = $_POST['address'];
        $city = $_POST['city'];
        $pincode = $_POST['pincode'];

        // Begin transaction
        $con->begin_transaction();

        try {
            // Step 1: Insert payment record
            $stmt_payment = $con->prepare("
                INSERT INTO payments (booking_id, amount, payment_method, payment_status) 
                VALUES (?, ?, ?, 'completed')
            ");
            $stmt_payment->bind_param("ids", $booking_id, $amount, $payment_method);
            if (!$stmt_payment->execute()) {
                throw new Exception("Payment could not be recorded.");
            }

            // Step 2: Update booking with address and payment method
            $stmt_update = $con->prepare("
                UPDATE bookings 
                SET delivery_address = ?, delivery_city = ?, delivery_pincode = ?, payment_method = ? 
                WHERE booking_id = ?
            ");
            $stmt_update->bind_param("ssssi", $address, $city, $pincode, $payment_method, $booking_id);
            if (!$stmt_update->execute()) {
                throw new Exception("Could not update booking details.");
            }

            // Step 3: Assign available delivery partner
            $partner_id = null;
            $partner_result = $con->query("
                SELECT id 
                FROM delivery_partner 
                WHERE status = 'active' 
                ORDER BY RAND() 
                LIMIT 1
            ");

            if ($partner_result && $partner_result->num_rows > 0) {
                $partner = $partner_result->fetch_assoc();
                $partner_id = $partner['id'];

                // Assign partner to booking
                $stmt_assign = $con->prepare("
                    UPDATE bookings 
                    SET assigned_partner_id = ?,
                        delivery_status = 'Out for Delivery'
                    WHERE booking_id = ?
                ");
                $stmt_assign->bind_param("ii", $partner_id, $booking_id);
                if (!$stmt_assign->execute()) {
                    throw new Exception("Could not assign delivery partner.");
                }

                // Step 3.5: Check number of active deliveries for this partner
                $max_orders = 10; // Change this number as needed
                $stmt_count = $con->prepare("
                    SELECT COUNT(*) AS active_orders 
                    FROM bookings 
                    WHERE assigned_partner_id = ? 
                    AND delivery_status IN ('Pending' , 'Out for Delivery')
                ");
                $stmt_count->bind_param("i", $partner_id);
                $stmt_count->execute();
                $result = $stmt_count->get_result()->fetch_assoc();
                $active_orders = $result['active_orders'] ?? 0;

                // If max active deliveries reached, mark partner as busy
                if ($active_orders >= $max_orders) {
                    $stmt_busy = $con->prepare("
                        UPDATE delivery_partner 
                        SET status = 'busy' 
                        WHERE id = ?
                    ");
                    $stmt_busy->bind_param("i", $partner_id);
                    $stmt_busy->execute();
                }
            }

            // Step 4: Commit transaction
            $con->commit();

            // Step 5: Redirect on success
            header("Location: myorders.php?success=1");
            exit;

        } catch (Exception $e) {
            $con->rollback();
            $error = "An error occurred: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment for <?php echo $spare_name; ?></title>
    <link rel="stylesheet" href="../assets/css/payment.css">
</head>
<body>
<div class="container">
    <h2>Payment for <?php echo $spare_name; ?></h2>
    <p><strong>Booking ID:</strong> <?php echo $booking_id; ?></p>
    <p><strong>Amount to Pay:</strong> ₹<?php echo number_format($amount, 2); ?></p>

    <?php if ($error): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="POST">
        <fieldset>
            <legend>Delivery Address</legend>
            <label for="address">Street Address:</label>
            <input type="text" id="address" name="address" required>

            <label for="city">City:</label>
            <input type="text" id="city" name="city" required>

            <label for="pincode">PIN Code:</label>
            <input type="text" id="pincode" name="pincode" required pattern="\d{6}">
        </fieldset>

        <fieldset>
            <legend>Payment Method</legend>
            <label for="payment_method">Select Payment Method:</label>
            <select name="payment_method" id="payment_method" required>
                <option value="">-- Choose --</option>
                <option value="upi">UPI</option>
                <option value="card">Credit/Debit Card</option>
                <option value="netbanking">Net Banking</option>
                <option value="wallet">Wallet</option>
                <option value="cod">Cash on Delivery</option>
            </select>
        </fieldset>

        <button type="submit">Confirm Payment & Place Order</button>
    </form>
</div>
</body>
</html>
