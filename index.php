<?php
include('db.php'); // Initialize database and session
include('header.php'); // Include common header

// Check if user is logged in
if (isset($_SESSION['user_id'])) {
    echo "<h2>Welcome to E-Governance Health System</h2>";
    echo "<p>You are logged in as: " . htmlspecialchars($_SESSION['role']) . "</p>";
    
    // Quick links based on role
    echo '<div class="quick-links">';
    if ($_SESSION['role'] == 'patient') {
        echo '<a href="appointment.php" class="button">Book Appointment</a>';
        echo '<a href="medical_records.php" class="button">View Medical Records</a>';
    } elseif ($_SESSION['role'] == 'doctor') {
        echo '<a href="dashboard.php" class="button">View Appointments</a>';
    }
    echo '</div>';
} else {
    // Show public welcome message
    echo "<h2>Welcome to E-Governance Health System</h2>";
    echo "<p>A simple platform for managing health services</p>";
    echo '<div class="auth-links">';
    echo '<a href="login.php" class="button">Login</a>';
    echo '<a href="register.php" class="button">Register</a>';
    echo '</div>';
}

include('footer.php'); // Include common footer
?>