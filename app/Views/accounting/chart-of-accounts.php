<?php include APP_PATH . '/Views/layouts/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h2>Chart of Accounts</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?php echo BASE_URL; ?>accounting" class="btn btn-secondary">
            <i class="cil-arrow-left"></i> Back
        </a>
        <button class="btn btn-primary">
            <i class="cil-plus"></i> New Account
        </button>
    </div>
</div>

<div class="card">
    <div class="card-header"><strong>All Accounts</strong></div>
    <div class="card-body">
        <p class="text-muted">Chart of Accounts management - Coming soon</p>
    </div>
</div>

<?php include APP_PATH . '/Views/layouts/footer.php'; ?>
