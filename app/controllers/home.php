<?php

class Home extends Controller
{

    private $db;
    public function __construct()
    {
        $this->db = new Database();
    }

    public function trip()
    {
        $this->view('frontend/trip');
    }

    public function record()
    {
        $this->view('pages/record');
    }

}
