<?php
session_start();

class Route extends Controller
{
    private $db;
    public function __construct()
    {
        $this->model('RouteModel');
        $this->db = new Database();
    }

    // public function index() {
    //     $route= $this->db->readAll('view_route_operator');
    //     // var_dump($route);
    //     // exit;
    //     $data = [
    //         'route' => $route
    //     ];
    //     $this->view('route/index', $data);
    // }
    public function index() 
    {
        $from = isset($_GET['from']) ? trim($_GET['from']) : '';
        $to = isset($_GET['to']) ? trim($_GET['to']) : '';
        $date = isset($_GET['date']) ? trim($_GET['date']) : '';

        if ($from || $to || $date) {
            $sql = "SELECT * FROM view_route_operator WHERE 1=1";
            $params = [];

            if (!empty($from)) {
                $sql .= " AND `from` LIKE :from";
                $params[':from'] = '%' . $from . '%';
            }

            if (!empty($to)) {
                $sql .= " AND `to` LIKE :to";
                $params[':to'] = '%' . $to . '%';
            }

            if (!empty($date)) {
                $sql .= " AND DATE(`departure_time`) = :date";
                $params[':date'] = $date;
            }

            $route = $this->db->runQuery($sql, $params);
        } else {
            $route = $this->db->readAll('view_route_operator');
        }

        $data = [
            'route' => $route
        ];

        $this->view('route/index', $data);
    }

    public function detail()
    {
        $id = isset($_GET['id']) ? base64_decode($_GET['id']) : null;

        if ($id) {
            $route = $this->db->getById('route', $id);
            if (!$route) {
                setMessage('error', '⚠️ Route not found.');
                redirect('/route');
            }

            $operator = null;
            if (!empty($route['operator_id'])) {
                $operator = $this->db->getById('operator', $route['operator_id']);
            }

            $data = [
                'route' => $route,
                'operator' => $operator
            ];

            $this->view('route/detail', $data);
        } else {
            // setMessage('error', '⚠️ Invalid ID.');
            redirect('/route');
        }
    }

    
    public function create() {
        // use session to get operator_id
        $operator = $this->db->readAll('operator');
        $data = [
            'operator' => $operator,
        ];
        
        $this->view('route/create', $data);
    }

    public function store() 
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $operator_id     = $_POST['operator_id'];
            $price           = $_POST['price'];
            $from           = $_POST['from'];
            $to           = $_POST['to'];
            $departure_time  = $_POST['departure_time'];
            $arrival_time    = $_POST['arrival_time'];
            $imageName = null;
            if (isset($_FILES['image']) && isset($_FILES['image']['tmp_name']) && isset($_FILES['image']['name'])) {
                $targetDir = dirname(APPROOT) . '/public/uploads/routes_images/';
                
                if (!file_exists($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }

                $originalName = basename($_FILES['image']['name']);
                $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
                
                $uniqueName = uniqid('route_', true) . '.' . $ext;
                $targetFile = $targetDir . $uniqueName;

                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                    $imageName = $uniqueName;
                }
            }

            // $route = new RouteModel();
            // $route->setOperatorId($operator_id);
            // $route->setPrice($price);
            // $route->setFrom($from);
            // $route->setTo($to);
            // $route->setDepartureTime($departure_time);
            // $route->setArrivalTime($arrival_time);
            // $route->setImage($imageName);
            $route = new \App\Models\RouteModel();
            $route->operator_id = $operator_id;
            $route->price = $price;
            $route->from = $from;
            $route->to = $to;
            $route->departure_time = $departure_time;
            $route->arrival_time = $arrival_time;
            $route->image = $imageName;

            $isCreated = $this->db->create('route', $route->toArray());

            $_SESSION['success'] = "✅ Route created successfully.";
            redirect('/route');
        }
    }


    public function delete($id)
    {
        $id = base64_decode($id); 

        $data = new \App\Models\RouteModel();

        $deleteroute =  $this->db->delete('route', $id);
        if(!$deleteroute){
        redirect('/route');
        }
        $_SESSION['success'] = "✅ Route deleted successfully.";
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
