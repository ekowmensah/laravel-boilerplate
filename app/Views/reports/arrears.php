<?php include APP_PATH . '/Views/layouts/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h2>Arrears Report</h2>
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
        <form method="GET" action="<?php echo BASE_URL; ?>reports/arrears">
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
                    <a href="<?php echo BASE_URL; ?>reports/arrears" class="btn btn-secondary">Clear</a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Summary -->
<div class="row mb-3">
    <div class="col-md-3">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <h6>Total Overdue Loans</h6>
                <h3><?php echo count($data['arrears']); ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <h6>Total Overdue Amount</h6>
                <h3><?php echo formatCurrency(array_sum(array_column($data['arrears'], 'total_outstanding_derived'))); ?></h3>
            </div>
        </div>
    </div>
</div>

<!-- Details Table -->
<div class="card">
    <div class="card-header"><strong>Overdue Loans</strong></div>
    <div class="card-body">
        <table class="table table-striped" id="arrearsTable">
            <thead>
                <tr>
                    <th>Account</th>
                    <th>Client</th>
                    <th>Mobile</th>
                    <th>Product</th>
                    <th>Branch</th>
                    <th>Days Overdue</th>
                    <th>Overdue Principal</th>
                    <th>Overdue Interest</th>
                    <th>Total Outstanding</th>
                    <th>First Overdue Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['arrears'] as $loan): ?>
                <tr>
                    <td><?php echo htmlspecialchars($loan['account_number']); ?></td>
                    <td><?php echo htmlspecialchars($loan['first_name'] . ' ' . $loan['last_name']); ?></td>
                    <td><?php echo htmlspecialchars($loan['mobile']); ?></td>
                    <td><?php echo htmlspecialchars($loan['product_name']); ?></td>
                    <td><?php echo htmlspecialchars($loan['branch_name']); ?></td>
                    <td><span class="badge bg-danger"><?php echo $loan['days_overdue']; ?> days</span></td>
                    <td><?php echo formatCurrency($loan['overdue_principal']); ?></td>
                    <td><?php echo formatCurrency($loan['overdue_interest']); ?></td>
                    <td><strong><?php echo formatCurrency($loan['total_outstanding_derived']); ?></strong></td>
                    <td><?php echo date('M d, Y', strtotime($loan['first_overdue_date'])); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#arrearsTable').DataTables({
        order: [[5, 'desc']],
        pageLength: 25
    });
});
</script>

<?php include APP_PATH . '/Views/layouts/footer.php'; ?>
