<?php include APP_PATH . '/Views/layouts/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h2>Create New Loan</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?php echo BASE_URL; ?>loans" class="btn btn-secondary">
            <i class="cil-arrow-left"></i> Back to Loans
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

<form method="POST" action="<?php echo BASE_URL; ?>loans/create" id="loanForm">
    <div class="row g-3">
        <!-- Client Information -->
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <strong>Client Information</strong>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Client Type <span class="text-danger">*</span></label>
                            <select class="form-select" name="client_type" id="clientType" required>
                                <option value="client">Individual</option>
                                <option value="group">Group</option>
                            </select>
                        </div>
                        
                        <div class="col-md-8" id="clientSelect">
                            <label class="form-label">Select Client <span class="text-danger">*</span></label>
                            <select class="form-select" name="client_id" id="clientId" required>
                                <option value="">-- Select Client --</option>
                                <?php foreach ($clients as $client): ?>
                                    <option value="<?php echo $client['id']; ?>">
                                        <?php echo htmlspecialchars($client['account_number'] . ' - ' . $client['first_name'] . ' ' . $client['last_name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="col-md-8 d-none" id="groupSelect">
                            <label class="form-label">Select Group <span class="text-danger">*</span></label>
                            <select class="form-select" name="group_id" id="groupId">
                                <option value="">-- Select Group --</option>
                                <?php foreach ($groups as $group): ?>
                                    <option value="<?php echo $group['id']; ?>">
                                        <?php echo htmlspecialchars($group['account_number'] . ' - ' . $group['first_name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Loan Details -->
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <strong>Loan Details</strong>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Loan Product <span class="text-danger">*</span></label>
                            <select class="form-select" name="loan_product_id" id="loanProduct" required>
                                <option value="">-- Select Product --</option>
                                <?php foreach ($loanProducts as $product): ?>
                                    <option value="<?php echo $product['id']; ?>" 
                                            data-min="<?php echo $product['minimum_principal']; ?>"
                                            data-max="<?php echo $product['maximum_principal']; ?>"
                                            data-rate="<?php echo $product['default_interest_rate']; ?>"
                                            data-term="<?php echo $product['default_loan_term']; ?>"
                                            data-freq="<?php echo $product['repayment_frequency']; ?>"
                                            data-freq-type="<?php echo $product['repayment_frequency_type']; ?>"
                                            data-methodology="<?php echo $product['interest_methodology']; ?>"
                                            data-rate-type="<?php echo $product['interest_rate_type']; ?>">
                                        <?php echo htmlspecialchars($product['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">Principal Amount <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="principal" id="principal" 
                                   step="0.01" min="0" required>
                            <small class="text-muted" id="principalRange"></small>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">Interest Rate (%) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="interest_rate" id="interestRate" 
                                   step="0.01" min="0" required>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">Loan Term <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="loan_term" id="loanTerm" 
                                   min="1" required>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">Repayment Frequency <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="repayment_frequency" id="repaymentFreq" 
                                   min="1" required>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">Frequency Type <span class="text-danger">*</span></label>
                            <select class="form-select" name="repayment_frequency_type" id="freqType" required>
                                <option value="days">Days</option>
                                <option value="weeks">Weeks</option>
                                <option value="months">Months</option>
                                <option value="years">Years</option>
                            </select>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">Interest Methodology <span class="text-danger">*</span></label>
                            <select class="form-select" name="interest_methodology" id="methodology" required>
                                <option value="flat">Flat Rate</option>
                                <option value="declining_balance">Declining Balance</option>
                            </select>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">Interest Rate Type <span class="text-danger">*</span></label>
                            <select class="form-select" name="interest_rate_type" id="rateType" required>
                                <option value="day">Per Day</option>
                                <option value="week">Per Week</option>
                                <option value="month">Per Month</option>
                                <option value="year">Per Year</option>
                            </select>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">Loan Purpose <span class="text-danger">*</span></label>
                            <select class="form-select" name="loan_purpose_id" required>
                                <option value="">-- Select Purpose --</option>
                                <?php foreach ($loanPurposes as $purpose): ?>
                                    <option value="<?php echo $purpose['id']; ?>">
                                        <?php echo htmlspecialchars($purpose['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Grace Periods -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <strong>Grace Periods (Optional)</strong>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Grace on Principal Paid</label>
                            <input type="number" class="form-control" name="grace_on_principal_paid" 
                                   value="0" min="0">
                            <small class="text-muted">Number of installments</small>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">Grace on Interest Paid</label>
                            <input type="number" class="form-control" name="grace_on_interest_paid" 
                                   value="0" min="0">
                            <small class="text-muted">Number of installments</small>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">Grace on Interest Charged</label>
                            <input type="number" class="form-control" name="grace_on_interest_charged" 
                                   value="0" min="0">
                            <small class="text-muted">Number of installments</small>
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
                            <label class="form-label">Loan Officer <span class="text-danger">*</span></label>
                            <select class="form-select" name="loan_officer_id" required>
                                <option value="">-- Select Officer --</option>
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
        
        <!-- Dates -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <strong>Expected Dates</strong>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Expected Disbursement Date</label>
                            <input type="date" class="form-control" name="expected_disbursement_date" 
                                   value="<?php echo date('Y-m-d'); ?>">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">First Payment Date</label>
                            <input type="date" class="form-control" name="first_payment_date">
                            <small class="text-muted">Leave empty to calculate automatically</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Submit -->
        <div class="col-12">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="cil-save"></i> Submit Loan Application
            </button>
            <a href="<?php echo BASE_URL; ?>loans" class="btn btn-secondary btn-lg">Cancel</a>
        </div>
    </div>
</form>

<script>
// Client type toggle
document.getElementById('clientType').addEventListener('change', function() {
    const clientSelect = document.getElementById('clientSelect');
    const groupSelect = document.getElementById('groupSelect');
    const clientId = document.getElementById('clientId');
    const groupId = document.getElementById('groupId');
    
    if (this.value === 'group') {
        clientSelect.classList.add('d-none');
        groupSelect.classList.remove('d-none');
        clientId.removeAttribute('required');
        groupId.setAttribute('required', 'required');
    } else {
        clientSelect.classList.remove('d-none');
        groupSelect.classList.add('d-none');
        clientId.setAttribute('required', 'required');
        groupId.removeAttribute('required');
    }
});

// Product selection auto-fill
document.getElementById('loanProduct').addEventListener('change', function() {
    const option = this.options[this.selectedIndex];
    if (option.value) {
        document.getElementById('principal').min = option.dataset.min;
        document.getElementById('principal').max = option.dataset.max;
        document.getElementById('interestRate').value = option.dataset.rate;
        document.getElementById('loanTerm').value = option.dataset.term;
        document.getElementById('repaymentFreq').value = option.dataset.freq;
        document.getElementById('freqType').value = option.dataset.freqType;
        document.getElementById('methodology').value = option.dataset.methodology;
        document.getElementById('rateType').value = option.dataset.rateType;
        
        document.getElementById('principalRange').textContent = 
            `Range: ${parseFloat(option.dataset.min).toLocaleString()} - ${parseFloat(option.dataset.max).toLocaleString()}`;
    }
});

// Form validation
document.getElementById('loanForm').addEventListener('submit', function(e) {
    const principal = parseFloat(document.getElementById('principal').value);
    const min = parseFloat(document.getElementById('principal').min);
    const max = parseFloat(document.getElementById('principal').max);
    
    if (principal < min || principal > max) {
        e.preventDefault();
        alert(`Principal amount must be between ${min.toLocaleString()} and ${max.toLocaleString()}`);
        return false;
    }
});
</script>

<?php include APP_PATH . '/Views/layouts/footer.php'; ?>
