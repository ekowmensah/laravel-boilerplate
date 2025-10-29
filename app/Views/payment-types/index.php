<?php include APP_PATH . '/Views/layouts/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h2>Payment Types</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?php echo BASE_URL; ?>payment-types/create" class="btn btn-primary">
            <i class="cil-plus"></i> Create Payment Type
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header"><strong>All Payment Types</strong></div>
    <div class="card-body">
        <table class="table table-striped datatable">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($types)): ?>
                    <?php foreach ($types as $type): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($type['name'] ?? ''); ?></strong></td>
                        <td><?php echo htmlspecialchars($type['description'] ?? 'N/A'); ?></td>
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
                        <td colspan="3" class="text-center">No payment types found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include APP_PATH . '/Views/layouts/footer.php'; ?>
