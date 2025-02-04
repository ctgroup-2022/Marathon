<?php
require 'vendor/autoload.php';

use Dotenv\Dotenv;

// Load environment variables from .env file
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Database configuration
$host = $_ENV['DB_HOST'];
$dbname = $_ENV['DB_NAME'];
$username = $_ENV['DB_USERNAME'];
$password = $_ENV['DB_PASSWORD'];

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
        appendToGoogleSheet([$fullName, $email, $contact, $address, $gender, $dob]);
    } else {
        echo "Registration failed. Please try again.";
    }
}

function appendToGoogleSheet($data) {
    $client = new Google_Client();
    $client->setApplicationName('Half Marathon Registration');
    $client->setScopes(Google_Service_Sheets::SPREADSHEETS);
    $client->setClientId($_ENV['GOOGLE_CLIENT_ID']);
    $client->setClientSecret($_ENV['GOOGLE_CLIENT_SECRET']);
    $client->setDeveloperKey($_ENV['GOOGLE_API_KEY']);
    $client->setAccessType('offline');

    $service = new Google_Service_Sheets($client);
    $spreadsheetId = $_ENV['GOOGLE_SPREADSHEET_ID'];
    $range = 'Sheet1!A1:F1'; // Replace with your sheet name and range

    $values = [$data];
    $body = new Google_Service_Sheets_ValueRange([
        'values' => $values
    ]);
    $params = [
        'valueInputOption' => 'RAW'
    ];

    $service->spreadsheets_values->append($spreadsheetId, $range, $body, $params);
}
?>