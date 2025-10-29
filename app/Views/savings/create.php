<?php include APP_PATH . '/Views/layouts/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h2>Create Savings Account</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?php echo BASE_URL; ?>savings" class="btn btn-secondary">
            <i class="cil-arrow-left"></i> Back to Savings
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

<form method="POST" action="<?php echo BASE_URL; ?>savings/create" id="savingsForm">
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
                                <!-- Groups will be loaded here -->
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Savings Product -->
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <strong>Savings Product</strong>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Savings Product <span class="text-danger">*</span></label>
                            <select class="form-select" name="savings_product_id" id="savingsProduct" required>
                                <option value="">-- Select Product --</option>
                                <?php foreach ($products as $product): ?>
                                    <option value="<?php echo $product['id']; ?>"
                                            data-rate="<?php echo $product['default_interest_rate']; ?>"
                                            data-lockin="<?php echo $product['lockin_period']; ?>"
                                            data-lockin-type="<?php echo $product['lockin_type']; ?>"
                                            data-min-balance="<?php echo $product['minimum_balance']; ?>">
                                        <?php echo htmlspecialchars($product['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Interest Rate (%)</label>
                            <input type="number" class="form-control" name="interest_rate" id="interestRate" 
                                   step="0.01" min="0" readonly>
                            <small class="text-muted">From product settings</small>
                        </div>
                    </div>
                    
                    <div class="alert alert-info mt-3" id="productInfo" style="display:none;">
                        <strong>Product Details:</strong>
                        <ul class="mb-0">
                            <li>Interest Rate: <span id="infoRate"></span>% per year</li>
                            <li>Lock-in Period: <span id="infoLockin"></span></li>
                            <li>Minimum Balance: <span id="infoMinBalance"></span></li>
                        </ul>
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
                            <label class="form-label">Savings Officer</label>
                            <select class="form-select" name="savings_officer_id">
                                <option value="">-- Select Officer (Optional) --</option>
                                <?php foreach ($officers as $officer): ?>
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
        
        <!-- Submit -->
        <div class="col-12">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="cil-save"></i> Create Savings Account
            </button>
            <a href="<?php echo BASE_URL; ?>savings" class="btn btn-secondary btn-lg">Cancel</a>
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

// Product selection
document.getElementById('savingsProduct').addEventListener('change', function() {
    const option = this.options[this.selectedIndex];
    const productInfo = document.getElementById('productInfo');
    
    if (option.value) {
        document.getElementById('interestRate').value = option.dataset.rate;
        document.getElementById('infoRate').textContent = option.dataset.rate;
        document.getElementById('infoLockin').textContent = option.dataset.lockin + ' ' + option.dataset.lockinType;
        document.getElementById('infoMinBalance').textContent = parseFloat(option.dataset.minBalance).toLocaleString();
        productInfo.style.display = 'block';
    } else {
        productInfo.style.display = 'none';
    }
});
</script>

<?php include APP_PATH . '/Views/layouts/footer.php'; ?>
