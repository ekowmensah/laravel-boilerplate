<?php include APP_PATH . '/Views/layouts/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h2>Edit Client</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?php echo BASE_URL; ?>clients/viewClient/<?php echo $client['id']; ?>" class="btn btn-secondary">
            <i class="cil-arrow-left"></i> Back to Client
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

<form method="POST" action="<?php echo BASE_URL; ?>clients/edit/<?php echo $client['id']; ?>">
    <div class="row g-3">
        <!-- Personal Information -->
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <strong>Personal Information</strong>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="first_name" value="<?php echo htmlspecialchars($client['first_name']); ?>" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="last_name" value="<?php echo htmlspecialchars($client['last_name']); ?>" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Gender</label>
                            <select class="form-select" name="gender">
                                <option value="male" <?php echo ($client['gender'] == 'male') ? 'selected' : ''; ?>>Male</option>
                                <option value="female" <?php echo ($client['gender'] == 'female') ? 'selected' : ''; ?>>Female</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" name="dob" value="<?php echo $client['dob']; ?>">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Mobile Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="mobile" value="<?php echo htmlspecialchars($client['mobile']); ?>" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($client['email']); ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Address Information -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <strong>Address Information</strong>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Address</label>
                            <textarea class="form-control" name="address" rows="2"><?php echo htmlspecialchars($client['address']); ?></textarea>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">City</label>
                            <input type="text" class="form-control" name="city" value="<?php echo htmlspecialchars($client['city']); ?>">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">State/Region</label>
                            <input type="text" class="form-control" name="state" value="<?php echo htmlspecialchars($client['state']); ?>">
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
                                <?php foreach ($branches as $branch): ?>
                                    <option value="<?php echo $branch['id']; ?>" <?php echo ($client['branch_id'] == $branch['id']) ? 'selected' : ''; ?>>
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
                                    <option value="<?php echo $officer['id']; ?>" <?php echo ($client['loan_officer_id'] == $officer['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($officer['first_name'] . ' ' . $officer['last_name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status">
                                <option value="active" <?php echo ($client['status'] == 'active') ? 'selected' : ''; ?>>Active</option>
                                <option value="inactive" <?php echo ($client['status'] == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Submit -->
        <div class="col-12">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="cil-save"></i> Update Client
            </button>
            <a href="<?php echo BASE_URL; ?>clients/viewClient/<?php echo $client['id']; ?>" class="btn btn-secondary btn-lg">Cancel</a>
        </div>
    </div>
</form>

<?php include APP_PATH . '/Views/layouts/footer.php'; ?>
