<?php include APP_PATH . '/Views/layouts/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h2>Create Loan Charge</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?php echo BASE_URL; ?>loan-charges" class="btn btn-secondary">
            <i class="cil-arrow-left"></i> Back
        </a>
    </div>
</div>

<form method="POST" action="<?php echo BASE_URL; ?>loan-charges/create">
    <div class="card">
        <div class="card-header"><strong>Charge Details</strong></div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Charge Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="name" required>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Charge Type <span class="text-danger">*</span></label>
                    <select class="form-select" name="charge_type_id" required>
                        <option value="">-- Select Type --</option>
                        <?php if (!empty($chargeTypes)): ?>
                            <?php foreach ($chargeTypes as $type): ?>
                                <option value="<?php echo $type['id']; ?>">
                                    <?php echo htmlspecialchars($type['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Amount</label>
                    <input type="number" step="0.01" class="form-control" name="amount">
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Percentage (%)</label>
                    <input type="number" step="0.01" class="form-control" name="percentage">
                </div>
                
                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea class="form-control" name="description" rows="3"></textarea>
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-3">
        <button type="submit" class="btn btn-primary btn-lg">
            <i class="cil-save"></i> Create Charge
        </button>
        <a href="<?php echo BASE_URL; ?>loan-charges" class="btn btn-secondary btn-lg">Cancel</a>
    </div>
</form>

<?php include APP_PATH . '/Views/layouts/footer.php'; ?>
