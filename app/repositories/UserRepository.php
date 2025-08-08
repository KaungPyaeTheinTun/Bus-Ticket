<?php

require_once APPROOT . '/interfaces/UserRepositoryInterface.php';

class UserRepository implements UserRepositoryInterface
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getByRole(string $table, int $roleId)
    {
        return $this->db->getByRole($table, $roleId);
    }

    public function getById(string $view, int $id)
    {
        return $this->db->getById($view, $id);
    }

    public function delete(string $table, int $id)
    {
        return $this->db->delete($table, $id);
    }

    public function readAll(string $table)
    {
        return $this->db->readAll($table);
    }
}
