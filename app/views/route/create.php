<?php require_once APPROOT . '/views/inc/sidebar.php' ?>
<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<?php 
   $today = date('Y-m-d\TH:i'); 
?>
<style>
    input[type="datetime-local"] {
        padding: 10px 12px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 16px;
        background-color: #f9f9f9;
        color: #333;
        width: 100%;
        box-sizing: border-box;
        transition: border-color 0.3s, box-shadow 0.3s;
    }

    input[type="datetime-local"]:focus {
        border-color: #3498db;
        box-shadow: 0 0 5px rgba(52, 152, 219, 0.5);
        outline: none;
    }

    input[type="datetime-local"]::-webkit-calendar-picker-indicator {
        filter: invert(0.5); /* change icon color */
        cursor: pointer;
    }
</style>
<div class="container">
    <main class="main-content">
        <?php require_once APPROOT . '/views/inc/profileHeader.php' ?>
        <section class="add-routes-card">
            <div class="card-header">Add Routes</div>
            <form class="route-form" method="POST" action="<?php echo URLROOT; ?>/route/store" enctype="multipart/form-data">    
                <div class="form-group">
                <select class="text-input" style="height:43px;" name="operator_id" required>
                    <!-- <option value="">-- Select Operator --</option> -->
                    <?php foreach ($data['operator'] as $operator): ?>
                        <option value="<?php echo $operator['id']; ?>">
                            <?php echo $operator['name'] . ' (' . $operator['type_name'] . ')'; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                </div>
                <div class="form-group">
                    <div class="amount-input-container">
                        <input type="number" id="amount" name="price" class="text-input amount-input" placeholder="Enter amount" min="0" required>
                        <span class="amount-currency">MMK</span>
                    </div>
                </div>
                <div class="form-group">
                    <div class="route-inputs">
                        <input type="text" id="routeFrom" name="from" class="text-input" placeholder="From" required>
                        <span class="route-separator"><i class="fas fa-arrow-right"></i></span>
                        <input type="text" id="routeTo" name="to" class="text-input" placeholder="To" required>
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-with-icon">
                        <input type="text" id="departureDateTime" name="departure_time" class="text-input" placeholder="Departure Date & Time" required>
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-with-icon">
                        <input type="text" id="arrivalDateTime" name="arrival_time" class="text-input" placeholder="Arrival Date & Time ( Estimate )" required>
                    </div>
                </div>
                <div class="form-group file-upload-group">
                    <div class="file-upload">
                        <label for="routeFile" class="custom-file-upload">
                            Choose file
                        </label>
                        <input type="file" id="routeFile" name="image"  required>
                        <span class="file-name">No file chosen</span>
                    </div>
                </div>

                <button type="submit" class="confirm-button">Confirm</button>
                <a href="<?php echo URLROOT; ?>/route"><i class="fas fa-arrow-left"></i> Back</a>
            </form>
        </section>
    </main>
</div>

<script>
    flatpickr("#departureDateTime", {
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        minDate: "today"
    });

    flatpickr("#arrivalDateTime", {
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        minDate: "today"
    });
    // Show selected file name
    const fileInput = document.getElementById('routeFile');
    const fileNameSpan = document.querySelector('.file-name');
    fileInput.addEventListener('change', function(){
        if(this.files && this.files.length > 0){
            fileNameSpan.textContent = this.files[0].name;
        } else {
            fileNameSpan.textContent = 'No file chosen';
        }
    });
</script>
