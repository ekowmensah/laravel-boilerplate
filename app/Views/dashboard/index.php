<?php include APP_PATH . '/Views/layouts/header.php'; ?>

<div class="row">
    <div class="col-12">
        <h2 class="mb-4">Dashboard</h2>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fs-6 fw-semibold">Total Clients</div>
                        <div class="fs-4 fw-bold"><?php echo number_format($stats['total_clients']); ?></div>
                    </div>
                    <div class="fs-1">
                        <i class="cil-people"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-sm-6 col-lg-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fs-6 fw-semibold">Active Loans</div>
                        <div class="fs-4 fw-bold"><?php echo number_format($stats['total_loans']); ?></div>
                    </div>
                    <div class="fs-1">
                        <i class="cil-dollar"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-sm-6 col-lg-3">
        <div class="card text-white bg-info">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fs-6 fw-semibold">Active Savings</div>
                        <div class="fs-4 fw-bold"><?php echo number_format($stats['total_savings']); ?></div>
                    </div>
                    <div class="fs-1">
                        <i class="cil-bank"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-sm-6 col-lg-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fs-6 fw-semibold">Overdue Loans</div>
                        <div class="fs-4 fw-bold"><?php echo number_format($stats['overdue_loans']); ?></div>
                    </div>
                    <div class="fs-1">
                        <i class="cil-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Financial Summary -->
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <strong>Loan Portfolio</strong>
            </div>
            <div class="card-body">
                <h3 class="text-primary"><?php echo formatCurrency($stats['active_loan_amount']); ?></h3>
                <p class="text-muted mb-0">Total Outstanding Loans</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <strong>Savings Portfolio</strong>
            </div>
            <div class="card-body">
                <h3 class="text-success"><?php echo formatCurrency($stats['total_savings_balance']); ?></h3>
                <p class="text-muted mb-0">Total Savings Balance</p>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="row g-3">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong>Recent Loans</strong>
                <a href="<?php echo BASE_URL; ?>loans" class="btn btn-sm btn-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Client</th>
                                <th>Product</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($recentLoans)): ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No loans found</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($recentLoans as $loan): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($loan['first_name'] . ' ' . $loan['last_name']); ?></td>
                                        <td><?php echo htmlspecialchars($loan['product_name']); ?></td>
                                        <td><?php echo formatCurrency($loan['principal']); ?></td>
                                        <td><?php echo getStatusBadge($loan['status']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong>Recent Transactions</strong>
                <a href="<?php echo BASE_URL; ?>transactions" class="btn btn-sm btn-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Client</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($recentTransactions)): ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No transactions found</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($recentTransactions as $transaction): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($transaction['first_name'] . ' ' . $transaction['last_name']); ?></td>
                                        <td><?php echo htmlspecialchars($transaction['transaction_type']); ?></td>
                                        <td><?php echo formatCurrency($transaction['amount']); ?></td>
                                        <td><?php echo formatDate($transaction['created_at']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include APP_PATH . '/Views/layouts/footer.php'; ?>
