<?php include APP_PATH . '/Views/layouts/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-12">
        <h2>Communication Management</h2>
        <p class="text-muted">Manage SMS, Email campaigns and client notifications</p>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <i class="cil-chat-bubble" style="font-size: 3rem; color: #321fdb;"></i>
                <h5 class="mt-3">SMS Management</h5>
                <p class="text-muted">Send SMS to clients</p>
                <a href="<?php echo BASE_URL; ?>communication/sms" class="btn btn-primary">Manage</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <i class="cil-envelope-closed" style="font-size: 3rem; color: #3399ff;"></i>
                <h5 class="mt-3">Email Management</h5>
                <p class="text-muted">Send emails to clients</p>
                <a href="<?php echo BASE_URL; ?>communication/email" class="btn btn-primary">Manage</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <i class="cil-bullhorn" style="font-size: 3rem; color: #2eb85c;"></i>
                <h5 class="mt-3">Campaigns</h5>
                <p class="text-muted">Marketing campaigns</p>
                <a href="<?php echo BASE_URL; ?>communication/campaigns" class="btn btn-primary">Manage</a>
            </div>
        </div>
    </div>
</div>

<div class="card mt-3">
    <div class="card-header"><strong>Recent Communications</strong></div>
    <div class="card-body">
        <p class="text-muted">No recent communications</p>
    </div>
</div>

<?php include APP_PATH . '/Views/layouts/footer.php'; ?>
