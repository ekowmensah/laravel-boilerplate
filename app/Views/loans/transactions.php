<?php include APP_PATH . '/Views/layouts/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h2>Loan Transaction History</h2>
        <p class="text-muted">Loan: <?php echo htmlspecialchars($loan['account_number'] ?? ''); ?></p>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?php echo BASE_URL; ?>loans/view/<?php echo $loan['id']; ?>" class="btn btn-secondary">
            <i class="cil-arrow-left"></i> Back to Loan
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header"><strong>All Transactions</strong></div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped datatable">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Principal</th>
                        <th>Interest</th>
                        <th>Fees</th>
                        <th>Penalties</th>
                        <th>Balance</th>
                        <th>User</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($transactions)): ?>
                        <?php foreach ($transactions as $txn): ?>
                        <tr>
                            <td><?php echo !empty($txn['submitted_on']) ? date('M d, Y', strtotime($txn['submitted_on'])) : 'N/A'; ?></td>
                            <td>
                                <?php 
                                $typeName = htmlspecialchars($txn['transaction_type_name'] ?? 'N/A');
                                $badgeClass = 'bg-secondary';
                                if (stripos($typeName, 'repayment') !== false) {
                                    $badgeClass = 'bg-success';
                                } elseif (stripos($typeName, 'disbursement') !== false) {
                                    $badgeClass = 'bg-primary';
                                } elseif (stripos($typeName, 'charge') !== false) {
                                    $badgeClass = 'bg-warning';
                                }
                                ?>
                                <span class="badge <?php echo $badgeClass; ?>"><?php echo $typeName; ?></span>
                            </td>
                            <td><strong><?php echo formatCurrency($txn['amount'] ?? 0); ?></strong></td>
                            <td><?php echo formatCurrency($txn['principal_repaid_derived'] ?? 0); ?></td>
                            <td><?php echo formatCurrency($txn['interest_repaid_derived'] ?? 0); ?></td>
                            <td><?php echo formatCurrency($txn['fees_repaid_derived'] ?? 0); ?></td>
                            <td><?php echo formatCurrency($txn['penalties_repaid_derived'] ?? 0); ?></td>
                            <td><?php echo formatCurrency($txn['credit'] ?? 0); ?></td>
                            <td><?php echo htmlspecialchars(($txn['user_first_name'] ?? '') . ' ' . ($txn['user_last_name'] ?? '')); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center">No transactions found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
                <?php if (!empty($transactions)): ?>
                    <tfoot class="table-light">
                        <tr>
                            <th colspan="2">TOTAL</th>
                            <th><?php echo formatCurrency(array_sum(array_column($transactions, 'amount'))); ?></th>
                            <th><?php echo formatCurrency(array_sum(array_column($transactions, 'principal_repaid_derived'))); ?></th>
                            <th><?php echo formatCurrency(array_sum(array_column($transactions, 'interest_repaid_derived'))); ?></th>
                            <th><?php echo formatCurrency(array_sum(array_column($transactions, 'fees_repaid_derived'))); ?></th>
                            <th><?php echo formatCurrency(array_sum(array_column($transactions, 'penalties_repaid_derived'))); ?></th>
                            <th colspan="2"></th>
                        </tr>
                    </tfoot>
                <?php endif; ?>
            </table>
        </div>
    </div>
</div>

<?php include APP_PATH . '/Views/layouts/footer.php'; ?>
