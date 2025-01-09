<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'db_connect.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MR.FREDDIE Repair Shop & Leather Craft</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #ff6b6b;
            --primary-hover: #ff5252;
        }
        
        .navbar {
            background-color: rgba(255, 255, 255, 0.95) !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            height: 70px;
            padding: 0 1rem;
        }

        .navbar-brand {
            padding: 0;
            display: flex;
            align-items: center;
            height: 100%;
        }

        .navbar-brand img {
            max-height: 65px;
            width: auto;
            object-fit: contain;
            transform: scale(4);
            transform-origin: left center;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
        }

        .sidebar {
            height: calc(100vh - 76px);
            width: 250px;
            position: fixed;
            top: 76px;
            left: 0;
            background: #f8f9fa;
            border-right: 1px solid #dee2e6;
            padding: 1rem;
        }

        .main-content {
            margin-left: 250px;
            padding: 2rem;
        }

        .nav-link {
            color: #333;
            padding: 0.5rem 1rem;
            margin: 0.2rem 0;
            border-radius: 0.25rem;
        }

        .nav-link:hover {
            background-color: #f0f0f0;
        }

        .nav-link.active {
            background-color: var(--primary-color);
            color: white;
        }

        .card {
            transition: transform 0.2s;
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .card:hover {
            transform: translateY(-5px);
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
                top: 0;
            }
            .main-content {
                margin-left: 0;
            }
        }

        .bi-person-circle {
            color: #000 !important;
        }

        #dropdownUser {
            color: #000 !important;
        }

        #dropdownUser span {
            color: #000 !important;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="dashboard.php">
                <img src="../assets/images/mr-freddie.png" alt="MR.FREDDIE Logo" style="height: 40px; width: auto;">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <div class="dropdown">
                            <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle fs-4 me-2"></i>
                                <span>Profile</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownUser">
                                <li><a class="dropdown-item" href="../profile.php">Profile</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="../logout.php">Log Out</a></li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</body>
</html>
