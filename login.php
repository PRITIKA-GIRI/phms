<?php
include('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['name'] = $user['name'];
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid email or password";
    }
}
?>

<h2>Login</h2>
<?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>

<form method="POST">
    <label>Email:</label>
    <input type="email" name="email" required>
    
    <label>Password:</label>
    <input type="password" name="password" required>
    
    <button type="submit">Login</button>
</form>

<p>Don't have an account? <a href="register.php">Register here</a></p>

<?php include('footer.php'); ?>