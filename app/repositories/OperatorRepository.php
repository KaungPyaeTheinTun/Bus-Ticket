<?php
require_once APPROOT . '/interfaces/OperatorRepositoryInterface.php';

require_once APPROOT . '/config/DBconnection.php';

class OperatorRepository extends DBconnection implements OperatorRepositoryInterface
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAll()
    {
        return $this->getDB()->readAll('operator');
    }

    public function getById($id)
    {
        return $this->getDB()->getById('operator', $id);
    }

    public function create(array $data)
    {
        return $this->getDB()->create('operator', $data);
    }

    public function update($id, array $data)
    {
        return $this->getDB()->update('operator', $id, $data);
    }

    public function delete($id)
    {
        return $this->getDB()->delete('operator', $id);
    }

    public function getBusTypes()
    {
        return $this->getDB()->readAll('bus_type');
    }
}
