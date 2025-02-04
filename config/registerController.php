<?php

// Database configuration
$host = "localhost";
$dbname = "cthalfmarathon";
$username = "Marathon2025NEW";
$password = "]qCG}}-N{Emu";

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

    // Insert into database
    try {
        $sql = "INSERT INTO marathon2025 (full_name, email, contact, address, gender, dob) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->execute([$fullName, $email, $contact, $address, $gender, $dob]);

        echo "Registration successful!<br>";

        // Fetch the last inserted data
        $lastId = $db->lastInsertId();
        $stmt = $db->prepare("SELECT * FROM marathon2025 WHERE id = ?");
        $stmt->execute([$lastId]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            echo "Data successfully added to the database.";
        }
    } catch (PDOException $e) {
        die("Registration failed. Please try again.");
    }
}
?>
