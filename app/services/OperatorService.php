<?php

require_once APPROOT . '/repositories/OperatorRepository.php';

class OperatorService
{
    private $repository;

    public function __construct(OperatorRepositoryInterface $repository = null)
    {
        $this->repository = $repository ?: new OperatorRepository();
    }

    public function getAllOperators()
    {
        return $this->repository->getAll();
    }

    public function getOperatorById($id)
    {
        return $this->repository->getById($id);
    }

    public function createOperator(array $data)
    {
        return $this->repository->create($data);
    }

    public function updateOperator($id, array $data)
    {
        return $this->repository->update($id, $data);
    }

    public function deleteOperator($id)
    {
        return $this->repository->delete($id);
    }
}
