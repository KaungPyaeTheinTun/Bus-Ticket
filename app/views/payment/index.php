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
    .modal-content form input[type="file"] {
        width: 100%;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 5px;
        cursor: pointer;
    }

    /* Adding an explicit style for the file input to ensure visibility */
    .modal-content form .form-group input[type="file"] {
        display: block; /* Ensure it's not hidden by a parent's display property */
    }

    .modal-content form .form-group { margin-bottom: 15px; }
    .modal-content form input.text-input { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 5px; }
    /* .modal-content form input[type="file"] { width: 100%; padding: 5px; } */

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

    .customer-table button { background-color: #0d47a1; color: white; border: none; padding: 8px 12px; border-radius: 5px; cursor: pointer; font-size: 0.9em; transition: background-color 0.3s ease; display: inline-flex; align-items: center; justify-content: center; min-width: 70px; }
    .customer-table button:hover { background-color: #0a3a80; }
    .customer-table button.delete-button { background-color: #dc3545; }
    .customer-table button.delete-button:hover { background-color: #c82333; }
    .customer-table th, .customer-table td { padding: 12px 8px; }

    .modal-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.6); justify-content: center; align-items: center; z-index: 1000; }
    .modal-content { background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3); text-align: center; max-width: 400px; width: 90%; animation: fadeInScale 0.3s ease-out; }
    .modal-content h3 { margin-top: 0; color: #333; font-size: 1.5em; margin-bottom: 20px; }

    .modal-buttons { margin-top: 25px; display: flex; justify-content: center; gap: 15px; }
    .modal-buttons button { padding: 12px 25px; border: none; border-radius: 5px; cursor: pointer; font-size: 1em; transition: background-color 0.2s ease; }
    .modal-buttons .btn-yes { background-color: #e74c3c; color: white; }
    .modal-buttons .btn-yes:hover { background-color: #c0392b; }
    .modal-buttons .btn-no { background-color: #bdc3c7; color: #333; }
    .modal-buttons .btn-no:hover { background-color: #95a5a6; }

    .input-with-icon { position: relative; }
    .input-with-icon .icon { position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: #555; }
    .input-with-icon .text-input { padding-left: 30px; }

    @keyframes fadeInScale { from { opacity: 0; transform: scale(0.9); } to { opacity: 1; transform: scale(1); } }
</style>

<div class="container">
<main class="main-content">
<?php require_once APPROOT . '/views/inc/profileHeader.php' ?>

<section class="operators-list-card">
    <div class="card-header-with-button">
        <h2 class="card-title"><i class="fas fa-dollar-sign"></i>&nbsp;&nbsp;&nbsp;Payment Methods</h2>
        <button class="add-button" id="openAddModal"><i class="fas fa-plus"></i> Add method</button>
    </div>

    <div class="operator-table-container">
        <table class="operator-table">
            <thead>
                <tr>
                    <th>Method</th>
                    <th>Phone Number</th>
                    <th>Scan_Image</th>
                    <th style="text-align: center;">Edit</th>
                    <th style="text-align: center;">Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data['payments'] as $payment): ?>
                <tr>
                    <td><?php echo htmlspecialchars($payment['method']); ?></td>
                    <td><?php echo htmlspecialchars($payment['phone']); ?></td>
                    <td>
                        <?php if (!empty($payment['scan_image'])): ?>
                            <img src="<?php echo URLROOT; ?>/public/uploads/scan_image/<?php echo htmlspecialchars($payment['scan_image']); ?>" width="50">
                        <?php endif; ?>
                    </td>
                    <td style="text-align: center;">
                        <a href="#" 
                            class="edit-btn"
                            data-id="<?php echo htmlspecialchars($payment['id']); ?>"
                            data-name="<?php echo htmlspecialchars($payment['method']); ?>"
                            data-phone="<?php echo htmlspecialchars($payment['phone']); ?>">
                            <i class="fas fa-edit action-icon edit-icon"></i>
                        </a>
                    </td>
                    <td style="text-align: center;">
                        <a href="<?php echo URLROOT; ?>/payment/delete/<?php echo base64_encode($payment['id']); ?>" 
                            class="delete-admin-btn">
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

<div id="addModal" class="modal-overlay">
<div class="modal-content">
    <form method="POST" action="<?php echo URLROOT; ?>/payment/store" enctype="multipart/form-data">
    <h3>Add Payment Method</h3>
    <div class="form-group input-with-icon">
        <input type="text" name="name" class="text-input" placeholder="Enter method name" required>
    </div>
    <div class="form-group input-with-icon">
        <input type="text" name="phone" class="text-input" placeholder="Enter phone number" required>
    </div>
    <div class="form-group">
        <label for="scan_image" style="display: block; text-align: left; margin-bottom: 5px;">Scan Image (QR Code)</label>
        <input type="file" name="scan_image" id="scan_image" accept="image/*" required>
    </div>

    <div class="modal-buttons">
        <button type="submit" class="btn-yes">Add</button>    
        <button type="button" class="btn-no" id="addCancelBtn">Cancel</button>
    </div>
    </form>
</div>
</div>

<div id="editModal" class="modal-overlay">
<div class="modal-content">
    <form id="editForm" method="POST" action="<?php echo URLROOT; ?>/payment/update" enctype="multipart/form-data">
    <h3>Edit Payment Method</h3>
    <input type="hidden" name="id" id="edit-id">
    <div class="form-group input-with-icon">
        <input type="text" name="name" id="edit-name" class="text-input" placeholder="Enter method name" required>
    </div>
    <div class="form-group input-with-icon">
        <input type="text" name="phone" id="edit-phone" class="text-input" placeholder="Enter phone number" required>
    </div>
    <div class="form-group">
        <label for="edit_scan_image" style="display: block; text-align: left; margin-bottom: 5px;">Scan Image (QR Code)</label>
        <input type="file" name="scan_image" id="edit_scan_image" accept="image/*" required>
    </div>
    <div class="modal-buttons">
        <button type="submit" class="btn-yes">Update</button>  
        <button type="button" class="btn-no" id="editCancelBtn">Cancel</button>
    </div>
    </form>
</div>
</div>

<div id="deleteConfirmationModal" class="modal-overlay">
<div class="modal-content">                           
    <form id="deleteForm" method="POST">
        <h3>Are you sure you want to delete <span id="adminNameToDelete"></span>?</h3>
        <p>This action cannot be undone.</p>
        <div class="modal-buttons">
            <button type="submit" class="btn-yes">Yes, Delete</button>  
            <button type="button" class="btn-no" id="confirmDeleteNo">No, Cancel</button>
        </div>
    </form>
</div>
</div>

<script>
// Delete modal
const deleteButtons = document.querySelectorAll('.delete-admin-btn');
const deleteModal = document.getElementById('deleteConfirmationModal');
const adminNameToDeleteSpan = document.getElementById('adminNameToDelete');
const deleteForm = document.getElementById('deleteForm');
const confirmDeleteNoBtn = document.getElementById('confirmDeleteNo');

deleteButtons.forEach(btn => {
    btn.addEventListener('click', e => {
        e.preventDefault();
        deleteForm.action = btn.href;
        adminNameToDeleteSpan.textContent = btn.closest('tr').querySelector('td').textContent;
        deleteModal.style.display = 'flex';
    });
});
confirmDeleteNoBtn.addEventListener('click', () => deleteModal.style.display = 'none');
deleteModal.addEventListener('click', e => { if(e.target === deleteModal) deleteModal.style.display = 'none'; });

// Edit modal
const editButtons = document.querySelectorAll('.edit-btn');
const editModal = document.getElementById('editModal');
const editId = document.getElementById('edit-id');
const editName = document.getElementById('edit-name');
const editPhone = document.getElementById('edit-phone');
const editCancelBtn = document.getElementById('editCancelBtn');

editButtons.forEach(btn => {
    btn.addEventListener('click', e => {
        e.preventDefault();
        editId.value = btn.dataset.id;
        editName.value = btn.dataset.name;
        editPhone.value = btn.dataset.phone;
        editModal.style.display = 'flex';
    });
});
editCancelBtn.addEventListener('click', () => editModal.style.display = 'none');
editModal.addEventListener('click', e => { if(e.target === editModal) editModal.style.display = 'none'; });

// Add modal
const openAddModalBtn = document.getElementById('openAddModal');
const addModal = document.getElementById('addModal');
const addCancelBtn = document.getElementById('addCancelBtn');
openAddModalBtn.addEventListener('click', () => addModal.style.display = 'flex');
addCancelBtn.addEventListener('click', () => addModal.style.display = 'none');
addModal.addEventListener('click', e => { if(e.target === addModal) addModal.style.display = 'none'; });

 // Auto-hide flash message after 2 seconds
    const flashMessage = document.getElementById('flashMessage');
    if (flashMessage) {
        setTimeout(() => {
            flashMessage.style.animation = "fadeOut 0.5s forwards";
            setTimeout(() => flashMessage.remove(), 500); // Remove after fadeOut completes
        }, 2000); // Show for 2 seconds
    }
</script>