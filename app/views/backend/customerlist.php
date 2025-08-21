<?php require_once APPROOT . '/views/inc/sidebar.php'; ?>

<style>
    .underline-search {
        width: 250px;
        margin-left:53%;
        padding: 8px 0;
        border: none;             /* remove all borders */
        border-bottom: 3px solid #ccc; /* only underline */
        outline: none;            /* remove default focus outline */
        font-size: 14px;
        transition: border-color 0.3s;
        background: transparent;  /* optional, removes input background */
    }

    .underline-search:focus {
        border-bottom-color:rgb(0, 25, 166); /* color on focus */
    }
    /* Flash message */
    .flash-message {
        position: fixed; top: 28px; left: 50%;
        padding: 12px 20px;
        border-radius: 8px;
        font-size: 14px; font-weight: 500;
        z-index: 9999;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        animation: fadeInScale 0.3s ease;
    }
    .success-message { background: #d4edda; color: #155724; }
    .error-message { background: rgb(239,154,161); color: #721c24; }

    @keyframes fadeOut { to { opacity:0; transform:scale(0.9); } }
    @keyframes fadeInScale { from {opacity:0;transform:scale(0.9);} to {opacity:1;transform:scale(1);} }

    /* Table */
    .customer-table th, .customer-table td { padding: 10px 8px; }

    /* Eye button: icon only */
    .eye-button {
        background: none; border: none;
        cursor: pointer; color: #0d47a1;
        font-size: 18px;
        padding: 4px;
        transition: color 0.2s;
    }
    .eye-button:hover { color: #0a3a80; }

    /* Delete icon style */
    .delete-icon { color: #dc3545; font-size: 16px; cursor: pointer; }
    .delete-icon:hover { color: #c82333; }

    /* Modal overlay & content */
    .modal-overlay {
        display: none; position: fixed; top:0; left:0;
        width:100%; height:100%;
        background: rgba(0,0,0,0.6);
        justify-content:center; align-items:center;
        z-index:1000;
    }
    .modal-content {
        background:#fff; padding:25px;
        border-radius:8px; max-width:700px;
        width:90%; text-align:left;
        position:relative;
        animation: fadeInScale 0.3s ease-out;
    }
    .modal-content h3 { margin-top:0; margin-bottom:15px; font-size:1.4em; color:#333; }

    /* Close button */
    .modal-close-btn {
        position:absolute; top:10px; right:12px;
        background:none; border:none;
        font-size:20px; cursor:pointer;
        color:#666; transition:color 0.2s;
    }
    .modal-close-btn:hover { color:#333; }

    /* Ticket table inside modal */
    .modal-content table {
        width:100%; margin-top:10px;
        border-collapse:collapse; font-size:14px;
    }
    .modal-content table th, .modal-content table td {
        padding:8px 6px; border-bottom:1px solid #ddd;
        text-align:left;
    }
    .modal-content table th { background:#f8f8f8; }

    /* Delete modal buttons */
    .modal-buttons {
        display:flex; justify-content:center; gap:12px; margin-top:20px;
    }
    .modal-buttons button {
        padding:10px 20px; border:none; border-radius:5px;
        cursor:pointer; font-size:1em; transition:background-color 0.2s;
    }
    .modal-buttons .btn-yes { background:#e74c3c; color:#fff; }
    .modal-buttons .btn-yes:hover { background:#c0392b; }
    .modal-buttons .btn-no { background:#bdc3c7; color:#333; }
    .modal-buttons .btn-no:hover { background:#95a5a6; }
</style>

<?php if (!empty($_SESSION['success'])): ?>
<div id="flashMessage" class="flash-message success-message">
    <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
</div>
<?php endif; ?>

<div class="container">
<main class="main-content">
<?php require_once APPROOT . '/views/inc/profileHeader.php'; ?>

<section class="customers-list-card">
<div class="card-header">
    <i class="fas fa-users"></i> &nbsp; Customers List
        <input type="text" id="operatorSearch" placeholder="Search by operator name..." class="underline-search">
</div>

<div class="customer-table-container">
<table class="customer-table">
<thead>
<tr>
    <th>Name</th>
    <th>Phone Number</th>
    <th>Total Tickets</th>
    <th>Last Booking</th>
    <th>Detail</th>
    <th>Delete</th>
</tr>
</thead>
<tbody>
    <tr id="noResultsRow" style="display:none; text-align:center;">
    <td colspan="7">No customers found for this operator !</td>
</tr>
<?php if (!empty($data['user']) && is_array($data['user'])): ?>
<?php foreach ($data['user'] as $user): 
    $stats = $data['ticketStats'][$user['id']] ?? null;
    $total = $stats['total_tickets'] ?? 0;
    $last = $stats ['last_booking'] ?? '-';
?>
<tr>
    <td><?php echo htmlspecialchars($user['name']); ?></td>
    <td><?php echo htmlspecialchars($user['phone']); ?></td>
    <td><?php echo $total; ?></td>
    <td><?php echo htmlspecialchars($last); ?></td>
    <td style="text-align:center;">
        <button class="eye-button" onclick="document.getElementById('modal-<?php echo $user['id']; ?>').style.display='flex'">
            <i class="fas fa-eye"></i>
        </button>
    </td>
    <td style="text-align:center;">
        <a href="<?php echo URLROOT; ?>/user/deletecustomer/<?php echo base64_encode($user['id']); ?>"
           class="delete-admin-btn" data-name="<?php echo htmlspecialchars($user['name']); ?>">
            <i class="fas fa-trash-alt delete-icon"></i>
        </a>
    </td>
</tr>
<?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="7" style="text-align:center;">No customer !</td>
    </tr>
<?php endif; ?>
</tbody>
</table>
</div>
</section>

<!-- Delete confirmation modal -->
<div id="deleteConfirmationModal" class="modal-overlay">
<div class="modal-content" style="text-align:center; max-width:400px;">
    <h3>Are you sure you want to delete <span id="adminNameToDelete"></span>?</h3>
    <p>This action cannot be undone.</p>
    <div class="modal-buttons">
        <form id="deleteForm" method="POST">
            <button type="submit" class="btn-yes">Yes, Delete</button>
            <button type="button" class="btn-no" id="cancelDelete">No, Cancel</button>
        </form>
    </div>
</div>
</div>

<!-- Ticket modals -->
<?php foreach ($data['user'] as $user): 
$tickets = $data['userTickets'][$user['id']] ?? [];
?>
<div id="modal-<?php echo $user['id']; ?>" class="modal-overlay">
<div class="modal-content">
    <button class="modal-close-btn" onclick="document.getElementById('modal-<?php echo $user['id']; ?>').style.display='none'">&times;</button>
    <h3>Tickets for <?php echo htmlspecialchars($user['name']); ?></h3>
    <?php if ($tickets): ?>
    <table>
        <thead>
        <tr>
            <th>Route</th><th>Departure</th><th>Price</th><th>Seat</th><th>Booked at</th><th>Operator</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($tickets as $t): 
            // ✅ Format departure_time here:
            $dt = new DateTime($t['departure_time']);
            $formattedDeparture = $dt->format('M j - G:i'); // e.g., Jul 30 9:00
        ?>
        <tr>
            <td><?php echo htmlspecialchars($t['route_from'].' → '.$t['route_to']); ?></td>
            <td><?php echo htmlspecialchars($formattedDeparture); ?></td>
            <td>MMK <?php echo htmlspecialchars(number_format($t['price'])); ?></td>
            <td><?php echo htmlspecialchars($t['seat_number']); ?></td>
            <td><?php echo htmlspecialchars($t['created_at_formatted']); ?></td>
            <td>    
                  <?php 
                    echo htmlspecialchars($t['operator_name']); 
                    if (!empty($t['bus_type'])) {
                        echo ' (' . htmlspecialchars($t['bus_type']) . ')';
                    }
                ?>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
    <p>No tickets found.</p>
    <?php endif; ?>
</div>
</div>
<?php endforeach; ?>
</main>
</div>

<script>
    // Search customers by operator name
    const searchInput = document.getElementById('operatorSearch');
    const noResultsRow = document.getElementById('noResultsRow');

    searchInput.addEventListener('input', function() {
        const filter = this.value.toLowerCase();
        const rows = document.querySelectorAll('.customer-table tbody tr');
        let anyVisible = false;

        rows.forEach(row => {
            // skip the noResultsRow itself
            if(row.id === 'noResultsRow') return;

            const userId = row.querySelector('button.eye-button')?.getAttribute('onclick')?.match(/\d+/)?.[0];
            if(!userId) return;

            const tickets = <?php echo json_encode($data['userTickets']); ?>;
            const userTickets = tickets[userId] || [];
            let match = false;

            userTickets.forEach(t => {
                if(t.operator_name.toLowerCase().includes(filter)) {
                    match = true;
                }
            });

            row.style.display = match || filter === "" ? "" : "none";
            if(row.style.display === "") anyVisible = true;
        });

        // Show no results row if nothing is visible
        noResultsRow.style.display = anyVisible ? "none" : "table-row";
    });
    // Flash message auto hide
    const flash = document.getElementById('flashMessage');
    if(flash){
        setTimeout(()=>{flash.style.animation="fadeOut 0.5s forwards"; setTimeout(()=>flash.remove(),500);},2000);
    }

    // Delete modal logic
    const deleteBtns=document.querySelectorAll('.delete-admin-btn');
    const deleteModal=document.getElementById('deleteConfirmationModal');
    const deleteForm=document.getElementById('deleteForm');
    const adminNameSpan=document.getElementById('adminNameToDelete');
    const cancelDelete=document.getElementById('cancelDelete');

    deleteBtns.forEach(btn=>{
        btn.addEventListener('click',e=>{
            e.preventDefault();
            deleteForm.action=btn.getAttribute('href');
            adminNameSpan.textContent=btn.dataset.name;
            deleteModal.style.display='flex';
        });
    });
    cancelDelete.addEventListener('click',()=>deleteModal.style.display='none');
    deleteModal.addEventListener('click',e=>{ if(e.target==deleteModal) deleteModal.style.display='none'; });
</script>
