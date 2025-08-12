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
        AuthMiddleware::requireRole(1);
        
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

        $result = $this->routeService->deleteRoute($id);
        $_SESSION[$result ? 'success' : 'error'] = $result ? "✅ Route deleted successfully." : "❌ Failed to delete route.";

        redirect('/route');
    }
    
    // public function resetSeats() 
    // {
    //     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //         $route_id = $_POST['route_id'] ?? null;

    //         if ($route_id) {
    //             $allSeats = $this->db->readAll('seats');

    //             $seatsToDelete = [];
    //             foreach ($allSeats as $seat) {
    //                 if ((int)$seat['route_id'] === (int)$route_id) {
    //                     $seatsToDelete[] = $seat['id'];
    //                 }
    //             }

    //             $encodedId = base64_encode($route_id);

    //             if (empty($seatsToDelete)) {
    //                 $_SESSION['error'] = "❌ No booked seats to reset!";
    //                 redirect('route/detail?id=' . $encodedId);
    //                 return;
    //             }

    //             foreach ($seatsToDelete as $seatId) {
    //                 $this->db->delete('seats', $seatId);
    //             }

    //             $_SESSION['success'] = "✅ Seats have been reset successfully.";
    //             redirect('route/detail?id=' . $encodedId);
    //             return;
    //         }
    //     }

    //     $_SESSION['error'] = "❌ Invalid request!";
    //     redirect('/route');
    // }
    public function resetSeats()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $route_id = $_POST['route_id'] ?? null;

            if ($route_id) {
                $service = new RouteService();
                $success = $service->resetSeats((int)$route_id);

                $encodedId = base64_encode($route_id);

                $_SESSION[$success ? 'success' : 'error'] = $success ? "✅ Seats have been reset." : "❌ No seats found for reset.";

                redirect('route/detail?id=' . $encodedId);
                return;
            }
        }
        $_SESSION['error'] = "❌ Invalid request!";
        redirect('/route');
    }

}
