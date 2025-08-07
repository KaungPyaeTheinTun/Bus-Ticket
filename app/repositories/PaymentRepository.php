<?php

require_once APPROOT . '/interfaces/PaymentRepositoryInterface.php';

class PaymentRepository implements PaymentRepositoryInterface
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getAll()
    {
        return $this->db->readAll('payments');
    }

    public function getById(int $id)
    {
        return $this->db->getById('payments', $id);
    }

    public function create(array $data)
    {
        return $this->db->create('payments', $data);
    }

    public function update(int $id, array $data)
    {
        return $this->db->update('payments', $id, $data);
    }

    public function delete(int $id)
    {
        return $this->db->delete('payments', $id);
    }
}
