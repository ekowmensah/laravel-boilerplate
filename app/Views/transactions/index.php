<?php include APP_PATH . '/Views/layouts/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h2>All Transactions</h2>
    </div>
    <div class="col-md-6 text-end">
        <button class="btn btn-primary" onclick="window.print()">
            <i class="cil-print"></i> Print
        </button>
    </div>
</div>

<!-- Filters -->
<div class="card mb-3">
    <div class="card-header"><strong>Filters</strong></div>
    <div class="card-body">
        <form method="GET" action="<?php echo BASE_URL; ?>transactions">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Transaction Type</label>
                    <select class="form-select" name="type">
                        <option value="">All Types</option>
                        <option value="loan" <?php echo (isset($_GET['type']) && $_GET['type'] == 'loan') ? 'selected' : ''; ?>>Loan Transactions</option>
                        <option value="savings" <?php echo (isset($_GET['type']) && $_GET['type'] == 'savings') ? 'selected' : ''; ?>>Savings Transactions</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">From Date</label>
                    <input type="date" class="form-control" name="date_from" value="<?php echo $_GET['date_from'] ?? ''; ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">To Date</label>
                    <input type="date" class="form-control" name="date_to" value="<?php echo $_GET['date_to'] ?? ''; ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="cil-filter"></i> Apply Filters
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Transactions Table -->
<div class="card">
    <div class="card-header"><strong>Transaction History</strong></div>
    <div class="card-body">
        <table class="table table-striped datatable" id="transactionsTable">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Account</th>
                    <th>Client</th>
                    <th>Description</th>
                    <th>Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($transactions)): ?>
                    <?php foreach ($transactions as $transaction): ?>
                    <tr>
                        <td><?php echo isset($transaction['created_at']) ? date('M d, Y H:i', strtotime($transaction['created_at'])) : 'N/A'; ?></td>
                        <td>
                            <?php if (isset($transaction['type']) && $transaction['type'] == 'loan'): ?>
                                <span class="badge bg-primary">Loan</span>
                            <?php else: ?>
                                <span class="badge bg-success">Savings</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($transaction['account_number'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($transaction['client_name'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($transaction['description'] ?? 'N/A'); ?></td>
                        <td><strong><?php echo formatCurrency($transaction['amount'] ?? 0); ?></strong></td>
                        <td>
                            <?php if (isset($transaction['reversed']) && $transaction['reversed']): ?>
                                <span class="badge bg-danger">Reversed</span>
                            <?php else: ?>
                                <span class="badge bg-success">Active</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">No transactions found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include APP_PATH . '/Views/layouts/footer.php'; ?>
