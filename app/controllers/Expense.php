<?php

class Expense extends Controller
{
    private $db;
    public function __construct()
    {
        $this->model('ExpenseModel');
        $this->db = new Database();
    }

    public function index()
    {
        // $expense = $this->db->readAll('vw_expenses_categories_users');
        // $data = [
        //     'expenses' => $expense
        // ];

        $this->view('expense/index');   // ,data
    }

    // For Expense
    public function expenseData()
    {
        $expense = $this->db->readAll('vw_expenses_categories_users');
        $json = array('data' => $expense);
        echo json_encode($json);
    }

    public function create()
    {
        $category = $this->db->readAll('categories');

        $types = $this->db->readAll('types');
        // print_r($category);
        $data = [
            'categories' => $category,
            'types' => $types
        ];

        $this->view('create/create', $data);
        // redirect('expense');
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $amount = $_POST['amount'];
            $category_id = $_POST['category_id'];
            $qty = $_POST['qty'];
            session_start();
            $user_id = base64_decode($_SESSION['id']);
            $date = date('Y/m/d');

            $expense = new ExpenseModel();
            $expense->setAmount($amount);
            $expense->setCategoryId($category_id);
            $expense->setUserId($user_id);
            $expense->setQty($qty);
            $expense->setDate($date);

            $isCreated = $this->db->create('expenses', $expense->toArray());
            setMessage('success', 'Create Successful');
            redirect('expense');
        }
    }

    public function edit($id)
    {
        $category = $this->db->readAll('categories');
        $expense = $this->db->getById('expenses', $id);
        $data = [
            'categories' => $category,
            'expenses'   => $expense
        ];

        $this->view('expense/edit', $data);
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $category_id = $_POST['category_id'];
            $amount = $_POST['amount'];
            $qty = $_POST['qty'];
            $date = date('Y/m/d');

            session_start();
            $user_id = base64_decode($_SESSION['id']);

            $expense = new ExpenseModel();

            $expense->setId($id);
            $expense->setCategoryId($category_id);
            $expense->setAmount($amount);
            $expense->setQty($qty);
            $expense->setUserId($user_id);
            $expense->setDate($date);

            $isUpdated = $this->db->update('expenses', $expense->getId(), $expense->toArray());
            setMessage('success', 'Update Successful!');
            redirect('expense');
        }
    }

    public function destroy($id)
    {
        // $id = $_POST['id'];
        $id = base64_decode($id);

        // $expense = new ExpenseModel();

        // $expense->setId($id);

        // $isDestroyed = $this->db->delete('expenses', $expense->getId());
        // $id = $_POST['id'];
        $this->db->delete('expenses', $id);
        //setMessage("Expense Data has been Deleted");
        redirect('expense');
    }
}
