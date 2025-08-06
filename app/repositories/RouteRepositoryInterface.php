<?php
interface RouteRepositoryInterface
{
    public function getAll(array $filters = []);
    public function getById(int $id);
    public function create(array $params);
    public function delete(int $id);
}
