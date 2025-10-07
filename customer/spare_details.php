<?php
session_start();
include("../includes/db_connect.php");

// Validate spare_id
if (!isset($_GET['spare_id']) || empty($_GET['spare_id'])) {
    die("Invalid request.");
}
$spare_id = intval($_GET['spare_id']);
// Fetch spare details
$stmt = $con->prepare("
    SELECT s.spare_id, s.spare_name, s.price, s.description, s.stock, s.image_path, m.model_name
    FROM spares s
    JOIN models m ON s.model_id = m.model_id
    WHERE s.spare_id=?
");
$stmt->bind_param("i", $spare_id);
$stmt->execute();
$spare = $stmt->get_result()->fetch_assoc();

if (!$spare) {
    die("Spare not found.");
}

// Handle actions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_SESSION['user_id'])) {
        die("Please login first to continue.");
    }

    $quantity = intval($_POST['quantity']);
    $user_id = $_SESSION['user_id'];
    $action = $_POST['action'] ?? '';

    if ($quantity > 0 && $quantity <= $spare['stock']) {

        if ($action === "cart") {
            // Add to cart
            $stmt_cart = $con->prepare("INSERT INTO cart (user_id, spare_id, quantity) VALUES (?, ?, ?)
                                        ON DUPLICATE KEY UPDATE quantity = quantity + VALUES(quantity)");
            $stmt_cart->bind_param("iii", $user_id, $spare_id, $quantity);
            $stmt_cart->execute();
            $success = "Item added to your cart.";

        } elseif ($action === "buy") {
            // Direct buy
            $con->begin_transaction();
            try {
                $stmt2 = $con->prepare("INSERT INTO bookings (user_id, spare_id, quantity) VALUES (?, ?, ?)");
                $stmt2->bind_param("iii", $user_id, $spare_id, $quantity);
                $stmt2->execute();

                $stmt3 = $con->prepare("UPDATE spares SET stock = stock - ? WHERE spare_id = ?");
                $stmt3->bind_param("ii", $quantity, $spare_id);
                $stmt3->execute();

                $con->commit();

                $booking_id = $stmt2->insert_id;
                $amount = $spare['price'] * $quantity;

                header("Location: payment.php?booking_id=$booking_id&amount=$amount&spare=" . urlencode($spare['spare_name']));
                exit();

            } catch (Exception $e) {
                $con->rollback();
                $error = "Booking failed: " . $e->getMessage();
            }
        }
    } else {
        $error = "Invalid quantity. Available stock: " . $spare['stock'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?php echo htmlspecialchars($spare['spare_name']); ?> - Spare Details</title>
  <link rel="stylesheet" href="../assets/css/spareDetails.css">
</head>
<body>
  <div class="container">
    <img src="../<?php echo htmlspecialchars($spare['image_path']); ?>" alt="">
    
    <div class="details">
      <h2><?php echo htmlspecialchars($spare['spare_name']); ?></h2>
      <p><b>Model:</b> <?php echo htmlspecialchars($spare['model_name']); ?></p>
      <p><b>Price:</b> ₹<?php echo number_format($spare['price']); ?></p>
      <p><b>Stock:</b> <?php echo $spare['stock']; ?></p>
      <p><?php echo htmlspecialchars($spare['description']); ?></p>

      <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
      <?php if (!empty($success)) echo "<p class='success'>$success</p>"; ?>

      <form method="post">
        <label>Quantity:</label>
        <input type="number" name="quantity" min="1" max="<?php echo $spare['stock']; ?>" required>
        <br><br>
        <button type="submit" name="action" value="cart">Add to Cart</button>
        <button type="submit" name="action" value="buy">Buy Now</button>
      </form>
    </div>
  </div>
</body>
</html>
