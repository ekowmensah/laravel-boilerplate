<?php include APP_PATH . '/Views/layouts/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h2>Asset Management</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?php echo BASE_URL; ?>assets/create" class="btn btn-primary">
            <i class="cil-plus"></i> Add Asset
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header"><strong>All Assets</strong></div>
    <div class="card-body">
        <table class="table table-striped datatable">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Serial Number</th>
                    <th>Purchase Date</th>
                    <th>Purchase Price</th>
                    <th>Current Value</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($assets)): ?>
                    <?php foreach ($assets as $asset): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($asset['name'] ?? ''); ?></strong></td>
                        <td><?php echo htmlspecialchars($asset['asset_type_name'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($asset['serial_number'] ?? 'N/A'); ?></td>
                        <td><?php echo isset($asset['purchase_date']) ? date('M d, Y', strtotime($asset['purchase_date'])) : 'N/A'; ?></td>
                        <td><?php echo formatCurrency($asset['purchase_price'] ?? 0); ?></td>
                        <td><?php echo formatCurrency($asset['current_value'] ?? 0); ?></td>
                        <td><?php echo getStatusBadge($asset['status'] ?? 'active'); ?></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="<?php echo BASE_URL; ?>assets/view/<?php echo $asset['id']; ?>" class="btn btn-info">
                                    <i class="cil-eye"></i>
                                </a>
                                <a href="<?php echo BASE_URL; ?>assets/edit/<?php echo $asset['id']; ?>" class="btn btn-warning">
                                    <i class="cil-pencil"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center">No assets found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include APP_PATH . '/Views/layouts/footer.php'; ?>
