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
            setMessage('error', '⚠️ Invalid ID.');
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

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            // Get data
            $operator_id     = $_POST['operator_id'];
            $price           = $_POST['price'];
            $from           = $_POST['from'];
            $to           = $_POST['to'];
            $departure_time  = $_POST['departure_time'];
            $arrival_time    = $_POST['arrival_time'];
            // Handle file upload
            $imageName = null;
            if (isset($_FILES['image']) && isset($_FILES['image']['tmp_name']) && isset($_FILES['image']['name'])) {
                $targetDir = dirname(APPROOT) . '/public/uploads/routes_images/';
                
                // Create directory if not exists
                if (!file_exists($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }

                $originalName = basename($_FILES['image']['name']);
                $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
                
                // Generate unique file name
                $uniqueName = uniqid('route_', true) . '.' . $ext;
                $targetFile = $targetDir . $uniqueName;

                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                    $imageName = $uniqueName;
                }
            }

            // Create model
            $route = new RouteModel();
            $route->setOperatorId($operator_id);
            $route->setPrice($price);
            $route->setFrom($from);
            $route->setTo($to);
            $route->setDepartureTime($departure_time);
            $route->setArrivalTime($arrival_time);
            $route->setImage($imageName);

            // Save to DB
            $isCreated = $this->db->create('route', $route->toArray());

            $_SESSION['success'] = "✅ Route created successfully.";
            redirect('/route');
        }
    }

    public function delete($id)
    {
        $id = base64_decode($id); 

        $data = new RouteModel();
        $data->setId($id);

        $deleteroute =  $this->db->delete('route', $data->getId());
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
        if ($passengers < 1 || $passengers > 2) {
            setMessage('error', 'Passenger number must be 1 or 2.');
            redirect('/pages/index');
            return;
        }
     
        $allRoutes = $this->db->readAll('view_route_operator');

        $filteredRoutes = array_filter($allRoutes, function($route) use ($from, $to, $date) {
            return (stripos($route['from'], $from) !== false) &&
                (stripos($route['to'], $to) !== false) &&
                (date('Y-m-d', strtotime($route['departure_time'])) == $date);
        });

        if (!empty($filteredRoutes)) {
            $_SESSION['search_result'] = array_values($filteredRoutes);
            $_SESSION['search_params'] = [
                'from' => $from,
                'to' => $to,
                'date' => $date,
                'passengers' => $passengers
            ];
            redirect('/home/trip'); 
        } else {
            $_SESSION['error'] = "No routes found matching your search !";
            redirect('/pages/index');
        }
    }


}
