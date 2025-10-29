<?php include APP_PATH . '/Views/layouts/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-12">
        <h2>System Settings</h2>
    </div>
</div>

<div class="row g-3">
    <!-- Branches -->
    <div class="col-md-6 col-lg-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="cil-building" style="font-size: 3rem; color: #321fdb;"></i>
                <h5 class="mt-3">Branches</h5>
                <p class="text-muted">Manage branch offices</p>
                <a href="<?php echo BASE_URL; ?>settings/branches" class="btn btn-primary">
                    <i class="cil-settings"></i> Manage
                </a>
            </div>
        </div>
    </div>
    
    <!-- Products -->
    <div class="col-md-6 col-lg-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="cil-tags" style="font-size: 3rem; color: #3399ff;"></i>
                <h5 class="mt-3">Products</h5>
                <p class="text-muted">Loan & Savings products</p>
                <a href="<?php echo BASE_URL; ?>settings/products" class="btn btn-primary">
                    <i class="cil-settings"></i> Manage
                </a>
            </div>
        </div>
    </div>
    
    <!-- Users -->
    <div class="col-md-6 col-lg-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="cil-people" style="font-size: 3rem; color: #2eb85c;"></i>
                <h5 class="mt-3">Users</h5>
                <p class="text-muted">Manage system users</p>
                <a href="<?php echo BASE_URL; ?>settings/users" class="btn btn-primary">
                    <i class="cil-settings"></i> Manage
                </a>
            </div>
        </div>
    </div>
    
    <!-- Account Numbers -->
    <div class="col-md-6 col-lg-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="cil-list-numbered" style="font-size: 3rem; color: #f9b115;"></i>
                <h5 class="mt-3">Account Numbers</h5>
                <p class="text-muted">Configure numbering format</p>
                <a href="<?php echo BASE_URL; ?>settings/account-numbers" class="btn btn-primary">
                    <i class="cil-settings"></i> Manage
                </a>
            </div>
        </div>
    </div>
    
    <!-- Roles & Permissions -->
    <div class="col-md-6 col-lg-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="cil-lock-locked" style="font-size: 3rem; color: #f9b115;"></i>
                <h5 class="mt-3">Roles</h5>
                <p class="text-muted">Roles & permissions</p>
                <a href="<?php echo BASE_URL; ?>roles" class="btn btn-primary">
                    <i class="cil-settings"></i> Manage
                </a>
            </div>
        </div>
    </div>
    
    <!-- System Settings -->
    <div class="col-md-6 col-lg-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="cil-cog" style="font-size: 3rem; color: #636f83;"></i>
                <h5 class="mt-3">System</h5>
                <p class="text-muted">General settings</p>
                <a href="<?php echo BASE_URL; ?>settings/system" class="btn btn-primary">
                    <i class="cil-settings"></i> Configure
                </a>
            </div>
        </div>
    </div>
    
    <!-- Communication -->
    <div class="col-md-6 col-lg-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="cil-envelope-closed" style="font-size: 3rem; color: #e55353;"></i>
                <h5 class="mt-3">Communication</h5>
                <p class="text-muted">SMS & Email settings</p>
                <a href="<?php echo BASE_URL; ?>communication" class="btn btn-primary">
                    <i class="cil-settings"></i> Manage
                </a>
            </div>
        </div>
    </div>
    
    <!-- Payroll -->
    <div class="col-md-6 col-lg-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="cil-money" style="font-size: 3rem; color: #9da5b1;"></i>
                <h5 class="mt-3">Payroll</h5>
                <p class="text-muted">Payroll settings</p>
                <a href="<?php echo BASE_URL; ?>payroll" class="btn btn-primary">
                    <i class="cil-settings"></i> Manage
                </a>
            </div>
        </div>
    </div>
    
    <!-- Shares -->
    <div class="col-md-6 col-lg-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="cil-chart-pie" style="font-size: 3rem; color: #39f;"></i>
                <h5 class="mt-3">Shares</h5>
                <p class="text-muted">Share products</p>
                <a href="<?php echo BASE_URL; ?>shares" class="btn btn-primary">
                    <i class="cil-settings"></i> Manage
                </a>
            </div>
        </div>
    </div>
</div>

<?php include APP_PATH . '/Views/layouts/footer.php'; ?>
