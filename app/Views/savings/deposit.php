<?php include APP_PATH . '/Views/layouts/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h2>Savings Deposit</h2>
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
            <div class="card-header bg-success text-white">
                <strong>Process Deposit</strong>
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
                                <th>Current Balance:</th>
                                <td><strong class="text-success"><?php echo formatCurrency($savings['balance_derived']); ?></strong></td>
                            </tr>
                            <tr>
                                <th>Total Deposits:</th>
                                <td><?php echo formatCurrency($savings['total_deposits_derived']); ?></td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td><?php echo getStatusBadge($savings['status']); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <form method="POST" action="<?php echo BASE_URL; ?>savings/deposit/<?php echo $savings['id']; ?>">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Deposit Amount <span class="text-danger">*</span></label>
                            <input type="number" class="form-control form-control-lg" name="amount" 
                                   step="0.01" min="0.01" required autofocus>
                            <small class="text-muted">Enter the amount to deposit</small>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Transaction Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="transaction_date" 
                                   value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label">Notes</label>
                            <textarea class="form-control" name="notes" rows="2" 
                                      placeholder="Receipt number, source of funds, etc."></textarea>
                        </div>
                    </div>
                    
                    <div class="alert alert-info mt-3">
                        <strong>Note:</strong> This deposit will:
                        <ul class="mb-0">
                            <li>Increase the account balance</li>
                            <li>Create a deposit transaction</li>
                            <li>Update total deposits</li>
                        </ul>
                    </div>
                    
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="cil-plus"></i> Process Deposit
                    </button>
                    <a href="<?php echo BASE_URL; ?>savings/view/<?php echo $savings['id']; ?>" class="btn btn-secondary btn-lg">Cancel</a>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <strong>Recent Transactions</strong>
            </div>
            <div class="card-body">
                <p class="text-muted small">Last 5 transactions</p>
                <!-- Transaction list would go here -->
                <p class="text-center text-muted">View full history in account details</p>
            </div>
        </div>
    </div>
</div>

<?php include APP_PATH . '/Views/layouts/footer.php'; ?>
