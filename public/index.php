<?php
/**
 * C19 Banking System - MVC Entry Point
 * Front Controller
 */

// Load configuration
require_once dirname(__DIR__) . '/config/config.php';

// Load core files
require_once CORE_PATH . '/Database.php';
require_once CORE_PATH . '/Router.php';
require_once CORE_PATH . '/Session.php';
require_once CORE_PATH . '/helpers.php';

// Create router instance
$router = new Core\Router();

// Define routes

// Authentication routes
$router->get('', ['controller' => 'Dashboard', 'action' => 'index']);
$router->get('auth/login', ['controller' => 'Auth', 'action' => 'login']);
$router->post('auth/login', ['controller' => 'Auth', 'action' => 'login']);
$router->get('auth/logout', ['controller' => 'Auth', 'action' => 'logout']);

// Dashboard
$router->get('dashboard', ['controller' => 'Dashboard', 'action' => 'index']);

// Client routes
$router->get('clients', ['controller' => 'Client', 'action' => 'index']);
$router->get('clients/create', ['controller' => 'Client', 'action' => 'create']);
$router->post('clients/create', ['controller' => 'Client', 'action' => 'create']);
$router->get('clients/view/{id}', ['controller' => 'Client', 'action' => 'viewClient']);
$router->get('clients/viewClient/{id}', ['controller' => 'Client', 'action' => 'viewClient']);
$router->get('clients/edit/{id}', ['controller' => 'Client', 'action' => 'edit']);
$router->post('clients/edit/{id}', ['controller' => 'Client', 'action' => 'edit']);

// Loan routes
$router->get('loans', ['controller' => 'Loan', 'action' => 'index']);
$router->get('loans/create', ['controller' => 'Loan', 'action' => 'create']);
$router->post('loans/create', ['controller' => 'Loan', 'action' => 'create']);
$router->get('loans/view/{id}', ['controller' => 'Loan', 'action' => 'viewLoan']);
$router->get('loans/viewLoan/{id}', ['controller' => 'Loan', 'action' => 'viewLoan']);
$router->get('loans/approve/{id}', ['controller' => 'Loan', 'action' => 'approve']);
$router->post('loans/approve/{id}', ['controller' => 'Loan', 'action' => 'approve']);
$router->get('loans/disburse/{id}', ['controller' => 'Loan', 'action' => 'disburse']);
$router->post('loans/disburse/{id}', ['controller' => 'Loan', 'action' => 'disburse']);
$router->get('loans/repayment/{id}', ['controller' => 'Loan', 'action' => 'repayment']);
$router->post('loans/repayment/{id}', ['controller' => 'Loan', 'action' => 'repayment']);
$router->get('loans/schedule/{id}', ['controller' => 'Loan', 'action' => 'schedule']);
$router->get('loans/transactions/{id}', ['controller' => 'Loan', 'action' => 'transactions']);

// Savings routes
$router->get('savings', ['controller' => 'Savings', 'action' => 'index']);
$router->get('savings/create', ['controller' => 'Savings', 'action' => 'create']);
$router->post('savings/create', ['controller' => 'Savings', 'action' => 'create']);
$router->get('savings/view/{id}', ['controller' => 'Savings', 'action' => 'viewSavings']);
$router->get('savings/viewSavings/{id}', ['controller' => 'Savings', 'action' => 'viewSavings']);
$router->get('savings/approve/{id}', ['controller' => 'Savings', 'action' => 'approve']);
$router->post('savings/approve/{id}', ['controller' => 'Savings', 'action' => 'approve']);
$router->get('savings/activate/{id}', ['controller' => 'Savings', 'action' => 'activate']);
$router->post('savings/activate/{id}', ['controller' => 'Savings', 'action' => 'activate']);
$router->get('savings/deposit/{id}', ['controller' => 'Savings', 'action' => 'deposit']);
$router->post('savings/deposit/{id}', ['controller' => 'Savings', 'action' => 'deposit']);
$router->get('savings/withdraw/{id}', ['controller' => 'Savings', 'action' => 'withdraw']);
$router->post('savings/withdraw/{id}', ['controller' => 'Savings', 'action' => 'withdraw']);

// Group routes
$router->get('groups', ['controller' => 'Group', 'action' => 'index']);
$router->get('groups/create', ['controller' => 'Group', 'action' => 'create']);
$router->post('groups/create', ['controller' => 'Group', 'action' => 'create']);
$router->get('groups/view/{id}', ['controller' => 'Group', 'action' => 'viewGroup']);
$router->get('groups/viewGroup/{id}', ['controller' => 'Group', 'action' => 'viewGroup']);
$router->get('groups/add-member/{id}', ['controller' => 'Group', 'action' => 'addMember']);
$router->post('groups/add-member/{id}', ['controller' => 'Group', 'action' => 'addMember']);
$router->post('groups/remove-member/{id}', ['controller' => 'Group', 'action' => 'removeMember']);

// Field Agent routes
$router->get('field-agents', ['controller' => 'FieldAgent', 'action' => 'index']);
$router->get('field-agents/create', ['controller' => 'FieldAgent', 'action' => 'create']);
$router->post('field-agents/create', ['controller' => 'FieldAgent', 'action' => 'create']);
$router->get('field-agents/view/{id}', ['controller' => 'FieldAgent', 'action' => 'viewAgent']);
$router->get('field-agents/viewAgent/{id}', ['controller' => 'FieldAgent', 'action' => 'viewAgent']);
$router->get('field-agents/edit/{id}', ['controller' => 'FieldAgent', 'action' => 'edit']);
$router->post('field-agents/edit/{id}', ['controller' => 'FieldAgent', 'action' => 'edit']);

// Report routes
$router->get('reports', ['controller' => 'Report', 'action' => 'index']);
$router->get('reports/loan-portfolio', ['controller' => 'Report', 'action' => 'loanPortfolio']);
$router->get('reports/savings-balance', ['controller' => 'Report', 'action' => 'savingsBalance']);

// Transaction routes
$router->get('transactions', ['controller' => 'Transaction', 'action' => 'index']);

// Settings routes
$router->get('settings', ['controller' => 'Settings', 'action' => 'index']);
$router->get('settings/branches', ['controller' => 'Settings', 'action' => 'branches']);
$router->get('settings/products', ['controller' => 'Settings', 'action' => 'products']);
$router->get('settings/users', ['controller' => 'Settings', 'action' => 'users']);
$router->get('settings/system', ['controller' => 'Settings', 'action' => 'system']);
$router->post('settings/system', ['controller' => 'Settings', 'action' => 'system']);
$router->get('settings/account-numbers', ['controller' => 'Settings', 'action' => 'accountNumbers']);
$router->post('settings/account-numbers', ['controller' => 'Settings', 'action' => 'accountNumbers']);

// Role routes
$router->get('roles', ['controller' => 'Role', 'action' => 'index']);
$router->get('roles/create', ['controller' => 'Role', 'action' => 'create']);
$router->post('roles/create', ['controller' => 'Role', 'action' => 'create']);
$router->get('roles/edit/{id}', ['controller' => 'Role', 'action' => 'edit']);
$router->post('roles/edit/{id}', ['controller' => 'Role', 'action' => 'edit']);
$router->get('roles/permissions/{id}', ['controller' => 'Role', 'action' => 'permissions']);
$router->post('roles/permissions/{id}', ['controller' => 'Role', 'action' => 'permissions']);
$router->get('roles/delete/{id}', ['controller' => 'Role', 'action' => 'delete']);

// Communication routes
$router->get('communication', ['controller' => 'Communication', 'action' => 'index']);
$router->get('communication/sms', ['controller' => 'Communication', 'action' => 'sms']);
$router->get('communication/email', ['controller' => 'Communication', 'action' => 'email']);
$router->get('communication/campaigns', ['controller' => 'Communication', 'action' => 'campaigns']);

// Payroll routes
$router->get('payroll', ['controller' => 'Payroll', 'action' => 'index']);
$router->get('payroll/employees', ['controller' => 'Payroll', 'action' => 'employees']);
$router->get('payroll/process', ['controller' => 'Payroll', 'action' => 'process']);

// Share routes
$router->get('shares', ['controller' => 'Share', 'action' => 'index']);
$router->get('shares/products', ['controller' => 'Share', 'action' => 'products']);
$router->get('shares/transactions', ['controller' => 'Share', 'action' => 'transactions']);

// Wallet routes
$router->get('wallet', ['controller' => 'Wallet', 'action' => 'index']);
$router->get('wallet/transactions', ['controller' => 'Wallet', 'action' => 'transactions']);

// Accounting routes
$router->get('accounting', ['controller' => 'Accounting', 'action' => 'index']);
$router->get('accounting/chart-of-accounts', ['controller' => 'Accounting', 'action' => 'chartOfAccounts']);
$router->get('accounting/journal-entries', ['controller' => 'Accounting', 'action' => 'journalEntries']);
$router->get('accounting/trial-balance', ['controller' => 'Accounting', 'action' => 'trialBalance']);
$router->get('accounting/financial-statements', ['controller' => 'Accounting', 'action' => 'financialStatements']);

// Asset routes
$router->get('assets', ['controller' => 'Asset', 'action' => 'index']);
$router->get('assets/create', ['controller' => 'Asset', 'action' => 'create']);
$router->post('assets/create', ['controller' => 'Asset', 'action' => 'create']);
$router->get('assets/view/{id}', ['controller' => 'Asset', 'action' => 'viewAsset']);
$router->get('assets/edit/{id}', ['controller' => 'Asset', 'action' => 'edit']);
$router->post('assets/edit/{id}', ['controller' => 'Asset', 'action' => 'edit']);
$router->get('assets/depreciation/{id}', ['controller' => 'Asset', 'action' => 'depreciation']);
$router->get('assets/maintenance/{id}', ['controller' => 'Asset', 'action' => 'maintenance']);
$router->get('assets/delete/{id}', ['controller' => 'Asset', 'action' => 'delete']);

// Income routes
$router->get('income', ['controller' => 'Income', 'action' => 'index']);
$router->get('income/create', ['controller' => 'Income', 'action' => 'create']);
$router->post('income/create', ['controller' => 'Income', 'action' => 'create']);
$router->get('income/view/{id}', ['controller' => 'Income', 'action' => 'viewIncome']);

// Expense routes
$router->get('expenses', ['controller' => 'Expense', 'action' => 'index']);
$router->get('expenses/create', ['controller' => 'Expense', 'action' => 'create']);
$router->post('expenses/create', ['controller' => 'Expense', 'action' => 'create']);
$router->get('expenses/view/{id}', ['controller' => 'Expense', 'action' => 'viewExpense']);

// Investor routes
$router->get('investors', ['controller' => 'Investor', 'action' => 'index']);
$router->get('investors/create', ['controller' => 'Investor', 'action' => 'create']);
$router->post('investors/create', ['controller' => 'Investor', 'action' => 'create']);
$router->get('investors/view/{id}', ['controller' => 'Investor', 'action' => 'viewInvestor']);

// Client Document routes
$router->get('client-documents/index/{client_id}', ['controller' => 'ClientDocument', 'action' => 'index']);
$router->get('client-documents/upload/{client_id}', ['controller' => 'ClientDocument', 'action' => 'upload']);
$router->post('client-documents/upload/{client_id}', ['controller' => 'ClientDocument', 'action' => 'upload']);

// Client Identification routes
$router->get('client-identification/index/{client_id}', ['controller' => 'ClientIdentification', 'action' => 'index']);
$router->get('client-identification/create/{client_id}', ['controller' => 'ClientIdentification', 'action' => 'create']);
$router->post('client-identification/create/{client_id}', ['controller' => 'ClientIdentification', 'action' => 'create']);

// Client Next of Kin routes
$router->get('client-next-of-kin/index/{client_id}', ['controller' => 'ClientNextOfKin', 'action' => 'index']);
$router->get('client-next-of-kin/create/{client_id}', ['controller' => 'ClientNextOfKin', 'action' => 'create']);
$router->post('client-next-of-kin/create/{client_id}', ['controller' => 'ClientNextOfKin', 'action' => 'create']);

// Loan Charges routes
$router->get('loan-charges', ['controller' => 'LoanCharge', 'action' => 'index']);
$router->get('loan-charges/create', ['controller' => 'LoanCharge', 'action' => 'create']);
$router->post('loan-charges/create', ['controller' => 'LoanCharge', 'action' => 'create']);

// Loan Collateral routes
$router->get('loan-collateral/index/{loan_id}', ['controller' => 'LoanCollateral', 'action' => 'index']);
$router->get('loan-collateral/create/{loan_id}', ['controller' => 'LoanCollateral', 'action' => 'create']);
$router->post('loan-collateral/create/{loan_id}', ['controller' => 'LoanCollateral', 'action' => 'create']);

// Loan Files routes
$router->get('loan-files/index/{loan_id}', ['controller' => 'LoanFile', 'action' => 'index']);
$router->get('loan-files/upload/{loan_id}', ['controller' => 'LoanFile', 'action' => 'upload']);
$router->post('loan-files/upload/{loan_id}', ['controller' => 'LoanFile', 'action' => 'upload']);

// Loan Application routes
$router->get('loan-applications', ['controller' => 'LoanApplication', 'action' => 'index']);
$router->get('loan-applications/create', ['controller' => 'LoanApplication', 'action' => 'create']);
$router->post('loan-applications/create', ['controller' => 'LoanApplication', 'action' => 'create']);

// Savings Charges routes
$router->get('savings-charges', ['controller' => 'SavingsCharge', 'action' => 'index']);
$router->get('savings-charges/create', ['controller' => 'SavingsCharge', 'action' => 'create']);
$router->post('savings-charges/create', ['controller' => 'SavingsCharge', 'action' => 'create']);

// Savings Files routes
$router->get('savings-files/index/{savings_id}', ['controller' => 'SavingsFile', 'action' => 'index']);

// Custom Fields routes
$router->get('custom-fields', ['controller' => 'CustomField', 'action' => 'index']);
$router->get('custom-fields/create', ['controller' => 'CustomField', 'action' => 'create']);
$router->post('custom-fields/create', ['controller' => 'CustomField', 'action' => 'create']);

// Payment Types routes
$router->get('payment-types', ['controller' => 'PaymentType', 'action' => 'index']);
$router->get('payment-types/create', ['controller' => 'PaymentType', 'action' => 'create']);
$router->post('payment-types/create', ['controller' => 'PaymentType', 'action' => 'create']);

// Tariffs routes
$router->get('tariffs', ['controller' => 'Tariff', 'action' => 'index']);

// Other Income routes
$router->get('other-income', ['controller' => 'OtherIncome', 'action' => 'index']);

// Funds routes
$router->get('funds', ['controller' => 'Fund', 'action' => 'index']);

// Currency routes
$router->get('currencies', ['controller' => 'Currency', 'action' => 'index']);

// Loan Purposes routes
$router->get('loan-purposes', ['controller' => 'LoanPurpose', 'action' => 'index']);

// Get the URL from the request
$url = $_SERVER['QUERY_STRING'] ?? '';

// Dispatch the router
try {
    $router->dispatch($url);
} catch (Exception $e) {
    if ($e->getCode() == 404) {
        http_response_code(404);
        echo "<h1>404 - Page Not Found</h1>";
        echo "<p>The page you are looking for does not exist.</p>";
    } else {
        http_response_code(500);
        echo "<h1>500 - Server Error</h1>";
        echo "<p>" . $e->getMessage() . "</p>";
        if (ini_get('display_errors')) {
            echo "<pre>" . $e->getTraceAsString() . "</pre>";
        }
    }
}
