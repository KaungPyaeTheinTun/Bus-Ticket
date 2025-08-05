<?php
session_start();
class Seat extends Controller
{
    private $db;
    public function __construct()
    {
        $this->model('SeatModel');
        $this->db = new Database();
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $route_id = $_POST['route_id'] ?? null;
            $selectedSeatsStr = $_POST['selected_seats'] ?? '';
            $selectedSeats = array_filter(explode(',', $selectedSeatsStr));
            $user_id = $_SESSION['session_loginuserid'] ?? null;
            $passengers = isset($_POST['passengers']) ? (int)$_POST['passengers'] : 1;

            if (!$user_id) {
                $_SESSION['error'] = "❌ You must be logged in to book seats!";
                redirect('pages/login');
            }
            if (!$route_id) {
                $_SESSION['error'] = "❌ Route_id missing or invalid!";
                redirect('home/selectseat?route_id=' . urlencode($route_id) . '&passengers=' . urlencode($passengers));
            }
            if (empty($selectedSeats) && count($selectedSeats)==0 ) {
                $_SESSION['error'] = "❌ No seats selected!";
                redirect('home/selectseat?route_id=' . urlencode($route_id) . '&passengers=' . urlencode($passengers));
            }
            if (count($selectedSeats) < $passengers) {
                $_SESSION['error'] = "❌ You need to select {$passengers} seats!";
                redirect('home/selectseat?route_id=' . urlencode($route_id) . '&passengers=' . urlencode($passengers));
            }

            $_SESSION['booking_data'] = [
                'route_id'       => (int)$route_id,
                'selected_seats' => $selectedSeats,
                'user_id'        => (int)$user_id,
                'passengers'     => $passengers
            ];
            redirect('home/payment');
        }
        die('Invalid request method');
    }

    public function finalStore() 
    {
        if(session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $payment_id = $_POST['payment_method'] ?? null;

            // Handle uploaded slip image
            $imageName = null;
            if (isset($_FILES['payment_slip']) && $_FILES['payment_slip']['error'] === 0) {
                $targetDir = dirname(APPROOT) . '/public/uploads/payment_slip/';
                if (!file_exists($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }

                $originalName = basename($_FILES['payment_slip']['name']);
                $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
                $allowedExt = ['jpg', 'jpeg', 'png', 'gif', 'pdf'];

                if (!in_array($ext, $allowedExt)) {
                    die('Invalid file type for payment slip.');
                }

                $uniqueName = uniqid('payment_', true) . '.' . $ext;
                $targetFile = $targetDir . $uniqueName;

                if (move_uploaded_file($_FILES['payment_slip']['tmp_name'], $targetFile)) {
                    $imageName = $uniqueName;
                } else {
                    die('Failed to upload payment slip.');
                }
            } else {
                die('Payment slip is required.');
            }

            $bookingData = $_SESSION['booking_data'] ?? null;
            if (!$bookingData) {
                die('Booking data missing in session');
            }

            $route_id = $bookingData['route_id'] ?? null;
            $selected_seats = $bookingData['selected_seats'] ?? [];
            $user_id = $bookingData['user_id'] ?? null;

            if (!$route_id || !$user_id || empty($selected_seats) || !$payment_id || !$imageName) {
                die('Missing required data to store booking');
            }

            // ✅ Instead of loop: store seat_numbers as JSON
            $seatNumbersJson = json_encode($selected_seats);

            // $seat = new SeatModel();
            // $seat->setRoute_id($route_id);
            // $seat->setSeat_number($seatNumbersJson);  
            // $seat->setIs_booked(1);
            // $seat->setUser_id($user_id);
            // $seat->setPaymentId($payment_id);
            // $seat->setPaymentSilp($imageName);
            $seat = new \App\Models\SeatModel();
            $seat->route_id = $route_id;
            $seat->seat_number = $seatNumbersJson;
            $seat->is_booked = 1;
            $seat->user_id = $user_id;
            $seat->payment_id = $payment_id;
            $seat->payment_slip = $imageName;

            $seatData = $seat->toArray();
            $this->db->create('seats', $seatData);

            redirect('home/record'); 
        } else {
            die('Invalid request method');
        }
    }





}
