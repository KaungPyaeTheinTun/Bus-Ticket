<?php require_once APPROOT . '/views/inc/sidebar.php' ?>

<style>
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

        /* Password Change Modal Specific Styles */
     /* Password Change Modal Specific Styles */
        #changePasswordModal .modal-content {
        text-align: left;
    }

    #changePasswordModal .modal-content h3 {
        text-align: center;
        margin-bottom: 20px;
    }

    #changePasswordModal form {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-top: 10px;
    }

    /* Input fields inside password modal */
    #changePasswordModal input[type="password"],
    #changePasswordModal input[type="text"] {
        width: 100%;
        padding: 12px 14px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 0.95em;
        outline: none;
        box-sizing: border-box;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    #changePasswordModal input[type="password"]:focus,
    #changePasswordModal input[type="text"]:focus {
        border-color: #3f51b5;
        box-shadow: 0 0 0 3px rgba(63, 81, 181, 0.2);
    }

    /* Show password checkbox container */
    #changePasswordModal .show-password-container {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.95em;
        color: #333;
        cursor: pointer;
        user-select: none;
    }
 
        /* Customize the checkbox itself */
        /* Show password checkbox container */
        @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
</style>
        <?php if (!empty($_SESSION['success'])): ?>
            <div id="flashMessage" class="flash-message success-message">
                <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

    <div class="container">
        <main class="main-content">
            <?php require_once APPROOT . '/views/inc/profileHeader.php' ?>
        <section class="customers-list-card">
                <div class="card-header">
                    <i class="fas fa-users"></i>&nbsp;&nbsp;&nbsp;Customers List
                </div>
                
                <div class="customer-table-container">
                    <table class="customer-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Phone Number</th>
                                <th>Total Tickets</th>
                                <th>Last Booking</th>
                                <th style="text-align: center;">Detail</th>
                                <th style="text-align: center;">Delete</th>
                            </tr>             
                        </thead>
                        <tbody>                
                           <?php foreach ($data['user'] as $user): ?>
                                <tr>
                                    <td><?php echo $user['name']; ?></td>
                                    <td><?php echo $user['phone']; ?></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <!-- <td style="text-align: center;">
                                        <a href="#" class="change-password-btn"><i class="fas fa-key action-icon"></i></a>
                                    </td> -->
                                    <td style="text-align: center;">
                                        <a href="<?php echo URLROOT; ?>/user/deletecustomer/<?php echo base64_encode($user['id']); ?>" 
                                            class="delete-admin-btn" >
                                            <i class="fas fa-trash-alt action-icon delete-icon"></i>
                                        </a>
                                    </td>
                                </tr>                                
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>
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

   <div id="personalDetailModal" class="modal">
        <div class="modal-content">
            <span class="close-button">&times;</span>
            <h2>Details</h2> <!-- Changed title for clarity -->
            <div class="modal-details">
                <p><strong>Name:</strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span id="modalName"></span></p>
                <p><strong>Phone:</strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span id="modalPhone"></span></p>
                <p><strong>Total Tickets:</strong> <span id="modalTotalTicket"></span></p>
                <p><strong>Last Booking:</strong> <span id="modalLastBooking"></span></p>
                <p><strong>Route:</strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span id="modalRoute"></span></p>
                <p><strong>Bus Operator:</strong> <span id="modalBusOperator"></span></p>
            </div>
        </div>
    </div>
    </div>
</body>
<script>
            const modal = document.getElementById("personalDetailModal");
            const closeButton = document.querySelector(".close-button");
            // Changed selector to target the new button class
            const viewButtons = document.querySelectorAll(".view-personal-detail-button"); 

            viewButtons.forEach(button => { // Iterating over buttons now
                button.addEventListener("click", function() {
                    const row = this.closest('tr'); 
                    
                    const name = row.querySelector('.booking-name').textContent;
                    const phone = row.querySelector('.booking-phone').textContent;
                    const ticket = row.querySelector('.booking-total-ticket').textContent;
                    const booking = row.querySelector('.booking-last-booking').textContent;
                    
                    document.getElementById("modalName").textContent = name;
                    document.getElementById("modalPhone").textContent = phone;
                    document.getElementById("modalTotalTicket").textContent = ticket;
                    document.getElementById("modalLastBooking").textContent = booking;
                    document.getElementById("modalRoute").textContent = this.dataset.route;
                    document.getElementById("modalBusOperator").textContent = this.dataset.operator;
                    
                    modal.style.display = "flex"; 
                });
            });

            closeButton.addEventListener("click", function() {
                modal.style.display = "none"; 
            });

            window.addEventListener("click", function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            });

            // The togglePasswordVisibility function is not used in this file,
            // but I'm keeping it for completeness if you copy-pasted it.
            function togglePasswordVisibility(id) {
                const passwordInput = document.getElementById(id);
                const toggleIcon = passwordInput.nextElementSibling.querySelector('i');
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    toggleIcon.classList.remove('fa-eye');
                    toggleIcon.classList.add('fa-eye-slash');
                } else {
                    passwordInput.type = 'password';
                    toggleIcon.classList.remove('fa-eye-slash');
                    toggleIcon.classList.add('fa-eye');
                }
            }

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
</script>
<script>
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
