<?php include APP_PATH . '/Views/layouts/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h2>Investor Management</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?php echo BASE_URL; ?>investors/create" class="btn btn-primary">
            <i class="cil-plus"></i> Add Investor
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header"><strong>All Investors</strong></div>
    <div class="card-body">
        <table class="table table-striped datatable">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($investors)): ?>
                    <?php foreach ($investors as $investor): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($investor['name'] ?? ''); ?></strong></td>
                        <td><?php echo htmlspecialchars($investor['email'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($investor['phone'] ?? 'N/A'); ?></td>
                        <td><?php echo isset($investor['created_at']) ? date('M d, Y', strtotime($investor['created_at'])) : 'N/A'; ?></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="<?php echo BASE_URL; ?>investors/view/<?php echo $investor['id']; ?>" class="btn btn-info">
                                    <i class="cil-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">No investors found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include APP_PATH . '/Views/layouts/footer.php'; ?>
