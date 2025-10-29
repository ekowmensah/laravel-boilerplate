<?php include APP_PATH . '/Views/layouts/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-12">
        <h2>Wallet Management</h2>
        <p class="text-muted">Manage client wallets and mobile money</p>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="card text-center">
            <div class="card-body">
                <i class="cil-wallet" style="font-size: 3rem; color: #321fdb;"></i>
                <h5 class="mt-3">Wallet Accounts</h5>
                <p class="text-muted">Manage wallets</p>
                <a href="<?php echo BASE_URL; ?>wallet/accounts" class="btn btn-primary">Manage</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card text-center">
            <div class="card-body">
                <i class="cil-transfer" style="font-size: 3rem; color: #3399ff;"></i>
                <h5 class="mt-3">Transactions</h5>
                <p class="text-muted">View transactions</p>
                <a href="<?php echo BASE_URL; ?>wallet/transactions" class="btn btn-primary">View</a>
            </div>
        </div>
    </div>
</div>

<?php include APP_PATH . '/Views/layouts/footer.php'; ?>
