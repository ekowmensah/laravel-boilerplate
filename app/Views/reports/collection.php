<?php include APP_PATH . '/Views/layouts/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h2>Collection Report</h2>
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
        <form method="GET" action="<?php echo BASE_URL; ?>reports/collection">
            <div class="row g-3">
                <div class="col-md-3">
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
                <div class="col-md-3">
                    <label class="form-label">From Date</label>
                    <input type="date" class="form-control" name="date_from" value="<?php echo $_GET['date_from'] ?? ''; ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">To Date</label>
                    <input type="date" class="form-control" name="date_to" value="<?php echo $_GET['date_to'] ?? ''; ?>">
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary"><i class="cil-filter"></i> Apply Filters</button>
                    <a href="<?php echo BASE_URL; ?>reports/collection" class="btn btn-secondary">Clear</a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Summary -->
<div class="row mb-3">
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h6>Total Collections</h6>
                <h3><?php echo count($data['collections']); ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h6>Total Amount</h6>
                <h3><?php echo formatCurrency($data['total']); ?></h3>
            </div>
        </div>
    </div>
</div>

<!-- Details Table -->
<div class="card">
    <div class="card-header"><strong>Collection Details</strong></div>
    <div class="card-body">
        <table class="table table-striped" id="collectionTable">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Account</th>
                    <th>Client</th>
                    <th>Branch</th>
                    <th>Amount</th>
                    <th>Principal</th>
                    <th>Interest</th>
                    <th>Fees</th>
                    <th>Penalties</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['collections'] as $payment): ?>
                <tr>
                    <td><?php echo date('M d, Y', strtotime($payment['payment_date'])); ?></td>
                    <td><?php echo htmlspecialchars($payment['account_number']); ?></td>
                    <td><?php echo htmlspecialchars($payment['first_name'] . ' ' . $payment['last_name']); ?></td>
                    <td><?php echo htmlspecialchars($payment['branch_name']); ?></td>
                    <td><strong><?php echo formatCurrency($payment['amount']); ?></strong></td>
                    <td><?php echo formatCurrency($payment['principal_repaid_derived']); ?></td>
                    <td><?php echo formatCurrency($payment['interest_repaid_derived']); ?></td>
                    <td><?php echo formatCurrency($payment['fees_repaid_derived']); ?></td>
                    <td><?php echo formatCurrency($payment['penalties_repaid_derived']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr class="table-active">
                    <th colspan="4" class="text-end">Total:</th>
                    <th><?php echo formatCurrency($data['total']); ?></th>
                    <th colspan="4"></th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#collectionTable').DataTable({
        order: [[0, 'desc']],
        pageLength: 25
    });
});
</script>

<?php include APP_PATH . '/Views/layouts/footer.php'; ?>
