<?php include APP_PATH . '/Views/layouts/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-12">
        <h2>Accounting & General Ledger</h2>
        <p class="text-muted">Manage accounts, journal entries and financial statements</p>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="cil-list" style="font-size: 3rem; color: #321fdb;"></i>
                <h5 class="mt-3">Chart of Accounts</h5>
                <p class="text-muted">Manage accounts</p>
                <a href="<?php echo BASE_URL; ?>accounting/chart-of-accounts" class="btn btn-primary">Manage</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="cil-book" style="font-size: 3rem; color: #3399ff;"></i>
                <h5 class="mt-3">Journal Entries</h5>
                <p class="text-muted">Record entries</p>
                <a href="<?php echo BASE_URL; ?>accounting/journal-entries" class="btn btn-primary">Manage</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="cil-balance-scale" style="font-size: 3rem; color: #2eb85c;"></i>
                <h5 class="mt-3">Trial Balance</h5>
                <p class="text-muted">View balance</p>
                <a href="<?php echo BASE_URL; ?>accounting/trial-balance" class="btn btn-primary">View</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="cil-chart" style="font-size: 3rem; color: #f9b115;"></i>
                <h5 class="mt-3">Financial Statements</h5>
                <p class="text-muted">View statements</p>
                <a href="<?php echo BASE_URL; ?>accounting/financial-statements" class="btn btn-primary">View</a>
            </div>
        </div>
    </div>
</div>

<?php include APP_PATH . '/Views/layouts/footer.php'; ?>
