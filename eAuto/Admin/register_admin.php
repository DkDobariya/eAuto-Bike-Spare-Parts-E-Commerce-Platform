<?php
$conn = new mysqli("localhost", "root", "", "eauto");

$email = "admin@eauto.com";
$password = password_hash("admin123", PASSWORD_DEFAULT);

$sql = "INSERT INTO admins (email, password) VALUES ('$email', '$password')";
if ($conn->query($sql)) {
    echo "Admin user inserted!";
} else {
    echo "Error: " . $conn->error;
}
?>
