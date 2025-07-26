<?php require_once APPROOT . '/views/inc/sidebar.php' ?>
<?php
$seats = [
    'day' => [
        1 => 'sold', 2 => 'available', 3 => 'sold', 4 => 'available', 5 => 'available', 6 => 'available',
        7 => 'sold', 8 => 'available', 9 => 'available', 10 => 'sold', 11 => 'available', 12 => 'available',
        13 => 'available', 14 => 'available', 15 => 'sold', 16 => 'available', 17 => 'available', 18 => 'available',
        19 => 'available', 20 => 'available', 21 => 'sold', 22 => 'available', 23 => 'available', 24 => 'available',
        25 => 'available', 26 => 'available', 27 => 'available', 28 => 'available', 29 => 'available', 30 => 'available',
        31 => 'sold', 32 => 'available',
    ],
    'afternoon' => [
        1 => 'available', 2 => 'sold', 3 => 'available', 4 => 'available', 5 => 'sold', 6 => 'available',
        7 => 'available', 8 => 'available', 9 => 'sold', 10 => 'available', 11 => 'sold', 12 => 'available',
        13 => 'available', 14 => 'available', 15 => 'available', 16 => 'sold', 17 => 'available', 18 => 'available',
        19 => 'available', 20 => 'sold', 21 => 'available', 22 => 'available', 23 => 'sold', 24 => 'available',
        25 => 'available', 26 => 'available', 27 => 'available', 28 => 'sold', 29 => 'available', 30 => 'available',
        31 => 'sold', 32 => 'sold',
    ],
    'night' => [
        1 => 'sold', 2 => 'sold', 3 => 'available', 4 => 'available', 5 => 'available', 6 => 'available',
        7 => 'available', 8 => 'sold', 9 => 'available', 10 => 'available', 11 => 'sold', 12 => 'available',
        13 => 'available', 14 => 'available', 15 => 'available', 16 => 'available', 17 => 'available', 18 => 'sold',
        19 => 'available', 20 => 'available', 21 => 'available', 22 => 'sold', 23 => 'available', 24 => 'available',
        25 => 'available', 26 => 'available', 27 => 'available', 28 => 'sold', 29 => 'available', 30 => 'available',
        31 => 'available', 32 => 'sold',
    ],
];

// Detect activeTab based on departure_time
$departure_time = $data['route']['departure_time'] ?? null;
$activeTab = 'day'; // default
if ($departure_time) {
    $time_parts = explode(':', $departure_time);
    $hour = (int)$time_parts[0];
    if ($hour >= 5 && $hour < 12) {
        $activeTab = 'day';
    } elseif ($hour >= 12 && $hour < 17) {
        $activeTab = 'afternoon';
    } else {
        $activeTab = 'night';
    }
}
?>
<style>
   
    .time-filter-dropdown {
        padding: 8px 12px;
        font-size: 14px;
        border-radius: 6px;
        border: 1px solid #ccc;
        background: #fff;
        cursor: pointer;
        max-width: 192px;
    }
    .flash-message {
        position: fixed;
        top: 28px;
        left: 50%;
        padding: 16px 24px;
        border-radius: 8px;
        font-size: 15px;
        font-weight: 500;
        z-index: 9999;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }
    .success-message {
        background-color: #d4edda;
        color: #155724;
    }
    @keyframes fadeOut {
        0% { opacity: 1; transform: scale(1); }
        100% { opacity: 0; transform: scale(0.9); }
    }
    .modal-overlay {
        display: none;
        position: fixed; top: 0; left: 0;
        width: 100%; height: 100%;
        background: rgba(0,0,0,0.6);
        justify-content: center; align-items: center;
        z-index: 1000;
    }
    .modal-content {
        background: #fff; padding: 30px; border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        text-align: center; max-width: 400px; width: 90%;
    }
    .modal-buttons { margin-top: 25px; display: flex; justify-content: center; gap: 15px; }
    .modal-buttons button {
        padding: 12px 25px; border: none; border-radius: 5px;
        cursor: pointer; font-size: 1em; transition: background-color 0.2s ease;
    }
    .btn-yes { background-color: #e74c3c; color: white; }
    .btn-yes:hover { background-color: #c0392b; }
    .btn-no { background-color: #bdc3c7; color: #333; }
    .btn-no:hover { background-color: #95a5a6; }
</style>

<main class="main-content">
<?php require_once APPROOT . '/views/inc/profileHeader.php' ?>

<div class="main-content-grid">
<section class="bus-info-card">
    <div class="card-header">Bus Information</div>
    <div class="info-grid">
        <div class="info-item"><span class="label">Operator :</span><span class="value"><?php echo htmlspecialchars($data['operator']['name']); ?></span></div>
        <div class="info-item"><span class="label">Phone :</span><span class="value"><?php echo htmlspecialchars($data['operator']['phone']); ?></span></div>
        <div class="info-item"><span class="label">From :</span><span class="value"><?php echo htmlspecialchars($data['route']['from']); ?></span></div>
        <div class="info-item"><span class="label">To :</span><span class="value"><?php echo htmlspecialchars($data['route']['to']); ?></span></div>
        <div class="info-item"><span class="label">Price :</span><span class="value">MMK <?php echo htmlspecialchars($data['route']['price']); ?></span></div>
        <div class="info-item">
            <span class="label">Departure :</span>
            <span class="value">
                <?php
                $depRaw = $data['route']['departure_time'] ?? null;
                if ($depRaw) {
                    $depDt = new DateTime($depRaw);
                    echo htmlspecialchars($depDt->format('F j') . ' at ' . $depDt->format('g:i A'));
                }
                ?>
            </span>
        </div>
        <div class="info-item">
            <span class="label">Arrival :</span>
            <span class="value">
                <?php
                $arrRaw = $data['route']['arrival_time'] ?? null;
                if ($arrRaw) {
                    $arrDt = new DateTime($arrRaw);
                    echo htmlspecialchars($arrDt->format('F j') . ' at ' . $arrDt->format('g:i A'));
                }
                ?>
            </span>
        </div>
    </div>
    <div class="card-actions">
        <a href="<?php echo URLROOT; ?>/route"><i class="fas fa-arrow-left"></i> Back</a>
        <!-- <a href="<?php echo URLROOT; ?>/route/edit/<?php echo base64_encode($data['route']['id']); ?>" class="update-button">Update Info</a> -->
    </div>
</section>

<section class="seat-layout-card">
    <div class="seat-layout-header">
        <button class="tab sold-tab" style="background-color:#8a8585; color:white;">Sold</button>
        <button class="tab available-tab" style="background-color:#d8e6f1; color:#333;">Available</button>
        <div class="tabs-container">
            <select id="timeFilterSelect" class="time-filter-dropdown">
                <option value="day">Day (5 AM - 11:59 AM)</option>
                <option value="afternoon">Afternoon (12 PM - 4:59 PM)</option>
                <option value="night">Night (5 PM - 4:59 AM)</option>
            </select>
        </div>
        <button class="reset-seats-button" id="resetSeatsButton">Reset seats</button>
    </div>

    <div class="seat-grid-container" id="seatGrid">
        <?php
        $activeTab = 'day'; // default
        foreach ($seats[$activeTab] as $num => $status) {
            $class = ($status === 'sold') ? 'seat-box sold' : 'seat-box available';
            echo "<div class=\"$class\">$num</div>";
        }
        ?>
    </div>
</section>
</div>
</main>

<div id="confirmModal" class="modal-overlay">
  <div class="modal-content">
    <p>Are you sure you want to reset all seats?</p>
    <div class="modal-buttons">
      <button id="confirmYes" class="btn-yes">Yes, Reset</button>
      <button id="confirmNo" class="btn-no">No, Cancel</button>
    </div>
  </div>
</div>

<script>
    const seatsData = <?php echo json_encode($seats); ?>;
    let activeTab = 'day';
    const seatGrid = document.getElementById('seatGrid');
    const timeFilter = document.getElementById('timeFilterSelect');

    // Render seats
    function renderSeats(tab) {
        seatGrid.innerHTML = '';
        for(let i=1; i<=32; i++) {
            const status = seatsData[tab][i] ?? 'available';
            const div = document.createElement('div');
            div.className = 'seat-box ' + (status==='sold' ? 'sold' : 'available');
            div.textContent = i;
            seatGrid.appendChild(div);
        }
    }

    // Init render
    renderSeats(activeTab);

    // Change time filter
    timeFilter.addEventListener('change', () => {
        activeTab = timeFilter.value;
        renderSeats(activeTab);
    });

    // Reset seats modal logic
    const resetBtn = document.getElementById('resetSeatsButton');
    const confirmModal = document.getElementById('confirmModal');
    const confirmYes = document.getElementById('confirmYes');
    const confirmNo = document.getElementById('confirmNo');

    resetBtn.addEventListener('click', ()=> confirmModal.style.display='flex');
    confirmYes.addEventListener('click', ()=>{
        for(let i=1;i<=32;i++) seatsData[activeTab][i]='available';
        renderSeats(activeTab);
        showFlashMessage(`All seats for ${activeTab} have been reset to available!`);
        confirmModal.style.display='none';
    });
    confirmNo.addEventListener('click', ()=> confirmModal.style.display='none');

    // Flash message
    function showFlashMessage(message){
        let flash = document.getElementById('flashMessage');
        if(!flash){
            flash = document.createElement('div');
            flash.id='flashMessage'; flash.className='flash-message success-message';
            document.body.appendChild(flash);
        }
        flash.textContent=message; flash.style.display='block';
        if(flash.fadeTimeout) clearTimeout(flash.fadeTimeout);
        flash.fadeTimeout = setTimeout(()=>{
            flash.style.animation='fadeOut 0.5s forwards';
            flash.addEventListener('animationend', ()=>{flash.style.display='none'; flash.style.animation='';}, {once:true});
        },2000);
    }
</script>
