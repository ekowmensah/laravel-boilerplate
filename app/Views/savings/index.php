<?php include APP_PATH . '/Views/layouts/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h2>Savings Accounts</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?php echo BASE_URL; ?>savings/create" class="btn btn-primary">
            <i class="cil-plus"></i> New Savings Account
        </a>
    </div>
</div>

<!-- Summary Cards -->
<div class="row mb-3">
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h6>Total Accounts</h6>
                <h3><?php echo count($savings); ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h6>Active Accounts</h6>
                <h3><?php echo count(array_filter($savings, fn($s) => $s['status'] === 'active')); ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h6>Total Balance</h6>
                <h3><?php echo formatCurrency(array_sum(array_column($savings, 'balance_derived'))); ?></h3>
            </div>
        </div>
    </div>
</div>

<!-- Savings Table -->
<div class="card">
    <div class="card-header">
        <strong>All Savings Accounts</strong>
    </div>
    <div class="card-body">
        <table class="table table-striped datatable" id="savingsTable">
            <thead>
                <tr>
                    <th>Account Number</th>
                    <th>Client</th>
                    <th>Product</th>
                    <th>Branch</th>
                    <th>Balance</th>
                    <th>Status</th>
                    <th>Activated</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($savings)): ?>
                    <?php foreach ($savings as $account): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($account['account_number'] ?? 'N/A'); ?></strong></td>
                        <td><?php echo htmlspecialchars(($account['first_name'] ?? '') . ' ' . ($account['last_name'] ?? '')); ?></td>
                        <td><?php echo htmlspecialchars($account['product_name'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($account['branch_name'] ?? 'N/A'); ?></td>
                        <td><strong><?php echo formatCurrency($account['balance_derived'] ?? 0); ?></strong></td>
                        <td><?php echo getStatusBadge($account['status'] ?? 'pending'); ?></td>
                        <td><?php echo isset($account['activated_on_date']) && $account['activated_on_date'] ? date('M d, Y', strtotime($account['activated_on_date'])) : 'N/A'; ?></td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="<?php echo BASE_URL; ?>savings/viewSavings/<?php echo $account['id']; ?>" class="btn btn-info" title="View">
                                    <i class="cil-eye"></i>
                                </a>
                                <?php if ($account['status'] === 'active'): ?>
                                <a href="<?php echo BASE_URL; ?>savings/deposit/<?php echo $account['id']; ?>" class="btn btn-success" title="Deposit">
                                    <i class="cil-arrow-bottom"></i>
                                </a>
                                <a href="<?php echo BASE_URL; ?>savings/withdraw/<?php echo $account['id']; ?>" class="btn btn-warning" title="Withdraw">
                                    <i class="cil-arrow-top"></i>
                                </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center">
                            <p class="my-3">No savings accounts found</p>
                            <a href="<?php echo BASE_URL; ?>savings/create" class="btn btn-primary">
                                <i class="cil-plus"></i> Create First Account
                            </a>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include APP_PATH . '/Views/layouts/footer.php'; ?>
