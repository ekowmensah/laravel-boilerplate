<?php include APP_PATH . '/Views/layouts/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-12">
        <h2>Payroll Management</h2>
        <p class="text-muted">Manage employee payroll and salaries</p>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <i class="cil-people" style="font-size: 3rem; color: #321fdb;"></i>
                <h5 class="mt-3">Employees</h5>
                <p class="text-muted">Manage employees</p>
                <a href="<?php echo BASE_URL; ?>payroll/employees" class="btn btn-primary">Manage</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <i class="cil-calculator" style="font-size: 3rem; color: #3399ff;"></i>
                <h5 class="mt-3">Process Payroll</h5>
                <p class="text-muted">Calculate salaries</p>
                <a href="<?php echo BASE_URL; ?>payroll/process" class="btn btn-primary">Process</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <i class="cil-file" style="font-size: 3rem; color: #2eb85c;"></i>
                <h5 class="mt-3">Payslips</h5>
                <p class="text-muted">View payslips</p>
                <a href="<?php echo BASE_URL; ?>payroll/payslips" class="btn btn-primary">View</a>
            </div>
        </div>
    </div>
</div>

<?php include APP_PATH . '/Views/layouts/footer.php'; ?>
