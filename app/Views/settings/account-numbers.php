<?php include APP_PATH . '/Views/layouts/header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h2>Account Number Settings</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?php echo BASE_URL; ?>settings" class="btn btn-secondary">
            <i class="cil-arrow-left"></i> Back to Settings
        </a>
    </div>
</div>

<?php if (!empty($success)): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <?php echo htmlspecialchars($success); ?>
        <button type="button" class="btn-close" data-coreui-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row">
    <!-- Client Account Numbers -->
    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header bg-primary text-white">
                <strong>Client Account Numbers</strong>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo BASE_URL; ?>settings/account-numbers">
                    <input type="hidden" name="type" value="client">
                    
                    <div class="mb-3">
                        <label class="form-label">Prefix</label>
                        <input type="text" class="form-control" name="prefix" value="<?php echo htmlspecialchars($clientPrefix ?? 'C19/'); ?>" required>
                        <small class="text-muted">Example: C19/</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Format</label>
                        <select class="form-select" name="format" required>
                            <option value="YEAR/Sequence Number (SL/2014/001)" <?php echo ($clientFormat ?? '') === 'YEAR/Sequence Number (SL/2014/001)' ? 'selected' : ''; ?>>
                                YEAR/Sequence Number
                            </option>
                            <option value="YEAR/MONTH/Sequence Number (SL/2014/08/001)" <?php echo ($clientFormat ?? '') === 'YEAR/MONTH/Sequence Number (SL/2014/08/001)' ? 'selected' : ''; ?>>
                                YEAR/MONTH/Sequence Number
                            </option>
                            <option value="Sequence Number" <?php echo ($clientFormat ?? '') === 'Sequence Number' ? 'selected' : ''; ?>>
                                Sequence Number
                            </option>
                            <option value="Random Number" <?php echo ($clientFormat ?? '') === 'Random Number' ? 'selected' : ''; ?>>
                                Random Number
                            </option>
                            <option value="Branch Sequence Number" <?php echo ($clientFormat ?? '') === 'Branch Sequence Number' ? 'selected' : ''; ?>>
                                Branch Sequence Number
                            </option>
                            <option value="Branch Product Sequence Number" <?php echo ($clientFormat ?? '') === 'Branch Product Sequence Number' ? 'selected' : ''; ?>>
                                Branch Product Sequence Number
                            </option>
                        </select>
                    </div>
                    
                    <div class="alert alert-info">
                        <strong>Preview:</strong><br>
                        <code id="clientPreview"><?php echo htmlspecialchars($clientPrefix ?? 'C19/'); ?>10001</code>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="cil-save"></i> Save Client Settings
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Loan Account Numbers -->
    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header bg-success text-white">
                <strong>Loan Account Numbers</strong>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo BASE_URL; ?>settings/account-numbers">
                    <input type="hidden" name="type" value="loan">
                    
                    <div class="mb-3">
                        <label class="form-label">Prefix</label>
                        <input type="text" class="form-control" name="prefix" value="<?php echo htmlspecialchars($loanPrefix ?? 'C19/L/'); ?>" required>
                        <small class="text-muted">Example: C19/L/</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Format</label>
                        <select class="form-select" name="format" required>
                            <option value="YEAR/Sequence Number (SL/2014/001)" <?php echo ($loanFormat ?? '') === 'YEAR/Sequence Number (SL/2014/001)' ? 'selected' : ''; ?>>
                                YEAR/Sequence Number
                            </option>
                            <option value="YEAR/MONTH/Sequence Number (SL/2014/08/001)" <?php echo ($loanFormat ?? '') === 'YEAR/MONTH/Sequence Number (SL/2014/08/001)' ? 'selected' : ''; ?>>
                                YEAR/MONTH/Sequence Number
                            </option>
                            <option value="Sequence Number" <?php echo ($loanFormat ?? '') === 'Sequence Number' ? 'selected' : ''; ?>>
                                Sequence Number
                            </option>
                            <option value="Random Number" <?php echo ($loanFormat ?? '') === 'Random Number' ? 'selected' : ''; ?>>
                                Random Number
                            </option>
                            <option value="Branch Sequence Number" <?php echo ($loanFormat ?? '') === 'Branch Sequence Number' ? 'selected' : ''; ?>>
                                Branch Sequence Number
                            </option>
                            <option value="Branch Product Sequence Number" <?php echo ($loanFormat ?? '') === 'Branch Product Sequence Number' ? 'selected' : ''; ?>>
                                Branch Product Sequence Number
                            </option>
                        </select>
                    </div>
                    
                    <div class="alert alert-info">
                        <strong>Preview:</strong><br>
                        <code id="loanPreview"><?php echo htmlspecialchars($loanPrefix ?? 'C19/L/'); ?>110001</code>
                    </div>
                    
                    <button type="submit" class="btn btn-success w-100">
                        <i class="cil-save"></i> Save Loan Settings
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Savings Account Numbers -->
    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header bg-warning text-dark">
                <strong>Savings Account Numbers</strong>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo BASE_URL; ?>settings/account-numbers">
                    <input type="hidden" name="type" value="savings">
                    
                    <div class="mb-3">
                        <label class="form-label">Prefix</label>
                        <input type="text" class="form-control" name="prefix" value="<?php echo htmlspecialchars($savingsPrefix ?? 'C19/'); ?>" required>
                        <small class="text-muted">Example: C19/</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Format</label>
                        <select class="form-select" name="format" required>
                            <option value="YEAR/Sequence Number (SL/2014/001)" <?php echo ($savingsFormat ?? '') === 'YEAR/Sequence Number (SL/2014/001)' ? 'selected' : ''; ?>>
                                YEAR/Sequence Number
                            </option>
                            <option value="YEAR/MONTH/Sequence Number (SL/2014/08/001)" <?php echo ($savingsFormat ?? '') === 'YEAR/MONTH/Sequence Number (SL/2014/08/001)' ? 'selected' : ''; ?>>
                                YEAR/MONTH/Sequence Number
                            </option>
                            <option value="Sequence Number" <?php echo ($savingsFormat ?? '') === 'Sequence Number' ? 'selected' : ''; ?>>
                                Sequence Number
                            </option>
                            <option value="Random Number" <?php echo ($savingsFormat ?? '') === 'Random Number' ? 'selected' : ''; ?>>
                                Random Number
                            </option>
                            <option value="Branch Sequence Number" <?php echo ($savingsFormat ?? '') === 'Branch Sequence Number' ? 'selected' : ''; ?>>
                                Branch Sequence Number
                            </option>
                            <option value="Branch Product Sequence Number" <?php echo ($savingsFormat ?? '') === 'Branch Product Sequence Number' ? 'selected' : ''; ?>>
                                Branch Product Sequence Number
                            </option>
                        </select>
                    </div>
                    
                    <div class="alert alert-info">
                        <strong>Preview:</strong><br>
                        <code id="savingsPreview"><?php echo htmlspecialchars($savingsPrefix ?? 'C19/'); ?>110001</code>
                    </div>
                    
                    <button type="submit" class="btn btn-warning w-100">
                        <i class="cil-save"></i> Save Savings Settings
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header"><strong>Format Examples</strong></div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Format</th>
                    <th>Example</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>YEAR/Sequence Number</td>
                    <td><code>C19/2025/001</code></td>
                    <td>Prefix + Year + Sequence</td>
                </tr>
                <tr>
                    <td>YEAR/MONTH/Sequence Number</td>
                    <td><code>C19/2025/10/001</code></td>
                    <td>Prefix + Year + Month + Sequence</td>
                </tr>
                <tr>
                    <td>Sequence Number</td>
                    <td><code>C19/00001</code></td>
                    <td>Prefix + 5-digit Sequence</td>
                </tr>
                <tr>
                    <td>Random Number</td>
                    <td><code>C19/12345</code></td>
                    <td>Prefix + Random 5-digit number</td>
                </tr>
                <tr>
                    <td>Branch Sequence Number</td>
                    <td><code>C19/10001</code></td>
                    <td>Prefix + Branch ID + 4-digit Sequence</td>
                </tr>
                <tr>
                    <td>Branch Product Sequence Number</td>
                    <td><code>C19/110001</code></td>
                    <td>Prefix + Branch ID + Product ID + 3-digit Sequence</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<?php include APP_PATH . '/Views/layouts/footer.php'; ?>
