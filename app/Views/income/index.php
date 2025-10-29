<?php include APP_PATH . '/Views/layouts/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h2>Income Management</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?php echo BASE_URL; ?>income/create" class="btn btn-primary">
            <i class="cil-plus"></i> Record Income
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header"><strong>All Income</strong></div>
    <div class="card-body">
        <table class="table table-striped datatable">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Branch</th>
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
                        <td><?php echo htmlspecialchars($item['income_type_name'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($item['branch_name'] ?? 'N/A'); ?></td>
                        <td><strong><?php echo formatCurrency($item['amount'] ?? 0); ?></strong></td>
                        <td><?php echo htmlspecialchars($item['description'] ?? ''); ?></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="<?php echo BASE_URL; ?>income/view/<?php echo $item['id']; ?>" class="btn btn-info">
                                    <i class="cil-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">No income found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include APP_PATH . '/Views/layouts/footer.php'; ?>
