<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// Database connection credentials
$host = 'localhost';
$dbname = 'signupDB';
$user = 'root';     // Default MySQL username
$pass = '';         // Default MySQL password (empty if using XAMPP/WAMP)

// Connect to MySQL database
$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["message" => "Database connection failed: " . $conn->connect_error]);
    exit();
}

// Get form data from JSON payload
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['name'], $data['phoneNumber'], $data['email'], $data['password'])) {
    $name = $conn->real_escape_string($data['name']);
    $phoneNumber = $conn->real_escape_string($data['phoneNumber']);
    $email = $conn->real_escape_string($data['email']);
    $password = password_hash($data['password'], PASSWORD_DEFAULT); // Hash the password for security

    $sql = "INSERT INTO users (name, phoneNumber, email, password) VALUES ('$name', '$phoneNumber', '$email', '$password')";

    if ($conn->query($sql) === TRUE) {
        http_response_code(201);
        echo json_encode(["message" => "User registered successfully"]);
    } else {
        http_response_code(500);
        echo json_encode(["message" => "Error: " . $conn->error]);
    }
} else {
    http_response_code(400);
    echo json_encode(["message" => "Invalid input"]);
}

$conn->close();
?>
