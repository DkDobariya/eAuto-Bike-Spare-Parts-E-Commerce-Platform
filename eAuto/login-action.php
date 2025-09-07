<?php
session_start();

// Connect to MySQL
$conn = new mysqli("localhost", "root", "", "eauto");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Prepare the SQL statement
    $stmt = $conn->prepare("SELECT id, fullname, email, password FROM users WHERE email = ?");
    
    // Check if prepare() failed
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    // Bind and execute
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user found
    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verify hashed password
        if (password_verify($password, $user["password"])) {
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["fullname"] = $user["fullname"];
            header("Location: my-profile.php");
            exit();
        } else {
            echo "<script>alert('Invalid password.'); window.location.href='login.php';</script>";
        }
    } else {
        echo "<script>alert('User not found.'); window.location.href='login.php';</script>";
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
?>
