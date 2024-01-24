<?php
// Database connection settings
$host = "localhost"; // Change this if your MySQL server is running on a different host
$user = "root"; // Your MySQL username
$password = ""; // Your MySQL password
$database = "jklc"; // Your database name

// Establish a connection to the database
$conn = new mysqli($host, $user, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user input from the registration form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // You should add validation and password hashing here for security

    // Insert the user into the database
    $sql = "INSERT INTO tlogin (username, password) VALUES ('$username', '$password')";

    if ($conn->query($sql) === TRUE) {
        echo "Registration successful. <a href='Tlogin.html'>Login here</a>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>
