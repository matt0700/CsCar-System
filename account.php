<?php
session_start();
// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
// Include database connection
include "connection.php";

// Sanitize username input
$username = mysqli_real_escape_string($connect, $_SESSION['username']);

// Query to retrieve user information
$sql = "SELECT ui.Ln, ui.Fn, ui.Mn
        FROM users u
        JOIN information ui ON u.user_id = ui.user_id
        WHERE u.username = '$username'";

$result = mysqli_query($connect, $sql);

if (!$result) {
    // Handle query error
    die("Query failed: " . mysqli_error($connect));
}

// Check if user information exists
if (mysqli_num_rows($result) > 0) {
    // Fetch user information
    $row = mysqli_fetch_assoc($result);
    $last_name = $row['Ln'];
    $first_name = $row['Fn'];
    $middle_name = $row['Mn']; 

    $full_name = $first_name . ' ' . $middle_name . ' ' . $last_name;
    
} else {
    // Handle case where user information is not found
    die("User information not found.");
}

mysqli_close($connect); // Close connection after use
?>


<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-2xl font-semibold mb-4">Welcome, <?php echo htmlspecialchars($full_name); ?>!</h2>
        <p class="text-lg">This is your account page.</p>
        <p class="mt-4"><a href="logout.php" class="text-blue-500 hover:underline">Logout</a></p>
    </div>
</div>

<!-- Add your JavaScript or additional HTML content here -->


</html>
