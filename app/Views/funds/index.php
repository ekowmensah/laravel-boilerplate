<?php include APP_PATH . '/Views/layouts/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h2>Funds Management</h2>
    </div>
    <div class="col-md-6 text-end">
        <button class="btn btn-primary">
            <i class="cil-plus"></i> Create Fund
        </button>
    </div>
</div>

<div class="card">
    <div class="card-header"><strong>All Funds</strong></div>
    <div class="card-body">
        <table class="table table-striped datatable">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($funds)): ?>
                    <?php foreach ($funds as $fund): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($fund['name'] ?? ''); ?></strong></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-info" title="View">
                                    <i class="cil-eye"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="2" class="text-center">No funds found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include APP_PATH . '/Views/layouts/footer.php'; ?>
