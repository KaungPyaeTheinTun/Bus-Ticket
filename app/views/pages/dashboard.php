
<?php require_once APPROOT . '/views/inc/sidebar.php' ?>
<style>
    .summary-card.revenue {
        grid-column: 3 / -1; /* Spans across all columns */
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 20px;
        padding: 30px 20px;
    }
</style>
    <div class="container">
        <main class="main-content">
            <?php require_once APPROOT . '/views/inc/profileHeader.php' ?>

            <section class="dashboard-summary">
                <div class="summary-card">
                    <div class="icon-wrapper blue">
                        <i class="fas fa-bus"></i>
                    </div>
                    <div class="details">
                        <p>Total Operators</p>
                        <span><?= $data['totalOperators']; ?></span>
                    </div>
                </div>

                <div class="summary-card">
                    <div class="icon-wrapper green">
                        <i class="fas fa-globe-asia"></i>
                    </div>
                    <div class="details">
                        <p>Total Routes</p>
                        <span><?= $data['totalRoutes']; ?></span>
                    </div>
                </div>

                <div class="summary-card">
                    <div class="icon-wrapper yellow">
                        <i class="fas fa-ticket-alt"></i>
                    </div>
                    <div class="details">
                        <p>Total Bookings</p>
                        <span><?= $data['totalBookings']; ?></span>
                    </div>
                </div>

                <div class="summary-card">
                    <div class="icon-wrapper blue">
                        <i class="fas fa-bus"></i>
                    </div>
                    <div class="details">
                        <p>Ongoing Bus</p>
                        <span><?= $data['ongoingBusCount']; ?></span>
                    </div>
                </div>

                <div class="summary-card">
                    <div class="icon-wrapper orange">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div class="details">
                        <p>Pending Transaction</p>
                        <span><?= $data['pendingBookings']; ?></span>
                    </div>
                </div>

                <div class="summary-card">
                    <div class="icon-wrapper green">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <div class="details">
                        <p>Total Payment Methods</p>
                        <span><?= $data['totalPayment']; ?></span>
                    </div>
                </div>

                <div class="summary-card revenue">
                    <div class="icon-wrapper green">
                       <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="details">
                        <p>Revenue</p>
                        <span><?= number_format($data['revenue']); ?> MMK</span>
                    </div>
                </div>

            </section>

            <section class="ongoing-bus">
                <h3>Ongoing Bus</h3>
                <table>
                    <tbody>
                        <?php if (empty($data['ongoingBuses'])): ?>
                            <tr><td colspan="3">No ongoing bus!</td></tr>
                        <?php else: ?>
                            <?php foreach ($data['ongoingBuses'] as $bus): ?>
                                <tr>
                                    <td><?= htmlspecialchars($bus['operator_name']); ?></td>
                                    <td><?= htmlspecialchars($bus['from_location'] . ' - ' . $bus['to_location']); ?></td>
                                    <td><?= date('M d, h:i A', strtotime($bus['departure_time'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>
