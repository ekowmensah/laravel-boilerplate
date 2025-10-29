<?php include APP_PATH . '/Views/layouts/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h2>Disburse Loan</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?php echo BASE_URL; ?>loans/view/<?php echo $loan['id']; ?>" class="btn btn-secondary">
            <i class="cil-arrow-left"></i> Back to Loan
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <strong>Loan Disbursement</strong>
            </div>
            <div class="card-body">
                <!-- Loan Summary -->
                <table class="table table-bordered mb-4">
                    <tr>
                        <th width="40%">Account Number:</th>
                        <td><?php echo htmlspecialchars($loan['account_number']); ?></td>
                    </tr>
                    <tr>
                        <th>Client:</th>
                        <td><?php echo htmlspecialchars($loan['first_name'] . ' ' . $loan['last_name']); ?></td>
                    </tr>
                    <tr>
                        <th>Product:</th>
                        <td><?php echo htmlspecialchars($loan['product_name']); ?></td>
                    </tr>
                    <tr>
                        <th>Approved Amount:</th>
                        <td><strong class="text-success"><?php echo formatCurrency($loan['approved_amount'] ?? $loan['principal']); ?></strong></td>
                    </tr>
                    <tr>
                        <th>Interest Rate:</th>
                        <td><?php echo $loan['interest_rate']; ?>% (<?php echo ucfirst($loan['interest_methodology']); ?>)</td>
                    </tr>
                    <tr>
                        <th>Loan Term:</th>
                        <td><?php echo $loan['loan_term']; ?> installments</td>
                    </tr>
                </table>
                
                <form method="POST" action="<?php echo BASE_URL; ?>loans/disburse/<?php echo $loan['id']; ?>">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Disbursement Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="disbursement_date" 
                                   value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">First Payment Date</label>
                            <input type="date" class="form-control" name="first_payment_date">
                            <small class="text-muted">Leave empty to calculate automatically</small>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label">Disbursement Notes</label>
                            <textarea class="form-control" name="notes" rows="3"></textarea>
                        </div>
                    </div>
                    
                    <div class="alert alert-warning mt-3">
                        <strong>Important:</strong> Disbursing this loan will:
                        <ul class="mb-0">
                            <li>Generate the complete repayment schedule</li>
                            <li>Create a disbursement transaction</li>
                            <li>Change loan status to ACTIVE</li>
                            <li>Start the repayment period</li>
                        </ul>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-lg" onclick="return confirm('Are you sure you want to disburse this loan?')">
                        <i class="cil-dollar"></i> Disburse Loan
                    </button>
                    <a href="<?php echo BASE_URL; ?>loans/view/<?php echo $loan['id']; ?>" class="btn btn-secondary btn-lg">Cancel</a>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <strong>Disbursement Checklist</strong>
            </div>
            <div class="card-body">
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="check1">
                    <label class="form-check-label" for="check1">
                        Loan agreement signed
                    </label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="check2">
                    <label class="form-check-label" for="check2">
                        Guarantors confirmed
                    </label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="check3">
                    <label class="form-check-label" for="check3">
                        Collateral registered
                    </label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="check4">
                    <label class="form-check-label" for="check4">
                        Payment method confirmed
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include APP_PATH . '/Views/layouts/footer.php'; ?>
