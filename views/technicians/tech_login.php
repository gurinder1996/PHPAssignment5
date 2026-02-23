<?php
// technician login
session_start();
require __DIR__ . '/../../db/database.php';

// if already logged in skip to incident list
if (isset($_SESSION['tech'])) {
    header("Location: incident_list.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    if ($email) {
        $stmt = $db->prepare("SELECT * FROM technicians WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $tech = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($tech) {
            // save tech in session
            $_SESSION['tech'] = $tech;
            header("Location: incident_list.php");
            exit;
        } else {
            $error = "No technician found with that email.";
        }
    }
}
include __DIR__ . '/../header.php';
?>

<h2 class="mb-3">Technician Login</h2>

<div class="card shadow">
    <div class="card-header bg-warning">
        <h4>Login</h4>
    </div>
    <div class="card-body">

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <button class="btn btn-primary">Login</button>
            <a href="/SportsPro/index.php" class="btn btn-secondary">Home</a>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../footer.php'; ?>
