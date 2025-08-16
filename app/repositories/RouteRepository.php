<?php
require_once APPROOT . '/interfaces/RouteRepositoryInterface.php';

require_once APPROOT . '/config/DBconnection.php';

class RouteRepository extends DBconnection implements RouteRepositoryInterface
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAll(array $filters = [])
    {
        if (!empty($filters)) 
        {
            $sql = "SELECT * FROM view_route_operator WHERE 1=1";
            $params = [];

            if (!empty($filters['from'])) {
                $sql .= " AND `from` LIKE :from"; //LIKE for partial match
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

            return $this->getDB()->runQuery($sql, $params);//dynamic SQL.
        }

        return $this->getDB()->readAll('view_route_operator');
    }

    public function getById(int $id)
    {
        return $this->getDB()->getById('route', $id);
    }

    public function create(array $params)
    {
        return $this->getDB()->callProcedure('sp_insert_route', $params);
    }

    public function delete(int $id)
    {
        return $this->getDB()->delete('route', $id);
    }

    public function resetSeatsByRoute(int $route_id)
        {
            $allSeats = $this->getDB()->readAll('seats');

            $deletedCount = 0;

            foreach ($allSeats as $seat) {
                if ((int)$seat['route_id'] === $route_id) {
                    $this->getDB()->delete('seats', $seat['id']);
                    $deletedCount++;
                }
            }

            return $deletedCount;
        }
}
