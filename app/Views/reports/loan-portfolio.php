<?php include APP_PATH . '/Views/layouts/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h2>Loan Portfolio Report</h2>
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
    <div class="card-header">
        <strong>Filters</strong>
    </div>
    <div class="card-body">
        <form method="GET" action="<?php echo BASE_URL; ?>reports/loan-portfolio">
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
                    <label class="form-label">Status</label>
                    <select class="form-select" name="status">
                        <option value="">All Status</option>
                        <option value="submitted" <?php echo (isset($_GET['status']) && $_GET['status'] == 'submitted') ? 'selected' : ''; ?>>Submitted</option>
                        <option value="approved" <?php echo (isset($_GET['status']) && $_GET['status'] == 'approved') ? 'selected' : ''; ?>>Approved</option>
                        <option value="active" <?php echo (isset($_GET['status']) && $_GET['status'] == 'active') ? 'selected' : ''; ?>>Active</option>
                        <option value="closed" <?php echo (isset($_GET['status']) && $_GET['status'] == 'closed') ? 'selected' : ''; ?>>Closed</option>
                        <option value="written_off" <?php echo (isset($_GET['status']) && $_GET['status'] == 'written_off') ? 'selected' : ''; ?>>Written Off</option>
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
                    <button type="submit" class="btn btn-primary">
                        <i class="cil-filter"></i> Apply Filters
                    </button>
                    <a href="<?php echo BASE_URL; ?>reports/loan-portfolio" class="btn btn-secondary">Clear</a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Summary -->
<div class="row mb-3">
    <?php foreach ($data['summary'] as $summary): ?>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h6 class="text-muted"><?php echo ucfirst($summary['status']); ?></h6>
                <h4><?php echo $summary['count']; ?> Loans</h4>
                <p class="mb-1"><strong>Principal:</strong> <?php echo formatCurrency($summary['total_principal']); ?></p>
                <p class="mb-0"><strong>Outstanding:</strong> <?php echo formatCurrency($summary['total_outstanding']); ?></p>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Details Table -->
<div class="card">
    <div class="card-header">
        <strong>Loan Details</strong>
    </div>
    <div class="card-body">
        <table class="table table-striped" id="loansTable">
            <thead>
                <tr>
                    <th>Account</th>
                    <th>Client</th>
                    <th>Product</th>
                    <th>Branch</th>
                    <th>Principal</th>
                    <th>Outstanding</th>
                    <th>Repaid</th>
                    <th>Status</th>
                    <th>Disbursed</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['details'] as $loan): ?>
                <tr>
                    <td><?php echo htmlspecialchars($loan['account_number']); ?></td>
                    <td><?php echo htmlspecialchars($loan['first_name'] . ' ' . $loan['last_name']); ?></td>
                    <td><?php echo htmlspecialchars($loan['product_name']); ?></td>
                    <td><?php echo htmlspecialchars($loan['branch_name']); ?></td>
                    <td><?php echo formatCurrency($loan['principal']); ?></td>
                    <td><?php echo formatCurrency($loan['principal_outstanding_derived']); ?></td>
                    <td><?php echo formatCurrency($loan['principal_repaid_derived']); ?></td>
                    <td><?php echo getStatusBadge($loan['status']); ?></td>
                    <td><?php echo date('M d, Y', strtotime($loan['disbursed_on_date'])); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#loansTable').DataTable({
        order: [[8, 'desc']],
        pageLength: 25
    });
});
</script>

<?php include APP_PATH . '/Views/layouts/footer.php'; ?>
