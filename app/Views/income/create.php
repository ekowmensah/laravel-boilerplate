<?php include APP_PATH . '/Views/layouts/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h2>Record Income</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?php echo BASE_URL; ?>income" class="btn btn-secondary">
            <i class="cil-arrow-left"></i> Back
        </a>
    </div>
</div>

<form method="POST" action="<?php echo BASE_URL; ?>income/create">
    <div class="card">
        <div class="card-header"><strong>Income Details</strong></div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Income Type <span class="text-danger">*</span></label>
                    <select class="form-select" name="income_type_id" required>
                        <option value="">-- Select Type --</option>
                        <?php if (!empty($incomeTypes)): ?>
                            <?php foreach ($incomeTypes as $type): ?>
                                <option value="<?php echo $type['id']; ?>">
                                    <?php echo htmlspecialchars($type['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Amount <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" class="form-control" name="amount" required>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Date <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="date" value="<?php echo date('Y-m-d'); ?>" required>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Branch</label>
                    <select class="form-select" name="branch_id">
                        <option value="">-- Select Branch --</option>
                    </select>
                </div>
                
                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea class="form-control" name="description" rows="3"></textarea>
                </div>
                
                <div class="col-12">
                    <label class="form-label">Notes</label>
                    <textarea class="form-control" name="notes" rows="2"></textarea>
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-3">
        <button type="submit" class="btn btn-primary btn-lg">
            <i class="cil-save"></i> Record Income
        </button>
        <a href="<?php echo BASE_URL; ?>income" class="btn btn-secondary btn-lg">Cancel</a>
    </div>
</form>

<?php include APP_PATH . '/Views/layouts/footer.php'; ?>
