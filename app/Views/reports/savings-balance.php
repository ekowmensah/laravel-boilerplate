<?php include APP_PATH . '/Views/layouts/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h2>Savings Balance Report</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?php echo BASE_URL; ?>reports" class="btn btn-secondary">
            <i class="cil-arrow-left"></i> Back to Reports
        </a>
        <a href="?export=csv&<?php echo http_build_query($_GET); ?>" class="btn btn-success">
            <i class="cil-cloud-download"></i> Export CSV
        </a>
    </div>
</div>

<!-- Filters -->
<div class="card mb-3">
    <div class="card-header"><strong>Filters</strong></div>
    <div class="card-body">
        <form method="GET" action="<?php echo BASE_URL; ?>reports/savings-balance">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Branch</label>
                    <select class="form-select" name="branch_id">
                        <option value="">All Branches</option>
                        <?php foreach ($branches as $branch): ?>
                            <option value="<?php echo $branch['id']; ?>" <?php echo (isset($_GET['branch_id']) && $_GET['branch_id'] == $branch['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($branch['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary"><i class="cil-filter"></i> Apply Filters</button>
                    <a href="<?php echo BASE_URL; ?>reports/savings-balance" class="btn btn-secondary">Clear</a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Summary -->
<div class="row mb-3">
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h6>Total Accounts</h6>
                <h3><?php echo count($data['accounts']); ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h6>Total Balance</h6>
                <h3><?php echo formatCurrency($data['total_balance']); ?></h3>
            </div>
        </div>
    </div>
</div>

<!-- Details Table -->
<div class="card">
    <div class="card-header"><strong>Account Balances</strong></div>
    <div class="card-body">
        <table class="table table-striped" id="savingsTable">
            <thead>
                <tr>
                    <th>Account</th>
                    <th>Client</th>
                    <th>Product</th>
                    <th>Branch</th>
                    <th>Balance</th>
                    <th>Total Deposits</th>
                    <th>Total Withdrawals</th>
                    <th>Activated Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['accounts'] as $account): ?>
                <tr>
                    <td><?php echo htmlspecialchars($account['account_number']); ?></td>
                    <td><?php echo htmlspecialchars($account['first_name'] . ' ' . $account['last_name']); ?></td>
                    <td><?php echo htmlspecialchars($account['product_name']); ?></td>
                    <td><?php echo htmlspecialchars($account['branch_name']); ?></td>
                    <td><strong><?php echo formatCurrency($account['balance_derived']); ?></strong></td>
                    <td><?php echo formatCurrency($account['total_deposits_derived']); ?></td>
                    <td><?php echo formatCurrency($account['total_withdrawals_derived']); ?></td>
                    <td><?php echo date('M d, Y', strtotime($account['activated_on_date'])); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr class="table-active">
                    <th colspan="4" class="text-end">Total:</th>
                    <th><?php echo formatCurrency($data['total_balance']); ?></th>
                    <th colspan="3"></th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#savingsTable').DataTable({
        order: [[4, 'desc']],
        pageLength: 25
    });
});
</script>

<?php include APP_PATH . '/Views/layouts/footer.php'; ?>
