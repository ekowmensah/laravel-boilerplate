<?php include APP_PATH . '/Views/layouts/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h2>Client Details</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?php echo BASE_URL; ?>clients" class="btn btn-secondary">
            <i class="cil-arrow-left"></i> Back to Clients
        </a>
        <a href="<?php echo BASE_URL; ?>clients/edit/<?php echo $client['id']; ?>" class="btn btn-primary">
            <i class="cil-pencil"></i> Edit
        </a>
    </div>
</div>

<div class="row">
    <!-- Client Information -->
    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-header bg-primary text-white">
                <strong>Personal Information</strong>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="30%">Account Number:</th>
                        <td><strong><?php echo htmlspecialchars($client['account_number']); ?></strong></td>
                    </tr>
                    <tr>
                        <th>Full Name:</th>
                        <td><?php echo htmlspecialchars($client['first_name'] . ' ' . $client['last_name']); ?></td>
                    </tr>
                    <tr>
                        <th>Gender:</th>
                        <td><?php echo ucfirst($client['gender']); ?></td>
                    </tr>
                    <tr>
                        <th>Date of Birth:</th>
                        <td><?php echo $client['dob'] ? date('M d, Y', strtotime($client['dob'])) : 'N/A'; ?></td>
                    </tr>
                    <tr>
                        <th>Mobile:</th>
                        <td><?php echo htmlspecialchars($client['mobile']); ?></td>
                    </tr>
                    <tr>
                        <th>Email:</th>
                        <td><?php echo htmlspecialchars($client['email']); ?></td>
                    </tr>
                    <tr>
                        <th>Address:</th>
                        <td><?php echo htmlspecialchars($client['address']); ?></td>
                    </tr>
                    <tr>
                        <th>City:</th>
                        <td><?php echo htmlspecialchars($client['city']); ?></td>
                    </tr>
                    <tr>
                        <th>State:</th>
                        <td><?php echo htmlspecialchars($client['state']); ?></td>
                    </tr>
                    <tr>
                        <th>Branch:</th>
                        <td><?php echo htmlspecialchars($client['branch_name']); ?></td>
                    </tr>
                    <tr>
                        <th>Loan Officer:</th>
                        <td><?php echo htmlspecialchars($client['officer_name'] ?? 'Not Assigned'); ?></td>
                    </tr>
                    <tr>
                        <th>Status:</th>
                        <td><?php echo getStatusBadge($client['status']); ?></td>
                    </tr>
                    <tr>
                        <th>Joined Date:</th>
                        <td><?php echo date('M d, Y', strtotime($client['created_at'])); ?></td>
                    </tr>
                </table>
            </div>
        </div>
        
        <!-- Loans -->
        <div class="card mb-3">
            <div class="card-header">
                <strong>Loans</strong>
                <a href="<?php echo BASE_URL; ?>loans/create?client_id=<?php echo $client['id']; ?>" class="btn btn-sm btn-primary float-end">
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
                                <a href="<?php echo BASE_URL; ?>loans/view/<?php echo $loan['id']; ?>" class="btn btn-sm btn-info">View</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <p class="text-muted">No loans found</p>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Savings -->
        <div class="card mb-3">
            <div class="card-header">
                <strong>Savings Accounts</strong>
                <a href="<?php echo BASE_URL; ?>savings/create?client_id=<?php echo $client['id']; ?>" class="btn btn-sm btn-success float-end">
                    <i class="cil-plus"></i> New Account
                </a>
            </div>
            <div class="card-body">
                <?php if (!empty($savings)): ?>
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Account</th>
                            <th>Product</th>
                            <th>Balance</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($savings as $account): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($account['account_number']); ?></td>
                            <td><?php echo htmlspecialchars($account['product_name']); ?></td>
                            <td><?php echo formatCurrency($account['balance_derived']); ?></td>
                            <td><?php echo getStatusBadge($account['status']); ?></td>
                            <td>
                                <a href="<?php echo BASE_URL; ?>savings/view/<?php echo $account['id']; ?>" class="btn btn-sm btn-info">View</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <p class="text-muted">No savings accounts found</p>
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
                <p class="mb-2"><strong>Total Loans:</strong> <?php echo count($loans); ?></p>
                <p class="mb-2"><strong>Active Loans:</strong> <?php echo count(array_filter($loans, fn($l) => $l['status'] === 'active')); ?></p>
                <p class="mb-2"><strong>Savings Accounts:</strong> <?php echo count($savings); ?></p>
                <p class="mb-0"><strong>Total Savings Balance:</strong> <?php echo formatCurrency(array_sum(array_column($savings, 'balance_derived'))); ?></p>
            </div>
        </div>
        
        <div class="card mb-3">
            <div class="card-header">
                <strong>Actions</strong>
            </div>
            <div class="card-body">
                <a href="<?php echo BASE_URL; ?>loans/create?client_id=<?php echo $client['id']; ?>" class="btn btn-primary btn-sm w-100 mb-2">
                    <i class="cil-plus"></i> New Loan
                </a>
                <a href="<?php echo BASE_URL; ?>savings/create?client_id=<?php echo $client['id']; ?>" class="btn btn-success btn-sm w-100 mb-2">
                    <i class="cil-plus"></i> New Savings
                </a>
                <a href="<?php echo BASE_URL; ?>clients/edit/<?php echo $client['id']; ?>" class="btn btn-warning btn-sm w-100 mb-2">
                    <i class="cil-pencil"></i> Edit Client
                </a>
            </div>
        </div>
    </div>
</div>

<?php include APP_PATH . '/Views/layouts/footer.php'; ?>
