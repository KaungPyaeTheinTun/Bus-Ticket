<?php
session_start();

class Operator extends Controller
{
    private $db;

    public function __construct()
    {
        $this->model('OperatorModel');
        $this->db = new Database();
    }

    // List all operators
    public function index()
    {
        $operators = $this->db->readAll('operator');
        $data = [
            'operator' => $operators
        ];
        $this->view('operator/index', $data);
    }

    // Show create form
    public function create()
    {
        $this->view('operator/create');
    }

    // Store new operator
    // public function store()
    // {
    //     if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //         $name = $_POST['name'] ?? '';
    //         $phone = $_POST['phone'] ?? '';
    //         $seat_capacity = $_POST['seat_capacity'] ?? '';

    //         // Optional: Validate fields here

    //         $operator = new OperatorModel();
    //         $operator->setName($name);
    //         $operator->setPhone($phone);
    //         $operator->setSeatCapacity($seat_capacity);

    //         $isCreated = $this->db->create('operator', $operator->toArray());

    //         if ($isCreated) {
    //             setMessage('success', '✅ Successfully added new operator.');
    //         } else {
    //             setMessage('error', '⚠️ Failed to add operator.');
    //         }
    //         redirect('/operator');
    //     }
    // }
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $seat_capacity = $_POST['seat_capacity'] ?? '';

            if (empty($name) || empty($phone) || $seat_capacity < 1 || $seat_capacity > 44) {
                setMessage('error', '⚠️ Invalid input data.');
                redirect('/operator/create');
                return;
            }

            // $operator = new OperatorModel();
            $operator = new \App\Models\OperatorModel();
            $operator->name = $name;
            $operator->phone = $phone;
            $operator->seat_capacity = $seat_capacity;

            $isCreated = $this->db->create('operator', $operator->toArray());

            if ($isCreated) {
                setMessage('success', '✅ Successfully added new operator.');
            } else {
                setMessage('error', '⚠️ Failed to add operator.');
            }
            redirect('/operator');
        }
    }


    // Show edit form
    public function edit($encodedId = null)
    {
        if ($encodedId === null) {
            redirect('/operator');
        }

        $id = base64_decode($encodedId);
        $operator = $this->db->getById('operator', $id);

        if (!$operator) {
            setMessage('error', '⚠️ Operator not found.');
            redirect('/operator');
        }

        $data = [
            'operator' => $operator
        ];
        $this->view('operator/edit', $data);
    }

    // Update operator
    // public function update()
    // {
    //     if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //         $id = $_POST['id'] ?? '';
    //         $name = $_POST['name'] ?? '';
    //         $phone = $_POST['phone'] ?? '';
    //         $seat_capacity = $_POST['seat_capacity'] ?? '';

    //         $operator = new OperatorModel();
    //         $operator->setId($id);
    //         $operator->setName($name);
    //         $operator->setPhone($phone);
    //         $operator->setSeatCapacity($seat_capacity);

    //         $isUpdated = $this->db->update('operator', $operator->getId(), $operator->toArray());

    //         if ($isUpdated) {
    //             setMessage('success', '✅ Operator updated successfully!');
    //         } else {
    //             setMessage('error', '⚠️ Failed to update operator!');
    //         }
    //         redirect('/operator');
    //     }
    // }
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'] ?? '';
            $name = $_POST['name'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $seat_capacity = $_POST['seat_capacity'] ?? '';

            if ($seat_capacity < 1 || $seat_capacity > 44) {
                // var_dump('error');exit;
                setMessage('error', '⚠️ Maximun seats is 44 !');
                redirect('/operator');
                return;
            }
            // $operator = new OperatorModel();
            $operator = new \App\Models\OperatorModel();
            $operator->id = $id;
            $operator->name = $name;
            $operator->phone = $phone;
            $operator->seat_capacity = $seat_capacity;

            $isUpdated = $this->db->update('operator', $operator->id, $operator->toArray());

            if ($isUpdated) {
                setMessage('success', '✅ Operator updated successfully!');
            } else {
                setMessage('error', '⚠️ Failed to update operator!');
            }
            redirect('/operator');
        }
    }
    // Delete operator
    // public function delete($id)
    // {
    //     $id = base64_decode($id);

    //     $deleted = $this->db->delete('operator', $id);
    //     if ($deleted) {
    //         setMessage('success', '✅ Operator deleted successfully.');
    //     } else {
    //         setMessage('error', '⚠️ Failed to delete operator.');
    //     }
    //     redirect('/operator');
    // }
    public function delete($encodedId)
    {
        $id = base64_decode($encodedId);

        if (!$id) {
            setMessage('error', '⚠️ Invalid operator ID.');
            redirect('/operator');
            return;
        }

        $deleted = $this->db->delete('operator', $id);

        if ($deleted) {
            setMessage('success', '✅ Operator deleted successfully.');
        } else {
            setMessage('error', '⚠️ Failed to delete operator.');
        }
        redirect('/operator');
    }
}

?>
