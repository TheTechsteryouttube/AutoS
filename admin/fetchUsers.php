<html>
<link rel="stylesheet" href="../assets/css/fetchUser.css">
</html>
<?php
include '../includes/db_connect.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    echo "Unauthorized access";
    exit();
}

$sql = "SELECT user_id, name, email, password FROM users";
$result = mysqli_query($con, $sql);

if (mysqli_num_rows($result) > 0) {
    echo "<div class='table-container'>";
    echo "<table border='1' style='width: 100%; text-align: left; color: white;'>";
    echo "<tr><th>ID</th><th>Name</th><th>Email</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['user_id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    echo "</div>";
} else {
    echo "No users found.";
}
?>
