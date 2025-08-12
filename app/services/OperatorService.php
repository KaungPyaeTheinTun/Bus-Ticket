<?php

require_once APPROOT . '/repositories/OperatorRepository.php';

class OperatorService
{
    private $repository;

    public function __construct(OperatorRepositoryInterface $repository = null)
    {
        $this->repository = $repository ?: new OperatorRepository();
    }

    public function getBusTypes()
    {
        return $this->repository->getBusTypes();
    }

    public function getAllOperatorsWithTypeNames()
    {
        $operators = $this->repository->getAll();
        $types = $this->getBusTypes();
        $typeMap = array_column($types, 'type_name', 'id');

        foreach ($operators as &$op) {
            $op['type_name'] = $typeMap[$op['bus_type_id']] ?? 'Unknown';
        }

        return $operators;
    }

    public function validateOperatorData(array $data): array
    {
        $errors = [];

        if (empty($data['name'])) {
            $errors[] = 'Name is required.';
        }

        if (empty($data['phone'])) {
            $errors[] = 'Phone is required.';
        }

        if (!isset($data['seat_capacity']) || !is_numeric($data['seat_capacity']) || $data['seat_capacity'] < 1) {
            $errors[] = 'Seat capacity must be a number greater than zero.';
        }

        if (!isset($data['bus_type_id']) || !in_array($data['bus_type_id'], [1, 2])) {
            $errors[] = 'Invalid bus type selected.';
        }

        if (isset($data['bus_type_id'], $data['seat_capacity'])) {
            if (($data['bus_type_id'] == 1 && $data['seat_capacity'] > 30) ||
                ($data['bus_type_id'] == 2 && $data['seat_capacity'] > 44)) {
                $errors[] = 'Seat capacity exceeds limit for selected bus type.';
            }
        }

        return $errors;
    }

    public function createOperator(array $data)
    {
        $errors = $this->validateOperatorData($data);
        if (!empty($errors)) {
            return ['errors' => $errors];
        }

        $created = $this->repository->create($data);
        return $created ? ['success' => true] : ['error' => 'Failed to add operator.'];
    }

    public function updateOperator($id, array $data)
    {
        $errors = $this->validateOperatorData($data);
        if (!empty($errors)) {
            return ['errors' => $errors];
        }

        $updated = $this->repository->update($id, $data);
        return $updated ? ['success' => true] : ['error' => 'Failed to update operator.'];
    }

    public function getAllOperators()
    {
        return $this->repository->getAll();
    }

    public function getOperatorById($id)
    {
        return $this->repository->getById($id);
    }

    public function deleteOperator($id)
    {
        return $this->repository->delete($id);
    }
}
