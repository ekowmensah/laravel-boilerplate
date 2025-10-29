<?php include APP_PATH . '/Views/layouts/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h2>Expense Management</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?php echo BASE_URL; ?>expenses/create" class="btn btn-primary">
            <i class="cil-plus"></i> Record Expense
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header"><strong>All Expenses</strong></div>
    <div class="card-body">
        <table class="table table-striped datatable">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Branch</th>
                    <th>Amount</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($expenses)): ?>
                    <?php foreach ($expenses as $expense): ?>
                    <tr>
                        <td><?php echo isset($expense['date']) ? date('M d, Y', strtotime($expense['date'])) : 'N/A'; ?></td>
                        <td><?php echo htmlspecialchars($expense['expense_type_name'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($expense['branch_name'] ?? 'N/A'); ?></td>
                        <td><strong><?php echo formatCurrency($expense['amount'] ?? 0); ?></strong></td>
                        <td><?php echo htmlspecialchars($expense['description'] ?? ''); ?></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="<?php echo BASE_URL; ?>expenses/view/<?php echo $expense['id']; ?>" class="btn btn-info">
                                    <i class="cil-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">No expenses found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include APP_PATH . '/Views/layouts/footer.php'; ?>
