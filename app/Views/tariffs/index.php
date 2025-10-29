<?php include APP_PATH . '/Views/layouts/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h2>Tariffs</h2>
    </div>
    <div class="col-md-6 text-end">
        <button class="btn btn-primary">
            <i class="cil-plus"></i> Create Tariff
        </button>
    </div>
</div>

<div class="card">
    <div class="card-header"><strong>All Tariffs</strong></div>
    <div class="card-body">
        <table class="table table-striped datatable">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Amount</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($tariffs)): ?>
                    <?php foreach ($tariffs as $tariff): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($tariff['name'] ?? ''); ?></strong></td>
                        <td><?php echo formatCurrency($tariff['amount'] ?? 0); ?></td>
                        <td><?php echo htmlspecialchars($tariff['description'] ?? 'N/A'); ?></td>
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
                        <td colspan="4" class="text-center">No tariffs found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include APP_PATH . '/Views/layouts/footer.php'; ?>
