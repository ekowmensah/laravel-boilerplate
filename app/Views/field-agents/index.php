<?php include APP_PATH . '/Views/layouts/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h2>Field Agents Management</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?php echo BASE_URL; ?>field-agents/create" class="btn btn-primary">
            <i class="cil-plus"></i> New Field Agent
        </a>
    </div>
</div>

<!-- Summary Cards -->
<div class="row mb-3">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h6>Total Agents</h6>
                <h3><?php echo count($agents ?? []); ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h6>Active Agents</h6>
                <h3><?php echo count(array_filter($agents ?? [], fn($a) => isset($a['active']) && $a['active'])); ?></h3>
            </div>
        </div>
    </div>
</div>

<!-- Field Agents Table -->
<div class="card">
    <div class="card-header"><strong>All Field Agents</strong></div>
    <div class="card-body">
        <table class="table table-striped datatable">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Branch</th>
                    <th>Clients</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($agents)): ?>
                    <?php foreach ($agents as $agent): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars(($agent['first_name'] ?? '') . ' ' . ($agent['last_name'] ?? '')); ?></strong></td>
                        <td><?php echo htmlspecialchars($agent['email'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($agent['phone'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($agent['branch_name'] ?? 'N/A'); ?></td>
                        <td><span class="badge bg-info"><?php echo $agent['client_count'] ?? 0; ?></span></td>
                        <td>
                            <?php if (isset($agent['active']) && $agent['active']): ?>
                                <span class="badge bg-success">Active</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Inactive</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="<?php echo BASE_URL; ?>field-agents/viewAgent/<?php echo $agent['id']; ?>" class="btn btn-info" title="View">
                                    <i class="cil-eye"></i>
                                </a>
                                <a href="<?php echo BASE_URL; ?>field-agents/edit/<?php echo $agent['id']; ?>" class="btn btn-warning" title="Edit">
                                    <i class="cil-pencil"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">
                            <p class="my-3">No field agents found</p>
                            <a href="<?php echo BASE_URL; ?>field-agents/create" class="btn btn-primary">
                                <i class="cil-plus"></i> Add First Field Agent
                            </a>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include APP_PATH . '/Views/layouts/footer.php'; ?>
