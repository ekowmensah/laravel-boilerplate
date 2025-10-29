<?php include APP_PATH . '/Views/layouts/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h2>Field Agent Details</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?php echo BASE_URL; ?>field-agents" class="btn btn-secondary">
            <i class="cil-arrow-left"></i> Back to Field Agents
        </a>
        <a href="<?php echo BASE_URL; ?>field-agents/edit/<?php echo $agent['id']; ?>" class="btn btn-warning">
            <i class="cil-pencil"></i> Edit
        </a>
    </div>
</div>

<div class="row">
    <!-- Agent Information -->
    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-header bg-primary text-white">
                <strong>Agent Information</strong>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="30%">Name:</th>
                        <td><strong><?php echo htmlspecialchars(($agent['first_name'] ?? '') . ' ' . ($agent['last_name'] ?? '')); ?></strong></td>
                    </tr>
                    <tr>
                        <th>Email:</th>
                        <td><?php echo htmlspecialchars($agent['email'] ?? 'N/A'); ?></td>
                    </tr>
                    <tr>
                        <th>Phone:</th>
                        <td><?php echo htmlspecialchars($agent['phone'] ?? 'N/A'); ?></td>
                    </tr>
                    <tr>
                        <th>Branch:</th>
                        <td><?php echo htmlspecialchars($agent['branch_name'] ?? 'N/A'); ?></td>
                    </tr>
                    <tr>
                        <th>Status:</th>
                        <td>
                            <?php if (isset($agent['active']) && $agent['active']): ?>
                                <span class="badge bg-success">Active</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Inactive</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Joined Date:</th>
                        <td><?php echo isset($agent['created_at']) ? date('M d, Y', strtotime($agent['created_at'])) : 'N/A'; ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Statistics -->
    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header">
                <strong>Performance Stats</strong>
            </div>
            <div class="card-body">
                <p class="mb-2"><strong>Total Clients:</strong> <?php echo $agent['client_count'] ?? 0; ?></p>
                <p class="mb-2"><strong>Active Loans:</strong> <?php echo $agent['loan_count'] ?? 0; ?></p>
                <p class="mb-2"><strong>Total Disbursed:</strong> <?php echo formatCurrency($agent['total_disbursed'] ?? 0); ?></p>
                <p class="mb-0"><strong>Portfolio at Risk:</strong> <?php echo formatCurrency($agent['par'] ?? 0); ?></p>
            </div>
        </div>
    </div>
</div>

<?php include APP_PATH . '/Views/layouts/footer.php'; ?>
