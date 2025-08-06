<?php require_once APPROOT . '/views/inc/sidebar.php' ?>

<div class="container">
    <main class="main-content">
        <?php require_once APPROOT . '/views/inc/profileHeader.php' ?>
        <section class="add-routes-card">
            <div class="card-header">Add Routes</div>
            
            <form class="route-form" method="POST" action="<?php echo URLROOT; ?>/route/store" enctype="multipart/form-data">    
            <div class="form-group">
                    <label>Operator</label>
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
                    <label for="departureDateTime">Departure Date & Time</label>
                    <div class="input-with-icon">
                        <input type="datetime-local" id="departureDateTime" name="departure_time" class="text-input" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="arrivalDateTime">Arrival Date & Time<span class="estimate-text">( Estimate )</span></label>
                    <div class="input-with-icon">
                        <input type="datetime-local" id="arrivalDateTime" name="arrival_time" class="text-input" required>
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
