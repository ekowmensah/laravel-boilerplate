<?php include APP_PATH . '/Views/layouts/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h2>Approve Loan</h2>
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
            <div class="card-header bg-success text-white">
                <strong>Loan Approval</strong>
            </div>
            <div class="card-body">
                <!-- Loan Summary -->
                <table class="table table-bordered">
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
                        <th>Applied Amount:</th>
                        <td><?php echo formatCurrency($loan['principal']); ?></td>
                    </tr>
                    <tr>
                        <th>Interest Rate:</th>
                        <td><?php echo $loan['interest_rate']; ?>% <?php echo ucfirst($loan['interest_rate_type']); ?></td>
                    </tr>
                    <tr>
                        <th>Loan Term:</th>
                        <td><?php echo $loan['loan_term']; ?> installments</td>
                    </tr>
                    <tr>
                        <th>Repayment:</th>
                        <td>Every <?php echo $loan['repayment_frequency']; ?> <?php echo $loan['repayment_frequency_type']; ?></td>
                    </tr>
                    <tr>
                        <th>Status:</th>
                        <td><?php echo getStatusBadge($loan['status']); ?></td>
                    </tr>
                </table>
                
                <form method="POST" action="<?php echo BASE_URL; ?>loans/approve/<?php echo $loan['id']; ?>">
                    <div class="mb-3">
                        <label class="form-label">Approved Amount</label>
                        <input type="number" class="form-control" name="approved_amount" 
                               value="<?php echo $loan['principal']; ?>" step="0.01" required>
                        <small class="text-muted">You can approve a different amount than applied</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Approval Notes</label>
                        <textarea class="form-control" name="notes" rows="3"></textarea>
                    </div>
                    
                    <div class="alert alert-info">
                        <strong>Note:</strong> Once approved, the loan can be disbursed.
                    </div>
                    
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="cil-check"></i> Approve Loan
                    </button>
                    <a href="<?php echo BASE_URL; ?>loans/view/<?php echo $loan['id']; ?>" class="btn btn-secondary btn-lg">Cancel</a>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <strong>Approval Checklist</strong>
            </div>
            <div class="card-body">
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="check1">
                    <label class="form-check-label" for="check1">
                        Client information verified
                    </label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="check2">
                    <label class="form-check-label" for="check2">
                        Credit history reviewed
                    </label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="check3">
                    <label class="form-check-label" for="check3">
                        Repayment capacity assessed
                    </label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="check4">
                    <label class="form-check-label" for="check4">
                        Documents complete
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include APP_PATH . '/Views/layouts/footer.php'; ?>
