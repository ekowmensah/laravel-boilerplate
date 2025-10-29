<?php include APP_PATH . '/Views/layouts/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h2>Loan Applications</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?php echo BASE_URL; ?>loan-applications/create" class="btn btn-primary">
            <i class="cil-plus"></i> New Application
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header"><strong>All Applications</strong></div>
    <div class="card-body">
        <table class="table table-striped datatable">
            <thead>
                <tr>
                    <th>Client</th>
                    <th>Product</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($applications)): ?>
                    <?php foreach ($applications as $app): ?>
                    <tr>
                        <td><?php echo htmlspecialchars(($app['first_name'] ?? '') . ' ' . ($app['last_name'] ?? '')); ?></td>
                        <td><?php echo htmlspecialchars($app['product_name'] ?? 'N/A'); ?></td>
                        <td><strong><?php echo formatCurrency($app['amount'] ?? 0); ?></strong></td>
                        <td><?php echo getStatusBadge($app['status'] ?? 'pending'); ?></td>
                        <td><?php echo isset($app['created_at']) ? date('M d, Y', strtotime($app['created_at'])) : 'N/A'; ?></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-info" title="View">
                                    <i class="cil-eye"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">No applications found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include APP_PATH . '/Views/layouts/footer.php'; ?>
