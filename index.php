<?php
// index.php - landing page for sportspro tech support
// base url points to project root folder under htdocs
$base_url = '/SportsPro';
require __DIR__ . '/views/header.php';
?>

<!-- PAGE HEADER -->
<div class="text-center mb-5">
    <h1 class="fw-bold">SportsPro Technical Support</h1>
    <p class="lead text-muted">
        Product management and technical support system
    </p>
</div>

<!-- ADMINISTRATORS -->
<div class="card shadow mb-4">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0">Administrators</h4>
    </div>
    <div class="card-body">
        <div class="list-group list-group-flush">
            <a href="<?= $base_url ?>/views/admin/project_manager.php" class="list-group-item list-group-item-action">
                Manage Products
            </a>
            <a href="<?= $base_url ?>/views/admin/manage_technicians.php" class="list-group-item list-group-item-action">
                Manage Technicians
            </a>
            <a href="<?= $base_url ?>/views/admin/customer_search.php" class="list-group-item list-group-item-action">
                Manage Customers
            </a>
            <a href="<?= $base_url ?>/views/admin/get_customer.php" class="list-group-item list-group-item-action">
                Create Incident
            </a>
            <a href="<?= $base_url ?>/views/admin/assign_incident.php" class="list-group-item list-group-item-action">
                Assign Incident
            </a>
            <a href="<?= $base_url ?>/views/admin/display_incidents.php" class="list-group-item list-group-item-action">
                Display Incidents
            </a>
        </div>
    </div>
</div>

<!-- TECHNICIANS -->
<div class="card shadow mb-4">
    <div class="card-header bg-warning">
        <h4 class="mb-0">Technicians</h4>
    </div>
    <div class="card-body">
        <div class="list-group list-group-flush">
            <a href="<?= $base_url ?>/views/technicians/tech_login.php" class="list-group-item list-group-item-action">
                Update Incident
            </a>
        </div>
    </div>
</div>

<!-- CUSTOMERS -->
<div class="card shadow mb-5">
    <div class="card-header bg-success text-white">
        <h4 class="mb-0">Customers</h4>
    </div>
    <div class="card-body">
        <div class="list-group list-group-flush">
            <a href="<?= $base_url ?>/views/customers/customer_login.php" class="list-group-item list-group-item-action">
                Register Product
            </a>
        </div>
    </div>
</div>

<?php include __DIR__ . '/views/footer.php'; ?>
