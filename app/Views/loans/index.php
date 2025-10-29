<?php include APP_PATH . '/Views/layouts/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h2>Loans Management</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?php echo BASE_URL; ?>loans/create" class="btn btn-primary">
            <i class="cil-plus"></i> Create New Loan
        </a>
    </div>
</div>

<!-- Filters -->
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" action="" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="all" <?php echo $status === 'all' ? 'selected' : ''; ?>>All Status</option>
                    <option value="submitted" <?php echo $status === 'submitted' ? 'selected' : ''; ?>>Submitted</option>
                    <option value="pending" <?php echo $status === 'pending' ? 'selected' : ''; ?>>Pending</option>
                    <option value="approved" <?php echo $status === 'approved' ? 'selected' : ''; ?>>Approved</option>
                    <option value="active" <?php echo $status === 'active' ? 'selected' : ''; ?>>Active</option>
                    <option value="closed" <?php echo $status === 'closed' ? 'selected' : ''; ?>>Closed</option>
                </select>
            </div>
            
            <div class="col-md-3">
                <label class="form-label">Client Type</label>
                <select name="client_type" class="form-select" onchange="this.form.submit()">
                    <option value="all" <?php echo $clientType === 'all' ? 'selected' : ''; ?>>All Types</option>
                    <option value="client" <?php echo $clientType === 'client' ? 'selected' : ''; ?>>Individual</option>
                    <option value="group" <?php echo $clientType === 'group' ? 'selected' : ''; ?>>Group</option>
                </select>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <strong>All Loans</strong>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover datatable">
                <thead>
                    <tr>
                        <th>Account #</th>
                        <th>Client</th>
                        <th>Type</th>
                        <th>Product</th>
                        <th>Principal</th>
                        <th>Outstanding</th>
                        <th>Disbursed Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($loans as $loan): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($loan['account_number'] ?? 'N/A'); ?></td>
                            <td>
                                <strong><?php echo htmlspecialchars($loan['first_name'] . ' ' . $loan['last_name']); ?></strong>
                            </td>
                            <td>
                                <span class="badge badge-<?php echo $loan['client_type'] === 'group' ? 'info' : 'primary'; ?>">
                                    <?php echo ucfirst($loan['client_type']); ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars($loan['product_name']); ?></td>
                            <td><?php echo formatCurrency($loan['principal']); ?></td>
                            <td><?php echo formatCurrency($loan['principal_outstanding_derived']); ?></td>
                            <td><?php echo formatDate($loan['disbursed_on_date']); ?></td>
                            <td><?php echo getStatusBadge($loan['status']); ?></td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="<?php echo BASE_URL; ?>loans/view/<?php echo $loan['id']; ?>" 
                                       class="btn btn-info" title="View">
                                        <i class="cil-eye"></i>
                                    </a>
                                    <?php if ($loan['status'] === 'submitted' || $loan['status'] === 'pending'): ?>
                                        <a href="<?php echo BASE_URL; ?>loans/approve/<?php echo $loan['id']; ?>" 
                                           class="btn btn-success" title="Approve">
                                            <i class="cil-check"></i>
                                        </a>
                                    <?php endif; ?>
                                    <?php if ($loan['status'] === 'approved'): ?>
                                        <a href="<?php echo BASE_URL; ?>loans/disburse/<?php echo $loan['id']; ?>" 
                                           class="btn btn-primary" title="Disburse">
                                            <i class="cil-dollar"></i>
                                        </a>
                                    <?php endif; ?>
                                    <?php if ($loan['status'] === 'active'): ?>
                                        <a href="<?php echo BASE_URL; ?>loans/repayment/<?php echo $loan['id']; ?>" 
                                           class="btn btn-warning" title="Repayment">
                                            <i class="cil-credit-card"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include APP_PATH . '/Views/layouts/footer.php'; ?>
