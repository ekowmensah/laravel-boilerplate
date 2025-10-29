<?php
if (!defined('APP_NAME')) {
    require_once dirname(__DIR__, 3) . '/config/config.php';
}

use Core\Session;

Session::requireLogin();
$user = Session::getUser();
$currentPage = basename($_SERVER['REQUEST_URI']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Dashboard'; ?> - <?php echo APP_NAME; ?></title>
    
    <!-- CoreUI CSS -->
    <link href="https://cdn.jsdelivr.net/npm/@coreui/coreui@4.2.0/dist/css/coreui.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@coreui/icons/css/all.min.css" rel="stylesheet">
    
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="<?php echo BASE_URL; ?>assets/css/custom.css" rel="stylesheet">
    
    <style>
        :root {
            --sidebar-width: 260px;
            --sidebar-narrow-width: 70px;
        }
        
        body {
            margin: 0;
            padding: 0;
        }
        
        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, #2c3e50 0%, #34495e 100%);
            transition: all 0.3s ease;
            z-index: 1030;
            overflow-y: auto;
            overflow-x: hidden;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        
        .sidebar-nav {
            padding: 0.5rem 0 3rem 0;
        }
        
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }
        
        .sidebar::-webkit-scrollbar-track {
            background: rgba(0,0,0,0.1);
        }
        
        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.2);
            border-radius: 3px;
        }
        
        .sidebar-brand {
            padding: 1.5rem 1rem;
            background: rgba(0,0,0,0.2);
            border-bottom: 1px solid rgba(255,255,255,0.1);
            text-align: center;
        }
        
        .sidebar-brand strong {
            color: #fff;
            font-size: 1.5rem;
            font-weight: 600;
        }
        
        /* Navigation Styles */
        .sidebar-nav {
            padding: 0;
            margin: 0;
            list-style: none;
        }
        
        .nav-title {
            padding: 1rem 1rem 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            color: rgba(255,255,255,0.5);
            letter-spacing: 0.5px;
        }
        
        .nav-item {
            margin: 0;
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: #ecf0f1;
            text-decoration: none;
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
        }
        
        .nav-link:hover {
            background: rgba(255,255,255,0.1);
            color: #fff;
            border-left-color: #3498db;
        }
        
        .nav-link.active {
            background: rgba(52, 152, 219, 0.2);
            color: #fff;
            border-left-color: #3498db;
        }
        
        .nav-icon {
            width: 20px;
            margin-right: 10px;
            text-align: center;
        }
        
        /* Dropdown Menu Styles */
        .nav-group {
            position: relative;
        }
        
        .nav-group-toggle {
            cursor: pointer;
            position: relative;
        }
        
        .nav-group-toggle::after {
            content: '▼';
            position: absolute;
            right: 1rem;
            font-size: 0.7rem;
            transition: transform 0.3s ease;
        }
        
        .nav-group.show .nav-group-toggle::after {
            transform: rotate(180deg);
        }
        
        .nav-group-items {
            display: none;
            background: rgba(0,0,0,0.2);
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .nav-group.show .nav-group-items {
            display: block;
        }
        
        .nav-group-items .nav-link {
            padding-left: 3rem;
            font-size: 0.9rem;
        }
        
        /* Main Content Area */
        .wrapper {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            background: #f5f7fa;
            transition: margin-left 0.3s ease;
        }
        
        /* Header */
        .header {
            background: #fff;
            border-bottom: 1px solid #e0e0e0;
            padding: 1rem 1.5rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 1020;
        }
        
        /* Content Area */
        .body {
            padding: 1.5rem;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .wrapper {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar" id="sidebar">
        <div class="sidebar-brand d-none d-md-flex">
            <div class="sidebar-brand-full">
                <strong>C19 Banking</strong>
            </div>
            <div class="sidebar-brand-narrow">
                <strong>C19</strong>
            </div>
        </div>
        
        <ul class="sidebar-nav">
            <!-- Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="<?php echo BASE_URL; ?>dashboard">
                    <i class="nav-icon cil-speedometer"></i> Dashboard
                </a>
            </li>
            
            <!-- Clients & Groups -->
            <li class="nav-group">
                <a class="nav-link nav-group-toggle" href="#">
                    <i class="nav-icon cil-people"></i> Clients & Groups
                </a>
                <ul class="nav-group-items">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>clients">
                            <i class="nav-icon cil-people"></i> All Clients
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>clients/create">
                            <i class="nav-icon cil-user-plus"></i> New Client
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>groups">
                            <i class="nav-icon cil-group"></i> All Groups
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>groups/create">
                            <i class="nav-icon cil-plus"></i> New Group
                        </a>
                    </li>
                </ul>
            </li>
            
            <!-- Loans -->
            <li class="nav-group">
                <a class="nav-link nav-group-toggle" href="#">
                    <i class="nav-icon cil-dollar"></i> Loans
                </a>
                <ul class="nav-group-items">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>loans">
                            <i class="nav-icon cil-list"></i> All Loans
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>loans/create">
                            <i class="nav-icon cil-file"></i> New Loan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>loans?status=active">
                            <i class="nav-icon cil-check-circle"></i> Active Loans
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>loans?overdue=1">
                            <i class="nav-icon cil-warning"></i> Overdue Loans
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>loan-types">
                            <i class="nav-icon cil-tags"></i> Loan Types
                        </a>
                    </li>
                </ul>
            </li>
            
            <!-- Savings -->
            <li class="nav-group">
                <a class="nav-link nav-group-toggle" href="#">
                    <i class="nav-icon cil-bank"></i> Savings
                </a>
                <ul class="nav-group-items">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>savings">
                            <i class="nav-icon cil-list"></i> All Savings
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>savings/create">
                            <i class="nav-icon cil-plus"></i> New Account
                        </a>
                    </li>
                </ul>
            </li>
            
            <!-- Transactions -->
            <li class="nav-item">
                <a class="nav-link" href="<?php echo BASE_URL; ?>transactions">
                    <i class="nav-icon cil-list"></i> Transactions
                </a>
            </li>
            
            <!-- Reports -->
            <li class="nav-group">
                <a class="nav-link nav-group-toggle" href="#">
                    <i class="nav-icon cil-chart-line"></i> Reports
                </a>
                <ul class="nav-group-items">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>reports">
                            <i class="nav-icon cil-speedometer"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>reports/loan-portfolio">
                            <i class="nav-icon cil-chart"></i> Loan Portfolio
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>reports/arrears">
                            <i class="nav-icon cil-warning"></i> Arrears
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>reports/disbursement">
                            <i class="nav-icon cil-money"></i> Disbursements
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>reports/collection">
                            <i class="nav-icon cil-credit-card"></i> Collections
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>reports/savings-balance">
                            <i class="nav-icon cil-wallet"></i> Savings Balances
                        </a>
                    </li>
                </ul>
            </li>
            
            <!-- Communication -->
            <li class="nav-item">
                <a class="nav-link" href="<?php echo BASE_URL; ?>communication">
                    <i class="nav-icon cil-envelope-closed"></i> Communication
                </a>
            </li>
            
            <!-- Payroll -->
            <li class="nav-item">
                <a class="nav-link" href="<?php echo BASE_URL; ?>payroll">
                    <i class="nav-icon cil-money"></i> Payroll
                </a>
            </li>
            
            <!-- Shares -->
            <li class="nav-item">
                <a class="nav-link" href="<?php echo BASE_URL; ?>shares">
                    <i class="nav-icon cil-chart-pie"></i> Shares
                </a>
            </li>
            
            <!-- Wallet -->
            <li class="nav-item">
                <a class="nav-link" href="<?php echo BASE_URL; ?>wallet">
                    <i class="nav-icon cil-wallet"></i> Wallet
                </a>
            </li>
            
            <!-- Accounting -->
            <li class="nav-item">
                <a class="nav-link" href="<?php echo BASE_URL; ?>accounting">
                    <i class="nav-icon cil-calculator"></i> Accounting/GL
                </a>
            </li>
            
            <!-- Finance -->
            <li class="nav-group">
                <a class="nav-link nav-group-toggle" href="#">
                    <i class="nav-icon cil-dollar"></i> Finance
                </a>
                <ul class="nav-group-items">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>income">
                            <i class="nav-icon cil-arrow-top"></i> Income
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>expenses">
                            <i class="nav-icon cil-arrow-bottom"></i> Expenses
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>other-income">
                            <i class="nav-icon cil-plus"></i> Other Income
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>investors">
                            <i class="nav-icon cil-people"></i> Investors
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>funds">
                            <i class="nav-icon cil-briefcase"></i> Funds
                        </a>
                    </li>
                </ul>
            </li>
            
            <!-- Assets -->
            <li class="nav-item">
                <a class="nav-link" href="<?php echo BASE_URL; ?>assets">
                    <i class="nav-icon cil-home"></i> Assets
                </a>
            </li>
            
            <!-- System -->
            <li class="nav-group">
                <a class="nav-link nav-group-toggle" href="#">
                    <i class="nav-icon cil-settings"></i> System
                </a>
                <ul class="nav-group-items">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>settings">
                            <i class="nav-icon cil-speedometer"></i> Settings Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>roles">
                            <i class="nav-icon cil-lock-locked"></i> Roles & Permissions
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>settings/branches">
                            <i class="nav-icon cil-building"></i> Branches
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>settings/products">
                            <i class="nav-icon cil-tags"></i> Products
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>settings/users">
                            <i class="nav-icon cil-people"></i> Users
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>field-agents">
                            <i class="nav-icon cil-user"></i> Field Agents
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>settings/system">
                            <i class="nav-icon cil-cog"></i> System Settings
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>payment-types">
                            <i class="nav-icon cil-credit-card"></i> Payment Types
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>currencies">
                            <i class="nav-icon cil-dollar"></i> Currencies
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>custom-fields">
                            <i class="nav-icon cil-input"></i> Custom Fields
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>tariffs">
                            <i class="nav-icon cil-list"></i> Tariffs
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>loan-purposes">
                            <i class="nav-icon cil-task"></i> Loan Purposes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>loan-charges">
                            <i class="nav-icon cil-money"></i> Loan Charges
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>savings-charges">
                            <i class="nav-icon cil-wallet"></i> Savings Charges
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>loan-applications">
                            <i class="nav-icon cil-file"></i> Loan Applications
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
    
    <div class="wrapper d-flex flex-column min-vh-100 bg-light">
        <header class="header header-sticky mb-4">
            <div class="container-fluid">
                <button class="header-toggler px-md-0 me-md-3" type="button" onclick="coreui.Sidebar.getInstance(document.querySelector('#sidebar')).toggle()">
                    <i class="icon cil-menu"></i>
                </button>
                
                <ul class="header-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link py-0" data-coreui-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                            <div class="avatar avatar-md">
                                <span class="avatar-status bg-success"></span>
                                <div class="avatar-text bg-primary"><?php echo strtoupper(substr($user['first_name'], 0, 1)); ?></div>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end pt-0">
                            <div class="dropdown-header bg-light py-2">
                                <strong><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></strong>
                            </div>
                            <a class="dropdown-item" href="<?php echo BASE_URL; ?>profile">
                                <i class="icon cil-user me-2"></i> Profile
                            </a>
                            <a class="dropdown-item" href="<?php echo BASE_URL; ?>settings">
                                <i class="icon cil-settings me-2"></i> Settings
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="<?php echo BASE_URL; ?>auth/logout">
                                <i class="icon cil-account-logout me-2"></i> Logout
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
        </header>
        
        <div class="body flex-grow-1 px-3">
            <div class="container-lg">
