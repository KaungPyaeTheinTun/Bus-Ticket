<?php

require_once APPROOT . '/middleware/authmiddleware.php';

require_once APPROOT . '/services/OperatorService.php';

class Operator extends Controller
{
    private $operatorService;

    public function __construct(OperatorService $operatorService)
    {
        AuthMiddleware::requireRole(1);
        
        $this->operatorService = $operatorService;
    }

    public function index()
    {
        $operators = $this->operatorService->getAllOperatorsWithTypeNames();

        $this->view('operator/index', ['operator' => $operators]);
    }

    public function create()
    {
        $busTypes = $this->operatorService->getBusTypes();
        $this->view('operator/create', ['busTypes' => $busTypes]);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/operator');
            return;
        }

        $data = [
            'name' => $_POST['name'] ?? '',
            'phone' => $_POST['phone'] ?? '',
            'seat_capacity' => $_POST['seat_capacity'] ?? 0,
            'bus_type_id' => $_POST['bus_type_id'] ?? 0,
        ];

        $result = $this->operatorService->createOperator($data);

        if (!empty($result['errors'])) {
            setMessage('error', implode(' ', $result['errors']));
            redirect('/operator');
            return;
        }

        setMessage($result['success'] ? 'success' : 'error', $result['success'] ? '✅ Operator added.' : '⚠️ Failed to add operator.');
        redirect('/operator');
    }

    public function edit($encodedId = null)
    {
        if (!$encodedId) {
            redirect('/operator');
            return;
        }

        $id = base64_decode($encodedId);
        $operator = $this->operatorService->getOperatorById($id);
        if (!$operator) {
            setMessage('error', '⚠️ Operator not found.');
            redirect('/operator');
            return;
        }

        $busTypes = $this->operatorService->getBusTypes();

        $this->view('operator/edit', ['operator' => $operator,'busTypes' => $busTypes,]);
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/operator');
            return;
        }

        $id = $_POST['id'] ?? '';
        $data = [
            'name' => $_POST['name'] ?? '',
            'phone' => $_POST['phone'] ?? '',
            'seat_capacity' => $_POST['seat_capacity'] ?? 0,
            'bus_type_id' => $_POST['bus_type_id'] ?? 0,
        ];

        $result = $this->operatorService->updateOperator($id, $data);

        if (!empty($result['errors'])) {
            setMessage('error', implode(' ', $result['errors']));
            redirect('/operator');
            return;
        }

        setMessage($result['success'] ? 'success' : 'error', $result['success'] ? '✅ Operator updated.' : '⚠️ Failed to update operator.');
        redirect('/operator');
    }

    public function delete($encodedId = null)
    {
        if (!$encodedId) {
            setMessage('error', '⚠️ Invalid ID.');
            redirect('/operator');
            return;
        }

        $id = base64_decode($encodedId);
        $deleted = $this->operatorService->deleteOperator($id);

        setMessage($deleted ? 'success' : 'error', $deleted ? '✅ Operator deleted.' : '⚠️ Failed to delete operator.');
        redirect('/operator');
    }
}
