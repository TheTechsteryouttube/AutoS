<?php
header("Content-Type: application/json");

// Dump exactly what we received
echo json_encode([
    "POST" => $_POST,
    "FILES" => $_FILES,
], JSON_PRETTY_PRINT);
?>