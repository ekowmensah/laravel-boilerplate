<?php include APP_PATH . '/Views/layouts/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h2>Roles Management</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?php echo BASE_URL; ?>roles/create" class="btn btn-primary">
            <i class="cil-plus"></i> New Role
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header"><strong>All Roles</strong></div>
    <div class="card-body">
        <table class="table table-striped datatable">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Display Name</th>
                    <th>Description</th>
                    <th>Users</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($roles)): ?>
                    <?php foreach ($roles as $role): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($role['name'] ?? ''); ?></strong></td>
                        <td><?php echo htmlspecialchars($role['display_name'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($role['description'] ?? 'N/A'); ?></td>
                        <td><span class="badge bg-info">0</span></td>
                        <td><?php echo isset($role['created_at']) ? date('M d, Y', strtotime($role['created_at'])) : 'N/A'; ?></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="<?php echo BASE_URL; ?>roles/permissions/<?php echo $role['id']; ?>" class="btn btn-success" title="Permissions">
                                    <i class="cil-lock-locked"></i>
                                </a>
                                <a href="<?php echo BASE_URL; ?>roles/edit/<?php echo $role['id']; ?>" class="btn btn-warning" title="Edit">
                                    <i class="cil-pencil"></i>
                                </a>
                                <a href="<?php echo BASE_URL; ?>roles/delete/<?php echo $role['id']; ?>" class="btn btn-danger" title="Delete" onclick="return confirm('Delete this role?')">
                                    <i class="cil-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">
                            <p class="my-3">No roles found</p>
                            <a href="<?php echo BASE_URL; ?>roles/create" class="btn btn-primary">
                                <i class="cil-plus"></i> Create First Role
                            </a>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include APP_PATH . '/Views/layouts/footer.php'; ?>
