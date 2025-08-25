<style>
    a {
        text-decoration: none;
    }

    .profile-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px; /* space between icon and text */
        background-color: #3f51b5; /* blue background */
        color: #fff; /* white text */
        padding: 8px 14px;
        border-radius: 6px;
        font-size: 16px;
        font-weight: 500;
        transition: background-color 0.3s ease;
    }

    .profile-btn:hover {
        background-color: #2c3e99; /* darker blue on hover */
    }

    .profile-btn i {
        font-size: 18px;
    }
</style>

<header class="navbar">
    <h2>Dashboard</h2>
    <div class="user-profile">
        <a href="<?php echo URLROOT; ?>/user/profile" class="profile-btn">
            <i class="fas fa-user-circle"></i>
            Profile
        </a>
    </div>
</header>
