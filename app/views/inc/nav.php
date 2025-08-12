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
         /* Styles for the Modal (Popup Box) */
    .modal {
        display: none; /* Hidden by default */
        position: fixed; /* Stay in place */
        z-index: 1000; /* Sit on top */
        left: 0;
        top: 0;
        width: 100%; /* Full width */
        height: 100%; /* Full height */
        overflow: auto; /* Enable scroll if needed */
        background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
        justify-content: center; /* Center horizontally */
        align-items: center; /* Center vertically */
    }

    .modal-content {
        background-color: #fefefe;
        margin: auto; /* For older browsers/center fallback */
        padding: 20px;
        border: 1px solid #888;
        width: 80%; /* Could be a fixed width or max-width */
        max-width: 400px; /* Max width for larger screens */
        border-radius: 8px;
        position: relative; /* Needed for absolute positioning of close button */
        box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);
        animation-name: animatetop;
        animation-duration: 0.4s;
    }

    /* Add Animation (Optional) */
    @keyframes animatetop {
        from {top: -300px; opacity: 0}
        to {top: 0; opacity: 1}
    }


    .close-button {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        position: absolute; /* Position relative to .modal-content */
        top: 10px;
        right: 15px;
    }

    .close-button:hover,
    .close-button:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }

    #logoutButton {
        background-color: #f44336; /* Red */
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        margin-top: 15px;
        width: 100%;
        font-size: 16px;
    }

    #logoutButton:hover {
        background-color: #da190b;
    }
</style>
<body>
    <header class="main-header">
        <div class="header-content">
            <div class="logo">
                <h1>MYTICKET</h1>
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
