<?php
require_once APPROOT . '/middleware/authmiddleware.php';

require_once APPROOT . '/services/BookingService.php';

require_once APPROOT . '/helpers/SessionHelper.php';

class Booking extends Controller
{
    private $bookingService;

    public function __construct(BookingService $bookingService)
    {
        AuthMiddleware::requireRole(1);
        
        $this->bookingService = $bookingService;
    }

    private function startSessionAndValidateCsrf()
    {
        SessionHelper::startSecureSession();
        if (!SessionHelper::validateCsrfToken($_POST['csrf_token'] ?? null)) {
            setMessage('error', '⚠️ Invalid request (CSRF).');
            redirect($_SERVER['HTTP_REFERER'] ?? 'pages/login');
            exit;
        }
    }

    public function index()
    {
        $data = ['booking' => $this->bookingService->getAllBookings()];
        $this->view('booking/index', $data);
    }

    public function deleteseat($encodedSeatNumber)
    {
        $result = $this->bookingService->deleteSeat($encodedSeatNumber);
        $_SESSION[$result ? 'success' : 'error'] = $result ? 'Seat deleted successfully' : 'Failed to delete seat';
        redirect('/booking'); 
    }

    public function updateStatus($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->startSessionAndValidateCsrf();
            $status = $_POST['status'] ?? null;
            if (!in_array($status, ['0', '1', '2'])) {
                $_SESSION['error'] = '❌ Invalid status value.';
            } else {
                $success = $this->bookingService->updateBookingStatus((int)$id, (int)$status);

                if (!$success) {
                    $_SESSION['error'] = '❌ Booking update failed!';
                } else {
                    $messages = [
                        0 => ['error', '❌ Booking canceled!'],
                        1 => ['pending', '⏳ Booking is Pending.'],
                        2 => ['success', '✅ Booking confirmed.']
                    ];
                    [$type, $msg] = $messages[(int)$status] ?? ['error', '❌ Invalid status!'];
                    $_SESSION[$type] = $msg;
                }
            }
        }
        redirect('/booking');
    }
}
