<?php include APP_PATH . '/Views/layouts/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h2>Custom Fields</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?php echo BASE_URL; ?>custom-fields/create" class="btn btn-primary">
            <i class="cil-plus"></i> Create Field
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header"><strong>All Custom Fields</strong></div>
    <div class="card-body">
        <table class="table table-striped datatable">
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Required</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($fields)): ?>
                    <?php foreach ($fields as $field): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($field['category'] ?? ''); ?></td>
                        <td><strong><?php echo htmlspecialchars($field['name'] ?? ''); ?></strong></td>
                        <td><?php echo htmlspecialchars($field['type'] ?? ''); ?></td>
                        <td><?php echo ($field['required'] ?? 0) ? '<span class="badge bg-warning">Yes</span>' : '<span class="badge bg-secondary">No</span>'; ?></td>
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
                        <td colspan="5" class="text-center">No custom fields found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include APP_PATH . '/Views/layouts/footer.php'; ?>
