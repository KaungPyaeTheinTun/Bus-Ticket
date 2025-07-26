<?php require_once APPROOT . '/views/inc/sidebar.php'; ?>
<style>
    .input-with-icon {
        position: relative;
        display: flex;
        align-items: center;
        width: 100%;
    }

    .input-with-icon .text-input {
        padding-left: 36px; /* space for the icon */
        width: 100%;
        box-sizing: border-box;
    }

    .input-with-icon .icon {
        position: absolute;
        left: 10px; /* adjust as needed */
        color: #555;
        font-size: 16px;
        pointer-events: none; /* let clicks go to the input */
    }
</style>

<div class="container">
    <main class="main-content">
        <?php require_once APPROOT . '/views/inc/profileHeader.php'; ?>
        <section class="update-bus-info-card">

            <form class="bus-info-form" method="POST" action="<?php echo URLROOT; ?>/operator/update">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($data['operator']['id']); ?>">

                <div class="form-group">
                    <input type="text" id="busName" name="name" class="text-input" placeholder="Enter bus name"
                        value="<?php echo htmlspecialchars($data['operator']['name']); ?>" required>
                </div>

                <div class="form-group">
                    <input type="text" id="phoneNumber" name="phone" class="text-input" placeholder="Enter phone number"
                        value="<?php echo htmlspecialchars($data['operator']['phone']); ?>" required>
                </div>

                <div class="form-group">
                    <div class="input-with-icon">
                        <i class="fas fa-chair icon"></i>
                        <input type="number" id="seatCapacity" name="seat_capacity" class="text-input number-input"
                            placeholder="Enter seat capacity" min="1"
                            value="<?php echo htmlspecialchars($data['operator']['seat_capacity']); ?>" required>
                    </div>
                </div>

                <button type="submit" class="confirm-button">Confirm</button>

                <a href="<?php echo URLROOT; ?>/operator/detail">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </form>
        </section>
    </main>
</div>
