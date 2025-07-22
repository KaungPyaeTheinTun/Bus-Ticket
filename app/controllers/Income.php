<?php

class Income extends Controller
{
    private $db;
    public function __construct()
    {
        $this->model('IncomeModel');

        $this->db = new Database();
    }
    public function index()
    {
        // $income = $this->db->readAll('vw_categories_income');
        // $data = [
        //     'income' => $income
        // ];
        $this->view('income/index');    // , $data
    }

    // For Income
    public function incomeData() {
        $income = $this->db->readAll('vw_categories_income');
        $json = array('data' => $income);
        echo json_encode($json);
    }

    public function create()
    {
        $category = $this->db->readAll('categories');
        // print_r($category);

        $type = $this->db->readAll('types');
        $data = [
            'categories' => $category,
            'types' => $type
        ];

        $this->view('create/create', $data);
        redirect('income');
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $amount = $_POST['amount'];
            $category_id = $_POST['category_id'];
            session_start();
            $user_id = base64_decode($_SESSION['id']);
            $date = date('Y/m/d');

            $income = new IncomeModel();

            $income->setAmount($amount);
            $income->setCategoryId($category_id);
            $income->setUserId($user_id);
            $income->setDate($date);

            $incomeCreated = $this->db->create('incomes', $income->toArray());
            setMessage('success', 'Create successful!');
            redirect('income');
        }
    }

    public function edit($id)
    {
        $category = $this->db->readAll('categories');

        $income = $this->db->getById('incomes', $id);

        $data = [
            'categories' => $category,
            'incomes'    => $income
        ];
        $this->view('income/edit', $data);
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $category_id = $_POST['category_id'];
            $amount = $_POST['amount'];
            session_start();
            $user_id = base64_decode($_SESSION['id']);
            $date = date('Y/m/d');
            // echo $category_id;

            $income = new IncomeModel();
            $income->setId($id);
            $income->setAmount($amount);
            $income->setCategoryId($category_id);
            $income->setUserId($user_id);
            $income->setDate($date);

            $isUpdated = $this->db->update('incomes', $income->getId(), $income->toArray());
            setMessage('success', 'Update successful!');
            redirect('income');
        }
    }

    public function destroy($id)
    {
        // $id = base64_decode($id);

        // $income = new IncomeModel();
        // $income->setId($id);

        // $isdestroy = $this->db->delete('incomes', $income->getId());
        $this->db->delete('incomes', $id);
        setMessage("Income Data has been Deleted");
        redirect('income');
    }

    public function import()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // $temp = $_FILES['file']['tmp_name'];
            // print_r($temp);
            session_start();
            if (file_exists($_FILES['file']['tmp_name'])) {
                $fileName = $_FILES['file']['tmp_name'];
                $handle = fopen($fileName, 'r');
                // print_r($handle);
                // exit;
                if ($handle !== FALSE) {
                    $header = fgetcsv($handle);
                    array_flip($header);
                    while($data = fgetcsv($handle)) {
                        // print_r($data);
                        // exit;
                        $category = $data[0];
                        $amount = $data[1];
                        $date = $data[2];
                        
                        $user_id = base64_decode($_SESSION['id']);
                        // echo $user_id;
                        // exit;
                        

                        $isColumnExist = $this->db->columnFilter('categories', 'name', $data[0]);
                        // print_r($isColumnExist);
                        if ($isColumnExist) {
                            $c_id = $this->db->getByCategoryId('categories', $data[0]);
                            $category_id = implode($c_id);
                            $this->model('IncomeModel');
                            $income = new IncomeModel();
                            $income->setAmount($amount);
                            $income->setCategoryId($category_id);
                            $income->setUserId($user_id);
                            $income->setDate($date);
                            $isCreated = $this->db->create('incomes', $income->toArray());
                            redirect('income');
                        } else {
                            $name = $data[0];
                            $type_id = 1;
                            $description = 'Automatic fill';
                            $category = $this->model('CategoryModel');
                            $category->setName($name);
                            $category->setDescription($description);
                            $category->setTypeId($type_id);

                            $c_id = $this->db->create('categories', $category->toArray());

                            // $c_id = $this->db->getByCategoryId('categories', $data[0]);
                            // $category_id = implode($c_id);
                            $this->model('IncomeModel');
                            $income = new IncomeModel();
                            $income->setAmount($amount);
                            $income->setCategoryId($c_id);
                            $income->setUserId($user_id);
                            $income->setDate($date);
                            $isCreated = $this->db->create('incomes', $income->toArray());
                            redirect('income');
                        };
                    }
                }
            }
        }
    }

    
}