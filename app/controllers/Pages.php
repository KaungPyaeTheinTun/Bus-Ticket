<?php

require_once APPROOT . '/middleware/authmiddleware.php';

class Pages extends Controller
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function index()
    {
        $this->view('pages/index');
    }

    public function login()
    {
        $this->view('pages/login');
    }

    public function register()
    {
        $this->view('pages/register');
    }

    public function forgetpassword()
    {
        $this->view('pages/forgetpassword');
    }

    public function changepassword()
    {
        $this->view('pages/changepassword');
    }

    public function otp()
    {
        $this->view('pages/otp');
    }
    public function dashboard()
    {
        AuthMiddleware::requireRole(1);
        
        // Fetch from your database
        $operators = $this->db->readAll('operator');
        $routes = $this->db->readAll('view_route_operator');
        $bookings = $this->db->readAll('seats'); // assumes seats = bookings

        // Init variables
        $totalOperators = count($operators);
        $totalRoutes = count($routes);
        $totalApprovedBookings = 0;
        $pendingBookings = 0;
        $revenue = 0;
        $ongoingBuses = [];

        $currentTime = date('Y-m-d H:i'); // current time to match format in DB

        // Operator ID => Name Map
        $operatorMap = [];
        foreach ($operators as $operator) {
            $operatorMap[$operator['id']] = $operator['name'];
        }
        // Build routeId => price map from route table
        $routePriceMap = [];
        foreach ($routes as $route) {
            $routePriceMap[$route['id']] = isset($route['price']) ? (int)$route['price'] : 0;
        }
        // Calculate Booking Stats
        foreach ($bookings as $booking) 
        {
            if (isset($booking['is_booked'])) {
                if ($booking['is_booked'] == 2) { 
                    $totalApprovedBookings++;

                    $routeId = $booking['route_id'] ?? null;
                    if ($routeId && isset($routePriceMap[$routeId])) {
                        $revenue += $routePriceMap[$routeId];
                    }

                } elseif ($booking['is_booked'] == 1) { 
                    $pendingBookings++;
                }
            }
        }

        // Find Ongoing Buses
        date_default_timezone_set('Asia/Yangon');

        $currentTime = time();
        $ongoingBuses = [];

        foreach ($routes as $route) {
            if (!empty($route['departure_time']) && !empty($route['arrival_time'])) {
                $departureTimestamp = strtotime($route['departure_time']);
                $arrivalTimestamp = strtotime($route['arrival_time']);

                if ($currentTime >= $departureTimestamp && $currentTime < $arrivalTimestamp) {
                    $ongoingBuses[] = [
                        'operator_name' => $route['operator_name'] ?? 'Unknown',
                        'from_location' => $route['from'],
                        'to_location' => $route['to'],
                        'departure_time' => $route['departure_time'],
                        'arrival_time' => $route['arrival_time']
                    ];
                }
            }
        }

        // Final data to pass to view
        $data = [
            'totalOperators' => $totalOperators,
            'totalRoutes' => $totalRoutes,
            'totalBookings' => $totalApprovedBookings,
            'pendingBookings' => $pendingBookings,
            'revenue' => $revenue,
            'ongoingBusCount' => count($ongoingBuses),
            'ongoingBuses' => $ongoingBuses
        ];

        $this->view('pages/dashboard', $data);
    }
   
}
