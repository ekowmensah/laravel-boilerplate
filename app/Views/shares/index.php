<?php include APP_PATH . '/Views/layouts/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-12">
        <h2>Share Management</h2>
        <p class="text-muted">Manage member shares and dividends</p>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <i class="cil-chart-pie" style="font-size: 3rem; color: #321fdb;"></i>
                <h5 class="mt-3">Share Products</h5>
                <p class="text-muted">Manage products</p>
                <a href="<?php echo BASE_URL; ?>shares/products" class="btn btn-primary">Manage</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <i class="cil-dollar" style="font-size: 3rem; color: #3399ff;"></i>
                <h5 class="mt-3">Transactions</h5>
                <p class="text-muted">Buy/Sell shares</p>
                <a href="<?php echo BASE_URL; ?>shares/transactions" class="btn btn-primary">View</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <i class="cil-money" style="font-size: 3rem; color: #2eb85c;"></i>
                <h5 class="mt-3">Dividends</h5>
                <p class="text-muted">Manage dividends</p>
                <a href="<?php echo BASE_URL; ?>shares/dividends" class="btn btn-primary">Manage</a>
            </div>
        </div>
    </div>
</div>

<?php include APP_PATH . '/Views/layouts/footer.php'; ?>
