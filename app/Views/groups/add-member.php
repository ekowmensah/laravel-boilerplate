<?php include APP_PATH . '/Views/layouts/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h2>Add Group Member</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?php echo BASE_URL; ?>groups/view/<?php echo $group['id']; ?>" class="btn btn-secondary">
            <i class="cil-arrow-left"></i> Back to Group
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-success text-white">
                <strong>Add Member to: <?php echo htmlspecialchars($group['first_name']); ?></strong>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo BASE_URL; ?>groups/add-member/<?php echo $group['id']; ?>">
                    <div class="mb-3">
                        <label class="form-label">Select Client <span class="text-danger">*</span></label>
                        <select class="form-select" name="client_id" required>
                            <option value="">-- Select Client --</option>
                            <?php foreach ($clients as $client): ?>
                                <option value="<?php echo $client['id']; ?>">
                                    <?php echo htmlspecialchars($client['account_number'] . ' - ' . $client['first_name'] . ' ' . $client['last_name']); ?>
                                    <?php if ($client['mobile']): ?>
                                        (<?php echo htmlspecialchars($client['mobile']); ?>)
                                    <?php endif; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <small class="text-muted">Only clients not already in a group are shown</small>
                    </div>
                    
                    <div class="alert alert-info">
                        <strong>Note:</strong> Once added, the client will be associated with this group for group loans and activities.
                    </div>
                    
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="cil-user-follow"></i> Add Member
                    </button>
                    <a href="<?php echo BASE_URL; ?>groups/view/<?php echo $group['id']; ?>" class="btn btn-secondary btn-lg">Cancel</a>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <strong>Group Information</strong>
            </div>
            <div class="card-body">
                <p class="mb-1"><strong>Group Name:</strong> <?php echo htmlspecialchars($group['first_name']); ?></p>
                <p class="mb-1"><strong>Account:</strong> <?php echo htmlspecialchars($group['account_number']); ?></p>
                <p class="mb-1"><strong>Branch:</strong> <?php echo htmlspecialchars($group['branch_name'] ?? 'N/A'); ?></p>
                <p class="mb-0"><strong>Status:</strong> <?php echo getStatusBadge($group['status']); ?></p>
            </div>
        </div>
    </div>
</div>

<?php include APP_PATH . '/Views/layouts/footer.php'; ?>
