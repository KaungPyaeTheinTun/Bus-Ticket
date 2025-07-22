<?php

class Dashboard extends Controller
{

    private $db;
    public function __construct()
    {
        $this->db = new Database();
    }

    // public function profile()
    // {
    //     $user = $this->db->getByRole('users' , '1');
    //     $this->view('backend/adminprofile' ,$user);
    // }

}
