<?php include APP_PATH . '/Views/layouts/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h2>Product Management</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?php echo BASE_URL; ?>settings" class="btn btn-secondary">
            <i class="cil-arrow-left"></i> Back to Settings
        </a>
    </div>
</div>

<!-- Loan Products -->
<div class="card mb-3">
    <div class="card-header">
        <strong>Loan Products</strong>
        <button class="btn btn-sm btn-primary float-end">
            <i class="cil-plus"></i> New Loan Product
        </button>
    </div>
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Short Name</th>
                    <th>Min Amount</th>
                    <th>Max Amount</th>
                    <th>Interest Rate</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($loanProducts)): ?>
                    <?php foreach ($loanProducts as $product): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($product['name'] ?? ''); ?></strong></td>
                        <td><?php echo htmlspecialchars($product['short_name'] ?? ''); ?></td>
                        <td><?php echo formatCurrency($product['min_principal'] ?? 0); ?></td>
                        <td><?php echo formatCurrency($product['max_principal'] ?? 0); ?></td>
                        <td><?php echo ($product['interest_rate'] ?? 0) . '%'; ?></td>
                        <td>
                            <?php if (isset($product['active']) && $product['active']): ?>
                                <span class="badge bg-success">Active</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Inactive</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-warning" title="Edit">
                                    <i class="cil-pencil"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">No loan products found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Savings Products -->
<div class="card">
    <div class="card-header">
        <strong>Savings Products</strong>
        <button class="btn btn-sm btn-success float-end">
            <i class="cil-plus"></i> New Savings Product
        </button>
    </div>
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Short Name</th>
                    <th>Min Balance</th>
                    <th>Interest Rate</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($savingsProducts)): ?>
                    <?php foreach ($savingsProducts as $product): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($product['name'] ?? ''); ?></strong></td>
                        <td><?php echo htmlspecialchars($product['short_name'] ?? ''); ?></td>
                        <td><?php echo formatCurrency($product['min_balance'] ?? 0); ?></td>
                        <td><?php echo ($product['interest_rate'] ?? 0) . '%'; ?></td>
                        <td>
                            <?php if (isset($product['active']) && $product['active']): ?>
                                <span class="badge bg-success">Active</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Inactive</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-warning" title="Edit">
                                    <i class="cil-pencil"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">No savings products found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include APP_PATH . '/Views/layouts/footer.php'; ?>
