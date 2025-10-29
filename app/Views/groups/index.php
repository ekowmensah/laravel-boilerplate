<?php include APP_PATH . '/Views/layouts/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h2>Groups Management</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?php echo BASE_URL; ?>groups/create" class="btn btn-primary">
            <i class="cil-plus"></i> New Group
        </a>
    </div>
</div>

<!-- Summary Cards -->
<div class="row mb-3">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h6>Total Groups</h6>
                <h3><?php echo count($groups); ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h6>Active Groups</h6>
                <h3><?php echo count(array_filter($groups, fn($g) => $g['status'] === 'active')); ?></h3>
            </div>
        </div>
    </div>
</div>

<!-- Groups Table -->
<div class="card">
    <div class="card-header">
        <strong>All Groups</strong>
    </div>
    <div class="card-body">
        <table class="table table-striped datatable" id="groupsTable">
            <thead>
                <tr>
                    <th>Account Number</th>
                    <th>Group Name</th>
                    <th>Mobile</th>
                    <th>Branch</th>
                    <th>Loan Officer</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($groups)): ?>
                    <?php foreach ($groups as $group): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($group['account_number'] ?? 'N/A'); ?></strong></td>
                        <td><?php echo htmlspecialchars($group['first_name'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($group['mobile'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($group['branch_name'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($group['officer_name'] ?? 'Not Assigned'); ?></td>
                        <td><?php echo getStatusBadge($group['status'] ?? 'pending'); ?></td>
                        <td><?php echo isset($group['created_at']) ? date('M d, Y', strtotime($group['created_at'])) : 'N/A'; ?></td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="<?php echo BASE_URL; ?>groups/viewGroup/<?php echo $group['id']; ?>" class="btn btn-info" title="View">
                                    <i class="cil-eye"></i>
                                </a>
                                <a href="<?php echo BASE_URL; ?>groups/add-member/<?php echo $group['id']; ?>" class="btn btn-success" title="Add Member">
                                    <i class="cil-user-follow"></i>
                                </a>
                                <a href="<?php echo BASE_URL; ?>loans/create?group_id=<?php echo $group['id']; ?>" class="btn btn-primary" title="New Loan">
                                    <i class="cil-dollar"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center">
                            <p class="my-3">No groups found</p>
                            <a href="<?php echo BASE_URL; ?>groups/create" class="btn btn-primary">
                                <i class="cil-plus"></i> Create First Group
                            </a>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include APP_PATH . '/Views/layouts/footer.php'; ?>
