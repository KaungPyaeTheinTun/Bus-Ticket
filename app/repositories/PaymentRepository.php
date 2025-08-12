<?php

require_once APPROOT . '/interfaces/PaymentRepositoryInterface.php';

require_once APPROOT . '/config/DBconnection.php';

class PaymentRepository extends DBconnection implements PaymentRepositoryInterface
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAll()
    {
        return $this->getDB()->readAll('payments');
    }

    public function getById(int $id)
    {
        return $this->getDB()->getById('payments', $id);
    }

    public function create(array $data)
    {
        return $this->getDB()->create('payments', $data);
    }

    public function update(int $id, array $data)
    {
        return $this->getDB()->update('payments', $id, $data);
    }

    public function delete(int $id)
    {
        return $this->getDB()->delete('payments', $id);
    }
}
