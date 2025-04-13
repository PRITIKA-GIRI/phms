<?php
include('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = 'patient'; // Only allow patient registration by default

    // Basic validation
    if (empty($name) || empty($email) || empty($password)) {
        $error = "All fields are required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format";
    } else {
        // Check if email exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->rowCount() > 0) {
            $error = "Email already registered";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $email, $hashed_password, $role]);
            
            $_SESSION['success'] = "Registration successful. Please login.";
            header("Location: login.php");
            exit();
        }
    }
}
?>

<h2>Register</h2>
<?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>

<form method="POST">
    <label>Full Name:</label>
    <input type="text" name="name" required>
    
    <label>Email:</label>
    <input type="email" name="email" required>
    
    <label>Password (min 6 characters):</label>
    <input type="password" name="password" minlength="6" required>
    
    <button type="submit">Register</button>
</form>

<p>Already have an account? <a href="login.php">Login here</a></p>

<?php include('footer.php'); ?>