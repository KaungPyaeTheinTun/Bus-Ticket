<?php

require_once APPROOT . '/services/SeatService.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class Seat extends Controller
{
    private $seatService;

    public function __construct(SeatService $seatService)
    {
        $this->seatService = $seatService;
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $route_id = $_POST['route_id'] ?? null;
            $selectedSeats = array_filter(explode(',', $_POST['selected_seats'] ?? ''));
            $user_id = $_SESSION['session_loginuserid'] ?? null;
            $passengers = (int)($_POST['passengers'] ?? 1);

            if (!$user_id) {
                $_SESSION['error'] = "❌ You must be logged in to book seats!";
                redirect('pages/login');
            }
            if (!$route_id) {
                $_SESSION['error'] = "❌ Route_id missing or invalid!";
                redirect('home/selectseat');
            }
            if (empty($selectedSeats) || count($selectedSeats) < $passengers) {
                $_SESSION['error'] = "❌ Please select {$passengers} seats!";
                redirect('home/selectseat');
            }

            $this->seatService->storeBookingSession($route_id, $selectedSeats, $user_id, $passengers);
            redirect('home/payment');
        }

        die('Invalid request method');
    }

    public function finalStore()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $payment_id = $_POST['payment_method'] ?? null;
            $imageName = $this->handlePaymentSlipUpload();

            $bookingData = $_SESSION['booking_data'] ?? null;
            if (!$bookingData) {
                die('Booking data missing in session');
            }

            $success = $this->seatService->finalizeBooking($bookingData, $payment_id, $imageName);
            $_SESSION[$success ? 'success' : 'error'] = $success ? '✅ Booking has been submitted. Please wait for confimation.' : '❌ Failed to store booking.';
            redirect($success ? 'home/record' : 'home/payment');
        }
        die('Invalid request method');
    }

    private function handlePaymentSlipUpload(): string
    {
        if (!isset($_FILES['payment_slip']) || $_FILES['payment_slip']['error'] !== 0) {
            die('Payment slip is required.');
        }

        $targetDir = dirname(APPROOT) . '/public/uploads/payment_slip/';
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $ext = strtolower(pathinfo($_FILES['payment_slip']['name'], PATHINFO_EXTENSION));
        $allowedExt = ['jpg', 'jpeg', 'png', 'gif', 'pdf'];

        if (!in_array($ext, $allowedExt)) {
            die('Invalid file type for payment slip.');
        }

        $uniqueName = uniqid('payment_', true) . '.' . $ext;
        $targetFile = $targetDir . $uniqueName;

        if (!move_uploaded_file($_FILES['payment_slip']['tmp_name'], $targetFile)) {
            die('Failed to upload payment slip.');
        }

        return $uniqueName;
    }
}
