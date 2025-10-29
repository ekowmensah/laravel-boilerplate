<?php include APP_PATH . '/Views/layouts/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h2>Group Details</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?php echo BASE_URL; ?>groups" class="btn btn-secondary">
            <i class="cil-arrow-left"></i> Back to Groups
        </a>
        <a href="<?php echo BASE_URL; ?>groups/add-member/<?php echo $group['id']; ?>" class="btn btn-success">
            <i class="cil-user-follow"></i> Add Member
        </a>
    </div>
</div>

<div class="row">
    <!-- Group Information -->
    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-header bg-primary text-white">
                <strong>Group Information</strong>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="30%">Account Number:</th>
                        <td><strong><?php echo htmlspecialchars($group['account_number']); ?></strong></td>
                    </tr>
                    <tr>
                        <th>Group Name:</th>
                        <td><?php echo htmlspecialchars($group['first_name']); ?></td>
                    </tr>
                    <tr>
                        <th>Mobile:</th>
                        <td><?php echo htmlspecialchars($group['mobile']); ?></td>
                    </tr>
                    <tr>
                        <th>Email:</th>
                        <td><?php echo htmlspecialchars($group['email']); ?></td>
                    </tr>
                    <tr>
                        <th>Address:</th>
                        <td><?php echo htmlspecialchars($group['address']); ?></td>
                    </tr>
                    <tr>
                        <th>Branch:</th>
                        <td><?php echo htmlspecialchars($group['branch_name']); ?></td>
                    </tr>
                    <tr>
                        <th>Loan Officer:</th>
                        <td><?php echo htmlspecialchars($group['officer_name'] ?? 'Not Assigned'); ?></td>
                    </tr>
                    <tr>
                        <th>Status:</th>
                        <td><?php echo getStatusBadge($group['status']); ?></td>
                    </tr>
                    <tr>
                        <th>Created Date:</th>
                        <td><?php echo date('M d, Y', strtotime($group['created_at'])); ?></td>
                    </tr>
                </table>
            </div>
        </div>
        
        <!-- Group Members -->
        <div class="card mb-3">
            <div class="card-header">
                <strong>Group Members</strong>
                <span class="badge bg-primary float-end"><?php echo count($members); ?> Members</span>
            </div>
            <div class="card-body">
                <?php if (!empty($members)): ?>
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Account</th>
                            <th>Name</th>
                            <th>Mobile</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($members as $member): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($member['account_number']); ?></td>
                            <td><?php echo htmlspecialchars($member['first_name'] . ' ' . $member['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($member['mobile']); ?></td>
                            <td><?php echo getStatusBadge($member['status']); ?></td>
                            <td>
                                <a href="<?php echo BASE_URL; ?>clients/viewClient/<?php echo $member['id']; ?>" class="btn btn-sm btn-info">View</a>
                                <form method="POST" action="<?php echo BASE_URL; ?>groups/remove-member/<?php echo $group['id']; ?>" style="display:inline;">
                                    <input type="hidden" name="client_id" value="<?php echo $member['id']; ?>">
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Remove this member from the group?')">Remove</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <p class="text-muted">No members in this group yet.</p>
                <a href="<?php echo BASE_URL; ?>groups/add-member/<?php echo $group['id']; ?>" class="btn btn-primary">
                    <i class="cil-user-follow"></i> Add First Member
                </a>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Group Loans -->
        <div class="card mb-3">
            <div class="card-header">
                <strong>Group Loans</strong>
                <a href="<?php echo BASE_URL; ?>loans/create?group_id=<?php echo $group['id']; ?>" class="btn btn-sm btn-primary float-end">
                    <i class="cil-plus"></i> New Loan
                </a>
            </div>
            <div class="card-body">
                <?php if (!empty($loans)): ?>
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Account</th>
                            <th>Product</th>
                            <th>Principal</th>
                            <th>Outstanding</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($loans as $loan): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($loan['account_number']); ?></td>
                            <td><?php echo htmlspecialchars($loan['product_name']); ?></td>
                            <td><?php echo formatCurrency($loan['principal']); ?></td>
                            <td><?php echo formatCurrency($loan['principal_outstanding_derived']); ?></td>
                            <td><?php echo getStatusBadge($loan['status']); ?></td>
                            <td>
                                <a href="<?php echo BASE_URL; ?>loans/viewLoan/<?php echo $loan['id']; ?>" class="btn btn-sm btn-info">View</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <p class="text-muted">No loans for this group yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Sidebar -->
    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header">
                <strong>Quick Stats</strong>
            </div>
            <div class="card-body">
                <p class="mb-2"><strong>Total Members:</strong> <?php echo count($members); ?></p>
                <p class="mb-2"><strong>Total Loans:</strong> <?php echo count($loans); ?></p>
                <p class="mb-2"><strong>Active Loans:</strong> <?php echo count(array_filter($loans, fn($l) => $l['status'] === 'active')); ?></p>
                <p class="mb-0"><strong>Total Outstanding:</strong> <?php echo formatCurrency(array_sum(array_column($loans, 'principal_outstanding_derived'))); ?></p>
            </div>
        </div>
        
        <div class="card mb-3">
            <div class="card-header">
                <strong>Actions</strong>
            </div>
            <div class="card-body">
                <a href="<?php echo BASE_URL; ?>groups/add-member/<?php echo $group['id']; ?>" class="btn btn-success btn-sm w-100 mb-2">
                    <i class="cil-user-follow"></i> Add Member
                </a>
                <a href="<?php echo BASE_URL; ?>loans/create?group_id=<?php echo $group['id']; ?>" class="btn btn-primary btn-sm w-100 mb-2">
                    <i class="cil-plus"></i> New Group Loan
                </a>
            </div>
        </div>
    </div>
</div>

<?php include APP_PATH . '/Views/layouts/footer.php'; ?>
