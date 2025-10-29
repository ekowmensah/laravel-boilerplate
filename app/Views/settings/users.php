<?php include APP_PATH . '/Views/layouts/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h2>User Management</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?php echo BASE_URL; ?>settings" class="btn btn-secondary">
            <i class="cil-arrow-left"></i> Back to Settings
        </a>
        <button class="btn btn-primary">
            <i class="cil-plus"></i> New User
        </button>
    </div>
</div>

<div class="card">
    <div class="card-header"><strong>All Users</strong></div>
    <div class="card-body">
        <table class="table table-striped datatable">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Branch</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Last Login</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($users)): ?>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')); ?></strong></td>
                        <td><?php echo htmlspecialchars($user['email'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($user['branch_name'] ?? 'N/A'); ?></td>
                        <td><span class="badge bg-info">User</span></td>
                        <td>
                            <?php if (isset($user['active']) && $user['active']): ?>
                                <span class="badge bg-success">Active</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Inactive</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo isset($user['last_login']) ? date('M d, Y', strtotime($user['last_login'])) : 'Never'; ?></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-warning" title="Edit">
                                    <i class="cil-pencil"></i>
                                </button>
                                <button class="btn btn-info" title="Permissions">
                                    <i class="cil-lock-locked"></i>
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
                        <td colspan="7" class="text-center">No users found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include APP_PATH . '/Views/layouts/footer.php'; ?>
