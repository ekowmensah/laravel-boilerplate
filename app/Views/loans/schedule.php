<?php include APP_PATH . '/Views/layouts/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h2>Loan Repayment Schedule</h2>
        <p class="text-muted">Loan: <?php echo htmlspecialchars($loan['account_number'] ?? ''); ?></p>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?php echo BASE_URL; ?>loans/view/<?php echo $loan['id']; ?>" class="btn btn-secondary">
            <i class="cil-arrow-left"></i> Back to Loan
        </a>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h6 class="text-muted">Total Installments</h6>
                <h3><?php echo count($schedule ?? []); ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h6 class="text-muted">Paid</h6>
                <h3 class="text-success"><?php echo $paidCount ?? 0; ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h6 class="text-muted">Pending</h6>
                <h3 class="text-warning"><?php echo $pendingCount ?? 0; ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h6 class="text-muted">Overdue</h6>
                <h3 class="text-danger"><?php echo $overdueCount ?? 0; ?></h3>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header"><strong>Repayment Schedule</strong></div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Due Date</th>
                        <th>Principal</th>
                        <th>Interest</th>
                        <th>Fees</th>
                        <th>Penalties</th>
                        <th>Total Due</th>
                        <th>Paid</th>
                        <th>Balance</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($schedule)): ?>
                        <?php foreach ($schedule as $index => $installment): ?>
                            <?php
                                $totalDue = ($installment['principal'] ?? 0) + ($installment['interest'] ?? 0) + 
                                           ($installment['fees'] ?? 0) + ($installment['penalties'] ?? 0);
                                $totalPaid = ($installment['principal_repaid_derived'] ?? 0) + 
                                            ($installment['interest_repaid_derived'] ?? 0) + 
                                            ($installment['fees_repaid_derived'] ?? 0) + 
                                            ($installment['penalties_repaid_derived'] ?? 0);
                                $balance = $totalDue - $totalPaid;
                                
                                $isOverdue = (!empty($installment['due_date']) && strtotime($installment['due_date']) < time()) && $balance > 0;
                                $isPaid = $balance <= 0;
                                
                                $rowClass = $isPaid ? 'table-success' : ($isOverdue ? 'table-danger' : '');
                            ?>
                            <tr class="<?php echo $rowClass; ?>">
                                <td><?php echo $index + 1; ?></td>
                                <td><?php echo !empty($installment['due_date']) ? date('M d, Y', strtotime($installment['due_date'])) : 'N/A'; ?></td>
                                <td><?php echo formatCurrency($installment['principal'] ?? 0); ?></td>
                                <td><?php echo formatCurrency($installment['interest'] ?? 0); ?></td>
                                <td><?php echo formatCurrency($installment['fees'] ?? 0); ?></td>
                                <td><?php echo formatCurrency($installment['penalties'] ?? 0); ?></td>
                                <td><strong><?php echo formatCurrency($totalDue); ?></strong></td>
                                <td class="text-success"><?php echo formatCurrency($totalPaid); ?></td>
                                <td><strong><?php echo formatCurrency($balance); ?></strong></td>
                                <td>
                                    <?php if ($isPaid): ?>
                                        <span class="badge bg-success">Paid</span>
                                    <?php elseif ($isOverdue): ?>
                                        <span class="badge bg-danger">Overdue</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning">Pending</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10" class="text-center">No repayment schedule found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
                <?php if (!empty($schedule)): ?>
                    <tfoot class="table-light">
                        <tr>
                            <th colspan="2">TOTAL</th>
                            <th><?php echo formatCurrency(array_sum(array_column($schedule, 'principal'))); ?></th>
                            <th><?php echo formatCurrency(array_sum(array_column($schedule, 'interest'))); ?></th>
                            <th><?php echo formatCurrency(array_sum(array_column($schedule, 'fees'))); ?></th>
                            <th><?php echo formatCurrency(array_sum(array_column($schedule, 'penalties'))); ?></th>
                            <th><strong><?php echo formatCurrency(
                                array_sum(array_column($schedule, 'principal')) +
                                array_sum(array_column($schedule, 'interest')) +
                                array_sum(array_column($schedule, 'fees')) +
                                array_sum(array_column($schedule, 'penalties'))
                            ); ?></strong></th>
                            <th colspan="3"></th>
                        </tr>
                    </tfoot>
                <?php endif; ?>
            </table>
        </div>
    </div>
</div>

<?php include APP_PATH . '/Views/layouts/footer.php'; ?>
