<?php

require_once APPROOT . '/middleware/AuthMiddleware.php';

require_once APPROOT . '/services/HomeService.php';

require_once APPROOT . '/helpers/SessionManager.php';

class Home extends Controller
{
    private $homeService;

    public function __construct(HomeService $homeService)
    {
        $this->homeService = $homeService;
    }

    public function index()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $routeId = $_SESSION['selected_trip']['route_id'] ?? null;
        $passengers = $_SESSION['selected_trip']['passengers'] ?? null;

        if ($routeId && $passengers) {
            header("Location: " . URLROOT . "/home/selectseat?route_id={$routeId}&passengers={$passengers}");
            exit;
        } else {
            $_SESSION['error'] = "Please choose a trip first.";
            redirect('/pages/index');
        }
    }

    public function trip()
    {
        $this->view('frontend/trip');
    }

    public function record()
    {
        AuthMiddleware::requireRole(2);
        $session = new SessionManager(); 
        
        if(session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $user_id = $_SESSION['session_loginuserid'] ?? null;
        if (!$user_id) {
            $_SESSION['error'] = "You must be logged in to see your records.";
            redirect('pages/login');
        }

        $data = $this->homeService->getUserRecords($user_id);
        $this->view('frontend/record', $data);
    }

    public function selectseat()
    {
        $routeId = isset($_GET['route_id']) ? intval($_GET['route_id']) : 0;
        
        $data = $this->homeService->getTripDetails($routeId);

        if (!$data) {
            $_SESSION['error'] = "Route or operator not found.";
            header("Location: " . URLROOT . "/home/index");
            exit;
        }
        $_SESSION['selected_trip'] = $data['trip'];

        if (isset($_GET['passengers'])) {
            $_SESSION['selected_trip']['passengers'] = max(1, intval($_GET['passengers']));
        }

        // Validation: Check seat selection if form is submitted
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $selectedSeats = isset($_POST['selected_seats']) ? $_POST['selected_seats'] : [];

            if (empty($selectedSeats)) {
                $_SESSION['error'] = "Please select at least one seat.";
            } elseif (count($selectedSeats) != $_SESSION['selected_trip']['passengers']) {
                $_SESSION['error'] = "Please select exactly {$_SESSION['selected_trip']['passengers']} seats.";
            }

            if (!empty($_SESSION['error'])) {
                header("Location: " . URLROOT . "/home/selectseat?route_id=" . $routeId . "&passengers=" . $_SESSION['selected_trip']['passengers']);
                exit;
            }
        }

        $this->view('frontend/selectseat', $data);
    }

    public function payment()
    {
        AuthMiddleware::requireRole(2);
        $session = new SessionManager(); 

        $selectedId = $_GET['payment_method'] ?? null;
        $data = $this->homeService->getPayments($selectedId);

        $this->view('frontend/payment', $data);
    }

    public function searchAndRedirect()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $from = isset($_GET['from']) ? trim($_GET['from']) : '';
        $to = isset($_GET['to']) ? trim($_GET['to']) : '';
        $date = isset($_GET['departure_time']) ? trim($_GET['departure_time']) : '';
        $passengers = isset($_GET['passengers']) ? (int)$_GET['passengers'] : 1;

        if (empty($from) || empty($to) || empty($date)) {
            $_SESSION['error'] = "Please Fill All Fields !";
            redirect('/pages/index');
            return;
        }

        if ($passengers < 1 || $passengers > 2) {
            $_SESSION['error'] = 'Passenger number must be 1 or 2.';
            redirect('/pages/index');
            return;
        }

        $_SESSION['search_params'] = [
            'from' => $from,
            'to' => $to,
            'date' => $date,
            'passengers' => $passengers
        ];
        
        $_SESSION['search_result'] = $this->homeService->searchRoutes($from, $to, $date);

        redirect('/home/trip');
    }
}
