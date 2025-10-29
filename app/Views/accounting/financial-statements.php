<?php include APP_PATH . '/Views/layouts/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h2>Financial Statements</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?php echo BASE_URL; ?>accounting" class="btn btn-secondary">
            <i class="cil-arrow-left"></i> Back
        </a>
        <button class="btn btn-primary">
            <i class="cil-print"></i> Print
        </button>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><strong>Balance Sheet</strong></div>
            <div class="card-body">
                <p class="text-muted">Balance Sheet - Coming soon</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><strong>Income Statement</strong></div>
            <div class="card-body">
                <p class="text-muted">Income Statement - Coming soon</p>
            </div>
        </div>
    </div>
</div>

<?php include APP_PATH . '/Views/layouts/footer.php'; ?>
