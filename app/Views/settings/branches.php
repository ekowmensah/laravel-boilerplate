<?php include APP_PATH . '/Views/layouts/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h2>Branch Management</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?php echo BASE_URL; ?>settings" class="btn btn-secondary">
            <i class="cil-arrow-left"></i> Back to Settings
        </a>
        <button class="btn btn-primary" data-coreui-toggle="modal" data-coreui-target="#addBranchModal">
            <i class="cil-plus"></i> New Branch
        </button>
    </div>
</div>

<div class="card">
    <div class="card-header"><strong>All Branches</strong></div>
    <div class="card-body">
        <table class="table table-striped datatable">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Code</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($branches)): ?>
                    <?php foreach ($branches as $branch): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($branch['name'] ?? ''); ?></strong></td>
                        <td><?php echo htmlspecialchars($branch['code'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($branch['phone'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($branch['email'] ?? 'N/A'); ?></td>
                        <td>
                            <?php if (isset($branch['active']) && $branch['active']): ?>
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
                                <button class="btn btn-danger" title="Delete">
                                    <i class="cil-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">No branches found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include APP_PATH . '/Views/layouts/footer.php'; ?>
