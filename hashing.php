<?php
// Include your database connection file
require 'connection.php';

// Function to hash password
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// Function to insert hashed special password into database
function insertSpecialPassword($username, $password) {
    global $connect; // Assuming $connect is your database connection object

    // Hash the password
    $hashed_password = hashPassword($password);

    // Prepare the SQL statement
    $stmt = $connect->prepare("UPDATE drivers SET special_password = ? WHERE username = ?");
    $stmt->bind_param('ss', $hashed_password, $username);

    // Execute the statement
    if ($stmt->execute()) {
        return true; // Special password inserted successfully
    } else {
        return false; // Insertion failed
    }
}

// Example usage
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve username and password from POST data (make sure to validate/sanitize input)
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Insert special password into database
    if (insertSpecialPassword($username, $password)) {
        echo "Special password inserted successfully!";
    } else {
        echo "Failed to insert special password.";
    }
}
?>
