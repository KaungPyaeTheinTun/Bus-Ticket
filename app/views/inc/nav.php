<?php 
        if(session_status() == PHP_SESSION_NONE) {
            session_start();
        }
?>

<?php require_once APPROOT . '/helpers/date_helper.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITENAME; ?></title>
    <link rel="stylesheet" href="<?php echo URLROOT ;  ?>/public/css/buscss/home/record.css">
     
    <link rel="icon" type="image/png" href="<?php echo URLROOT; ?>/images/icons/icon.png"/>

    <link rel="stylesheet" href="<?php echo URLROOT ;  ?>/public/css/buscss/home/style.css"> <!-- Ensure this path is correct -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/buscss/home/trip.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/buscss/home/selectseat.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/buscss/home/payment.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>
<style>
    .animated-link h1 {
        display: inline-block;
        position: relative;
        color:rgb(255, 255, 255);
        transition: color 0.3s ease;
        cursor: pointer;
    }

    .animated-link h1::after {
        content: '';
        position: absolute;
        width: 0;
        height: 3px;
        bottom: -5px;
        left: 0;
        background-color:rgb(255, 255, 255); /* underline color */
        transition: width 0.3s ease;
    }

    .animated-link:hover h1 {
        color:rgb(255, 255, 255); /* text color on hover */
    }

    .animated-link:hover h1::after {
        width: 100%; /* underline expands on hover */
    }
</style>
<body>
    <header class="main-header">
        <div class="header-content">
            <div class="logo">
                <img src="<?php echo URLROOT; ?>/images/icons/icon.png">
                <a href="<?php echo URLROOT; ?>/pages/index/#" class="animated-link"><h1>MYBUSTICKET</h1></a>
            </div>
            <nav class="main-nav" id="main-nav">
                <ul>
                    <li><a href="<?php echo URLROOT; ?>/pages/index/#">Home</a></li>
                    <li><a href="<?php echo URLROOT; ?>/pages/index/#operator">Bus Operator</a></li>
                    <?php if (isset($_SESSION['session_loginuserid'])): ?>
                        <li><a href="<?php echo URLROOT; ?>/home/record">Record</a></li>
                        <li><a href="<?php echo URLROOT; ?>/auth/logout">Logout</a></li>
                    <?php else: ?>
                        <li><a href="<?php echo URLROOT; ?>/pages/login">Login</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
            <div class="menu-toggle" id="mobile-menu">
                <i class="fas fa-bars"></i>
            </div>
        </div>

    </header>
<br><br><br>
