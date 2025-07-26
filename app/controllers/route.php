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

    public function index() {
        $route= $this->db->readAll('view_route_operator');
        // var_dump($route);
        // exit;
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
}
