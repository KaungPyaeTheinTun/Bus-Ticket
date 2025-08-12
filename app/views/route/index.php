<?php require_once APPROOT . '/views/inc/sidebar.php' ?>

<?php if (!empty($_SESSION['success'])): ?>
    <div id="flashMessage" class="flash-message success-message">
        <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<?php 
   $today = date('Y-m-d'); 
?>

<style>
    input[type="date"] {
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 13px;
        background-color: #f9f9f9;
        color: #333;
        width: 120px;
        box-sizing: border-box;
        transition: border-color 0.3s, box-shadow 0.3s;
        /* margin-left:-30px; */
    }

    input[type="date"]:focus {
        border-color: #3498db;
        box-shadow: 0 0 5px rgba(52, 152, 219, 0.5);
        outline: none;
    }

    input[type="date"]::-webkit-calendar-picker-indicator {
        filter: invert(0.5); /* change icon color */
        cursor: pointer;
    }
    .all-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background-color:#28a745; 
        color: white;
        border: none;
        padding: 8px 12px;
        border-radius: 5px;
        font-size: 0.9em;
        margin-left: 8px;
        text-decoration: none;
        transition: background-color 0.3s ease;
    }

    .all-button i {
        margin-right: 5px;
    }

    .all-button:hover {
        background-color: #5a6268;
    }
        .detail-button {
            background-color:green; 
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            font-size: 0.9em;
            cursor: pointer;
            transition: background-color 0.2s ease;
            display: flex;
            align-items: center;
        }
        .detail-button i {
            margin-right: 5px;
        }

        .detail-button:hover {
            background-color:rgb(8, 183, 43);
        }
        .flash-message {
            position: fixed;
            top: 28px;
            left: 50%;
            /* transform: translateX(0); */
            padding: 16px 24px;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 500;
            z-index: 9999;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            animation: fadeInScale 0.3s ease;
        }

        .success-message {
            background-color: #d4edda;
            color: #155724;
            /* border-left: 5px solid #28a745; */
        }

        .error-message {
            background-color:rgb(239, 154, 161);
            color: #721c24;
            /* border-left: 5px solid #dc3545; */
        }
        @keyframes fadeOut {
            0% {
                opacity: 1;
                transform: scale(1);
            }
            100% {
                opacity: 0;
                transform:  scale(0.9);
            }
        }
    /* Add or adjust these styles in your customerlist.css for better separation */
        .customer-table button.view-personal-detail-button,
        .customer-table button.delete-button { /* Applied similar styling for consistency */
            background-color: #0d47a1; /* Blue for view button */
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.9em;
            transition: background-color 0.3s ease;
            display: inline-flex; /* Use flex to align icon and text if both were present */
            align-items: center;
            justify-content: center;
            min-width: 70px; /* Give it a minimum width */
        }

        .customer-table button.view-personal-detail-button:hover {
            background-color: #0a3a80; /* Darker blue on hover */
        }

        .customer-table button.delete-button {
            background-color: #dc3545; /* Red for delete button */
        }

        .customer-table button.delete-button:hover {
            background-color: #c82333; /* Darker red on hover */
        }

        /* Adjust icon spacing within buttons if needed */
        .customer-table button i {
            margin-right: 5px; /* Space between icon and text if text is added */
        }
        
        /* Ensure table cells have appropriate padding */
        .customer-table th, .customer-table td {
            padding: 12px 8px; /* Adjust as needed */
        }
        /* --- Styles for Modals (Delete and Password Change) --- */
                /* --- Styles for Modals (Delete and Password Change) --- */
        .modal-overlay {
            display: none; /* Hidden by default */
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6); /* Semi-transparent black background */
            justify-content: center;
            align-items: center;
            z-index: 1000; /* Ensure it's above other content */
        }

        .modal-content {
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            text-align: center;
            max-width: 400px;
            width: 90%;
            animation: fadeInScale 0.3s ease-out; /* Simple animation */
        }

        .modal-content h3 {
            margin-top: 0;
            color: #333;
            font-size: 1.5em;
            margin-bottom: 20px;
        }

        .modal-buttons {
            margin-top: 25px;
            display: flex;
            justify-content: center;
            gap: 15px; /* Space between buttons */
        }

        .modal-buttons button {
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.2s ease;
        }

        .modal-buttons .btn-yes {
            background-color: #e74c3c; /* Red for Yes/Delete */
            color: white;
        }

        .modal-buttons .btn-yes:hover {
            background-color: #c0392b;
        }

        .modal-buttons .btn-no {
            background-color: #bdc3c7; /* Grey for No/Cancel */
            color: #333;
        }

        .modal-buttons .btn-no:hover {
            background-color: #95a5a6;
        }

</style>

    <div class="container">  
        <main class="main-content">
            <?php require_once APPROOT . '/views/inc/profileHeader.php' ?>
       <section class="all-routes-section">
                <div class="routes-header-controls">
                    <h2 class="section-title">All Routes</h2>
                    <div class="search-and-add">
                        <!-- <div class="search-bar">
                            <input type="text" placeholder="From" class="search-input">
                            <span class="ex-icon"><i class="fas fa-arrow-right"></i></span>
                            <input type="text" placeholder="To" class="search-input">
                            &nbsp;&nbsp;&nbsp;
                            <input type="date" style="height: 30px;">
                            
                           <button class="search-button"><i class="fas fa-search"></i></button>
                           
                        </div> -->
                        <a href="<?php echo URLROOT; ?>/route/index" class="all-button" title="Show all routes">
                                <i class="fas fa-sync-alt"></i> All
                        </a>
                        <form method="get" action="<?php echo URLROOT; ?>/route/index" class="search-bar">
                            <input type="text" name="from" placeholder="From" class="search-input" 
                                value="<?php echo isset($_GET['from']) ? htmlspecialchars($_GET['from']) : ''; ?>">
                            <span class="ex-icon"><i class="fas fa-arrow-right"></i></span>
                            <input type="text" name="to" placeholder="To" class="search-input"
                                value="<?php echo isset($_GET['to']) ? htmlspecialchars($_GET['to']) : ''; ?>">
                            &nbsp;&nbsp;&nbsp;
                            <input type="date" name="date" style="height: 30px;"
                             min="<?php echo $today; ?>"
                                value="<?php echo isset($_GET['date']) ? htmlspecialchars($_GET['date']) : ''; ?>">
                            <button type="submit" class="search-button"><i class="fas fa-search"></i></button>
                        </form>
                        <a href="<?php echo URLROOT; ?>/route/create"><button class="add-routes-button"><i class="fas fa-plus"></i> Add Routes</button></a>
                    </div>
                </div>

                <div class="route-cards-container">
                    <?php if (!empty($data['route']) && is_array($data['route'])): ?>
                        <?php foreach ($data['route'] as $route): ?>
                            <?php
                                $depRaw = $route['departure_time'];
                                $arrRaw = $route['arrival_time'];

                                $depDateTime = new DateTime($depRaw);
                                $arrDateTime = new DateTime($arrRaw);

                                // Detect badge based on departure hour
                                $hour = (int)$depDateTime->format('H'); // 24-hour format
                                    if ($hour >= 5 && $hour < 12) {
                                        $badge = "Day";
                                    } elseif ($hour >= 12 && $hour < 17) {
                                        $badge = "Afternoon";
                                    } else {
                                        $badge = "Night";
                                    }
                            ?>
                            <div class="route-card">
                                <div class="route-details">
                                    <div class="main-route">
                                        <?php echo htmlspecialchars($route['from']); ?> - <?php echo htmlspecialchars($route['to']); ?> 
                                        <span class="badge"><?php echo $badge; ?></span>
                                        <span class="badge"><?php echo htmlspecialchars($route['bus_type']); ?></span>
                                    </div>
                                    <div class="departure-arrival-info">
                                        <span><?php echo htmlspecialchars($route['from']); ?> - <?php echo formatDate($route['departure_time']); ?> (Departs At)</span>
                                        <span><?php echo htmlspecialchars($route['to']); ?> - <?php echo formatDate($route['arrival_time']); ?> (Estimate Arrival)</span>
                                    </div>
                                    <div class="bus-operator-info">
                                        <span><?php echo htmlspecialchars($route['operator_name']); ?></span>
                                        <span><?php echo htmlspecialchars($route['operator_phone']); ?></span>
                                    </div>
                                </div>
                                <div class="trip-operator-info">
                                    <img src="<?php echo URLROOT; ?>/public/uploads/routes_images/<?php echo htmlspecialchars($route['image']); ?>" class="operator-logo">
                                    <p class="operator-name"><?php echo htmlspecialchars($route['operator_name']); ?></p>
                                    <!-- <p class="departure-point">Yangon, Aung Mingalar (Departs Gate)</p> -->
                                </div>
                                <div class="route-actions">
                                    <div class="price-info">
                                        <span class="price-value">MMK <?php echo htmlspecialchars($route['price']); ?></span>
                                        <span class="seat-price">1 seat x <?php echo htmlspecialchars($route['price']); ?></span>
                                    </div>
                                    <a href="<?php echo URLROOT; ?>/route/delete/<?php echo base64_encode($route['id']); ?>" class="delete-admin-btn">
                                        <button class="delete-button"><i class="fas fa-trash-alt"></i> Delete</button>
                                    </a>
                                    <a href="<?php echo URLROOT; ?>/route/detail?id=<?php echo base64_encode($route['id']); ?>">
                                        <button class="detail-button"><i class="fas fa-file-alt"></i> Details</button>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <?php else: ?>
                                <td colspan="7" style="text-align:center;">No operators found.</td>
                        <?php endif; ?>
                </div>
            </section>

    </div>
</body>
<div id="deleteConfirmationModal" class="modal-overlay">
                <div class="modal-content">                         
                    <form id="deleteForm" method="POST">
                        <h3>Are you sure you want to delete <span id="adminNameToDelete"></span>?</h3>
                        <p>This action cannot be undone.</p>
                        <div class="modal-buttons">

                            <button class="btn-yes" id="confirmDeleteYes">Yes, Delete</button>
                            
                            <button type="button" class="btn-no" id="confirmDeleteNo">No, Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
<script>
    const deleteButtons = document.querySelectorAll('.delete-admin-btn');
                    const deleteModal = document.getElementById('deleteConfirmationModal');
                    const adminNameToDeleteSpan = document.getElementById('adminNameToDelete');
                    const confirmDeleteNoBtn = document.getElementById('confirmDeleteNo');
                    const deleteForm = document.getElementById('deleteForm');

                    deleteButtons.forEach(button => {
                        button.addEventListener('click', function(event) {
                            event.preventDefault();
                            const deleteUrl = this.getAttribute('href');
                            const adminName = this.dataset.name;
                            deleteForm.action = deleteUrl;
                            adminNameToDeleteSpan.textContent = adminName;
                            deleteModal.style.display = 'flex';
                        });
                    });

                    confirmDeleteNoBtn.addEventListener('click', function() {
                        deleteModal.style.display = 'none';
                    });

                    deleteModal.addEventListener('click', function(event) {
                        if (event.target === deleteModal) {
                            deleteModal.style.display = 'none';
                        }
                    });

    // Auto-hide flash message after 2 seconds
    const flashMessage = document.getElementById('flashMessage');
    if (flashMessage) {
        setTimeout(() => {
            flashMessage.style.animation = "fadeOut 0.5s forwards";
            setTimeout(() => flashMessage.remove(), 500); // Remove after fadeOut completes
        }, 2000); // Show for 2 seconds
    }
</script>

</html>