<?php
// Database configuration
$host = 'localhost';
$dbname = 'cthalfmarathon';
$username = 'Marathon2025';
$password = 'MCg7J1pGiKl}';

// Create a new PDO instance
try {
    $db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullName = trim($_POST['fullName']);
    $email = trim($_POST['email']);
    $contact = trim($_POST['contact']);
    $address = trim($_POST['address']);
    $gender = trim($_POST['gender']);
    $dob = trim($_POST['dob']);

    // Validate input
    if (empty($fullName) || empty($email) || empty($contact) || empty($address) || empty($gender) || empty($dob)) {
        echo "All fields are required.";
        return;
    }

    // Prepare SQL statement
    $sql = "INSERT INTO marathon2025 (full_name, email, contact, address, gender, dob) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $db->prepare($sql);

    // Bind parameters and execute
    if ($stmt->execute([$fullName, $email, $contact, $address, $gender, $dob])) {
        echo "Registration successful!";
    } else {
        echo "Registration failed. Please try again.";
    }
}
?>