<?php
require_once APPROOT . '/repositories/RouteRepositoryInterface.php';

class RouteRepository implements RouteRepositoryInterface
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getAll(array $filters = [])
    {
        if (!empty($filters)) {
            $sql = "SELECT * FROM view_route_operator WHERE 1=1";
            $params = [];

            if (!empty($filters['from'])) {
                $sql .= " AND `from` LIKE :from";
                $params[':from'] = '%' . $filters['from'] . '%';
            }

            if (!empty($filters['to'])) {
                $sql .= " AND `to` LIKE :to";
                $params[':to'] = '%' . $filters['to'] . '%';
            }

            if (!empty($filters['date'])) {
                $sql .= " AND DATE(`departure_time`) = :date";
                $params[':date'] = $filters['date'];
            }

            return $this->db->runQuery($sql, $params);
        }

        return $this->db->readAll('view_route_operator');
    }

    public function getById(int $id)
    {
        return $this->db->getById('route', $id);
    }

    public function create(array $params)
    {
        return $this->db->routeProcedure('sp_insert_route', $params);
    }

    public function delete(int $id)
    {
        return $this->db->delete('route', $id);
    }
}
