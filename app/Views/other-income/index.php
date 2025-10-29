<?php include APP_PATH . '/Views/layouts/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h2>Other Income</h2>
    </div>
    <div class="col-md-6 text-end">
        <button class="btn btn-primary">
            <i class="cil-plus"></i> Record Income
        </button>
    </div>
</div>

<div class="card">
    <div class="card-header"><strong>All Other Income</strong></div>
    <div class="card-body">
        <table class="table table-striped datatable">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($income)): ?>
                    <?php foreach ($income as $item): ?>
                    <tr>
                        <td><?php echo isset($item['date']) ? date('M d, Y', strtotime($item['date'])) : 'N/A'; ?></td>
                        <td><?php echo htmlspecialchars($item['type_name'] ?? 'N/A'); ?></td>
                        <td><strong><?php echo formatCurrency($item['amount'] ?? 0); ?></strong></td>
                        <td><?php echo htmlspecialchars($item['description'] ?? ''); ?></td>
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
                        <td colspan="5" class="text-center">No other income found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include APP_PATH . '/Views/layouts/footer.php'; ?>
