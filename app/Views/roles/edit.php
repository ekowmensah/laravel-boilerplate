<?php include APP_PATH . '/Views/layouts/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h2>Edit Role</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?php echo BASE_URL; ?>roles" class="btn btn-secondary">
            <i class="cil-arrow-left"></i> Back to Roles
        </a>
    </div>
</div>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="POST" action="<?php echo BASE_URL; ?>roles/edit/<?php echo $role['id']; ?>">
    <div class="card">
        <div class="card-header"><strong>Role Information</strong></div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($role['name'] ?? ''); ?>" required>
                    <small class="text-muted">Lowercase, no spaces (use underscores)</small>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Display Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="display_name" value="<?php echo htmlspecialchars($role['display_name'] ?? ''); ?>" required>
                </div>
                
                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea class="form-control" name="description" rows="3"><?php echo htmlspecialchars($role['description'] ?? ''); ?></textarea>
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-3">
        <button type="submit" class="btn btn-primary btn-lg">
            <i class="cil-save"></i> Update Role
        </button>
        <a href="<?php echo BASE_URL; ?>roles" class="btn btn-secondary btn-lg">Cancel</a>
    </div>
</form>

<?php include APP_PATH . '/Views/layouts/footer.php'; ?>
