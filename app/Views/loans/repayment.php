<?php include APP_PATH . '/Views/layouts/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h2>Loan Repayment</h2>
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
            <div class="card-header bg-warning text-dark">
                <strong>Process Repayment</strong>
            </div>
            <div class="card-body">
                <!-- Loan Summary -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <table class="table table-sm table-bordered">
                            <tr>
                                <th>Account:</th>
                                <td><?php echo htmlspecialchars($loan['account_number'] ?? 'N/A'); ?></td>
                            </tr>
                            <tr>
                                <th>Client:</th>
                                <td><?php echo htmlspecialchars(($loan['first_name'] ?? '') . ' ' . ($loan['last_name'] ?? '')); ?></td>
                            </tr>
                            <tr>
                                <th>Product:</th>
                                <td><?php echo htmlspecialchars($loan['product_name'] ?? 'N/A'); ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm table-bordered">
                            <tr>
                                <th>Principal Outstanding:</th>
                                <td><strong><?php echo formatCurrency($loan['principal_outstanding_derived']); ?></strong></td>
                            </tr>
                            <tr>
                                <th>Interest Outstanding:</th>
                                <td><strong><?php echo formatCurrency($loan['interest_outstanding_derived']); ?></strong></td>
                            </tr>
                            <tr>
                                <th>Total Outstanding:</th>
                                <td><strong class="text-danger"><?php echo formatCurrency($loan['total_outstanding_derived']); ?></strong></td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <form method="POST" action="<?php echo BASE_URL; ?>loans/repayment/<?php echo $loan['id']; ?>" id="repaymentForm">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Payment Amount <span class="text-danger">*</span></label>
                            <input type="number" class="form-control form-control-lg" name="amount" 
                                   id="paymentAmount" step="0.01" min="0.01" required autofocus>
                            <small class="text-muted">Enter the amount received from client</small>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Payment Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="payment_date" 
                                   value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label">Payment Notes</label>
                            <textarea class="form-control" name="notes" rows="2" 
                                      placeholder="Receipt number, payment method, etc."></textarea>
                        </div>
                    </div>
                    
                    <!-- Payment Allocation Preview -->
                    <div class="card mt-3 bg-light">
                        <div class="card-body">
                            <h6 class="card-title">Payment Allocation (Preview)</h6>
                            <p class="text-muted small">This shows how the payment will be allocated:</p>
                            <table class="table table-sm mb-0">
                                <tr>
                                    <th>1. Penalties:</th>
                                    <td class="text-end"><?php echo formatCurrency($loan['penalties_outstanding_derived']); ?></td>
                                </tr>
                                <tr>
                                    <th>2. Fees:</th>
                                    <td class="text-end"><?php echo formatCurrency($loan['fees_outstanding_derived']); ?></td>
                                </tr>
                                <tr>
                                    <th>3. Interest:</th>
                                    <td class="text-end"><?php echo formatCurrency($loan['interest_outstanding_derived']); ?></td>
                                </tr>
                                <tr>
                                    <th>4. Principal:</th>
                                    <td class="text-end"><?php echo formatCurrency($loan['principal_outstanding_derived']); ?></td>
                                </tr>
                                <tr class="table-active">
                                    <th>Total Outstanding:</th>
                                    <th class="text-end"><?php echo formatCurrency($loan['total_outstanding_derived']); ?></th>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <div class="alert alert-info mt-3">
                        <strong>Payment Allocation Order:</strong>
                        <ol class="mb-0">
                            <li>Penalties (if any)</li>
                            <li>Fees (if any)</li>
                            <li>Interest</li>
                            <li>Principal</li>
                            <li>Overpayment (if amount exceeds total due)</li>
                        </ol>
                    </div>
                    
                    <button type="submit" class="btn btn-warning btn-lg">
                        <i class="cil-credit-card"></i> Process Repayment
                    </button>
                    <button type="button" class="btn btn-success btn-lg" onclick="payFull()">
                        <i class="cil-dollar"></i> Pay Full Amount
                    </button>
                    <a href="<?php echo BASE_URL; ?>loans/view/<?php echo $loan['id']; ?>" class="btn btn-secondary btn-lg">Cancel</a>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <strong>Quick Amounts</strong>
            </div>
            <div class="card-body">
                <button type="button" class="btn btn-outline-primary btn-sm w-100 mb-2" 
                        onclick="setAmount(<?php echo $loan['total_outstanding_derived']; ?>)">
                    Full Amount: <?php echo formatCurrency($loan['total_outstanding_derived']); ?>
                </button>
                <button type="button" class="btn btn-outline-secondary btn-sm w-100 mb-2" 
                        onclick="setAmount(<?php echo $loan['principal_outstanding_derived']; ?>)">
                    Principal Only: <?php echo formatCurrency($loan['principal_outstanding_derived']); ?>
                </button>
                <button type="button" class="btn btn-outline-info btn-sm w-100 mb-2" 
                        onclick="setAmount(<?php echo $loan['interest_outstanding_derived']; ?>)">
                    Interest Only: <?php echo formatCurrency($loan['interest_outstanding_derived']); ?>
                </button>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <strong>Repayment History</strong>
            </div>
            <div class="card-body">
                <p class="mb-1"><strong>Total Repaid:</strong> <?php echo formatCurrency($loan['total_repaid_derived']); ?></p>
                <p class="mb-1"><strong>Principal Repaid:</strong> <?php echo formatCurrency($loan['principal_repaid_derived']); ?></p>
                <p class="mb-0"><strong>Interest Repaid:</strong> <?php echo formatCurrency($loan['interest_repaid_derived']); ?></p>
            </div>
        </div>
    </div>
</div>

<script>
function setAmount(amount) {
    document.getElementById('paymentAmount').value = amount.toFixed(2);
}

function payFull() {
    setAmount(<?php echo $loan['total_outstanding_derived']; ?>);
    document.getElementById('repaymentForm').submit();
}
</script>

<?php include APP_PATH . '/Views/layouts/footer.php'; ?>
