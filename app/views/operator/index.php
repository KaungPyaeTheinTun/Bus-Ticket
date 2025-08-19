<?php require_once APPROOT . '/views/inc/sidebar.php' ?>

<?php if (!empty($_SESSION['success'])): ?>
    <div id="flashMessage" class="flash-message success-message">
        <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<?php if (!empty($_SESSION['error'])): ?>
    <div id="flashMessage" class="flash-message error-message">
        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>

<style>
    .radio-group {
        display: flex;
        justify-content: space-between;  /* pushes VIP left, Normal right */
        width: 100%;
        margin-top: 10px;
        
    }

    .radio-group label {
        font-size: 15px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .modal-content .input-with-icon .text-input {
            padding-left: 35px; /* Make space for the icon on the left */
            width: 100%; /* Ensure input fills the wrapper */
        }

    .modal-content form .form-group {
        margin-bottom: 15px;
    }

    .modal-content form input.text-input {
        width: 100%;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }
    .flash-message {
        position: fixed;
        top: 28px;
        left: 45%;
        /* transform: translateX(-50%); */
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
    }

    .error-message {
        background-color:rgb(239, 154, 161);
        color: #721c24;
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

    /* Buttons */
    .customer-table button.view-personal-detail-button,
    .customer-table button.delete-button {
        background-color: #0d47a1;
        color: white;
        border: none;
        padding: 8px 12px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 0.9em;
        transition: background-color 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 70px;
    }

    .customer-table button.view-personal-detail-button:hover {
        background-color: #0a3a80;
    }

    .customer-table button.delete-button {
        background-color: #dc3545;
    }

    .customer-table button.delete-button:hover {
        background-color: #c82333;
    }

    .customer-table button i {
        margin-right: 5px;
    }

    .customer-table th, .customer-table td {
        padding: 12px 8px;
    }

    /* Modals */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        justify-content: center;
        align-items: center;
        z-index: 1000;
    }

    .modal-content {
        background: #fff;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        text-align: center;
        max-width: 400px;
        width: 90%;
        animation: fadeInScale 0.3s ease-out;
    }

    .modal-content h3 {
        margin-top: 0;
        color: #333;
        font-size: 1.5em;
        margin-bottom: 20px;
        text-align:left;
    }

    .modal-buttons {
        margin-top: 25px;
        display: flex;
        justify-content: center;
        gap: 15px;
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
        background-color: #e74c3c;
        color: white;
    }

    .modal-buttons .btn-yes:hover {
        background-color: #c0392b;
    }

    .modal-buttons .btn-no {
        background-color: #bdc3c7;
        color: #333;
    }

    .modal-buttons .btn-no:hover {
        background-color: #95a5a6;
    }

    /* Input with icon */
    .input-with-icon {
        position: relative;
    }

    .input-with-icon .icon {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        color: #555;
    }

    .input-with-icon .text-input {
        padding-left: 30px;
    }
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

<div class="container">

    <main class="main-content">
        <?php require_once APPROOT . '/views/inc/profileHeader.php' ?>

        <section class="operators-list-card">
            <div class="card-header-with-button">
                <h2 class="card-title"><i class="fas fa-bus"></i>&nbsp;&nbsp;&nbsp;Operators List</h2>
                <button class="add-button" id="openAddOperatorModal"><i class="fas fa-plus"></i> Add Operator</button>
            </div>
            
            <div class="operator-table-container">
                <table class="operator-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Phone Number</th>
                            <th>Seat</th>
                            <th>Bus_type</th>
                            <th style="text-align: center;">Edit</th>
                            <th style="text-align: center;">Delete</th>
                        </tr>
                    </thead>
                    <tbody>                
                      <?php if (!empty($data['operator']) && is_array($data['operator'])): ?>
                        <?php foreach ($data['operator'] as $operator): ?>
                            <tr>
                                <td><?php echo $operator['name']; ?></td>
                                <td><?php echo $operator['phone']; ?></td>
                                <td><?php echo $operator['seat_capacity']; ?></td>
                                <td><?php echo $operator['type_name']; ?></td>
                                <td style="text-align: center;">
                                    <a href="#" 
                                   class="edit-btn"
                                   data-id="<?php echo htmlspecialchars($operator['id']); ?>"
                                   data-name="<?php echo htmlspecialchars($operator['name']); ?>"
                                   data-phone="<?php echo htmlspecialchars($operator['phone']); ?>"
                                   data-seat="<?php echo htmlspecialchars($operator['seat_capacity']); ?>"
                                   data-bus-type-id="<?php echo htmlspecialchars($operator['bus_type_id']); ?>">
                                    <i class="fas fa-edit action-icon edit-icon"></i>
                                </a>
                                </td>
                                <td style="text-align: center;">
                                    <a href="<?php echo URLROOT; ?>/operator/delete/<?php echo base64_encode($operator['id']); ?>" 
                                        class="delete-admin-btn" >
                                        <i class="fas fa-trash-alt action-icon delete-icon"></i>
                                    </a>
                                </td>
                            </tr>                                
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align:center;">No operators found.</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</div>

<!-- Delete Modal -->
<div id="deleteConfirmationModal" class="modal-overlay">
    <div class="modal-content">                         
        <form id="deleteForm" method="POST">
            <h3>Are you sure you want to delete "<span style="color:#3f51b5;" id="adminNameToDelete"></span>" ?</h3>
            <p>This action cannot be undone.</p>
            <div class="modal-buttons">
                <button type="submit" class="btn-yes" id="confirmDeleteYes">Yes, Delete</button>  
                <button type="button" class="btn-no" id="confirmDeleteNo">No, Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="modal-overlay">
    <div class="modal-content">
        <form id="editForm" method="POST" action="<?php echo URLROOT; ?>/operator/update">
            <h3>Change <span style="color:#3f51b5;" id="adminNameToDelete"></span> Info.</h3>
            <input type="hidden" name="id" id="edit-id">
            <input type="hidden" name="bus_type_id" id="edit-bus-type-id">

            <div class="form-group input-with-icon">
                <i class="fas fa-bus icon"></i>
                <input type="text" name="name" id="edit-name" class="text-input" placeholder="Enter bus name" required>
            </div>
            <div class="form-group input-with-icon">
                <i class="fas fa-phone icon"></i>
                <input type="text" name="phone" id="edit-phone" class="text-input" placeholder="Enter phone number" required>
            </div>

            <div class="form-group input-with-icon">
                <i class="fas fa-chair icon"></i>
                <input type="number" name="seat_capacity" id="edit-seat" class="text-input" placeholder="Enter seat capacity" min="1" required>
            </div>

            <div class="modal-buttons">
                <button type="submit" class="btn-yes" id="editConfirmBtn">Update</button>  
                <button type="button" class="btn-no" id="editCancelBtn">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- ADD OPERATOR MODAL -->
<div id="addOperatorModal" class="modal-overlay">
    <div class="modal-content">
        <form method="POST" action="<?php echo URLROOT; ?>/operator/store">
            <h3>Add New Operator</h3>
            
            <div class="form-group input-with-icon">
                <i class="fas fa-bus icon"></i>
                <input type="text" name="name" class="text-input" placeholder="Enter operator name" required>
            </div>

            <div class="form-group input-with-icon">
                <i class="fas fa-phone icon"></i>
                <input type="text" name="phone" class="text-input" placeholder="Enter phone number" required>
            </div>

            <div class="form-group input-with-icon">
                <i class="fas fa-chair icon"></i>
                <input type="number" name="seat_capacity" class="text-input" placeholder="Enter seat capacity" min="1" max="44" required>
            </div>

            <div class="form-group radio-group">
                <label>
                    <input type="radio" name="bus_type_id" value="1" required> VIP
                    <input type="radio" name="bus_type_id" value="2" required> Normal
                </label>
            </div>

            <div class="modal-buttons">
                <button type="submit" class="btn-yes">Yes, Confirm</button>  
                <button type="button" class="btn-no" id="cancelAddOperator">No, Cancel</button>
            </div>
        </form>
    </div>
</div>
<script>
    // Add Operator modal
    const addOperatorModal = document.getElementById('addOperatorModal');
    const openAddOperatorBtn = document.getElementById('openAddOperatorModal');
    const cancelAddOperatorBtn = document.getElementById('cancelAddOperator');

    openAddOperatorBtn.addEventListener('click', function() {
        addOperatorModal.style.display = 'flex';
    });

    cancelAddOperatorBtn.addEventListener('click', function() {
        addOperatorModal.style.display = 'none';
    });

    addOperatorModal.addEventListener('click', function(event) {
        if (event.target === addOperatorModal) {
            addOperatorModal.style.display = 'none';
        }
    });

    // Delete modal elements
    const deleteButtons = document.querySelectorAll('.delete-admin-btn');
    const deleteModal = document.getElementById('deleteConfirmationModal');
    const adminNameToDeleteSpan = document.getElementById('adminNameToDelete');
    const confirmDeleteNoBtn = document.getElementById('confirmDeleteNo');
    const deleteForm = document.getElementById('deleteForm');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            const deleteUrl = this.getAttribute('href');
            // Optional: get name from data attribute or fallback text
            const adminName = this.closest('tr').querySelector('td').textContent || 'this operator';
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

    // Edit modal elements
    const editButtons = document.querySelectorAll('.edit-btn');
    const editModal = document.getElementById('editModal');
    const editForm = document.getElementById('editForm');
    const editId = document.getElementById('edit-id');
    const editName = document.getElementById('edit-name');
    const editPhone = document.getElementById('edit-phone');
    const editSeat = document.getElementById('edit-seat');
    const editCancelBtn = document.getElementById('editCancelBtn');
    const editBusTypeId = document.getElementById('edit-bus-type-id');

    editButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            editId.value = this.dataset.id;
            editName.value = this.dataset.name;
            editPhone.value = this.dataset.phone;
            editSeat.value = this.dataset.seat;
            editModal.style.display = 'flex';
            editBusTypeId.value = this.dataset.busTypeId; 
        });
    });

    editCancelBtn.addEventListener('click', function() {
        editModal.style.display = 'none';
    });

    editModal.addEventListener('click', function(event) {
        if (event.target === editModal) {
            editModal.style.display = 'none';
        }
    });

    // Auto-hide flash message after 2 seconds
    const flashMessage = document.getElementById('flashMessage');
    if (flashMessage) {
        setTimeout(() => {
            flashMessage.style.animation = "fadeOut 0.5s forwards";
            setTimeout(() => flashMessage.remove(), 500);
        }, 2000);
    }
</script>
