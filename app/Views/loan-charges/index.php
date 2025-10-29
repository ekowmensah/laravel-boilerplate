<?php include APP_PATH . '/Views/layouts/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h2>Loan Charges</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?php echo BASE_URL; ?>loan-charges/create" class="btn btn-primary">
            <i class="cil-plus"></i> Create Charge
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header"><strong>All Loan Charges</strong></div>
    <div class="card-body">
        <table class="table table-striped datatable">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($charges)): ?>
                    <?php foreach ($charges as $charge): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($charge['name'] ?? ''); ?></strong></td>
                        <td><?php echo htmlspecialchars($charge['type_name'] ?? 'N/A'); ?></td>
                        <td><?php echo formatCurrency($charge['amount'] ?? 0); ?></td>
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
                        <td colspan="4" class="text-center">No charges found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include APP_PATH . '/Views/layouts/footer.php'; ?>
