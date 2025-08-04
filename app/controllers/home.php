<?php
class Home extends Controller
{

    private $db;
    public function __construct()
    {
        $this->db = new Database();
    }

    public function trip()
    {
        $this->view('frontend/trip');
    }

    public function record()
    {   
        session_start();
        $user_id = $_SESSION['session_loginuserid'] ?? null;
        // var_dump($user_id);exit;
        if (!$user_id) {
            $_SESSION['error'] = "You must be logged in to see your records.";
            redirect('pages/login');
        }

        $allRecords = $this->db->readAll('view_history');
        $userRecords = array_filter($allRecords, function($rec) use ($user_id) {
            return $rec['user_id'] == $user_id;
        });

        $data = [
            'record' => $userRecords,
        ];
        $this->view('frontend/record', $data);
    }


    public function selectseat()
    {
        $routeId = isset($_GET['route_id']) ? intval($_GET['route_id']) : 0;
        $route = $this->db->getById('view_route_operator', $routeId);
        if (!$route) {
            die('Route not found');
        }
        $selectedseat = $this->db->readAll('seats');
        $bookedSeatNumbers = [];
        foreach ($selectedseat as $seat) {
            if (((int)$seat['is_booked'] === 2 || (int)$seat['is_booked'] === 1) && (int)$seat['route_id'] === $routeId) {
                $seatNumbersArray = json_decode($seat['seat_number'], true);
                if (is_array($seatNumbersArray)) {
                    foreach ($seatNumbersArray as $number) {
                        $bookedSeatNumbers[] = (int)$number;
                    }
                }
            }
        }
        
        $_SESSION['selected_trip'] = [
            'route_id'       => $routeId,
            'from'           => $route['from'],
            'to'             => $route['to'],
            'departure_time' => $route['departure_time'],
            'arrival_time'   => $route['arrival_time'],
            'operator_name'  => $route['operator_name'],
            'price'          => $route['price']
        ];
        
        if (isset($_GET['passengers'])) {
            $_SESSION['selected_trip']['passengers'] = intval($_GET['passengers']);
        } else {
            $_SESSION['selected_trip']['passengers'] = 1;
        }
        
        $data = [
            'trip' => $_SESSION['selected_trip'],
            'bookedSeatNumbers' => $bookedSeatNumbers,
        ];

        $this->view('frontend/selectseat', $data);
    }

   public function payment()
    {
        $payments = $this->db->readAll('payments');

        $selectedId = $_GET['payment_method'] ?? null;

        $selectedPayment = null;

        if ($selectedId) {
            foreach ($payments as $p) {
                if ($p['id'] == $selectedId) {
                    $selectedPayment = $p;
                    break;
                }
            }
        }

        if (!$selectedPayment && !empty($payments)) {
            $selectedPayment = $payments[0];
        }

        $data = [
            'payments' => $payments,
            'selectedPayment' => $selectedPayment
        ];

        $this->view('frontend/payment', $data);
    }

}
