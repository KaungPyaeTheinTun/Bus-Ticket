<?php

require_once APPROOT . '/interfaces/UserRepositoryInterface.php';

require_once APPROOT . '/config/DBconnection.php';

class UserRepository extends DBconnection implements UserRepositoryInterface
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getByRole(string $table, int $roleId)
    {
        return $this->getDB()->getByRole($table, $roleId);
    }

    public function getById(string $view, int $id)
    {
        return $this->getDB()->getById($view, $id);
    }

    public function delete(string $table, int $id)
    {
        return $this->getDB()->delete($table, $id);
    }

    public function readAll(string $table)
    {
        return $this->getDB()->readAll($table);
    }
}
