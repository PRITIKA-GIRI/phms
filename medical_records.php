<?php
include('db.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'patient') {
    header('Location: login.php');
    exit();
}

// Get medical records
$records = $pdo->prepare("SELECT m.*, u.name as doctor_name 
                         FROM medical_records m 
                         JOIN users u ON m.doctor_id = u.id 
                         WHERE m.patient_id = ? 
                         ORDER BY m.created_at DESC");
$records->execute([$_SESSION['user_id']]);
$records = $records->fetchAll();
?>

<h2>Your Medical Records</h2>

<?php if($records): ?>
    <div class="records">
        <?php foreach ($records as $record): ?>
            <div class="record">
                <h3>Dr. <?php echo htmlspecialchars($record['doctor_name']); ?></h3>
                <p><strong>Date:</strong> <?php echo date('M j, Y', strtotime($record['created_at'])); ?></p>
                <p><strong>Diagnosis:</strong> <?php echo nl2br(htmlspecialchars($record['diagnosis'])); ?></p>
                <p><strong>Treatment:</strong> <?php echo nl2br(htmlspecialchars($record['treatment'])); ?></p>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <p>No medical records found.</p>
<?php endif; ?>

<?php include('footer.php'); ?>