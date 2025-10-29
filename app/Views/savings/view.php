<?php include APP_PATH . '/Views/layouts/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h2>Savings Account Details</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?php echo BASE_URL; ?>savings" class="btn btn-secondary">
            <i class="cil-arrow-left"></i> Back to Savings
        </a>
        <?php if ($savings['status'] === 'pending'): ?>
            <a href="<?php echo BASE_URL; ?>savings/approve/<?php echo $savings['id']; ?>" class="btn btn-success">
                <i class="cil-check"></i> Approve
            </a>
        <?php elseif ($savings['status'] === 'approved'): ?>
            <a href="<?php echo BASE_URL; ?>savings/activate/<?php echo $savings['id']; ?>" class="btn btn-primary">
                <i class="cil-check-circle"></i> Activate
            </a>
        <?php elseif ($savings['status'] === 'active'): ?>
            <a href="<?php echo BASE_URL; ?>savings/deposit/<?php echo $savings['id']; ?>" class="btn btn-success">
                <i class="cil-plus"></i> Deposit
            </a>
            <a href="<?php echo BASE_URL; ?>savings/withdraw/<?php echo $savings['id']; ?>" class="btn btn-warning">
                <i class="cil-minus"></i> Withdraw
            </a>
        <?php endif; ?>
    </div>
</div>

<div class="row">
    <!-- Account Information -->
    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-header bg-primary text-white">
                <strong>Account Information</strong>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="30%">Account Number:</th>
                        <td><strong><?php echo htmlspecialchars($savings['account_number'] ?? 'N/A'); ?></strong></td>
                    </tr>
                    <tr>
                        <th>Client:</th>
                        <td>
                            <a href="<?php echo BASE_URL; ?>clients/viewClient/<?php echo $savings['client_id']; ?>">
                                <?php echo htmlspecialchars(($savings['first_name'] ?? '') . ' ' . ($savings['last_name'] ?? '')); ?>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <th>Product:</th>
                        <td><?php echo htmlspecialchars($savings['product_name'] ?? 'N/A'); ?></td>
                    </tr>
                    <tr>
                        <th>Status:</th>
                        <td><?php echo getStatusBadge($savings['status'] ?? 'pending'); ?></td>
                    </tr>
                    <tr>
                        <th>Balance:</th>
                        <td><strong class="text-success"><?php echo formatCurrency($savings['balance_derived'] ?? 0); ?></strong></td>
                    </tr>
                    <tr>
                        <th>Interest Rate:</th>
                        <td><?php echo ($savings['interest_rate'] ?? 0) . '%'; ?></td>
                    </tr>
                    <tr>
                        <th>Opened Date:</th>
                        <td><?php echo isset($savings['submittedon_date']) ? date('M d, Y', strtotime($savings['submittedon_date'])) : 'N/A'; ?></td>
                    </tr>
                    <?php if (isset($savings['approved_date'])): ?>
                    <tr>
                        <th>Approved Date:</th>
                        <td><?php echo date('M d, Y', strtotime($savings['approved_date'])); ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if (isset($savings['activated_date'])): ?>
                    <tr>
                        <th>Activated Date:</th>
                        <td><?php echo date('M d, Y', strtotime($savings['activated_date'])); ?></td>
                    </tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="card">
            <div class="card-header">
                <strong>Recent Transactions</strong>
            </div>
            <div class="card-body">
                <?php if (!empty($transactions)): ?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($transactions as $transaction): ?>
                            <tr>
                                <td><?php echo isset($transaction['transaction_date']) ? date('M d, Y', strtotime($transaction['transaction_date'])) : 'N/A'; ?></td>
                                <td>
                                    <?php if ($transaction['transaction_type'] === 'deposit'): ?>
                                        <span class="badge bg-success">Deposit</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning">Withdrawal</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo formatCurrency($transaction['amount'] ?? 0); ?></td>
                                <td><?php echo formatCurrency($transaction['balance_derived'] ?? 0); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="text-muted">No transactions yet</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Summary -->
    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header">
                <strong>Account Summary</strong>
            </div>
            <div class="card-body">
                <p class="mb-2"><strong>Total Deposits:</strong><br><?php echo formatCurrency($savings['total_deposits_derived'] ?? 0); ?></p>
                <p class="mb-2"><strong>Total Withdrawals:</strong><br><?php echo formatCurrency($savings['total_withdrawals_derived'] ?? 0); ?></p>
                <p class="mb-2"><strong>Interest Earned:</strong><br><?php echo formatCurrency($savings['total_interest_earned_derived'] ?? 0); ?></p>
                <p class="mb-0"><strong>Current Balance:</strong><br><span class="text-success h4"><?php echo formatCurrency($savings['balance_derived'] ?? 0); ?></span></p>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <strong>Quick Actions</strong>
            </div>
            <div class="card-body">
                <?php if ($savings['status'] === 'active'): ?>
                    <a href="<?php echo BASE_URL; ?>savings/deposit/<?php echo $savings['id']; ?>" class="btn btn-success btn-block mb-2 w-100">
                        <i class="cil-plus"></i> Make Deposit
                    </a>
                    <a href="<?php echo BASE_URL; ?>savings/withdraw/<?php echo $savings['id']; ?>" class="btn btn-warning btn-block mb-2 w-100">
                        <i class="cil-minus"></i> Make Withdrawal
                    </a>
                <?php endif; ?>
                <a href="<?php echo BASE_URL; ?>savings-files/index/<?php echo $savings['id']; ?>" class="btn btn-info btn-block w-100">
                    <i class="cil-file"></i> Documents
                </a>
            </div>
        </div>
    </div>
</div>

<?php include APP_PATH . '/Views/layouts/footer.php'; ?>
