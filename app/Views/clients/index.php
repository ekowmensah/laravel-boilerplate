<?php include APP_PATH . '/Views/layouts/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h2>Clients Management</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?php echo BASE_URL; ?>clients/create" class="btn btn-primary">
            <i class="cil-plus"></i> Add New Client
        </a>
    </div>
</div>

<?php
$flash = \Core\Session::getFlash('message');
if ($flash):
?>
    <div class="alert alert-<?php echo $flash['type']; ?> alert-dismissible fade show" role="alert">
        <?php echo htmlspecialchars($flash['message']); ?>
        <button type="button" class="btn-close" data-coreui-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <strong>All Clients</strong>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover datatable">
                <thead>
                    <tr>
                        <th>Account #</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Mobile</th>
                        <th>Email</th>
                        <th>Branch</th>
                        <th>Loan Officer</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($clients as $client): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($client['account_number'] ?? 'N/A'); ?></td>
                            <td>
                                <strong><?php echo htmlspecialchars($client['first_name'] . ' ' . $client['last_name']); ?></strong>
                            </td>
                            <td><?php echo htmlspecialchars($client['client_type_name'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($client['mobile'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($client['email'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($client['branch_name'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars(($client['officer_first_name'] ?? '') . ' ' . ($client['officer_last_name'] ?? '')); ?></td>
                            <td><?php echo getStatusBadge($client['status']); ?></td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="<?php echo BASE_URL; ?>clients/view/<?php echo $client['id']; ?>" 
                                       class="btn btn-info" title="View">
                                        <i class="cil-eye"></i>
                                    </a>
                                    <a href="<?php echo BASE_URL; ?>clients/edit/<?php echo $client['id']; ?>" 
                                       class="btn btn-warning" title="Edit">
                                        <i class="cil-pencil"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include APP_PATH . '/Views/layouts/footer.php'; ?>
