<?php include APP_PATH . '/Views/layouts/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h2>Currency Management</h2>
    </div>
    <div class="col-md-6 text-end">
        <button class="btn btn-primary">
            <i class="cil-plus"></i> Add Currency
        </button>
    </div>
</div>

<div class="card">
    <div class="card-header"><strong>All Currencies</strong></div>
    <div class="card-body">
        <table class="table table-striped datatable">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Code</th>
                    <th>Symbol</th>
                    <th>Rate</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($currencies)): ?>
                    <?php foreach ($currencies as $currency): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($currency['name'] ?? ''); ?></strong></td>
                        <td><?php echo htmlspecialchars($currency['code'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($currency['symbol'] ?? 'N/A'); ?></td>
                        <td><?php echo number_format($currency['rate'] ?? 1, 4); ?></td>
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
                        <td colspan="5" class="text-center">No currencies found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include APP_PATH . '/Views/layouts/footer.php'; ?>
