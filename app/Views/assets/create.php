<?php include APP_PATH . '/Views/layouts/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h2>Add Asset</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?php echo BASE_URL; ?>assets" class="btn btn-secondary">
            <i class="cil-arrow-left"></i> Back to Assets
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

<form method="POST" action="<?php echo BASE_URL; ?>assets/create">
    <div class="card">
        <div class="card-header"><strong>Asset Information</strong></div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Asset Type <span class="text-danger">*</span></label>
                    <select class="form-select" name="asset_type_id" required>
                        <option value="">-- Select Type --</option>
                        <?php if (!empty($assetTypes)): ?>
                            <?php foreach ($assetTypes as $type): ?>
                                <option value="<?php echo $type['id']; ?>">
                                    <?php echo htmlspecialchars($type['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Branch</label>
                    <select class="form-select" name="branch_id">
                        <option value="">-- Select Branch --</option>
                        <?php if (!empty($branches)): ?>
                            <?php foreach ($branches as $branch): ?>
                                <option value="<?php echo $branch['id']; ?>">
                                    <?php echo htmlspecialchars($branch['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Asset Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="name" required>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Serial Number</label>
                    <input type="text" class="form-control" name="serial_number">
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Purchase Date <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="purchase_date" required>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Purchase Price <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" class="form-control" name="purchase_price" required>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Current Value</label>
                    <input type="number" step="0.01" class="form-control" name="current_value">
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Depreciation Rate (%)</label>
                    <input type="number" step="0.01" class="form-control" name="depreciation_rate" value="0">
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Status</label>
                    <select class="form-select" name="status">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="disposed">Disposed</option>
                    </select>
                </div>
                
                <div class="col-12">
                    <label class="form-label">Notes</label>
                    <textarea class="form-control" name="notes" rows="3"></textarea>
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-3">
        <button type="submit" class="btn btn-primary btn-lg">
            <i class="cil-save"></i> Create Asset
        </button>
        <a href="<?php echo BASE_URL; ?>assets" class="btn btn-secondary btn-lg">Cancel</a>
    </div>
</form>

<?php include APP_PATH . '/Views/layouts/footer.php'; ?>
