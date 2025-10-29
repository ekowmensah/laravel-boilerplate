<?php include APP_PATH . '/Views/layouts/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h2>Investor Details</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?php echo BASE_URL; ?>investors" class="btn btn-secondary">
            <i class="cil-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-header bg-primary text-white"><strong>Investor Information</strong></div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="30%">Name:</th>
                        <td><strong><?php echo htmlspecialchars($investor['name'] ?? ''); ?></strong></td>
                    </tr>
                    <tr>
                        <th>Email:</th>
                        <td><?php echo htmlspecialchars($investor['email'] ?? 'N/A'); ?></td>
                    </tr>
                    <tr>
                        <th>Phone:</th>
                        <td><?php echo htmlspecialchars($investor['phone'] ?? 'N/A'); ?></td>
                    </tr>
                    <tr>
                        <th>Address:</th>
                        <td><?php echo htmlspecialchars($investor['address'] ?? 'N/A'); ?></td>
                    </tr>
                    <tr>
                        <th>Notes:</th>
                        <td><?php echo htmlspecialchars($investor['notes'] ?? 'N/A'); ?></td>
                    </tr>
                </table>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header"><strong>Investment History</strong></div>
            <div class="card-body">
                <?php if (!empty($funds)): ?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($funds as $fund): ?>
                            <tr>
                                <td><?php echo isset($fund['date']) ? date('M d, Y', strtotime($fund['date'])) : 'N/A'; ?></td>
                                <td><?php echo formatCurrency($fund['amount'] ?? 0); ?></td>
                                <td><?php echo htmlspecialchars($fund['type'] ?? 'Investment'); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="text-muted">No investment history</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header"><strong>Summary</strong></div>
            <div class="card-body">
                <p><strong>Total Investments:</strong><br><?php echo formatCurrency(0); ?></p>
                <p><strong>Returns:</strong><br><?php echo formatCurrency(0); ?></p>
            </div>
        </div>
    </div>
</div>

<?php include APP_PATH . '/Views/layouts/footer.php'; ?>
