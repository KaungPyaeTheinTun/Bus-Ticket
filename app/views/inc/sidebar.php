<?php
    $currentUrl = $_SERVER['REQUEST_URI']; 
    // example: /mvc-bus-ticket/pages/dashboard
?>
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

    
</head>
<body>
        <aside class="sidebar fixed-sidebar"> <div class="logo">
                <h1>MYTICKET</h1>
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
                    <li class="<?php echo (strpos($currentUrl, '/user/operator') !== false) ? 'active' : ''; ?>">
                        <a href="">
                            <i class="fa-solid fa-book"></i>
                            <span>Operators</span>
                        </a>
                    </li>
                    <li class="<?php echo (strpos($currentUrl, '/user/route') !== false) ? 'active' : ''; ?>">
                        <a href="">
                            <i class="fas fa-route"></i>
                            <span>Routes</span>
                        </a>
                    </li>
                    <li class="<?php echo (strpos($currentUrl, '/user/ticket') !== false) ? 'active' : ''; ?>">
                        <a href="">
                            <i class="fas fa-ticket-alt"></i>
                            <span>Tickets</span>
                        </a>
                    </li>
                      <li class="<?php echo (strpos($currentUrl, '/user/booking') !== false) ? 'active' : ''; ?>">
                        <a href="">
                            <i class="fa-solid fa-book"></i>
                            <span>Bookings</span>
                        </a>
                    </li>
                    <li class="<?php echo (strpos($currentUrl, '/user/payment') !== false) ? 'active' : ''; ?>">
                        <a href="">
                            <i class="fas fa-dollar-sign"></i>
                            <span>Payment</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo URLROOT; ?>/user/logout">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>
        
</body>
</html>