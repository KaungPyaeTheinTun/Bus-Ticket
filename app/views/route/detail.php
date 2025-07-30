<?php require_once APPROOT . '/views/inc/sidebar.php' ?>

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
        <button class="reset-seats-button" id="resetSeatsButton">Reset seats</button>
    </div>

                <div class="seat-grid-container">
                        <div class="driver-box">Driver</div>
                        <div class="seat-box available">1</div>
                        <div class="seat-box available">2</div> 
                        <div class="seat-box available">3</div>
                        <div class="seat-box available">4</div>
                        <div class="seat-box available">5</div>
                        <div class="seat-box available">6</div>
                        <div class="seat-box available">7</div>
                        <div class="seat-box available">8</div>
                        <div class="seat-box available">9</div>
                        <div class="seat-box available">10</div>
                        <div class="seat-box available">11</div>
                        <div class="seat-box available">12</div>
                        <div class="seat-box available">13</div>
                        <div class="seat-box available">14</div>
                        <div class="seat-box available">15</div>
                        <div class="seat-box available">16</div>
                        <div class="seat-box available">17</div>
                        <div class="seat-box available">18</div>
                        <div class="seat-box available">19</div>
                        <div class="seat-box available">20</div>
                        <div class="seat-box available">21</div>
                        <div class="seat-box available">22</div>
                        <div class="seat-box available">23</div>
                        <div class="seat-box available">24</div>
                        <div class="seat-box available">25</div>
                        <div class="seat-box available">26</div>
                        <div class="seat-box available">27</div>
                        <div class="seat-box available">28</div>
                        <div class="seat-box available">29</div>
                        <div class="seat-box available">30</div>
                        <div class="seat-box available">31</div>
                        <div class="seat-box available">32</div>
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
    // Reset seats modal logic
   document.getElementById('resetSeatsButton').addEventListener('click', function() {
            const selectedSeats = document.querySelectorAll('.seat-box.sold');
            selectedSeats.forEach(seat => {
                seat.classList.remove('sold');
            });
            alert('All selected seats have been reset!'); // Optional: provide user feedback
        });
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
