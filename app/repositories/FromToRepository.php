<?php

require_once APPROOT . '/interfaces/FromToRepositoryInterface.php';

class FromToRepository implements IRouteRepository {
    private $db;

    public function __construct() {
        $this->db = new Database(); // your PDO wrapper
    }

    public function getDistinctFromCities(): array {
        return $this->db->callProcedure('sp_routes_distinct_from', [], true);
    }

    public function getDistinctToCities(): array {
        return $this->db->callProcedure('sp_routes_distinct_to', [], true);
    }
}
