<?php include APP_PATH . '/Views/layouts/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h2>Asset Details</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?php echo BASE_URL; ?>assets" class="btn btn-secondary">
            <i class="cil-arrow-left"></i> Back
        </a>
        <a href="<?php echo BASE_URL; ?>assets/edit/<?php echo $asset['id']; ?>" class="btn btn-warning">
            <i class="cil-pencil"></i> Edit
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-header bg-primary text-white"><strong>Asset Information</strong></div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="30%">Name:</th>
                        <td><strong><?php echo htmlspecialchars($asset['name'] ?? ''); ?></strong></td>
                    </tr>
                    <tr>
                        <th>Type:</th>
                        <td><?php echo htmlspecialchars($asset['asset_type_name'] ?? 'N/A'); ?></td>
                    </tr>
                    <tr>
                        <th>Serial Number:</th>
                        <td><?php echo htmlspecialchars($asset['serial_number'] ?? 'N/A'); ?></td>
                    </tr>
                    <tr>
                        <th>Purchase Date:</th>
                        <td><?php echo isset($asset['purchase_date']) ? date('M d, Y', strtotime($asset['purchase_date'])) : 'N/A'; ?></td>
                    </tr>
                    <tr>
                        <th>Purchase Price:</th>
                        <td><?php echo formatCurrency($asset['purchase_price'] ?? 0); ?></td>
                    </tr>
                    <tr>
                        <th>Current Value:</th>
                        <td><?php echo formatCurrency($asset['current_value'] ?? 0); ?></td>
                    </tr>
                    <tr>
                        <th>Depreciation Rate:</th>
                        <td><?php echo ($asset['depreciation_rate'] ?? 0) . '%'; ?></td>
                    </tr>
                    <tr>
                        <th>Status:</th>
                        <td><?php echo getStatusBadge($asset['status'] ?? 'active'); ?></td>
                    </tr>
                    <tr>
                        <th>Branch:</th>
                        <td><?php echo htmlspecialchars($asset['branch_name'] ?? 'N/A'); ?></td>
                    </tr>
                    <tr>
                        <th>Notes:</th>
                        <td><?php echo htmlspecialchars($asset['notes'] ?? 'N/A'); ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header"><strong>Quick Actions</strong></div>
            <div class="card-body">
                <a href="<?php echo BASE_URL; ?>assets/depreciation/<?php echo $asset['id']; ?>" class="btn btn-info btn-block mb-2 w-100">
                    <i class="cil-chart"></i> View Depreciation
                </a>
                <a href="<?php echo BASE_URL; ?>assets/maintenance/<?php echo $asset['id']; ?>" class="btn btn-warning btn-block w-100">
                    <i class="cil-wrench"></i> Maintenance History
                </a>
            </div>
        </div>
    </div>
</div>

<?php include APP_PATH . '/Views/layouts/footer.php'; ?>
