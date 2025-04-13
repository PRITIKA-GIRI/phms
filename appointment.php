<?php
include('db.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'patient') {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $doctor_id = (int)$_POST['doctor_id'];
    $appointment_date = $_POST['appointment_date'];
    
    // Basic validation
    if (empty($doctor_id) || empty($appointment_date)) {
        $error = "All fields are required";
    } elseif (strtotime($appointment_date) < time()) {
        $error = "Appointment date cannot be in the past";
    } else {
        $stmt = $pdo->prepare("INSERT INTO appointments (patient_id, doctor_id, appointment_date) VALUES (?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $doctor_id, $appointment_date]);
        $success = "Appointment booked successfully!";
    }
}

// Get list of doctors
$doctors = $pdo->query("SELECT id, name FROM users WHERE role = 'doctor'")->fetchAll();
?>

<h2>Book Appointment</h2>

<?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
<?php if(isset($success)) echo "<p class='success'>$success</p>"; ?>

<form method="POST">
    <label>Select Doctor:</label>
    <select name="doctor_id" required>
        <?php foreach ($doctors as $doctor): ?>
            <option value="<?php echo $doctor['id']; ?>">Dr. <?php echo htmlspecialchars($doctor['name']); ?></option>
        <?php endforeach; ?>
    </select>
    
    <label>Appointment Date & Time:</label>
    <input type="datetime-local" name="appointment_date" required>
    
    <button type="submit">Book Appointment</button>
</form>

<?php include('footer.php'); ?>