<?php
interface UserRepositoryInterface
{
    public function getByRole(string $table, int $roleId);
    public function getById(string $view, int $id);
    public function delete(string $table, int $id);
    public function readAll(string $table);
}
