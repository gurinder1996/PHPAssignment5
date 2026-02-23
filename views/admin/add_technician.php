<?php
// add technician form
require __DIR__ . '/../../db/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    if ($firstName && $lastName && $email && $phone) {
        $sql = "INSERT INTO technicians (firstName, lastName, email, phone)
                VALUES (:firstName, :lastName, :email, :phone)";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':firstName' => $firstName,
            ':lastName' => $lastName,
            ':email' => $email,
            ':phone' => $phone
        ]);

        header("Location: manage_technicians.php");
        exit;
    } else {
        $error = "Please fill in all fields.";
        include 'error.php';
        exit;
    }
}
include __DIR__ . '/../header.php';

?>
<div class="card shadow">
    <div class="card-header bg-primary text-white">
        <h4>Add Technician</h4>
    </div>

    <div class="card-body">
        <form method="post">

            <div class="mb-3">
                <label class="form-label">First Name</label>
                <input type="text" name="firstName" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Last Name</label>
                <input type="text" name="lastName" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Phone</label>
                <input type="text" name="phone" class="form-control" required>
            </div>

            <button class="btn btn-success">Add Technician</button>
            <a href="manage_technicians.php" class="btn btn-secondary">View Technician List</a>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../footer.php'; ?>
