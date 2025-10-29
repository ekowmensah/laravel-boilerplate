<?php include APP_PATH . '/Views/layouts/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h2>Create Custom Field</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?php echo BASE_URL; ?>custom-fields" class="btn btn-secondary">
            <i class="cil-arrow-left"></i> Back
        </a>
    </div>
</div>

<form method="POST" action="<?php echo BASE_URL; ?>custom-fields/create">
    <div class="card">
        <div class="card-header"><strong>Field Details</strong></div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Category <span class="text-danger">*</span></label>
                    <select class="form-select" name="category" required>
                        <option value="">-- Select Category --</option>
                        <option value="client">Client</option>
                        <option value="loan">Loan</option>
                        <option value="savings">Savings</option>
                        <option value="group">Group</option>
                    </select>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Field Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="name" required>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Field Type <span class="text-danger">*</span></label>
                    <select class="form-select" name="type" required>
                        <option value="">-- Select Type --</option>
                        <option value="text">Text</option>
                        <option value="number">Number</option>
                        <option value="date">Date</option>
                        <option value="select">Select/Dropdown</option>
                        <option value="textarea">Textarea</option>
                        <option value="checkbox">Checkbox</option>
                    </select>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Required</label>
                    <select class="form-select" name="required">
                        <option value="0">No</option>
                        <option value="1">Yes</option>
                    </select>
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
            <i class="cil-save"></i> Create Field
        </button>
        <a href="<?php echo BASE_URL; ?>custom-fields" class="btn btn-secondary btn-lg">Cancel</a>
    </div>
</form>

<?php include APP_PATH . '/Views/layouts/footer.php'; ?>
