<?php
require_once APPROOT . '/interfaces/OperatorRepositoryInterface.php';

class OperatorRepository implements OperatorRepositoryInterface
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getAll()
    {
        return $this->db->readAll('operator');
    }

    public function getById($id)
    {
        return $this->db->getById('operator', $id);
    }

    public function create(array $data)
    {
        return $this->db->create('operator', $data);
    }

    public function update($id, array $data)
    {
        return $this->db->update('operator', $id, $data);
    }

    public function delete($id)
    {
        return $this->db->delete('operator', $id);
    }
}
