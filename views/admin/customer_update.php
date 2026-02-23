<?php
// view and update customer
require __DIR__ . '/../../db/database.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header("Location: customer_search.php");
    exit;
}

// get the customer data
$stmt = $db->prepare("SELECT * FROM customers WHERE customerID = :id");
$stmt->execute([':id' => $id]);
$customer = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$customer) {
    header("Location: customer_search.php");
    exit;
}

// handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $postalCode = $_POST['postalCode'];
    $countryCode = $_POST['countryCode'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];

    $sql = "UPDATE customers SET
                firstName = :firstName,
                lastName = :lastName,
                address = :address,
                city = :city,
                state = :state,
                postalCode = :postalCode,
                countryCode = :countryCode,
                phone = :phone,
                email = :email
            WHERE customerID = :id";
    $stmt = $db->prepare($sql);
    $stmt->execute([
        ':firstName' => $firstName,
        ':lastName' => $lastName,
        ':address' => $address,
        ':city' => $city,
        ':state' => $state,
        ':postalCode' => $postalCode,
        ':countryCode' => $countryCode,
        ':phone' => $phone,
        ':email' => $email,
        ':id' => $id
    ]);

    header("Location: customer_search.php");
    exit;
}

include __DIR__ . '/../header.php';
?>

<h2 class="mb-3">View/Update Customer</h2>

<div class="card shadow">
    <div class="card-header bg-warning">
        <h4 class="mb-0">Customer Details</h4>
    </div>
    <div class="card-body">
        <form method="post">

            <div class="mb-3">
                <label class="form-label">First Name</label>
                <input type="text" name="firstName" class="form-control"
                       value="<?= htmlspecialchars($customer['firstName']) ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Last Name</label>
                <input type="text" name="lastName" class="form-control"
                       value="<?= htmlspecialchars($customer['lastName']) ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Address</label>
                <input type="text" name="address" class="form-control"
                       value="<?= htmlspecialchars($customer['address']) ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">City</label>
                <input type="text" name="city" class="form-control"
                       value="<?= htmlspecialchars($customer['city']) ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">State</label>
                <input type="text" name="state" class="form-control"
                       value="<?= htmlspecialchars($customer['state']) ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Postal Code</label>
                <input type="text" name="postalCode" class="form-control"
                       value="<?= htmlspecialchars($customer['postalCode']) ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Country Code</label>
                <input type="text" name="countryCode" class="form-control"
                       value="<?= htmlspecialchars($customer['countryCode']) ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Phone</label>
                <input type="text" name="phone" class="form-control"
                       value="<?= htmlspecialchars($customer['phone']) ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control"
                       value="<?= htmlspecialchars($customer['email']) ?>">
            </div>

            <button class="btn btn-success">Update Customer</button>
            <a href="customer_search.php" class="btn btn-secondary">Back</a>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../footer.php'; ?>
