<?php
include('db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$role = $_SESSION['role'];
?>

<h2>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?></h2>

<div class="dashboard">
    <?php if($role == 'patient'): ?>
        <div class="card">
            <h3>Quick Actions</h3>
            <a href="appointment.php" class="button">Book Appointment</a>
            <a href="medical_records.php" class="button">View Medical Records</a>
        </div>
        
        <!-- Upcoming Appointments -->
        <div class="card">
            <h3>Your Appointments</h3>
            <?php
            $stmt = $pdo->prepare("SELECT a.*, u.name as doctor_name 
                                  FROM appointments a 
                                  JOIN users u ON a.doctor_id = u.id 
                                  WHERE a.patient_id = ? AND a.status = 'confirmed'
                                  ORDER BY a.appointment_date ASC");
            $stmt->execute([$_SESSION['user_id']]);
            $appointments = $stmt->fetchAll();
            
            if ($appointments) {
                echo "<ul>";
                foreach ($appointments as $app) {
                    echo "<li>Dr. ".htmlspecialchars($app['doctor_name'])." - ".
                         date('M j, Y g:i A', strtotime($app['appointment_date']))."</li>";
                }
                echo "</ul>";
            } else {
                echo "<p>No upcoming appointments</p>";
            }
            ?>
        </div>
        
    <?php elseif($role == 'doctor'): ?>
        <div class="card">
            <h3>Doctor Dashboard</h3>
            <p>Today's Appointments: 5</p>
            <p>Pending Approvals: 2</p>
        </div>
        
    <?php else: ?>
        <div class="card">
            <h3>Admin Dashboard</h3>
            <p>Total Users: 25</p>
            <p>Total Appointments: 120</p>
        </div>
    <?php endif; ?>
</div>

<?php include('footer.php'); ?>