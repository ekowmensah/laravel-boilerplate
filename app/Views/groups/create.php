<?php include APP_PATH . '/Views/layouts/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h2>Create New Group</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?php echo BASE_URL; ?>groups" class="btn btn-secondary">
            <i class="cil-arrow-left"></i> Back to Groups
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

<form method="POST" action="<?php echo BASE_URL; ?>groups/create">
    <div class="row g-3">
        <!-- Group Information -->
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <strong>Group Information</strong>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Group Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="first_name" required>
                            <small class="text-muted">Enter the group name</small>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Group Type</label>
                            <select class="form-select" name="client_type_id">
                                <option value="">-- Select Type --</option>
                                <?php foreach ($clientTypes as $type): ?>
                                    <?php if (strtolower($type['name']) === 'group'): ?>
                                        <option value="<?php echo $type['id']; ?>" selected>
                                            <?php echo htmlspecialchars($type['name']); ?>
                                        </option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Mobile Number</label>
                            <input type="text" class="form-control" name="mobile">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email">
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label">Address</label>
                            <textarea class="form-control" name="address" rows="2"></textarea>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">City</label>
                            <input type="text" class="form-control" name="city">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">State/Region</label>
                            <input type="text" class="form-control" name="state">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Assignment -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <strong>Assignment</strong>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Branch <span class="text-danger">*</span></label>
                            <select class="form-select" name="branch_id" required>
                                <option value="">-- Select Branch --</option>
                                <?php foreach ($branches as $branch): ?>
                                    <option value="<?php echo $branch['id']; ?>">
                                        <?php echo htmlspecialchars($branch['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Loan Officer</label>
                            <select class="form-select" name="loan_officer_id">
                                <option value="">-- Select Officer (Optional) --</option>
                                <?php foreach ($loanOfficers as $officer): ?>
                                    <option value="<?php echo $officer['id']; ?>">
                                        <?php echo htmlspecialchars($officer['first_name'] . ' ' . $officer['last_name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Additional Information -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <strong>Additional Information</strong>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Notes</label>
                            <textarea class="form-control" name="notes" rows="3" 
                                      placeholder="Meeting schedule, group rules, etc."></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Submit -->
        <div class="col-12">
            <div class="alert alert-info">
                <strong>Note:</strong> After creating the group, you can add members from the group details page.
            </div>
            
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="cil-save"></i> Create Group
            </button>
            <a href="<?php echo BASE_URL; ?>groups" class="btn btn-secondary btn-lg">Cancel</a>
        </div>
    </div>
</form>

<?php include APP_PATH . '/Views/layouts/footer.php'; ?>
