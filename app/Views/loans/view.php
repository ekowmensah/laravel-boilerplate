<?php include APP_PATH . '/Views/layouts/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h2>Loan Details</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?php echo BASE_URL; ?>loans" class="btn btn-secondary">
            <i class="cil-arrow-left"></i> Back to Loans
        </a>
        <?php if ($loan['status'] === 'submitted'): ?>
            <a href="<?php echo BASE_URL; ?>loans/approve/<?php echo $loan['id']; ?>" class="btn btn-success">
                <i class="cil-check"></i> Approve
            </a>
        <?php elseif ($loan['status'] === 'approved'): ?>
            <a href="<?php echo BASE_URL; ?>loans/disburse/<?php echo $loan['id']; ?>" class="btn btn-primary">
                <i class="cil-dollar"></i> Disburse
            </a>
        <?php elseif ($loan['status'] === 'active'): ?>
            <a href="<?php echo BASE_URL; ?>loans/repayment/<?php echo $loan['id']; ?>" class="btn btn-success">
                <i class="cil-money"></i> Record Payment
            </a>
        <?php endif; ?>
    </div>
</div>

<div class="row">
    <!-- Loan Information -->
    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-header bg-primary text-white">
                <strong>Loan Information</strong>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="30%">Account Number:</th>
                        <td><strong><?php echo htmlspecialchars($loan['account_number'] ?? 'N/A'); ?></strong></td>
                    </tr>
                    <tr>
                        <th>Client:</th>
                        <td>
                            <a href="<?php echo BASE_URL; ?>clients/viewClient/<?php echo $loan['client_id']; ?>">
                                <?php echo htmlspecialchars(($loan['first_name'] ?? '') . ' ' . ($loan['last_name'] ?? '')); ?>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <th>Product:</th>
                        <td><?php echo htmlspecialchars($loan['product_name'] ?? 'N/A'); ?></td>
                    </tr>
                    <tr>
                        <th>Status:</th>
                        <td><?php echo getStatusBadge($loan['status'] ?? 'submitted'); ?></td>
                    </tr>
                    <tr>
                        <th>Principal Amount:</th>
                        <td><strong class="text-primary"><?php echo formatCurrency($loan['principal'] ?? 0); ?></strong></td>
                    </tr>
                    <tr>
                        <th>Interest Rate:</th>
                        <td><?php echo ($loan['interest_rate'] ?? 0) . '% per ' . ($loan['interest_rate_type'] ?? 'year'); ?></td>
                    </tr>
                    <tr>
                        <th>Loan Term:</th>
                        <td><?php echo ($loan['loan_term'] ?? 0) . ' ' . ($loan['repayment_frequency_type'] ?? 'months'); ?></td>
                    </tr>
                    <tr>
                        <th>Repayment:</th>
                        <td>Every <?php echo ($loan['repayment_frequency'] ?? 1) . ' ' . ($loan['repayment_frequency_type'] ?? 'month'); ?></td>
                    </tr>
                    <tr>
                        <th>Branch:</th>
                        <td><?php echo htmlspecialchars($loan['branch_name'] ?? 'N/A'); ?></td>
                    </tr>
                    <tr>
                        <th>Loan Officer:</th>
                        <td><?php echo htmlspecialchars(($loan['officer_first_name'] ?? '') . ' ' . ($loan['officer_last_name'] ?? '')); ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Loan Dates -->
        <div class="card mb-3">
            <div class="card-header"><strong>Important Dates</strong></div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="30%">Submitted Date:</th>
                        <td><?php echo isset($loan['submitted_on_date']) ? date('M d, Y', strtotime($loan['submitted_on_date'])) : 'N/A'; ?></td>
                    </tr>
                    <?php if (isset($loan['approved_on_date'])): ?>
                    <tr>
                        <th>Approved Date:</th>
                        <td><?php echo date('M d, Y', strtotime($loan['approved_on_date'])); ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if (isset($loan['disbursed_on_date'])): ?>
                    <tr>
                        <th>Disbursed Date:</th>
                        <td><?php echo date('M d, Y', strtotime($loan['disbursed_on_date'])); ?></td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <th>Expected Disbursement:</th>
                        <td><?php echo isset($loan['expected_disbursement_date']) ? date('M d, Y', strtotime($loan['expected_disbursement_date'])) : 'N/A'; ?></td>
                    </tr>
                    <tr>
                        <th>Expected Maturity:</th>
                        <td><?php echo isset($loan['expected_maturity_date']) ? date('M d, Y', strtotime($loan['expected_maturity_date'])) : 'N/A'; ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header"><strong>Quick Actions</strong></div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-md-4">
                        <a href="<?php echo BASE_URL; ?>loans/schedule/<?php echo $loan['id']; ?>" class="btn btn-info w-100">
                            <i class="cil-calendar"></i> Repayment Schedule
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="<?php echo BASE_URL; ?>loans/transactions/<?php echo $loan['id']; ?>" class="btn btn-warning w-100">
                            <i class="cil-list"></i> Transaction History
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="<?php echo BASE_URL; ?>loan-guarantors/index/<?php echo $loan['id']; ?>" class="btn btn-secondary w-100">
                            <i class="cil-people"></i> Guarantors
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="<?php echo BASE_URL; ?>loan-collateral/index/<?php echo $loan['id']; ?>" class="btn btn-secondary w-100">
                            <i class="cil-briefcase"></i> Collateral
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="<?php echo BASE_URL; ?>loan-files/index/<?php echo $loan['id']; ?>" class="btn btn-secondary w-100">
                            <i class="cil-file"></i> Documents
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="<?php echo BASE_URL; ?>loan-charges" class="btn btn-secondary w-100">
                            <i class="cil-money"></i> Charges
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary -->
    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header bg-success text-white">
                <strong>Loan Summary</strong>
            </div>
            <div class="card-body">
                <p class="mb-2"><strong>Principal:</strong><br><?php echo formatCurrency($loan['principal'] ?? 0); ?></p>
                <p class="mb-2"><strong>Disbursed:</strong><br><?php echo formatCurrency($loan['principal_disbursed_derived'] ?? 0); ?></p>
                <p class="mb-2"><strong>Outstanding:</strong><br><span class="text-danger"><?php echo formatCurrency($loan['principal_outstanding_derived'] ?? 0); ?></span></p>
                <p class="mb-2"><strong>Repaid:</strong><br><span class="text-success"><?php echo formatCurrency($loan['principal_repaid_derived'] ?? 0); ?></span></p>
                <hr>
                <p class="mb-2"><strong>Interest:</strong><br><?php echo formatCurrency($loan['interest_charged_derived'] ?? 0); ?></p>
                <p class="mb-2"><strong>Interest Repaid:</strong><br><?php echo formatCurrency($loan['interest_repaid_derived'] ?? 0); ?></p>
                <p class="mb-0"><strong>Interest Outstanding:</strong><br><?php echo formatCurrency($loan['interest_outstanding_derived'] ?? 0); ?></p>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><strong>Status</strong></div>
            <div class="card-body text-center">
                <h3><?php echo getStatusBadge($loan['status'] ?? 'submitted'); ?></h3>
                <?php if ($loan['status'] === 'active'): ?>
                    <p class="text-muted mt-2">Loan is active and accepting payments</p>
                <?php elseif ($loan['status'] === 'submitted'): ?>
                    <p class="text-muted mt-2">Awaiting approval</p>
                <?php elseif ($loan['status'] === 'approved'): ?>
                    <p class="text-muted mt-2">Approved, awaiting disbursement</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include APP_PATH . '/Views/layouts/footer.php'; ?>
