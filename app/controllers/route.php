<?php
// if(session_status() == PHP_SESSION_NONE) {
//     session_start();
// }
require_once APPROOT . '/services/Service_route.php';

require_once APPROOT . '/middleware/authmiddleware.php';

class Route extends Controller
{
    private $routeService;

    public function __construct()
    {
        AuthMiddleware::adminOnly();
        $this->routeService = new RouteService();
    }

    public function index()
    {
        $filters = [
            'from' => isset($_GET['from']) ? trim($_GET['from']) : '',
            'to' => isset($_GET['to']) ? trim($_GET['to']) : '',
            'date' => isset($_GET['date']) ? trim($_GET['date']) : ''
        ];

        $routes = $this->routeService->getRoutes($filters);

        $this->view('route/index', ['route' => $routes]);
    }

    public function detail()
    {
        $id = isset($_GET['id']) ? base64_decode($_GET['id']) : null;

        if (!$id) {
            redirect('/route');
        }

        $route = $this->routeService->getRouteById($id);

        if (!$route) {
            setMessage('error', '⚠️ Route not found.');
            redirect('/route');
        }

        $operator = null;
        if (!empty($route['operator_id'])) {
            $operator = (new Database())->getById('operator', $route['operator_id']);
        }

        $this->view('route/detail', [
            'route' => $route,
            'operator' => $operator
        ]);
    }

    public function create()
    {
        $db = new Database();
        $operators = $db->readAll('operator');
        $busTypes = $db->readAll('bus_type');

        $typeMap = [];
        foreach ($busTypes as $type) {
            $typeMap[$type['id']] = $type['type_name'];
        }

        foreach ($operators as &$op) {
            $op['type_name'] = $typeMap[$op['bus_type_id']] ?? 'Unknown';
        }

        $this->view('route/create', ['operator' => $operators]);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/route');
        }

        try {
            $this->routeService->createRoute($_POST);
            $_SESSION['success'] = "✅ Route created successfully.";
        } catch (Exception $e) {
            $_SESSION['error'] = "❌ " . $e->getMessage();
        }

        redirect('/route');
    }

    public function delete($id)
    {
        $id = base64_decode($id);

        if ($this->routeService->deleteRoute($id)) {
            $_SESSION['success'] = "✅ Route deleted successfully.";
        } else {
            $_SESSION['error'] = "❌ Failed to delete route.";
        }

        redirect('/route');
    }

    public function searchAndRedirect()
    {
        $from = isset($_GET['from']) ? trim($_GET['from']) : '';
        $to = isset($_GET['to']) ? trim($_GET['to']) : '';
        $date = isset($_GET['date']) ? trim($_GET['date']) : '';
        $passengers = isset($_GET['passengers']) ? (int)$_GET['passengers'] : 1;

        // Validate required fields
        if (empty($from) || empty($to) || empty($date)) {
            $_SESSION['error'] = "Please Fill All Fields !";
            redirect('/pages/index');
            return;
        }

        // Validate passengers
        if ($passengers < 1 || $passengers > 2) {
            $_SESSION['error'] = 'Passenger number must be 1 or 2.';
            redirect('/pages/index');
            return;
        }

        // Read all routes from view
        $allRoutes = $this->db->readAll('view_route_operator');

        // Filter routes based on search criteria
        $filteredRoutes = array_filter($allRoutes, function($route) use ($from, $to, $date) {
            return (stripos($route['from'], $from) !== false) &&
                (stripos($route['to'], $to) !== false) &&
                (date('Y-m-d', strtotime($route['departure_time'])) == $date);
        });

        // Always set search result (empty or not) so view can handle it
        $_SESSION['search_result'] = array_values($filteredRoutes);
        $_SESSION['search_params'] = [
            'from' => $from,
            'to' => $to,
            'date' => $date,
            'passengers' => $passengers
        ];

        redirect('/home/trip');
    }


    public function resetSeats() 
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $route_id = $_POST['route_id'] ?? null;

            if ($route_id) {
                $allSeats = $this->db->readAll('seats');

                $seatsToDelete = [];
                foreach ($allSeats as $seat) {
                    if ((int)$seat['route_id'] === (int)$route_id) {
                        $seatsToDelete[] = $seat['id'];
                    }
                }

                $encodedId = base64_encode($route_id);

                if (empty($seatsToDelete)) {
                    $_SESSION['error'] = "❌ No booked seats to reset!";
                    redirect('route/detail?id=' . $encodedId);
                    return;
                }

                foreach ($seatsToDelete as $seatId) {
                    $this->db->delete('seats', $seatId);
                }

                $_SESSION['success'] = "✅ Seats have been reset successfully.";
                redirect('route/detail?id=' . $encodedId);
                return;
            }
        }

        $_SESSION['error'] = "❌ Invalid request!";
        redirect('/route');
    }


}
