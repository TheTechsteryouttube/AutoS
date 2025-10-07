<?php
// --- 1. DATABASE CONNECTION ---
// Replace these with your actual database credentials.
$servername = "localhost";
$username = "root"; // Common default for localhost
$password = "";     // Common default for localhost
$dbname = "autostream_db"; // As seen in your screenshot

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// --- 2. FETCH DATA FROM DATABASE ---
$sql = "SELECT booking_id, user_id, spare_id, quantity, booking_date, payment_method, delivery_address, delivery_city, delivery_pincode, assigned_partner_id, delivery_status, current_location FROM bookings ORDER BY booking_date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - All Bookings</title>
    <style>
        /* --- 3. CSS STYLING (DARK & RED THEME) --- */
        body {
            background-color: #121212; /* Very dark grey background */
            color: #e0e0e0;           /* Light grey text for readability */
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        h1 {
            color: #e53935; /* Bright red for the main title */
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 2px;
            border-bottom: 2px solid #e53935;
            padding-bottom: 10px;
            margin-bottom: 30px;
        }

        .table-container {
            overflow-x: auto; /* Allows horizontal scrolling on small screens */
        }

        table {
            width: 100%;
            border-collapse: collapse; /* Clean, modern table lines */
            background-color: #1e1e1e; /* Slightly lighter dark for the table */
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
            border-radius: 8px;
            overflow: hidden; /* Ensures border-radius is applied to corners */
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #333; /* Subtle separator lines */
        }

        /* Table Header Styling */
        thead {
            background-color: #c0392b; /* A deep, solid red for the header */
            color: #ffffff; /* White text for contrast */
            text-transform: uppercase;
            font-size: 0.85em;
            letter-spacing: 1px;
        }

        /* Table Body Styling */
        tbody tr:hover {
            background-color: #2c3e50; /* A dark blue-grey for hover effect */
        }
        
        /* Message for when no bookings are found */
        .no-bookings {
            text-align: center;
            font-size: 1.2em;
            color: #777;
            padding: 50px;
        }

        /* Styling for NULL or empty values to make them noticeable */
        td:empty::after,
        .null-value {
            content: "NULL";
            color: #777;
            font-style: italic;
        }
    </style>
</head>
<body>

    <h1>Delivery Bookings</h1>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>User ID</th>
                    <th>Spare ID</th>
                    <th>Qty</th>
                    <th>Booking Date</th>
                    <th>Payment</th>
                    <th>Address</th>
                    <th>City</th>
                    <th>Pincode</th>
                    <th>Partner ID</th>
                    <th>Status</th>
                    <th>Location</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // --- 4. DISPLAY DATA IN HTML TABLE ---
                if ($result && $result->num_rows > 0) {
                    // Output data of each row
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row["booking_id"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["user_id"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["spare_id"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["quantity"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["booking_date"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["payment_method"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["delivery_address"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["delivery_city"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["delivery_pincode"]) . "</td>";
                        // Special handling for NULL values to apply specific styling
                        if (is_null($row["assigned_partner_id"])) {
                            echo "<td class='null-value'></td>";
                        } else {
                            echo "<td>" . htmlspecialchars($row["assigned_partner_id"]) . "</td>";
                        }
                        echo "<td>" . htmlspecialchars($row["delivery_status"]) . "</td>";
                        if (is_null($row["current_location"])) {
                            echo "<td class='null-value'></td>";
                        } else {
                            echo "<td>" . htmlspecialchars($row["current_location"]) . "</td>";
                        }
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='12' class='no-bookings'>No bookings found</td></tr>";
                }
                // --- 5. CLOSE CONNECTION ---
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>

</body>
</html>