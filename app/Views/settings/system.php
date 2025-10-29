<?php include APP_PATH . '/Views/layouts/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h2>System Settings</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?php echo BASE_URL; ?>settings" class="btn btn-secondary">
            <i class="cil-arrow-left"></i> Back to Settings
        </a>
    </div>
</div>

<form method="POST" action="<?php echo BASE_URL; ?>settings/system">
    <div class="row g-3">
        <!-- General Settings -->
        <div class="col-12">
            <div class="card">
                <div class="card-header"><strong>General Settings</strong></div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Company Name</label>
                            <input type="text" class="form-control" name="company_name" value="C19 Banking">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Currency</label>
                            <select class="form-select" name="currency">
                                <option value="USD">USD - US Dollar</option>
                                <option value="EUR">EUR - Euro</option>
                                <option value="GBP">GBP - British Pound</option>
                                <option value="KES">KES - Kenyan Shilling</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date Format</label>
                            <select class="form-select" name="date_format">
                                <option value="Y-m-d">YYYY-MM-DD</option>
                                <option value="d/m/Y">DD/MM/YYYY</option>
                                <option value="m/d/Y">MM/DD/YYYY</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Timezone</label>
                            <select class="form-select" name="timezone">
                                <option value="UTC">UTC</option>
                                <option value="Africa/Nairobi">Africa/Nairobi</option>
                                <option value="America/New_York">America/New_York</option>
                                <option value="Europe/London">Europe/London</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Email Settings -->
        <div class="col-12">
            <div class="card">
                <div class="card-header"><strong>Email Settings</strong></div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">SMTP Host</label>
                            <input type="text" class="form-control" name="smtp_host" placeholder="smtp.gmail.com">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">SMTP Port</label>
                            <input type="number" class="form-control" name="smtp_port" value="587">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">SMTP Username</label>
                            <input type="text" class="form-control" name="smtp_username">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">SMTP Password</label>
                            <input type="password" class="form-control" name="smtp_password">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- SMS Settings -->
        <div class="col-12">
            <div class="card">
                <div class="card-header"><strong>SMS Settings</strong></div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">SMS Provider</label>
                            <select class="form-select" name="sms_provider">
                                <option value="">-- Select Provider --</option>
                                <option value="twilio">Twilio</option>
                                <option value="africastalking">Africa's Talking</option>
                                <option value="nexmo">Nexmo</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Sender ID</label>
                            <input type="text" class="form-control" name="sms_sender_id">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">API Key</label>
                            <input type="text" class="form-control" name="sms_api_key">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">API Secret</label>
                            <input type="password" class="form-control" name="sms_api_secret">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Loan Settings -->
        <div class="col-12">
            <div class="card">
                <div class="card-header"><strong>Loan Settings</strong></div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Default Loan Term (months)</label>
                            <input type="number" class="form-control" name="default_loan_term" value="12">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Default Interest Rate (%)</label>
                            <input type="number" step="0.01" class="form-control" name="default_interest_rate" value="10">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Grace Period (days)</label>
                            <input type="number" class="form-control" name="grace_period" value="0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Late Payment Penalty (%)</label>
                            <input type="number" step="0.01" class="form-control" name="late_penalty" value="5">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-3">
        <button type="submit" class="btn btn-primary btn-lg">
            <i class="cil-save"></i> Save Settings
        </button>
    </div>
</form>

<?php include APP_PATH . '/Views/layouts/footer.php'; ?>
