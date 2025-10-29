<?php include APP_PATH . '/Views/layouts/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h2>Add New Client</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?php echo BASE_URL; ?>clients" class="btn btn-secondary">
            <i class="cil-arrow-left"></i> Back to Clients
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

<div class="card">
    <div class="card-header">
        <strong>Client Information</strong>
    </div>
    <div class="card-body">
        <form method="POST" action="<?php echo BASE_URL; ?>clients/create">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="first_name" name="first_name" required 
                           value="<?php echo htmlspecialchars($_POST['first_name'] ?? ''); ?>">
                </div>
                
                <div class="col-md-4">
                    <label for="middle_name" class="form-label">Middle Name</label>
                    <input type="text" class="form-control" id="middle_name" name="middle_name"
                           value="<?php echo htmlspecialchars($_POST['middle_name'] ?? ''); ?>">
                </div>
                
                <div class="col-md-4">
                    <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="last_name" name="last_name" required
                           value="<?php echo htmlspecialchars($_POST['last_name'] ?? ''); ?>">
                </div>
                
                <div class="col-md-4">
                    <label for="client_type_id" class="form-label">Client Type <span class="text-danger">*</span></label>
                    <select class="form-select" id="client_type_id" name="client_type_id" required>
                        <option value="">Select Type</option>
                        <?php foreach ($clientTypes as $type): ?>
                            <option value="<?php echo $type['id']; ?>"
                                <?php echo (($_POST['client_type_id'] ?? '') == $type['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($type['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="col-md-4">
                    <label for="gender" class="form-label">Gender</label>
                    <select class="form-select" id="gender" name="gender">
                        <option value="unspecified">Unspecified</option>
                        <option value="male" <?php echo (($_POST['gender'] ?? '') == 'male') ? 'selected' : ''; ?>>Male</option>
                        <option value="female" <?php echo (($_POST['gender'] ?? '') == 'female') ? 'selected' : ''; ?>>Female</option>
                        <option value="other" <?php echo (($_POST['gender'] ?? '') == 'other') ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>
                
                <div class="col-md-4">
                    <label for="dob" class="form-label">Date of Birth</label>
                    <input type="date" class="form-control" id="dob" name="dob"
                           value="<?php echo htmlspecialchars($_POST['dob'] ?? ''); ?>">
                </div>
                
                <div class="col-md-4">
                    <label for="mobile" class="form-label">Mobile Number</label>
                    <input type="tel" class="form-control" id="mobile" name="mobile"
                           value="<?php echo htmlspecialchars($_POST['mobile'] ?? ''); ?>">
                </div>
                
                <div class="col-md-4">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email"
                           value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                </div>
                
                <div class="col-md-4">
                    <label for="branch_id" class="form-label">Branch <span class="text-danger">*</span></label>
                    <select class="form-select" id="branch_id" name="branch_id" required>
                        <option value="">Select Branch</option>
                        <?php foreach ($branches as $branch): ?>
                            <option value="<?php echo $branch['id']; ?>"
                                <?php echo (($_POST['branch_id'] ?? '') == $branch['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($branch['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="col-md-4">
                    <label for="loan_officer_id" class="form-label">Loan Officer</label>
                    <select class="form-select" id="loan_officer_id" name="loan_officer_id">
                        <option value="">Select Officer</option>
                        <?php foreach ($loanOfficers as $officer): ?>
                            <option value="<?php echo $officer['id']; ?>"
                                <?php echo (($_POST['loan_officer_id'] ?? '') == $officer['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($officer['first_name'] . ' ' . $officer['last_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="col-md-12">
                    <label for="address" class="form-label">Address</label>
                    <textarea class="form-control" id="address" name="address" rows="2"><?php echo htmlspecialchars($_POST['address'] ?? ''); ?></textarea>
                </div>
                
                <div class="col-md-4">
                    <label for="city" class="form-label">City</label>
                    <input type="text" class="form-control" id="city" name="city"
                           value="<?php echo htmlspecialchars($_POST['city'] ?? ''); ?>">
                </div>
                
                <div class="col-md-4">
                    <label for="state" class="form-label">State/Region</label>
                    <input type="text" class="form-control" id="state" name="state"
                           value="<?php echo htmlspecialchars($_POST['state'] ?? ''); ?>">
                </div>
                
                <div class="col-md-4">
                    <label for="employer" class="form-label">Employer</label>
                    <input type="text" class="form-control" id="employer" name="employer"
                           value="<?php echo htmlspecialchars($_POST['employer'] ?? ''); ?>">
                </div>
                
                <div class="col-md-12">
                    <label for="notes" class="form-label">Notes</label>
                    <textarea class="form-control" id="notes" name="notes" rows="3"><?php echo htmlspecialchars($_POST['notes'] ?? ''); ?></textarea>
                </div>
                
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="cil-save"></i> Save Client
                    </button>
                    <a href="<?php echo BASE_URL; ?>clients" class="btn btn-secondary">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>

<?php include APP_PATH . '/Views/layouts/footer.php'; ?>
