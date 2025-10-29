<?php include APP_PATH . '/Views/layouts/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h2>Manage Permissions: <?php echo htmlspecialchars($role['display_name'] ?? ''); ?></h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?php echo BASE_URL; ?>roles" class="btn btn-secondary">
            <i class="cil-arrow-left"></i> Back to Roles
        </a>
    </div>
</div>

<form method="POST" action="<?php echo BASE_URL; ?>roles/permissions/<?php echo $role['id']; ?>">
    <div class="card">
        <div class="card-header"><strong>Assign Permissions</strong></div>
        <div class="card-body">
            <?php if (!empty($permissions)): ?>
                <?php foreach ($permissions as $category => $perms): ?>
                    <div class="mb-4">
                        <h5 class="text-primary"><?php echo ucwords(str_replace('_', ' ', $category)); ?></h5>
                        <div class="row">
                            <?php foreach ($perms as $permission): ?>
                                <div class="col-md-4 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="permissions[]" 
                                               value="<?php echo $permission['id']; ?>" 
                                               id="perm_<?php echo $permission['id']; ?>"
                                               <?php echo in_array($permission['id'], $rolePermissionIds) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="perm_<?php echo $permission['id']; ?>">
                                            <?php echo htmlspecialchars($permission['display_name'] ?? $permission['name']); ?>
                                        </label>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <hr>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-muted">No permissions available. Please seed permissions first.</p>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="mt-3">
        <button type="submit" class="btn btn-primary btn-lg">
            <i class="cil-save"></i> Save Permissions
        </button>
        <a href="<?php echo BASE_URL; ?>roles" class="btn btn-secondary btn-lg">Cancel</a>
    </div>
</form>

<?php include APP_PATH . '/Views/layouts/footer.php'; ?>
