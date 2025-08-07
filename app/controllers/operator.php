<?php

require_once APPROOT . '/middleware/authmiddleware.php';

require_once APPROOT . '/services/OperatorService.php';

class Operator extends Controller
{
    private $operatorService;
    private $db;

    public function __construct()
    {
        AuthMiddleware::adminOnly();
        $this->model('OperatorModel');
        $this->operatorService = new OperatorService();
        $this->db = new Database(); 
    }

    public function index()
    {
        $operators = $this->operatorService->getAllOperators();
        $types = $this->db->readAll('bus_type');

        $typeMap = array_column($types, 'type_name', 'id');

        foreach ($operators as &$op) {
            $op['type_name'] = $typeMap[$op['bus_type_id']] ?? 'Unknown';
        }

        $data = ['operator' => $operators];
        $this->view('operator/index', $data);
    }

    public function create()
    {
        $this->view('operator/create');
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $seat_capacity = $_POST['seat_capacity'] ?? '';
            $bus_type_id = $_POST['bus_type_id'] ?? '';

            if (empty($name) || empty($phone) || $seat_capacity < 1) {
                setMessage('error', '⚠️ Invalid input data.');
                redirect('/operator/create');
                return;
            }

            if (($bus_type_id == 1 && $seat_capacity > 30) || ($bus_type_id == 2 && $seat_capacity > 44)) {
                setMessage('error', '⚠️ Seat capacity exceeds limit for selected bus type.');
                redirect('/operator/create');
                return;
            }

            $operator = new \App\Models\OperatorModel();
            $operator->name = $name;
            $operator->phone = $phone;
            $operator->seat_capacity = $seat_capacity;
            $operator->bus_type_id = $bus_type_id;

            $created = $this->operatorService->createOperator($operator->toArray());

            setMessage($created ? 'success' : 'error', $created ? '✅ Operator added.' : '⚠️ Failed to add operator.');
            redirect('/operator');
        }
    }

    public function edit($encodedId = null)
    {
        if (!$encodedId) redirect('/operator');

        $id = base64_decode($encodedId);
        $operator = $this->operatorService->getOperatorById($id);

        if (!$operator) {
            setMessage('error', '⚠️ Operator not found.');
            redirect('/operator');
        }

        $this->view('operator/edit', ['operator' => $operator]);
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'] ?? '';
            $name = $_POST['name'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $seat_capacity = $_POST['seat_capacity'] ?? '';
            $bus_type_id = $_POST['bus_type_id'] ?? '';

            if ($seat_capacity < 1 || $seat_capacity > 44) {
                setMessage('error', '⚠️ Invalid seat count.');
                redirect('/operator');
                return;
            }

            $operator = new \App\Models\OperatorModel();
            $operator->id = $id;
            $operator->name = $name;
            $operator->phone = $phone;
            $operator->seat_capacity = $seat_capacity;
            $operator->bus_type_id = $bus_type_id;

            $updated = $this->operatorService->updateOperator($id, $operator->toArray());

            setMessage($updated ? 'success' : 'error', $updated ? '✅ Operator updated.' : '⚠️ Failed to update operator.');
            redirect('/operator');
        }
    }

    public function delete($encodedId)
    {
        $id = base64_decode($encodedId);
        if (!$id) {
            setMessage('error', '⚠️ Invalid ID.');
            redirect('/operator');
            return;
        }

        $deleted = $this->operatorService->deleteOperator($id);
        setMessage($deleted ? 'success' : 'error', $deleted ? '✅ Operator deleted.' : '⚠️ Failed to delete operator.');
        redirect('/operator');
    }
}
