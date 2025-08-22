<?php
    $currentUrl = $_SERVER['REQUEST_URI']; 
?>

<?php require_once APPROOT . '/helpers/date_helper.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITENAME; ?></title>
    <link rel="icon" type="image/png" href="<?php echo URLROOT; ?>/images/icons/icon.png"/>

    <link rel="stylesheet" href="<?php echo URLROOT;?>/public/css/buscss/backend/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <link rel="stylesheet" href="<?php echo URLROOT;?>/public/css/buscss/backend/adminprofile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <link rel="stylesheet" href="<?php echo URLROOT;?>/public/css/buscss/backend/addadmin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link rel="stylesheet" href="<?php echo URLROOT;?>/public/css/buscss/backend/customerlist.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/buscss/backend/operator.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/buscss/backend/addoperator.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/buscss/backend/operatorDetail.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/buscss/backend/editoperator.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/buscss/backend/route.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/buscss/backend/addroute.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/buscss/backend/booking.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/buscss/backend/payment.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>
<body>
        <aside class="sidebar fixed-sidebar">
            <div class="logo">
                <!-- <img src="<?php echo URLROOT; ?>/images/icons/icon.png"> -->
                <h1>MYBUSTICKET</h1>
            </div>
            <nav class="navigation">
                <ul>
                    <li class="<?php echo (strpos($currentUrl, '/pages/dashboard') !== false || strpos($currentUrl, '/user/profile') !== false) ? 'active' : ''; ?>">
                            <a href="<?php echo URLROOT; ?>/pages/dashboard">
                            <i class="fas fa-th-large"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="<?php echo (strpos($currentUrl, '/user/customer') !== false) ? 'active' : ''; ?>">
                        <a href="<?php echo URLROOT; ?>/user/customer">
                            <i class="fas fa-users"></i>
                            <span>Customers</span>
                        </a>
                    </li>
                    <li class="<?php echo (strpos($currentUrl, '/operator') !== false || strpos($currentUrl, '/operator/detail') !== false || strpos($currentUrl, '/operator/edit') !== false)? 'active' : ''; ?>">
                        <a href="<?php echo URLROOT; ?>/operator">
                            <i class="fas fa-bus"></i>
                            <span>Operators</span>
                        </a>
                    </li>
                    <li class="<?php echo (strpos($currentUrl, '/route') !== false) ? 'active' : ''; ?>">
                        <a href="<?php echo URLROOT; ?>/route">
                            <i class="fas fa-route"></i>
                            <span>Routes</span>
                        </a>
                    </li>
                    <!-- <li class="<?php echo (strpos($currentUrl, '/user/ticket') !== false) ? 'active' : ''; ?>">
                        <a href="">
                            <i class="fas fa-ticket-alt"></i>
                            <span>Tickets</span>
                        </a>
                    </li> -->
                      <li class="<?php echo (strpos($currentUrl, '/booking') !== false) ? 'active' : ''; ?>">
                        <a href="<?php echo URLROOT; ?>/booking">
                            <i class="fa-solid fa-book"></i>
                            <span>Bookings</span>
                        </a>
                    </li>
                    <li class="<?php echo (strpos($currentUrl, '/payment') !== false) ? 'active' : ''; ?>">
                        <a href="<?php echo URLROOT; ?>/payment">
                            <i class="fas fa-dollar-sign"></i>
                            <span>Payment</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo URLROOT; ?>/auth/logout">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>
        
</body>
</html>