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

    /* Change Password Button */
        .change-password-section {
            margin-top: 20px;
            text-align: left;  /* or right, if you prefer */
        }

        .change-password-btn {
            background-color: #3f51b5;       /* Bootstrap-like blue */
            color: #fff;
            height: 55px ;
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            transition: background-color 0.3s ease, transform 0.2s ease;
            text-decoration: none;
        }

        .change-password-btn i {
            margin-right: 6px;
            font-size: 16px;
        }

        .change-password-btn:hover {
            background-color: #0056b3;  /* darker on hover */
            transform: translateY(-1px);
        }

        .change-password-btn:active {
            transform: scale(0.98);
        }
        /* Basic styles for dropdown */
        a {
            text-decoration: none;
        }
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
<!--  -->
        <?php if (!empty($_SESSION['error'])): ?>
            <div id="flashMessage" class="flash-message error-message">
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($_SESSION['success'])): ?>
            <div id="flashMessage" class="flash-message success-message">
                <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>
    <div class="container">
        <main class="main-content">
                <?php require_once APPROOT . '/views/inc/profileHeader.php' ?>
            <main class="content-area">
                <section class="profile-card">
                    <!-- <div class="card-header">
                        <i class="fas fa-user-circle profile-icon"></i> Profile
                    </div> -->
                    <div class="user-overview">
                        <img src="<?php echo URLROOT; ?>/images/pf.png" alt="User Profile" class="user-avatar">
                        <div class="user-info">
                            <h2><?php echo $data['login_user']['name'] ?></h2>
                            <p>Current Admin</p>
                        </div>
                    </div>
                    <h3>Personal Information</h3>
                    <div class="personal-info-grid">
                        <div class="info-item">
                            <span class="label">Full Name</span>
                            <span class="value"><?php echo $data['login_user']['name'] ?></span>
                        </div>
                        <div class="info-item">
                            <span class="label">Role</span>
                            <span class="value"><?php echo $data['login_user']['role_name'] ?> <i class="fas fa-check-circle verified-icon"></i></span>
                        </div>
                        <div class="info-item">
                            <span class="label">Phone Number</span>
                            <span class="value"><?php echo $data['login_user']['phone'] ?></span>
                        </div>
                        <div class="info-item">
                            <span class="label">Email</span>
                            <span class="value"><?php echo $data['login_user']['email'] ?></span>
                        </div>
                        <div class="info-item">
                            <button type="button" class="change-password-btn">
                                <i class="fas fa-lock"></i> Change Password
                            </button>
                        </div>
                    </div>

            </section>

            <section class="admin-list-section">
                <div class="section-header">
                    <h2>Sub Admin List</h2>
                    <a href="<?php echo URLROOT; ?>/user/addadmin" style="text-decoration: none;"><button class="add-admin-btn"><i class="fas fa-plus"></i> Add admin</button></a>
                </div>

                <div class="admin-table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th style="text-align: center;">Delete</th>
                </tr>
            </thead>
            <tbody class="scrollable-tbody">
                <?php foreach ($data['user'] as $user): ?>
                    <tr>
                        <td><?php echo $user['name']; ?></td>
                        <td><?php echo $user['phone']; ?></td>
                        <td><?php echo $user['email']; ?></td>
                        <td style="text-align: center;">
                            <a href="<?php echo URLROOT; ?>/user/delete/<?php echo base64_encode($user['id']); ?>" 
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
        </main>
    </div>

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

    <div id="changePasswordModal" class="modal-overlay">
    <div class="modal-content">
        <h3>Change Password</h3>
        <?php require_once APPROOT . '/views/components/auth_message.php'; ?>
        <form id="changePasswordForm" method="POST" action="<?php echo URLROOT; ?>/auth/changepasswordadmin">
            <input type="password" id="newPassword" name="password" placeholder="New Password" required autocomplete="new-password">
            <input type="password" id="confirmNewPassword" name="confirm-password" placeholder="Confirm New Password" required autocomplete="new-password">
            <div class="show-password-container">
                <input type="checkbox" id="showPassword">
                <label for="showPassword">Show Password</label>
            </div>
            <div class="modal-buttons">
                <button type="submit" class="btn-yes">Change Password</button>
                <button type="button" class="btn-no" id="cancelPasswordChange">Cancel</button>
            </div>
        </form>
    </div>
</div>

</main>
</div>
<script>
    document.getElementById('showPassword').addEventListener('change', function() {
        const newPass = document.getElementById('newPassword');
        const confirmPass = document.getElementById('confirmNewPassword');
        const type = this.checked ? 'text' : 'password';
        newPass.type = type;
        confirmPass.type = type;
    });
</script>
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
        // --- Change Password Modal Logic ---
        // Get modal and buttons
        const changePasswordButtons = document.querySelectorAll('.change-password-btn');
        const changePasswordModal = document.getElementById('changePasswordModal');
        const cancelPasswordChangeBtn = document.getElementById('cancelPasswordChange');

        // Show modal when any change password button clicked
        changePasswordButtons.forEach(button => {
            button.addEventListener('click', (event) => {
                event.preventDefault();
                changePasswordModal.style.display = 'flex';
            });
        });

        // Hide modal when cancel button clicked
        cancelPasswordChangeBtn.addEventListener('click', () => {
            changePasswordModal.style.display = 'none';
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