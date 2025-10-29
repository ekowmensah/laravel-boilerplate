<?php include APP_PATH . '/Views/layouts/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h2>Savings Withdrawal</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?php echo BASE_URL; ?>savings/view/<?php echo $savings['id']; ?>" class="btn btn-secondary">
            <i class="cil-arrow-left"></i> Back to Account
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-warning text-dark">
                <strong>Process Withdrawal</strong>
            </div>
            <div class="card-body">
                <!-- Account Summary -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <table class="table table-sm table-bordered">
                            <tr>
                                <th>Account Number:</th>
                                <td><?php echo htmlspecialchars($savings['account_number']); ?></td>
                            </tr>
                            <tr>
                                <th>Client:</th>
                                <td><?php echo htmlspecialchars($savings['first_name'] . ' ' . $savings['last_name']); ?></td>
                            </tr>
                            <tr>
                                <th>Product:</th>
                                <td><?php echo htmlspecialchars($savings['product_name']); ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm table-bordered">
                            <tr>
                                <th>Available Balance:</th>
                                <td><strong class="text-success"><?php echo formatCurrency($savings['balance_derived']); ?></strong></td>
                            </tr>
                            <tr>
                                <th>Total Withdrawals:</th>
                                <td><?php echo formatCurrency($savings['total_withdrawals_derived']); ?></td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td><?php echo getStatusBadge($savings['status']); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <form method="POST" action="<?php echo BASE_URL; ?>savings/withdraw/<?php echo $savings['id']; ?>" id="withdrawForm">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Withdrawal Amount <span class="text-danger">*</span></label>
                            <input type="number" class="form-control form-control-lg" name="amount" id="withdrawAmount"
                                   step="0.01" min="0.01" max="<?php echo $savings['balance_derived']; ?>" required autofocus>
                            <small class="text-muted">Maximum: <?php echo formatCurrency($savings['balance_derived']); ?></small>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Transaction Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="transaction_date" 
                                   value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label">Notes</label>
                            <textarea class="form-control" name="notes" rows="2" 
                                      placeholder="Purpose of withdrawal, etc."></textarea>
                        </div>
                    </div>
                    
                    <?php if ($savings['lockin_period'] > 0): ?>
                    <div class="alert alert-warning mt-3">
                        <strong>Lock-in Period:</strong> This account has a lock-in period of 
                        <?php echo $savings['lockin_period'] . ' ' . $savings['lockin_type']; ?>.
                        Withdrawals may be restricted if the period hasn't elapsed.
                    </div>
                    <?php endif; ?>
                    
                    <div class="alert alert-info mt-3">
                        <strong>Note:</strong> This withdrawal will:
                        <ul class="mb-0">
                            <li>Decrease the account balance</li>
                            <li>Create a withdrawal transaction</li>
                            <li>Update total withdrawals</li>
                            <li>Be validated against lock-in period</li>
                        </ul>
                    </div>
                    
                    <button type="submit" class="btn btn-warning btn-lg" onclick="return confirm('Confirm withdrawal of ' + document.getElementById('withdrawAmount').value + '?')">
                        <i class="cil-minus"></i> Process Withdrawal
                    </button>
                    <button type="button" class="btn btn-danger btn-lg" onclick="withdrawFull()">
                        <i class="cil-dollar"></i> Withdraw Full Balance
                    </button>
                    <a href="<?php echo BASE_URL; ?>savings/view/<?php echo $savings['id']; ?>" class="btn btn-secondary btn-lg">Cancel</a>
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
                        onclick="setAmount(<?php echo $savings['balance_derived']; ?>)">
                    Full Balance: <?php echo formatCurrency($savings['balance_derived']); ?>
                </button>
                <button type="button" class="btn btn-outline-secondary btn-sm w-100 mb-2" 
                        onclick="setAmount(<?php echo $savings['balance_derived'] / 2; ?>)">
                    Half Balance: <?php echo formatCurrency($savings['balance_derived'] / 2); ?>
                </button>
                <button type="button" class="btn btn-outline-info btn-sm w-100 mb-2" 
                        onclick="setAmount(<?php echo $savings['balance_derived'] / 4; ?>)">
                    Quarter Balance: <?php echo formatCurrency($savings['balance_derived'] / 4); ?>
                </button>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <strong>Account Info</strong>
            </div>
            <div class="card-body">
                <p class="mb-1"><strong>Activated:</strong> <?php echo date('M d, Y', strtotime($savings['activated_on_date'])); ?></p>
                <p class="mb-1"><strong>Interest Rate:</strong> <?php echo $savings['interest_rate']; ?>%</p>
                <p class="mb-0"><strong>Branch:</strong> <?php echo htmlspecialchars($savings['branch_name']); ?></p>
            </div>
        </div>
    </div>
</div>

<script>
function setAmount(amount) {
    document.getElementById('withdrawAmount').value = amount.toFixed(2);
}

function withdrawFull() {
    setAmount(<?php echo $savings['balance_derived']; ?>);
    if (confirm('Confirm withdrawal of full balance?')) {
        document.getElementById('withdrawForm').submit();
    }
}
</script>

<?php include APP_PATH . '/Views/layouts/footer.php'; ?>
