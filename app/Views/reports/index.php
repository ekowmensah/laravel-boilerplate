<?php include APP_PATH . '/Views/layouts/header.php'; ?>

<div class="row mb-3">
    <div class="col-12">
        <h2>Reports & Analytics</h2>
    </div>
</div>

<div class="row g-3">
    <!-- Loan Reports -->
    <div class="col-md-6 col-lg-4">
        <div class="card h-100">
            <div class="card-header bg-primary text-white">
                <strong><i class="cil-dollar"></i> Loan Reports</strong>
            </div>
            <div class="list-group list-group-flush">
                <a href="<?php echo BASE_URL; ?>reports/loan-portfolio" class="list-group-item list-group-item-action">
                    <i class="cil-chart-line"></i> Loan Portfolio Report
                </a>
                <a href="<?php echo BASE_URL; ?>reports/loan-disbursement" class="list-group-item list-group-item-action">
                    <i class="cil-money"></i> Loan Disbursement Report
                </a>
                <a href="<?php echo BASE_URL; ?>reports/loan-arrears" class="list-group-item list-group-item-action">
                    <i class="cil-warning"></i> Loan Arrears Report
                </a>
            </div>
        </div>
    </div>
    
    <!-- Savings Reports -->
    <div class="col-md-6 col-lg-4">
        <div class="card h-100">
            <div class="card-header bg-success text-white">
                <strong><i class="cil-bank"></i> Savings Reports</strong>
            </div>
            <div class="list-group list-group-flush">
                <a href="<?php echo BASE_URL; ?>reports/savings-balance" class="list-group-item list-group-item-action">
                    <i class="cil-chart-pie"></i> Savings Balance Report
                </a>
                <a href="<?php echo BASE_URL; ?>reports/savings-transactions" class="list-group-item list-group-item-action">
                    <i class="cil-list"></i> Savings Transactions
                </a>
            </div>
        </div>
    </div>
    
    <!-- Client Reports -->
    <div class="col-md-6 col-lg-4">
        <div class="card h-100">
            <div class="card-header bg-info text-white">
                <strong><i class="cil-people"></i> Client Reports</strong>
            </div>
            <div class="list-group list-group-flush">
                <a href="<?php echo BASE_URL; ?>reports/client-list" class="list-group-item list-group-item-action">
                    <i class="cil-list"></i> Client List Report
                </a>
                <a href="<?php echo BASE_URL; ?>reports/group-performance" class="list-group-item list-group-item-action">
                    <i class="cil-group"></i> Group Performance
                </a>
            </div>
        </div>
    </div>
</div>

<?php include APP_PATH . '/Views/layouts/footer.php'; ?>
